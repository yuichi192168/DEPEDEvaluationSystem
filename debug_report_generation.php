<?php
/**
 * Debug: Test IES Report Generation
 * Generate a complete test report to see if criteria render
 */

require_once 'config/evaluation_criteria.php';
require_once 'classes/HRMPSBEvaluator.php';
require_once 'classes/IESReportGenerator.php';

// Create evaluation (same as before)
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

echo "<h2>Testing IES Report Generation</h2>\n";
echo "<p><strong>Evaluation Total Score:</strong> " . $evaluation['total_score'] . "</p>\n";
echo "<p><strong>Criteria Count:</strong> " . count($evaluation['criteria']) . "</p>\n";

// Generate report
$generator = new IESReportGenerator();
$additionalData = [
    'position_group' => $positionGroup,
    'salary_grade' => $salaryGrade,
    'category' => null,
    'application_code' => 'TEST-001',
    'schools_division_office' => 'Test Division',
    'contact_number' => '09123456789',
    'job_group_sg_level' => 'SG 11',
    'hrmpsb_chair' => 'Test Chair'
];

$html = $generator->generateIES($evaluation, $additionalData);

// Count the criteria rows in the generated HTML
preg_match_all('/<tr>[\s\S]*?<\/tr>/', $html, $matches);
$totalRows = count($matches[0]);

// Count data rows (excluding header and total)
$dataRowCount = 0;
foreach ($matches[0] as $row) {
    if (strpos($row, 'col-criteria') !== false && strpos($row, 'TOTAL') === false) {
        $dataRowCount++;
    }
}

echo "<h3>Generated HTML Analysis:</h3>\n";
echo "<p><strong>Total table rows:</strong> " . $totalRows . "</p>\n";
echo "<p><strong>Data rows (criteria):</strong> " . $dataRowCount . "</p>\n";

// Look for specific criteria in HTML
$criteria_names = [
    'Education',
    'Training',
    'Experience',
    'PBET, LET, or LEPT Rating',
    'PPST COIs',
    'PPST NCOIs'
];

echo "<h3>Criteria Found in HTML:</h3>\n";
$found_count = 0;
foreach ($criteria_names as $name) {
    if (strpos($html, $name) !== false) {
        echo "<p style='color:green;'>✓ $name</p>\n";
        $found_count++;
    } else {
        echo "<p style='color:red;'>✗ $name (NOT FOUND)</p>\n";
    }
}

echo "<h3>Summary</h3>\n";
if ($found_count >= 6) {
    echo "<p style='color:green;'><strong>✓ ALL CRITERIA FOUND</strong> - Report is generating correctly</p>\n";
} elseif ($found_count > 0) {
    echo "<p style='color:orange;'><strong>⚠ PARTIAL:</strong> Only $found_count of 6 criteria found</p>\n";
} else {
    echo "<p style='color:red;'><strong>✗ NO CRITERIA FOUND</strong> - Issue in HTML generation</p>\n";
}

// Extract and show a sample criteria row
echo "<h3>Sample Criteria Row from HTML:</h3>\n";
preg_match('/<td class="col-criteria">[^T].*?<\/tr>/s', $html, $sampleRow);
if ($sampleRow) {
    echo "<pre style='background:#f0f0f0; padding:10px; overflow-x:auto;'>\n";
    echo htmlspecialchars(substr($sampleRow[0], 0, 200));
    echo "...\n</pre>\n";
}
?>
