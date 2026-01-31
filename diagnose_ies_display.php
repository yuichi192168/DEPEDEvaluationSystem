<?php
/**
 * Diagnostic: Test actual IES display issue
 * This simulates exactly what happens in process_evaluation.php
 */

require_once 'config/evaluation_criteria.php';
require_once 'classes/HRMPSBEvaluator.php';
require_once 'classes/IESReportGenerator.php';

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial; margin: 20px; }
        .debug { background: #f9f9f9; border: 1px solid #ddd; padding: 10px; margin: 10px 0; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        pre { background: #f0f0f0; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
<h1>IES Display Diagnostics</h1>";

// Simulate POST data
$_POST = [
    'position_applied' => 'Teacher I',
    'job_group_sg_level' => 'Group TEACHING POSITIONS / Salary Grade 11',
    'application_code' => 'TEST-001',
    'schools_division_office' => 'Test Division',
    'contact_number' => '09123456789',
    'hrmpsb_chair' => 'Dr. Test Chair'
];

echo "<div class='debug'><h2>Step 1: Parse Position Data</h2>";

// Extract position group and salary grade (as done in process_evaluation.php)
$jobGroupInput = $_POST['job_group_sg_level'] ?? '';
$positionApplied = $_POST['position_applied'] ?? '';

// Parse from formatted string: "Group TEACHING POSITIONS / Salary Grade 11"
preg_match('/Group\s+(.+?)\s*\/\s*Salary Grade\s+(\d+)/', $jobGroupInput, $matches);
if (!empty($matches)) {
    $positionGroup = $matches[1]; // "TEACHING POSITIONS"
    $salaryGrade = intval($matches[2]); // 11
} else {
    echo "<p class='error'>Could not parse position: " . htmlspecialchars($jobGroupInput) . "</p>";
    exit;
}

echo "<p><strong>Position Group:</strong> " . htmlspecialchars($positionGroup) . " (Type: " . gettype($positionGroup) . ")</p>";
echo "<p><strong>Salary Grade:</strong> " . $salaryGrade . " (Type: " . gettype($salaryGrade) . ")</p>";
echo "<p><strong>Position Applied:</strong> " . htmlspecialchars($positionApplied) . "</p>";
echo "</div>";

echo "<div class='debug'><h2>Step 2: Create Evaluator and Generate Evaluation</h2>";

try {
    $evaluator = new HRMPSBEvaluator($positionGroup, $salaryGrade, null);
    echo "<p class='success'>✓ Evaluator created</p>";
    
    $applicantData = [
        'name' => 'Test Applicant',
        'position' => $positionApplied,
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
    echo "<p class='success'>✓ Evaluation completed</p>";
    echo "<p>Total Score: " . $evaluation['total_score'] . "/100</p>";
    echo "<p>Criteria count: " . count($evaluation['criteria']) . "</p>";
    
} catch (Exception $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    exit;
}

echo "</div>";

echo "<div class='debug'><h2>Step 3: Create IES Report Generator with Position Context</h2>";

$generator = new IESReportGenerator();

// Set position context BEFORE generating (as should happen in process_evaluation.php)
$generator->setPositionGroup($positionGroup);
$generator->setSalaryGrade($salaryGrade);
$generator->setCategory(null);

echo "<p class='success'>✓ Position context set</p>";
echo "<p>Position Group: " . $positionGroup . "</p>";
echo "<p>Salary Grade: " . $salaryGrade . "</p>";

echo "</div>";

echo "<div class='debug'><h2>Step 4: Generate IES HTML</h2>";

$additionalData = [
    'application_code' => $_POST['application_code'],
    'schools_division_office' => $_POST['schools_division_office'],
    'contact_number' => $_POST['contact_number'],
    'job_group_sg_level' => $_POST['job_group_sg_level'],
    'hrmpsb_chair' => $_POST['hrmpsb_chair'],
    'position_group' => $positionGroup,
    'salary_grade' => $salaryGrade,
    'category' => null
];

try {
    $html = $generator->generateIES($evaluation, $additionalData);
    echo "<p class='success'>✓ HTML generated (" . strlen($html) . " bytes)</p>";
    
    // Analyze HTML
    preg_match_all('/<tr[^>]*>.*?<\/tr>/s', $html, $allRows);
    echo "<p>Total table rows: " . count($allRows[0]) . "</p>";
    
    // Count criteria rows (those with col-criteria class, excluding TOTAL)
    preg_match_all('/<tr>[\s\S]*?<td[^>]*class="col-criteria"[^>]*>(?!TOTAL).*?<\/tr>/s', $html, $criteriaRows);
    $criteriaCount = count($criteriaRows[0]);
    echo "<p><strong>Criteria rows found: " . $criteriaCount . "</strong></p>";
    
    if ($criteriaCount >= 6) {
        echo "<p class='success'>✓ All 6 criteria present</p>";
    } else {
        echo "<p class='error'>✗ Only " . $criteriaCount . " criteria rows (expected 6)</p>";
    }
    
    // Extract and show first criteria row
    preg_match('/<tr>[\s\S]*?<td[^>]*class="col-criteria"[^>]*>(.*?)<\/td>/s', $html, $firstCriteria);
    if ($firstCriteria) {
        echo "<p><strong>First Criteria Row:</strong></p>";
        echo "<p>" . htmlspecialchars($firstCriteria[1]) . "</p>";
    }
    
    // Check for specific criteria names
    $expectedCriteria = [
        'Education',
        'Training',
        'Experience',
        'PBET, LET, or LEPT Rating',
        'PPST COIs',
        'PPST NCOIs'
    ];
    
    echo "<p><strong>Criteria Names Found:</strong></p>";
    foreach ($expectedCriteria as $name) {
        if (strpos($html, $name) !== false) {
            echo "<p class='success'>✓ " . $name . "</p>";
        } else {
            echo "<p class='error'>✗ " . $name . "</p>";
        }
    }
    
    // Show excerpt of HTML around criteria table
    $tableStart = strpos($html, '<tbody>');
    $tableEnd = strpos($html, '</tbody>') + 8;
    if ($tableStart !== false && $tableEnd !== false) {
        echo "<h3>HTML Table Content (first 1000 chars of tbody):</h3>";
        echo "<pre>" . htmlspecialchars(substr($html, $tableStart, min(1000, $tableEnd - $tableStart))) . "</pre>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>Error generating HTML: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "</div>";

echo "<div class='debug'><h2>Summary</h2>";
echo "<p>If you see 6 criteria names above (Education, Training, Experience, PBET, PPST COIs, PPST NCOIs), then the IES is generating correctly.</p>";
echo "<p>If criteria are missing, it means the position context is not being properly set or the criteria are not in the evaluation data.</p>";
echo "</div>";

echo "</body></html>";
?>
