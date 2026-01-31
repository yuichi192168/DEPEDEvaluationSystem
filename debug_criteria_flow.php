<?php
/**
 * Debug: Trace Criteria Data Flow
 * Inspect what data is actually available at report generation time
 */

require_once 'config/evaluation_criteria.php';
require_once 'classes/HRMPSBEvaluator.php';

// Simulate the same evaluation flow as process_evaluation.php

// Simulate form input
$positionGroup = 'TEACHING POSITIONS';
$salaryGrade = 11;
$category = null;

// Step 1: Create evaluator
$evaluator = new HRMPSBEvaluator($positionGroup, $salaryGrade, $category);

// Step 2: Prepare applicant data
$applicantData = [
    'name' => 'Test Applicant',
    'position' => 'Teacher I',
    'education' => [
        'degree' => 'Bachelor',
        'masters_units' => 0,
        'doctoral_units' => 0
    ],
    'training' => 100,
    'experience' => 60,  // months
    'performance' => 4,
    'outstanding_accomplishments' => 28,
    'application_of_education' => 22,
    'application_of_ld' => 0,
    'potential' => 0
];

// Step 3: Prepare baseline data
$baselineData = [
    'education' => [
        'degree' => 'Bachelor',
        'masters_units' => 0,
        'doctoral_units' => 0
    ],
    'training' => 80,
    'experience' => 12,  // months
    'performance' => 3,
    'outstanding_accomplishments' => 0,
    'application_of_education' => 0,
    'application_of_ld' => 0,
    'potential' => 0
];

// Step 4: Evaluate
$evaluation = $evaluator->evaluateApplicant($applicantData, $baselineData);

// Step 5: Check what's in the evaluation
echo "<h2>Evaluation Data Structure Analysis</h2>\n";

echo "<h3>Overall Evaluation Keys:</h3>\n";
echo "<pre>";
print_r(array_keys($evaluation));
echo "</pre>\n";

echo "<h3>Evaluation ['criteria'] Structure:</h3>\n";
echo "<p><strong>Count of criteria:</strong> " . count($evaluation['criteria']) . "</p>\n";
echo "<p><strong>Criteria keys:</strong></p>\n";
echo "<ul>\n";
foreach ($evaluation['criteria'] as $key => $data) {
    $score = $data['final_score'] ?? 0;
    echo "<li><strong>$key:</strong> Final Score = $score</li>\n";
}
echo "</ul>\n";

// Step 6: Test criteria mapping
echo "<h3>Testing Criteria Mapping (What the report generator does):</h3>\n";

$criteria = getEvaluationCriteria($positionGroup, $salaryGrade, $category);
echo "<p><strong>getEvaluationCriteria() returned:</strong> " . ($criteria ? 'ARRAY' : 'NULL') . "</p>\n";

if ($criteria) {
    echo "<p><strong>Criteria config keys:</strong></p>\n";
    echo "<pre>";
    print_r(array_keys($criteria['criteria']));
    echo "</pre>\n";
    
    echo "<p><strong>Mapping (config a-h to db keys):</strong></p>\n";
    $dbToKey = [
        'education' => 'a',
        'training' => 'b',
        'experience' => 'c',
        'performance' => 'd',
        'outstanding_accomplishments' => 'e',
        'application_of_education' => 'f',
        'application_of_ld' => 'g',
        'potential' => 'h'
    ];
    
    $mappings = [];
    foreach ($dbToKey as $dbKey => $configKey) {
        if (isset($criteria['criteria'][$configKey])) {
            $maxPoints = $criteria['criteria'][$configKey]['max_points'];
            $name = $criteria['criteria'][$configKey]['name'];
            $mappings[$dbKey] = [
                'name' => $name,
                'max_points' => $maxPoints
            ];
            $exists = isset($evaluation['criteria'][$dbKey]) ? 'YES' : 'NO';
            echo "<p>• <strong>$dbKey</strong> → config key '$configKey' → {$name} ({$maxPoints}pt) [In evaluation: $exists]</p>\n";
        }
    }
    
    echo "<h3>Valid Criteria That Would Be Displayed:</h3>\n";
    $validCount = 0;
    foreach ($mappings as $dbKey => $mapping) {
        if ($mapping['max_points'] > 0 && isset($evaluation['criteria'][$dbKey])) {
            $validCount++;
            $score = $evaluation['criteria'][$dbKey]['final_score'];
            echo "<p style='color:green;'>✓ {$mapping['name']} ({$mapping['max_points']}pt) - Actual Score: {$score}</p>\n";
        }
    }
    echo "<p><strong>Total valid criteria to display: $validCount</strong></p>\n";
}

echo "<h3>Summary</h3>\n";
if (count($evaluation['criteria']) > 0 && $criteria && count($mappings) > 0) {
    echo "<p style='color:green;'><strong>✓ DATA IS AVAILABLE</strong> - Criteria should display</p>\n";
} else {
    echo "<p style='color:red;'><strong>✗ DATA MISSING</strong> - Need to investigate</p>\n";
}
?>
