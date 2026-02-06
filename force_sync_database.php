<?php
/**
 * Force Synchronize Database with Live Preview Calculations
 * This FORCES recalculation of all records using HRMPSBEvaluator
 * regardless of whether draft data exists
 */

require_once 'initialize.php';
require_once 'config/baseline_library.php';
require_once 'config/evaluation_criteria.php';
require_once 'classes/HRMPSBEvaluator.php';

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error . "\n");
}
$conn->set_charset("utf8mb4");

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║    FORCE Recalculate - Live Preview Formula to Database   ║\n";
echo "║    Using HRMPSBEvaluator (authoritative calculation)      ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Fetch ALL records from comparative_assessment_results
$query = "SELECT * FROM comparative_assessment_results ORDER BY id ASC";
$result = $conn->query($query);

if (!$result) {
    die("❌ Error fetching records: " . $conn->error . "\n");
}

$totalRecords = $result->num_rows;
$updated = 0;
$errors = 0;
$changes = [];

echo "📊 Found {$totalRecords} records to process.\n";
echo "🔄 Using HRMPSBEvaluator for all calculations...\n\n";

while ($row = $result->fetch_assoc()) {
    $oldTotal = floatval($row['total_score']);
    $applicationCode = $row['application_code'] ?? '';
    
    printf("[%d] %-20s ... ", $row['id'], $applicationCode);
    
    try {
        // Fetch draft data if it exists
        $draftData = [];
        if (!empty($applicationCode)) {
            $stmt = $conn->prepare("SELECT data FROM drafts WHERE application_code = ? LIMIT 1");
            $stmt->bind_param("s", $applicationCode);
            $stmt->execute();
            $draftResult = $stmt->get_result();
            if ($draftResult->num_rows > 0) {
                $draft = $draftResult->fetch_assoc();
                $draftData = json_decode($draft['data'], true) ?: [];
            }
            $stmt->close();
        }
        
        // If no draft data, skip
        if (empty($draftData)) {
            echo "⚠️  No draft data - SKIP\n";
            continue;
        }
        
        // Determine position group and salary grade
        $posGroup = $row['position_group'] ?? 'NON-TEACHING LEVEL II';
        $salaryGrade = floatval($draftData['salary_grade'] ?? 0) ?: 15;
        $category = $draftData['category'] ?? '';
        
        // Create evaluator using correct position group
        $evaluator = new HRMPSBEvaluator($posGroup, $salaryGrade, $category);
        
        // Get baseline
        $positionKey = $draftData['position_key'] ?? 'custom';
        $baseline = getBaselineForPosition($positionKey);
        
        // Build applicant data from draft
        $appData = [
            'name' => $draftData['applicant_name'] ?? '',
            'position' => $row['position_name'] ?? '',
            'education' => [
                'degree' => $draftData['applicant_education_degree'] ?? '',
                'masters_units' => intval($draftData['applicant_education_masters_units'] ?? 0),
                'doctoral_units' => intval($draftData['applicant_education_doctoral_units'] ?? 0)
            ],
            'training' => floatval($draftData['applicant_training'] ?? 0),
            'experience' => floatval($draftData['applicant_experience'] ?? 0),
            'performance' => floatval($draftData['applicant_performance'] ?? 0),
            'outstanding_accomplishments' => floatval($draftData['applicant_outstanding_accomplishments'] ?? 0),
            'application_of_education' => floatval($draftData['applicant_application_of_education'] ?? 0),
            'application_of_ld' => floatval($draftData['applicant_application_of_ld'] ?? 0),
            'potential' => floatval($draftData['applicant_potential'] ?? 0)
        ];
        
        // Build baseline data
        $baseData = [
            'name' => 'Baseline',
            'position' => $row['position_name'] ?? '',
            'education' => [
                'degree' => $baseline['education']['degree'] ?? '',
                'masters_units' => intval($baseline['education']['masters_units'] ?? 0),
                'doctoral_units' => intval($baseline['education']['doctoral_units'] ?? 0)
            ],
            'training' => floatval($baseline['training'] ?? 0),
            'experience' => floatval($baseline['experience'] ?? 0),
            'performance' => floatval($baseline['performance'] ?? 0),
            'outstanding_accomplishments' => floatval($baseline['outstanding_accomplishments'] ?? 0),
            'application_of_education' => floatval($baseline['application_of_education'] ?? 0),
            'application_of_ld' => floatval($baseline['application_of_ld'] ?? 0),
            'potential' => floatval($baseline['potential'] ?? 0)
        ];
        
        // Recalculate using HRMPSBEvaluator (authoritative engine)
        $evaluation = $evaluator->evaluateApplicant($appData, $baseData);
        
        if (!isset($evaluation['criteria'])) {
            echo "❌ Evaluation failed\n";
            $errors++;
            continue;
        }
        
        // Extract new scores
        $newScores = [];
        foreach (['education', 'training', 'experience', 'performance', 'outstanding_accomplishments', 
                  'application_of_education', 'application_of_ld', 'potential'] as $criterion) {
            $newScores[$criterion] = floatval($evaluation['criteria'][$criterion]['final_score'] ?? 0);
        }
        
        // Calculate new total
        $newTotal = array_sum($newScores);
        $difference = $newTotal - $oldTotal;
        
        // Build update statement
        $updateStmt = $conn->prepare("
            UPDATE comparative_assessment_results 
            SET education_score = ?,
                training_score = ?,
                experience_score = ?,
                performance_score = ?,
                outstanding_accomplishments_score = ?,
                application_of_education_score = ?,
                application_of_ld_score = ?,
                potential_score = ?,
                total_score = ?
            WHERE id = ?
        ");
        
        $updateStmt->bind_param(
            "dddddddddi",
            $newScores['education'],
            $newScores['training'],
            $newScores['experience'],
            $newScores['performance'],
            $newScores['outstanding_accomplishments'],
            $newScores['application_of_education'],
            $newScores['application_of_ld'],
            $newScores['potential'],
            $newTotal,
            $row['id']
        );
        
        if ($updateStmt->execute()) {
            printf("✅ Updated (%.2f → %.2f, %+.2f)\n", $oldTotal, $newTotal, $difference);
            $changes[] = [
                'code' => $applicationCode,
                'old' => $oldTotal,
                'new' => $newTotal,
                'diff' => $difference,
                'exp' => $newScores['experience'],
                'aoe' => $newScores['application_of_education']
            ];
            $updated++;
        } else {
            printf("❌ Update failed: %s\n", $updateStmt->error);
            $errors++;
        }
        
        $updateStmt->close();
        
    } catch (Exception $e) {
        printf("❌ Error: %s\n", $e->getMessage());
        $errors++;
    }
}

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║                        COMPLETED                            ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

echo "📊 Total records:       {$totalRecords}\n";
echo "✅ Successfully updated:  {$updated}\n";
echo "❌ Errors:              {$errors}\n";

if (!empty($changes)) {
    echo "\n╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                   SYNCHRONIZED RECORDS                      ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n\n";
    
    echo sprintf(
        "%-20s | %-8s | %-8s | %-8s | %-5s | %-5s\n",
        "Code",
        "Old",
        "New",
        "Change",
        "Exp",
        "AoE"
    );
    echo str_repeat("-", 73) . "\n";
    
    foreach ($changes as $change) {
        echo sprintf(
            "%-20s | %8.2f | %8.2f | %+8.2f | %5.2f | %5.2f\n",
            $change['code'],
            $change['old'],
            $change['new'],
            $change['diff'],
            $change['exp'],
            $change['aoe']
        );
    }
}

echo "\n✅ Database fully synchronized with live preview!\n\n";

$conn->close();
?>
