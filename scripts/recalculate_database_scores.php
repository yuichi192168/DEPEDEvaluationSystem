<?php
/**
 * Command-line script to recalculate all database scores
 * Usage: php scripts/recalculate_database_scores.php
 */

// Include necessary files
require_once __DIR__ . '/../initialize.php';
require_once __DIR__ . '/../config/baseline_library.php';
require_once __DIR__ . '/../config/evaluation_criteria.php';
require_once __DIR__ . '/../classes/HRMPSBEvaluator.php';

// Database connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error . "\n");
}
$conn->set_charset("utf8mb4");

echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║  Recalculate All Database Scores (Live Preview Formula)  ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Fetch all records
$query = "SELECT * FROM comparative_assessment_results ORDER BY id ASC";
$result = $conn->query($query);

if (!$result) {
    die("❌ Error fetching records: " . $conn->error . "\n");
}

$totalRecords = $result->num_rows;
$updated = 0;
$skipped = 0;
$errors = 0;
$changes = [];

echo "📊 Found {$totalRecords} records to process.\n\n";

while ($row = $result->fetch_assoc()) {
    $oldTotal = floatval($row['total_score']);
    $applicationCode = $row['application_code'];
    
    echo "Processing [{$row['id']}] {$row['name']} ({$applicationCode})... ";
    
    try {
        // Fetch draft data
        $stmt = $conn->prepare("SELECT data FROM drafts WHERE application_code = ? LIMIT 1");
        $stmt->bind_param("s", $applicationCode);
        $stmt->execute();
        $draftResult = $stmt->get_result();
        
        if ($draftResult->num_rows === 0) {
            echo "⚠️  No draft data - SKIPPED\n";
            $skipped++;
            continue;
        }
        
        $draft = $draftResult->fetch_assoc();
        $draftData = json_decode($draft['data'], true) ?: [];
        
        if (empty($draftData)) {
            echo "⚠️  Empty draft - SKIPPED\n";
            $skipped++;
            continue;
        }
        
        // Determine position group
        $posGroup = $row['position_group'] ?? 'NON-TEACHING LEVEL II';
        $evaluator = new HRMPSBEvaluator($posGroup);
        
        // Get baseline
        $positionKey = $draftData['position_key'] ?? 'custom';
        $baseline = getBaselineForPosition($positionKey);
        
        // Build applicant data
        $appData = [
            'name' => $row['name'],
            'position' => $row['position_name'],
            'education' => [
                'degree' => $draftData['applicant_education_degree'] ?? 'None',
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
        
        // Build baseline
        $baseData = [
            'name' => 'Baseline',
            'position' => $row['position_name'],
            'education' => [
                'degree' => $baseline['education']['degree'] ?? 'None',
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
        
        // Recalculate using HRMPSBEvaluator (matches live preview)
        $evaluation = $evaluator->evaluateApplicant($appData, $baseData);
        
        if (isset($evaluation['criteria'])) {
            $newScores = [
                'education_score' => floatval($evaluation['criteria']['education']['final_score'] ?? 0),
                'training_score' => floatval($evaluation['criteria']['training']['final_score'] ?? 0),
                'experience_score' => floatval($evaluation['criteria']['experience']['final_score'] ?? 0),
                'performance_score' => floatval($evaluation['criteria']['performance']['final_score'] ?? 0),
                'outstanding_accomplishments_score' => floatval($evaluation['criteria']['outstanding_accomplishments']['final_score'] ?? 0),
                'application_of_education_score' => floatval($evaluation['criteria']['application_of_education']['final_score'] ?? 0),
                'application_of_ld_score' => floatval($evaluation['criteria']['application_of_ld']['final_score'] ?? 0),
                'potential_score' => floatval($evaluation['criteria']['potential']['final_score'] ?? 0)
            ];
            
            $newTotal = array_sum($newScores);
            $difference = $newTotal - $oldTotal;
            
            // Update database
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
                $newScores['education_score'],
                $newScores['training_score'],
                $newScores['experience_score'],
                $newScores['performance_score'],
                $newScores['outstanding_accomplishments_score'],
                $newScores['application_of_education_score'],
                $newScores['application_of_ld_score'],
                $newScores['potential_score'],
                $newTotal,
                $row['id']
            );
            
            if ($updateStmt->execute()) {
                if (abs($difference) > 0.01) {
                    echo sprintf("✅ UPDATED (%.2f → %.2f, %+.2f)\n", $oldTotal, $newTotal, $difference);
                    $changes[] = [
                        'name' => $row['name'],
                        'code' => $applicationCode,
                        'old' => $oldTotal,
                        'new' => $newTotal,
                        'diff' => $difference
                    ];
                } else {
                    echo "✓ No change\n";
                }
                $updated++;
            } else {
                echo "❌ Update failed: {$updateStmt->error}\n";
                $errors++;
            }
            
            $updateStmt->close();
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║                       SUMMARY                              ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";
echo "📊 Total records processed: {$totalRecords}\n";
echo "✅ Successfully updated: {$updated}\n";
echo "⚠️  Skipped (no draft data): {$skipped}\n";
echo "❌ Errors: {$errors}\n";

if (!empty($changes)) {
    echo "\n╔════════════════════════════════════════════════════════════╗\n";
    echo "║                   SCORE CHANGES                            ║\n";
    echo "╚════════════════════════════════════════════════════════════╝\n\n";
    
    foreach ($changes as $change) {
        printf(
            "• %s (%s): %.2f → %.2f (%+.2f)\n",
            $change['name'],
            $change['code'],
            $change['old'],
            $change['new'],
            $change['diff']
        );
    }
}

echo "\n✅ Database recalculation complete!\n\n";

$conn->close();
?>
