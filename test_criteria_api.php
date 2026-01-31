<?php
/**
 * Test script for evaluation criteria API
 */

// Load positions
require_once __DIR__ . '/config/baseline_library.php';
require_once __DIR__ . '/config/evaluation_criteria.php';

// Find a TEACHING POSITIONS entry
echo "=== TESTING EVALUATION CRITERIA API ===\n\n";

// Test 1: Direct function call for TEACHING POSITIONS with default (Teacher I)
echo "Test 1: getEvaluationCriteria('TEACHING POSITIONS', 11)\n";
$criteria = getEvaluationCriteria('TEACHING POSITIONS', 11);
echo json_encode(formatCriteriaForJSON($criteria), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

// Test 2: NON-TEACHING LEVEL I
echo "Test 2: getEvaluationCriteria('NON-TEACHING LEVEL I', 5, 'non_general_services')\n";
$criteria2 = getEvaluationCriteria('NON-TEACHING LEVEL I', 5, 'non_general_services');
echo json_encode(formatCriteriaForJSON($criteria2), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

// Test 3: Check a position from baseline library
echo "Test 3: Find a TEACHING POSITIONS position in baseline library\n";
$found = false;
foreach ($positions as $key => $pos) {
    if ($pos['position_group'] === 'TEACHING POSITIONS') {
        echo "Found position key: {$key}\n";
        echo "Position name: {$pos['position_name']}\n";
        echo "Position group: {$pos['position_group']}\n";
        echo "Salary grade: {$pos['salary_grade']}\n\n";
        
        $criteria3 = getEvaluationCriteria($pos['position_group'], $pos['salary_grade']);
        echo "Criteria for this position:\n";
        echo json_encode(formatCriteriaForJSON($criteria3), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        $found = true;
        break;
    }
}

if (!$found) {
    echo "No TEACHING POSITIONS found in baseline library\n";
}
?>
