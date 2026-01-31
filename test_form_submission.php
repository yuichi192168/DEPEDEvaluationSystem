<?php
/**
 * Test: Simulate actual form submission process
 */

// Simulate the POST request as it comes from the form
$_POST = [
    'submit' => 'Generate Report',
    'position_applied' => 'Teacher I',
    'job_group_sg_level' => 'Group TEACHING POSITIONS / Salary Grade 11',
    'applicant_name' => 'jkljkljklLKJ',
    'application_code' => 'l;k;lkkl;k;',
    'schools_division_office' => 'City Schools Division of Cabuyao',
    'contact_number' => '',
    'hrmpsb_chair' => 'RANDY D. PUNZALAN, CESO VI',
    'applicant_education' => 'Bachelor',
    'applicant_education_masters' => '0',
    'applicant_education_doctoral' => '0',
    'applicant_training' => '0',
    'applicant_experience_years' => '0',
    'applicant_experience_months' => '0',
    'applicant_performance' => '0',
    'applicant_outstanding' => '0',
    'applicant_application_education' => '0',
    'applicant_application_ld' => '0',
    'applicant_potential' => '0',
    'baseline_type' => 'library',
    'baseline_library_position' => 'Teacher I',
    'baseline_education' => 'Bachelor',
    'baseline_education_masters' => '0',
    'baseline_education_doctoral' => '0',
    'baseline_training' => '0',
    'baseline_experience_years' => '0',
    'baseline_experience_months' => '0',
    'baseline_performance' => '3',
    'baseline_outstanding' => '0',
    'baseline_application_education' => '0',
    'baseline_application_ld' => '0',
    'baseline_potential' => '0',
    'output_format' => 'html'
];

require_once 'config/evaluation_criteria.php';
require_once 'classes/HRMPSBEvaluator.php';
require_once 'classes/IESReportGenerator.php';

// Now run through the same logic as process_evaluation.php

echo "<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial; margin: 20px; }
        .section { background: #f9f9f9; border: 1px solid #ccc; padding: 15px; margin: 15px 0; }
        .good { color: green; }
        .bad { color: red; }
        pre { background: #f0f0f0; padding: 10px; overflow-x: auto; }
        table { border-collapse: collapse; width: 100%; }
        td { border: 1px solid #ccc; padding: 5px; }
    </style>
</head>
<body>
<h1>Form Submission Simulation</h1>";

// Extract and parse position group/salary grade
$jobGroupInput = $_POST['job_group_sg_level'];
$positionApplied = $_POST['position_applied'];

// Parse formatted string
preg_match('/Group\s+(.+?)\s*\/\s*Salary Grade\s+(\d+)/', $jobGroupInput, $matches);
if (!empty($matches)) {
    $positionGroup = $matches[1];
    $salaryGrade = intval($matches[2]);
} else {
    die("ERROR: Could not parse position");
}

echo "<div class='section'>";
echo "<h2>Position Context</h2>";
echo "<p>Position Group: <strong>" . htmlspecialchars($positionGroup) . "</strong></p>";
echo "<p>Salary Grade: <strong>" . $salaryGrade . "</strong> (Type: " . gettype($salaryGrade) . ")</p>";
echo "</div>";

// Prepare applicant data as done in process_evaluation.php
$applicantData = [
    'name' => $_POST['applicant_name'] ?? '',
    'position' => $positionApplied,
    'education' => [
        'degree' => $_POST['applicant_education'] ?? 'Bachelor',
        'masters_units' => intval($_POST['applicant_education_masters'] ?? 0),
        'doctoral_units' => intval($_POST['applicant_education_doctoral'] ?? 0)
    ],
    'training' => intval($_POST['applicant_training'] ?? 0),
    'experience' => (intval($_POST['applicant_experience_years'] ?? 0) * 12) + intval($_POST['applicant_experience_months'] ?? 0),
    'performance' => intval($_POST['applicant_performance'] ?? 0),
    'outstanding_accomplishments' => intval($_POST['applicant_outstanding'] ?? 0),
    'application_of_education' => intval($_POST['applicant_application_education'] ?? 0),
    'application_of_ld' => intval($_POST['applicant_application_ld'] ?? 0),
    'potential' => intval($_POST['applicant_potential'] ?? 0)
];

$baselineData = [
    'education' => [
        'degree' => $_POST['baseline_education'] ?? 'Bachelor',
        'masters_units' => intval($_POST['baseline_education_masters'] ?? 0),
        'doctoral_units' => intval($_POST['baseline_education_doctoral'] ?? 0)
    ],
    'training' => intval($_POST['baseline_training'] ?? 0),
    'experience' => (intval($_POST['baseline_experience_years'] ?? 0) * 12) + intval($_POST['baseline_experience_months'] ?? 0),
    'performance' => intval($_POST['baseline_performance'] ?? 0),
    'outstanding_accomplishments' => intval($_POST['baseline_outstanding'] ?? 0),
    'application_of_education' => intval($_POST['baseline_application_education'] ?? 0),
    'application_of_ld' => intval($_POST['baseline_application_ld'] ?? 0),
    'potential' => intval($_POST['baseline_potential'] ?? 0)
];

echo "<div class='section'>";
echo "<h2>Applicant Data</h2>";
echo "<p>Name: " . htmlspecialchars($applicantData['name']) . "</p>";
echo "<p>Experience: " . $applicantData['experience'] . " months</p>";
echo "<p>Performance Score: " . $applicantData['performance'] . "</p>";
echo "</div>";

echo "<div class='section'>";
echo "<h2>Create Evaluator and Evaluate</h2>";

try {
    $evaluator = new HRMPSBEvaluator($positionGroup, $salaryGrade, null);
    echo "<p class='good'>✓ Evaluator created</p>";
    
    $evaluation = $evaluator->evaluateApplicant($applicantData, $baselineData);
    echo "<p class='good'>✓ Evaluation complete</p>";
    echo "<p>Total Score: <strong>" . $evaluation['total_score'] . "</strong>/100</p>";
    echo "<p>Criteria count: <strong>" . count($evaluation['criteria']) . "</strong></p>";
    
    // Check criteria array
    if (empty($evaluation['criteria'])) {
        echo "<p class='bad'>ERROR: evaluation['criteria'] is empty!</p>";
    } else {
        echo "<p class='good'>✓ evaluation['criteria'] has " . count($evaluation['criteria']) . " items</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='bad'>ERROR: " . $e->getMessage() . "</p>";
    exit;
}

echo "</div>";

echo "<div class='section'>";
echo "<h2>Generate IES Report</h2>";

$additionalData = [
    'application_code' => $_POST['application_code'] ?? '',
    'schools_division_office' => $_POST['schools_division_office'] ?? '',
    'contact_number' => $_POST['contact_number'] ?? '',
    'job_group_sg_level' => $_POST['job_group_sg_level'] ?? '',
    'hrmpsb_chair' => $_POST['hrmpsb_chair'] ?? '',
    'position_group' => $positionGroup,
    'salary_grade' => $salaryGrade,
    'category' => null
];

try {
    $generator = new IESReportGenerator();
    $generator->setPositionGroup($positionGroup);
    $generator->setSalaryGrade($salaryGrade);
    echo "<p class='good'>✓ Generator configured</p>";
    
    $html = $generator->generateIES($evaluation, $additionalData);
    echo "<p class='good'>✓ HTML generated (" . strlen($html) . " bytes)</p>";
    
    // Analyze the HTML
    preg_match_all('/<tr>[\s\S]*?<\/tr>/', $html, $allRows);
    preg_match_all('/<tr>[\s\S]*?<td[^>]*class="col-criteria"[^>]*>((?!TOTAL).*?)<\/td>/s', $html, $dataRows);
    
    echo "<p>Total rows in table: <strong>" . count($allRows[0]) . "</strong></p>";
    echo "<p>Data rows (criteria): <strong>" . count($dataRows[0]) . "</strong></p>";
    
    if (count($dataRows[0]) === 0) {
        echo "<p class='bad'><strong>PROBLEM: No criteria rows in HTML!</strong></p>";
    } else {
        echo "<p class='good'><strong>✓ All criteria rows present in HTML</strong></p>";
    }
    
    // Show table excerpt
    if (preg_match('/<tbody>([\s\S]*?)<\/tbody>/', $html, $tbody)) {
        echo "<p><strong>Table Body (first 800 chars):</strong></p>";
        echo "<pre>" . htmlspecialchars(substr($tbody[1], 0, 800)) . "...</pre>";
    }
    
    // Check if page source would have criteria
    echo "<p><strong>Checking for specific criteria names in HTML:</strong></p>";
    $criteria_to_check = ['Education', 'Training', 'Experience'];
    foreach ($criteria_to_check as $crit) {
        if (strpos($html, $crit) !== false) {
            echo "<p class='good'>✓ Found '" . $crit . "' in HTML</p>";
        } else {
            echo "<p class='bad'>✗ NOT found '" . $crit . "' in HTML</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p class='bad'>ERROR: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "</div>";

echo "<div class='section'>";
echo "<h2>Next Step</h2>";
echo "<p>If you see ✓ for all criteria rows above, then:</p>";
echo "<ol>";
echo "<li>The HTML IS being generated correctly</li>";
echo "<li>Check your browser console (F12) for JavaScript errors</li>";
echo "<li>Try a hard refresh (Ctrl+Shift+R)</li>";
echo "<li>View page source (Ctrl+U) and search for 'col-criteria'</li>";
echo "</ol>";
echo "</div>";

echo "</body></html>";
?>
