<?php
/**
 * Test: Verify position parsing fix
 */

// Test the regex parsing
$testValues = [
    'Group TEACHING POSITIONS / Salary Grade 11',
    'Group ADMINISTRATIVE POSITIONS / Salary Grade 15',
    'Group SUPPORT STAFF / Salary Grade 5'
];

echo "<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial; margin: 20px; }
        .test { background: #f9f9f9; border: 1px solid #ccc; padding: 10px; margin: 10px 0; }
        .good { color: green; }
        .bad { color: red; }
    </style>
</head>
<body>
<h1>Position Parsing Test</h1>";

foreach ($testValues as $input) {
    echo "<div class='test'>";
    echo "<p><strong>Input:</strong> " . htmlspecialchars($input) . "</p>";
    
    preg_match('/Group\s+(.+?)\s*\/\s*Salary Grade\s+(\d+)/', $input, $matches);
    
    if (!empty($matches)) {
        $positionGroup = trim($matches[1]);
        $salaryGrade = intval($matches[2]);
        echo "<p class='good'>✓ <strong>Position Group:</strong> " . htmlspecialchars($positionGroup) . "</p>";
        echo "<p class='good'>✓ <strong>Salary Grade:</strong> " . $salaryGrade . " (Type: " . gettype($salaryGrade) . ")</p>";
    } else {
        echo "<p class='bad'>✗ Failed to parse</p>";
    }
    
    echo "</div>";
}

echo "</body></html>";
?>
