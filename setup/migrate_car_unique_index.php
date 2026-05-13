<?php
/**
 * One-time migration to relax the CAR unique key so historical evaluations
 * can coexist across different assessment dates.
 */

require_once __DIR__ . '/../classes/DBConnection.php';

header('Content-Type: text/plain; charset=utf-8');

$conn = DBConnection::getConnection();
if (!$conn) {
    echo "Unable to connect to the database.\n";
    exit(1);
}

$conn->set_charset('utf8mb4');

$hasOld = false;
$hasNew = false;

$oldRes = $conn->query("SHOW INDEX FROM comparative_assessment_results WHERE Key_name = 'unique_position_applicant'");
if ($oldRes && $oldRes->num_rows > 0) {
    $hasOld = true;
}

$newRes = $conn->query("SHOW INDEX FROM comparative_assessment_results WHERE Key_name = 'unique_position_applicant_period'");
if ($newRes && $newRes->num_rows > 0) {
    $hasNew = true;
}

if ($hasOld) {
    $sql = "ALTER TABLE comparative_assessment_results DROP INDEX unique_position_applicant, ADD UNIQUE KEY unique_position_applicant_period (position_id, applicant_id, assessment_date)";
    if (!$conn->query($sql)) {
        echo "Migration failed: " . $conn->error . "\n";
        exit(1);
    }
    echo "Migrated CAR unique index to unique_position_applicant_period.\n";
} elseif (!$hasNew) {
    $sql = "ALTER TABLE comparative_assessment_results ADD UNIQUE KEY unique_position_applicant_period (position_id, applicant_id, assessment_date)";
    if (!$conn->query($sql)) {
        echo "Migration failed: " . $conn->error . "\n";
        exit(1);
    }
    echo "Added CAR unique index unique_position_applicant_period.\n";
} else {
    echo "CAR unique index is already migrated.\n";
}

$conn->close();
exit(0);
