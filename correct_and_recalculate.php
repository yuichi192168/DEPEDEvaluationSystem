<?php
/**
 * Update Draft Data with Correct Applicant Input Values
 * Then Recalculate Database from Updated Drafts
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
echo "║   Update Drafts & Recalculate Database                      ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Map of application codes to corrected values
$corrections = [
    'NT-AOII-2026-003' => [
        'applicant_application_of_education' => 8.5,
        'reason' => 'User confirmed: AoE should be 8.5'
    ]
];

foreach ($corrections as $code => $correction) {
    echo "Processing {$code}...\n";
    
    // Fetch current draft
    $stmt = $conn->prepare("SELECT id, data FROM drafts WHERE application_code = ? LIMIT 1");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo "  ⚠️  No draft found\n";
        continue;
    }
    
    $draft = $result->fetch_assoc();
    $draftData = json_decode($draft['data'], true);
    $draftId = $draft['id'];
    
    // Apply corrections
    foreach ($correction as $key => $value) {
        if ($key !== 'reason') {
            $oldVal = $draftData[$key] ?? 'N/A';
            $draftData[$key] = $value;
            echo "  - $key: $oldVal → $value\n";
        }
    }
    
    // Save updated draft
    $updatedData = json_encode($draftData);
    $updateStmt = $conn->prepare("UPDATE drafts SET data = ? WHERE id = ?");
    $updateStmt->bind_param("si", $updatedData, $draftId);
    
    if ($updateStmt->execute()) {
        echo "  ✅ Draft updated\n";
    } else {
        echo "  ❌ Draft update failed: " . $updateStmt->error . "\n";
    }
    $updateStmt->close();
}

echo "\n════════════════════════════════════════════════════════════════\n";
echo "Now recalculating database scores from updated drafts...\n";
echo "════════════════════════════════════════════════════════════════\n\n";

// Fetch ALL records and recalculate
$query = "SELECT * FROM comparative_assessment_results WHERE application_code IN ('" . implode("','", array_keys($corrections)) . "')";
$result = $conn->query($query);

$updated = 0;
$changes = [];

while ($row = $result->fetch_assoc()) {
    $applicationCode = $row['application_code'];
    $oldTotal = floatval($row['total_score']);
    
    printf("[%s] ... ", $applicationCode);
    
    try {
        // Fetch updated draft
        $stmt = $conn->prepare("SELECT data FROM drafts WHERE application_code = ? LIMIT 1");
        $stmt->bind_param("s", $applicationCode);
        $stmt->execute();
        $draftResult = $stmt->get_result();
        $draft = $draftResult->fetch_assoc();
        $draftData = json_decode($draft['data'], true);
        $stmt->close();
        
        if (empty($draftData)) {
            echo "❌ Invalid draft\n";
            continue;
        }
        
        // Recreate evaluator
        $posGroup = $row['position_group'] ?? 'NON-TEACHING LEVEL II';
        $evaluator = new HRMPSBEvaluator($posGroup);
        
        $positionKey = $draftData['position_key'] ?? 'custom';
        $baseline = getBaselineForPosition($positionKey);
        
        // Build applicant data
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
        
        // Recalculate
        $evaluation = $evaluator->evaluateApplicant($appData, $baseData);
        
        if (!isset($evaluation['criteria'])) {
            echo "❌ Evaluation failed\n";
            continue;
        }
        
        // Extract scores
        $newScores = [];
        foreach (['education', 'training', 'experience', 'performance', 'outstanding_accomplishments', 
                  'application_of_education', 'application_of_ld', 'potential'] as $criterion) {
            $newScores[$criterion] = floatval($evaluation['criteria'][$criterion]['final_score'] ?? 0);
        }
        
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
            printf("✅ %.2f → %.2f (%+.2f)\n", $oldTotal, $newTotal, $difference);
            $changes[] = [
                'code' => $applicationCode,
                'old' => $oldTotal,
                'new' => $newTotal,
                'exp' => $newScores['experience'],
                'aoe' => $newScores['application_of_education']
            ];
            $updated++;
        } else {
            echo "❌ Update failed\n";
        }
        
        $updateStmt->close();
        
    } catch (Exception $e) {
        printf("❌ Error: %s\n", $e->getMessage());
    }
}

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║                        RESULTS                              ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

echo "📊 Records updated: {$updated}\n";

if (!empty($changes)) {
    echo "\n👇 DETAILED CHANGES:\n\n";
    foreach ($changes as $change) {
        echo "Code: {$change['code']}\n";
        echo "  Database: {$change['old']} → {$change['new']} ({$change['exp']} exp, {$change['aoe']} aoe)\n\n";
    }
}

echo "✅ Complete!\n\n";

$conn->close();
?>
