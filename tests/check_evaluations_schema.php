<?php
require_once 'classes/DBConnection.php';

$conn = DBConnection::getConnection();

echo "Evaluations table structure:\n";
$result = $conn->query("DESCRIBE evaluations");
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . " (" . ($row['Null'] == 'YES' ? 'nullable' : 'NOT NULL') . ")\n";
}

echo "\n\nEvaluation_details table structure:\n";
$result = $conn->query("DESCRIBE evaluation_details");
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . " (" . ($row['Null'] == 'YES' ? 'nullable' : 'NOT NULL') . ")\n";
}
?>
