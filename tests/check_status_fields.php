<?php
require_once 'classes/DBConnection.php';

$conn = DBConnection::getConnection();

echo "=== APPLICANTS TABLE ===\n";
$result = $conn->query("DESCRIBE applicants");
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}

echo "\n=== COMPARATIVE_ASSESSMENT_RESULTS TABLE ===\n";
$result = $conn->query("DESCRIBE comparative_assessment_results");
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}

echo "\n=== EVALUATIONS TABLE ===\n";
$result = $conn->query("DESCRIBE evaluations");
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
?>
