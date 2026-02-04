<?php
/**
 * Test Administrator Position Criteria Loading
 * Check if ADMINISTRATIVE POSITIONS criteria are properly defined and accessible
 */

require_once 'config/evaluation_criteria.php';

echo "<h2>Testing Administrator Position Criteria</h2>\n";
echo "<pre>\n";

// Check what keys exist in evaluation_criteria
echo "Keys in \$evaluationCriteria:\n";
echo "================================\n";
foreach ($evaluationCriteria as $key => $value) {
    echo "- $key\n";
}
echo "\n";

// Test loading criteria for ADMINISTRATIVE POSITIONS (what code looks for)
echo "Testing: getCriteriaMappings('ADMINISTRATIVE POSITIONS', 15, null)\n";
echo "=================================================================\n";
require_once 'api/get_baseline.php'; // This likely has getCriteriaMappings function

if (function_exists('getCriteriaMappings')) {
    $result = getCriteriaMappings('ADMINISTRATIVE POSITIONS', 15, null);
    echo "Result: ";
    var_dump($result);
    echo "\n";
} else {
    echo "ERROR: getCriteriaMappings() function not found!\n";
    echo "Searching for function definition...\n";
}

// Test loading criteria for SCHOOL ADMINISTRATION POSITION (what's in config)
echo "\nTesting: getCriteriaMappings('SCHOOL ADMINISTRATION POSITION', 15, null)\n";
echo "===================================================================\n";
if (function_exists('getCriteriaMappings')) {
    $result = getCriteriaMappings('SCHOOL ADMINISTRATION POSITION', 15, null);
    echo "Result: ";
    var_dump($result);
    echo "Count: " . count($result) . " criteria\n";
    if (!empty($result)) {
        echo "\nCriteria Details:\n";
        foreach ($result as $criteria) {
            echo "  - " . $criteria[1] . " (" . $criteria[2] . "pt)\n";
        }
    }
} else {
    echo "ERROR: getCriteriaMappings() function not found!\n";
}

// Check the actual evaluation_criteria values for admin
echo "\n\nDirect check of \$evaluationCriteria['SCHOOL ADMINISTRATION POSITION']:\n";
echo "================================================================\n";
if (isset($evaluationCriteria['SCHOOL ADMINISTRATION POSITION'])) {
    echo "✓ Key found\n";
    echo "Structure: " . print_r($evaluationCriteria['SCHOOL ADMINISTRATION POSITION'], true) . "\n";
} else {
    echo "✗ Key NOT found\n";
}

echo "\n</pre>\n";
?>
