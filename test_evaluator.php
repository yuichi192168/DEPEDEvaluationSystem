<?php
require_once 'config/evaluation_criteria.php';
require_once 'classes/HRMPSBEvaluator.php';

// Test NON-TEACHING LEVEL II with SG 2
$posGroup = 'NON-TEACHING LEVEL II';
$salaryGrade = 'SG 2';
$category = 'NON-TEACHING LEVEL II';

echo "Creating evaluator with:\n";
echo "- Position: $posGroup\n";
echo "- Salary Grade: $salaryGrade\n";
echo "- Category: $category\n\n";

$evaluator = new HRMPSBEvaluator($posGroup, $salaryGrade, $category);

// Test with 93 months, baseline 0
$appData = [
    'name' => 'Test',
    'position' => 'Admin Aide II',
    'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
    'training' => 0,
    'experience' => 93,
    'performance' => 0,
    'outstanding_accomplishments' => 0,
    'application_of_education' => 0,
    'application_of_ld' => 0,
    'potential' => 0
];

$baseData = [
    'name' => 'Baseline',
    'position' => 'Admin Aide II',
    'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
    'training' => 0,
    'experience' => 0,
    'performance' => 0,
    'outstanding_accomplishments' => 0,
    'application_of_education' => 0,
    'application_of_ld' => 0,
    'potential' => 0
];

$eval = $evaluator->evaluateApplicant($appData, $baseData);

echo "Experience Score: " . $eval['criteria']['experience']['final_score'] . "\n\n";

echo "Details:\n";
print_r($eval['criteria']['experience']);
