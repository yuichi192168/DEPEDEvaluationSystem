<?php
/**
 * Recalculate All Database Scores Using Live Preview Formula
 * This script updates all comparative_assessment_results records to match live preview calculations
 */

session_start();
require_once 'initialize.php';
require_once 'config/baseline_library.php';
require_once 'config/evaluation_criteria.php';
require_once 'classes/HRMPSBEvaluator.php';

// Database connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Recalculate All Scores</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { color: blue; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .changed { background-color: #fff3cd; }
        pre { background: #f4f4f4; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Recalculate All Database Scores</h1>
    <p class='info'>This will update all records in comparative_assessment_results to match live preview calculations.</p>
";

// Fetch all records
$query = "SELECT * FROM comparative_assessment_results ORDER BY id ASC";
$result = $conn->query($query);

if (!$result) {
    echo "<p class='error'>Error fetching records: " . $conn->error . "</p>";
    exit;
}

$totalRecords = $result->num_rows;
$updated = 0;
$skipped = 0;
$errors = 0;

echo "<p class='info'>Found {$totalRecords} records to process.</p>";
echo "<table>
<thead>
    <tr>
        <th>ID</th>
        <th>Application Code</th>
        <th>Name</th>
        <th>Position</th>
        <th>Old Total</th>
        <th>New Total</th>
        <th>Difference</th>
        <th>Status</th>
    </tr>
</thead>
<tbody>";

while ($row = $result->fetch_assoc()) {
    $oldTotal = floatval($row['total_score']);
    $applicationCode = $row['application_code'];
    
    try {
        // Fetch draft data for this applicant
        $stmt = $conn->prepare("SELECT data FROM drafts WHERE application_code = ? LIMIT 1");
        $stmt->bind_param("s", $applicationCode);
        $stmt->execute();
        $draftResult = $stmt->get_result();
        
        if ($draftResult->num_rows === 0) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$applicationCode}</td>
                <td>{$row['name']}</td>
                <td>{$row['position_name']}</td>
                <td>{$oldTotal}</td>
                <td>-</td>
                <td>-</td>
                <td class='warning'>No draft data</td>
            </tr>";
            $skipped++;
            continue;
        }
        
        $draft = $draftResult->fetch_assoc();
        $draftData = json_decode($draft['data'], true) ?: [];
        
        if (empty($draftData)) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$applicationCode}</td>
                <td>{$row['name']}</td>
                <td>{$row['position_name']}</td>
                <td>{$oldTotal}</td>
                <td>-</td>
                <td>-</td>
                <td class='warning'>Empty draft</td>
            </tr>";
            $skipped++;
            continue;
        }
        
        // Determine position group
        $posGroup = $row['position_group'] ?? 'NON-TEACHING LEVEL II';
        $evaluator = new HRMPSBEvaluator($posGroup);
        
        // Get baseline using position_key from draft
        $positionKey = $draftData['position_key'] ?? 'custom';
        $baseline = getBaselineForPosition($positionKey);
        
        // Build applicant data from draft
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
        
        // Recalculate using current formulas (same as live preview)
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
            
            // Calculate new total
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
                $rowClass = abs($difference) > 0.01 ? 'changed' : '';
                $diffDisplay = abs($difference) > 0.01 ? sprintf("%+.2f", $difference) : 'No change';
                
                echo "<tr class='{$rowClass}'>
                    <td>{$row['id']}</td>
                    <td>{$applicationCode}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['position_name']}</td>
                    <td>" . number_format($oldTotal, 2) . "</td>
                    <td>" . number_format($newTotal, 2) . "</td>
                    <td>{$diffDisplay}</td>
                    <td class='success'>Updated</td>
                </tr>";
                $updated++;
            } else {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$applicationCode}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['position_name']}</td>
                    <td>{$oldTotal}</td>
                    <td>-</td>
                    <td>-</td>
                    <td class='error'>Update failed: {$updateStmt->error}</td>
                </tr>";
                $errors++;
            }
            
            $updateStmt->close();
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$applicationCode}</td>
            <td>{$row['name']}</td>
            <td>{$row['position_name']}</td>
            <td>{$oldTotal}</td>
            <td>-</td>
            <td>-</td>
            <td class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</td>
        </tr>";
        $errors++;
    }
}

echo "</tbody>
</table>

<h2>Summary</h2>
<ul>
    <li class='info'>Total records: {$totalRecords}</li>
    <li class='success'>Successfully updated: {$updated}</li>
    <li class='warning'>Skipped (no draft data): {$skipped}</li>
    <li class='error'>Errors: {$errors}</li>
</ul>

<p><a href='comparative_assessment_results.php?view=all'>View All Results</a></p>
</body>
</html>";

$conn->close();
?>
