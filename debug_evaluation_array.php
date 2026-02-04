<?php
/**
 * DEBUG: Check what's in the evaluation array being passed to generateIES
 * This will help identify if the criteria array is empty or has wrong structure
 */

require_once 'config/evaluation_criteria.php';
require_once 'classes/HRMPSBEvaluator.php';
require_once 'classes/IESReportGenerator.php';

echo "<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial; margin: 20px; }
        .debug { background: #fff8dc; border: 2px solid red; padding: 15px; margin: 10px 0; }
        .good { color: green; }
        .bad { color: red; }
        pre { background: #f0f0f0; padding: 10px; overflow-x: auto; font-size: 11px; }
    </style>
</head>
<body>
<h1>IES Evaluation Array Debug</h1>";

// Create a test evaluation
$positionGroup = 'TEACHING POSITIONS';
$salaryGrade = 11;

$evaluator = new HRMPSBEvaluator($positionGroup, $salaryGrade, null);

$applicantData = [
    'name' => 'Test Applicant',
    'position' => 'Teacher I',
    'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
    'training' => 100,
    'experience' => 60,
    'performance' => 4,
    'outstanding_accomplishments' => 28,
    'application_of_education' => 22,
    'application_of_ld' => 0,
    'potential' => 0
];

$baselineData = [
    'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
    'training' => 80,
    'experience' => 12,
    'performance' => 3,
    'outstanding_accomplishments' => 0,
    'application_of_education' => 0,
    'application_of_ld' => 0,
    'potential' => 0
];

$evaluation = $evaluator->evaluateApplicant($applicantData, $baselineData);

echo "<div class='debug'>";
echo "<h2>Evaluation Array Structure</h2>";
echo "<p><strong>Keys in evaluation array:</strong></p>";
echo "<pre>";
print_r(array_keys($evaluation));
echo "</pre>";

echo "<p><strong>evaluation['criteria'] exists?</strong> ";
if (isset($evaluation['criteria'])) {
    echo "<span class='good'>YES</span>";
} else {
    echo "<span class='bad'>NO - THIS IS THE PROBLEM!</span>";
}
echo "</p>";

if (isset($evaluation['criteria'])) {
    echo "<p><strong>evaluation['criteria'] type:</strong> " . gettype($evaluation['criteria']) . "</p>";
    echo "<p><strong>Number of criteria:</strong> " . count($evaluation['criteria']) . "</p>";
    echo "<p><strong>Criteria keys:</strong></p>";
    echo "<pre>";
    print_r(array_keys($evaluation['criteria']));
    echo "</pre>";
    
    echo "<p><strong>First criterion structure (education):</strong></p>";
    if (isset($evaluation['criteria']['education'])) {
        echo "<pre>";
        print_r($evaluation['criteria']['education']);
        echo "</pre>";
    } else {
        echo "<span class='bad'>education key not found!</span>";
    }
} else {
    echo "<p class='bad'><strong>PROBLEM: evaluation['criteria'] is not set!</strong></p>";
    echo "<p>This means HRMPSBEvaluator is not returning criteria in the right structure.</p>";
}

echo "<p><strong>Total Score:</strong> " . $evaluation['total_score'] . "</p>";
echo "<p><strong>Applicant Name:</strong> " . $evaluation['applicant_name'] . "</p>";

echo "</div>";

// Now test with IES Report Generator
echo "<div class='debug'>";
echo "<h2>Testing IES Report Generation</h2>";

$generator = new IESReportGenerator();
$generator->setPositionGroup($positionGroup);
$generator->setSalaryGrade($salaryGrade);

$additionalData = [
    'application_code' => 'TEST',
    'schools_division_office' => 'Test',
    'contact_number' => '09123456789',
    'job_group_sg_level' => 'Group TEACHING POSITIONS / Salary Grade 11',
    'hrmpsb_chair' => 'Test Chair',
    'position_group' => $positionGroup,
    'salary_grade' => $salaryGrade,
    'category' => null
];

$html = $generator->generateIES($evaluation, $additionalData);

// Count data rows in the generated HTML
preg_match_all('/<tr>[\s\S]*?<\/tr>/', $html, $allRows);
preg_match_all('/<tr>[\s\S]*?<td[^>]*class="col-criteria"[^>]*>((?!TOTAL).*?)<\/td>/s', $html, $dataRows);

echo "<p><strong>Total table rows in HTML:</strong> " . count($allRows[0]) . "</p>";
echo "<p><strong>Data rows (criteria) in HTML:</strong> " . count($dataRows[0]) . "</p>";

if (count($dataRows[0]) > 0) {
    echo "<p class='good'><strong>✓ HTML IS rendering criteria rows correctly</strong></p>";
    echo "<p>If you're not seeing them in the browser, it's a CSS/display issue.</p>";
} else {
    echo "<p class='bad'><strong>✗ HTML has NO criteria rows - the loop is not rendering</strong></p>";
    echo "<p>This means evaluation['criteria'] is empty or validCriteriaOrder is empty.</p>";
}

// Extract table content for inspection
if (preg_match('/<tbody>([\s\S]*?)<\/tbody>/', $html, $tbody)) {
    echo "<p><strong>First 500 chars of table body:</strong></p>";
    echo "<pre>" . htmlspecialchars(substr($tbody[1], 0, 500)) . "...</pre>";
}

echo "</div>";

echo "</body></html>";
?>
