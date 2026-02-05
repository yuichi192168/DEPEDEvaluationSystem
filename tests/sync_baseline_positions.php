<?php
/**
 * Sync Baseline Library Positions to Database
 * This script reads all positions from the baseline library and inserts them into the database
 */

require_once 'config/database.php';
require_once 'config/baseline_library.php';
require_once 'classes/DBConnection.php';

$conn = DBConnection::getConnection();

echo "Starting position sync from baseline library...\n\n";

$inserted = 0;
$skipped = 0;
$errors = 0;

// Iterate through all positions in the baseline library
foreach ($baselineLibrary as $key => $pos) {
    $position_name = $pos['position_name'];
    $position_group = $pos['position_group'];
    $salary_grade = $pos['salary_grade'];
    
    // Check if position already exists
    $checkStmt = $conn->prepare("SELECT id FROM positions WHERE position_name = ? AND salary_grade = ?");
    $checkStmt->bind_param("ss", $position_name, $salary_grade);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        echo "[SKIPPED] $position_name (SG $salary_grade) - Already exists\n";
        $skipped++;
        continue;
    }
    
    // Insert new position
    $insertStmt = $conn->prepare(
        "INSERT INTO positions (position_name, position_group, salary_grade) VALUES (?, ?, ?)"
    );
    $insertStmt->bind_param("sss", $position_name, $position_group, $salary_grade);
    
    try {
        if ($insertStmt->execute()) {
            $position_id = $conn->insert_id;
            
            // Also insert baseline qualifications for this position
            $insertBaselineStmt = $conn->prepare(
                "INSERT INTO baseline_qualifications (
                    position_id, education_degree, education_masters_units, education_doctoral_units,
                    training_hours, experience_months, performance_rating, outstanding_accomplishments,
                    application_of_education_level, application_of_ld_level, potential_level
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            
            $edu_degree = $pos['education']['degree'];
            $edu_masters = $pos['education']['masters_units'];
            $edu_doctoral = $pos['education']['doctoral_units'];
            $training = $pos['training'];
            $experience = $pos['experience'];
            $performance = $pos['performance'];
            $accomplishments = $pos['outstanding_accomplishments'];
            $app_education = $pos['application_of_education'];
            $app_ld = $pos['application_of_ld'];
            $potential = $pos['potential'];
            
            $insertBaselineStmt->bind_param(
                "isiiiiiiiii",
                $position_id, $edu_degree, $edu_masters, $edu_doctoral,
                $training, $experience, $performance, $accomplishments,
                $app_education, $app_ld, $potential
            );
            
            if ($insertBaselineStmt->execute()) {
                echo "[✓ INSERTED] $position_name (Group $position_group, SG $salary_grade)\n";
                $inserted++;
            } else {
                echo "[ERROR] Failed to insert baseline for $position_name: " . $insertBaselineStmt->error . "\n";
                $errors++;
            }
            $insertBaselineStmt->close();
        } else {
            echo "[ERROR] Failed to insert $position_name: " . $insertStmt->error . "\n";
            $errors++;
        }
    } catch (Exception $e) {
        echo "[ERROR] Exception for $position_name: " . $e->getMessage() . "\n";
        $errors++;
    }
    $insertStmt->close();
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "Sync Complete!\n";
echo "Inserted: $inserted positions\n";
echo "Skipped: $skipped positions (already exist)\n";
echo "Errors: $errors\n";
echo str_repeat("=", 60) . "\n";

// Verify final count
$countStmt = $conn->query("SELECT COUNT(*) as total FROM positions");
$countResult = $countStmt->fetch_assoc();
echo "\nTotal positions in database: " . $countResult['total'] . "\n";

?>
