<?php
/**
 * Sample Test Case
 * DepEd HRMPSB Evaluation System
 * 
 * This file demonstrates the evaluation system using the sample data
 * from the requirements document.
 */

require_once 'classes/HRMPSBEvaluator.php';
require_once 'classes/IESReportGenerator.php';

// Sample Data from Requirements
$positionGroup = 'A'; // Non-Teaching Level 1 (ICT)

// Initialize Evaluator
$evaluator = new HRMPSBEvaluator($positionGroup);

// Applicant Qualifications
$applicantData = [
    'name' => 'Juan Dela Cruz',
    'position' => 'Information and Communications Technology',
    'education' => [
        'degree' => 'Bachelor',
        'masters_units' => 18,
        'doctoral_units' => 0
    ],
    'training' => 40, // hours
    'experience' => 48, // months (4 years)
    'performance' => 5, // assuming rating scale
    'outstanding_accomplishments' => 2,
    'application_of_education' => 3,
    'application_of_ld' => 2,
    'potential' => 4
];

// Minimum Qualification Standards (Baseline)
$baselineData = [
    'education' => [
        'degree' => 'Bachelor',
        'masters_units' => 0,
        'doctoral_units' => 0
    ],
    'training' => 0, // None required
    'experience' => 0, // None required
    'performance' => 0,
    'outstanding_accomplishments' => 0,
    'application_of_education' => 0,
    'application_of_ld' => 0,
    'potential' => 0
];

// Perform Evaluation
echo "=== DepEd HRMPSB Evaluation System Test ===\n\n";
echo "Position: " . $applicantData['position'] . "\n";
echo "Position Group: Group " . $positionGroup . "\n";
echo "Applicant: " . $applicantData['name'] . "\n\n";

$evaluation = $evaluator->evaluateApplicant($applicantData, $baselineData);

// Display Detailed Results
echo "=== DETAILED EVALUATION RESULTS ===\n\n";

foreach ($evaluation['criteria'] as $criterion => $result) {
    echo strtoupper($result['criterion']) . ":\n";
    echo "  Applicant Qualification: " . $result['applicant_qualification'] . "\n";
    echo "  Applicant Level: " . $result['applicant_level'] . "\n";
    echo "  Baseline Qualification: " . $result['baseline_qualification'] . "\n";
    echo "  Baseline Level: " . $result['baseline_level'] . "\n";
    echo "  Increment: Level " . $result['applicant_level'] . " - Level " . $result['baseline_level'] . " = " . $result['increment'] . "\n";
    echo "  Weight: " . $result['weight'] . "%\n";
    echo "  Points: " . number_format($result['points'], 2) . "\n";
    echo "  Final Score: " . number_format($result['final_score'], 2) . "\n";
    echo "\n";
}

echo "=== TOTAL SCORE: " . number_format($evaluation['total_score'], 2) . " ===\n\n";

// Generate IES Report
$reportGenerator = new IESReportGenerator();
$iesText = $reportGenerator->generateTextIES($evaluation);
echo $iesText;

// Also generate HTML version and save to file
$iesHTML = $reportGenerator->generateIES($evaluation);
file_put_contents('sample_ies_report.html', $iesHTML);
echo "\nHTML Report saved to: sample_ies_report.html\n";
?>

