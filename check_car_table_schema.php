<?php
/**
 * Check comparative_assessment_results table schema
 */

// Database connection
$conn = new mysqli('localhost', 'root', '', 'hrmpsb_evaluationsystem_v2');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>comparative_assessment_results Table Schema</h2>\n";
echo "<pre>\n";

// Get table structure
$query = "DESCRIBE comparative_assessment_results";
$result = $conn->query($query);

if ($result) {
    echo "Columns:\n";
    echo "========\n";
    while ($row = $result->fetch_assoc()) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
} else {
    echo "Error: " . $conn->error . "\n";
}

echo "\nChecking for position_group and salary_grade columns:\n";
echo "================================================\n";

// Check if columns exist
$checkQuery = "SHOW COLUMNS FROM comparative_assessment_results WHERE Field IN ('position_group', 'salary_grade')";
$checkResult = $conn->query($checkQuery);

if ($checkResult && $checkResult->num_rows > 0) {
    echo "Found columns:\n";
    while ($row = $checkResult->fetch_assoc()) {
        echo "✓ " . $row['Field'] . "\n";
    }
} else {
    echo "✗ position_group and salary_grade columns NOT found\n";
    echo "\nThis means we need to add these columns to the table\n";
}

$conn->close();
echo "\n</pre>\n";
?>
