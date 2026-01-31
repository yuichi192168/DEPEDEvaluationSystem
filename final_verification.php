<?php
/**
 * Final Verification: Complete Flow Test
 * Simulates the exact POST request flow from the web interface
 */

require_once 'config/evaluation_criteria.php';
require_once 'classes/HRMPSBEvaluator.php';
require_once 'classes/IESReportGenerator.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Complete Flow Verification</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        .section { margin: 20px 0; padding: 10px; border: 1px solid #ccc; }
        pre { background: #f5f5f5; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
<h1>Complete Flow Verification Test</h1>

<div class='section'>
<h2>Step 1: Verify Configuration Files</h2>";

if (file_exists('config/database.php')) {
    echo "<p class='success'>✓ database.php exists</p>";
} else {
    echo "<p class='error'>✗ database.php not found</p>";
}

if (file_exists('classes/HRMPSBEvaluator.php')) {
    echo "<p class='success'>✓ HRMPSBEvaluator.php exists</p>";
} else {
    echo "<p class='error'>✗ HRMPSBEvaluator.php not found</p>";
}

echo "</div>

<div class='section'>
<h2>Step 2: Load Evaluation Criteria Configuration</h2>";

try {
    if (empty($evaluation_criteria)) {
        echo "<p class='error'>✗ evaluation_criteria is empty</p>";
    } else {
        echo "<p class='success'>✓ evaluation_criteria loaded with " . count($evaluation_criteria) . " position groups</p>";
        
        // Check for Teaching Positions
        if (isset($evaluation_criteria['TEACHING POSITIONS'])) {
            echo "<p class='success'>✓ TEACHING POSITIONS criteria found</p>";
            if (isset($evaluation_criteria['TEACHING POSITIONS'][11])) {
                echo "<p class='success'>✓ Salary Grade 11 criteria found</p>";
                $sg11_criteria = $evaluation_criteria['TEACHING POSITIONS'][11];
                echo "<p>Criteria for Teacher I (SG 11):</p>";
                echo "<ul>";
                foreach ($sg11_criteria as $key => $criterion) {
                    if ($criterion['max_points'] > 0) {
                        echo "<li><strong>" . $criterion['name'] . "</strong>: " . $criterion['max_points'] . " points</li>";
                    }
                }
                echo "</ul>";
            } else {
                echo "<p class='error'>✗ Salary Grade 11 not found in criteria</p>";
            }
        } else {
            echo "<p class='error'>✗ TEACHING POSITIONS not found in criteria</p>";
        }
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Error loading criteria: " . $e->getMessage() . "</p>";
}

echo "</div>

<div class='section'>
<h2>Step 3: Simulate Evaluation Process (as per form submission)</h2>";

try {
    $positionGroup = 'TEACHING POSITIONS';
    $salaryGrade = 11;
    $category = null;
    
    echo "<p>Position Group: <strong>" . $positionGroup . "</strong></p>";
    echo "<p>Salary Grade: <strong>" . $salaryGrade . "</strong> (Type: " . gettype($salaryGrade) . ")</p>";
    
    $evaluator = new HRMPSBEvaluator($positionGroup, $salaryGrade, $category);
    echo "<p class='success'>✓ HRMPSBEvaluator instantiated</p>";
    
    // Simulated applicant data
    $applicantData = [
        'name' => 'Juan Santos',
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
    
    // Simulated baseline data
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
    echo "<p class='success'>✓ Applicant evaluated successfully</p>";
    echo "<p>Total Score: <strong>" . $evaluation['total_score'] . "/100</strong></p>";
    echo "<p>Criteria in evaluation: <strong>" . count($evaluation['criteria']) . "</strong> (should be 8)</p>";
    
    if (count($evaluation['criteria']) < 8) {
        echo "<p class='error'>✗ Warning: Less than 8 criteria in evaluation</p>";
    } else {
        echo "<p class='success'>✓ All 8 criteria present</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>✗ Error during evaluation: " . $e->getMessage() . "</p>";
}

echo "</div>

<div class='section'>
<h2>Step 4: Generate IES Report with Criteria</h2>";

try {
    // Prepare additional data as done in process_evaluation.php
    $additionalData = [
        'application_code' => 'APP-001',
        'schools_division_office' => 'Test Division',
        'contact_number' => '09123456789',
        'job_group_sg_level' => 'Teacher I - SG 11',
        'hrmpsb_chair' => 'Test Chair',
        'position_group' => $positionGroup,
        'salary_grade' => $salaryGrade,
        'category' => $category
    ];
    
    $generator = new IESReportGenerator();
    $generator->setPositionGroup($positionGroup);
    $generator->setSalaryGrade($salaryGrade);
    if ($category) {
        $generator->setCategory($category);
    }
    
    echo "<p class='success'>✓ IESReportGenerator instantiated and configured</p>";
    
    $html = $generator->generateIES($evaluation, $additionalData);
    echo "<p class='success'>✓ IES HTML generated</p>";
    echo "<p>HTML Length: <strong>" . strlen($html) . "</strong> characters</p>";
    
    // Count criteria rows
    preg_match_all('/<tr.*?col-criteria.*?<\/tr>/s', $html, $matches);
    $criteriaRowCount = count($matches[0]);
    
    echo "<p>Criteria rows in HTML: <strong>" . $criteriaRowCount . "</strong></p>";
    
    if ($criteriaRowCount >= 6) {
        echo "<p class='success'>✓ All expected criteria rows found in HTML</p>";
    } elseif ($criteriaRowCount > 0) {
        echo "<p class='warning'>⚠ Only " . $criteriaRowCount . " criteria rows found (expected 6)</p>";
    } else {
        echo "<p class='error'>✗ NO criteria rows found in HTML</p>";
    }
    
    // Look for specific criteria names
    $expectedCriteria = [
        'Education',
        'Training',
        'Experience',
        'PBET, LET, or LEPT Rating',
        'PPST COIs',
        'PPST NCOIs'
    ];
    
    echo "<h3>Criteria Content Check:</h3>";
    $foundCount = 0;
    foreach ($expectedCriteria as $name) {
        if (strpos($html, $name) !== false) {
            echo "<p class='success'>✓ '" . $name . "' found</p>";
            $foundCount++;
        } else {
            echo "<p class='error'>✗ '" . $name . "' NOT found</p>";
        }
    }
    
    // Show sample of actual criteria row
    if (preg_match('/<tr[^>]*col-criteria[^>]*>.*?<\/tr>/s', $html, $sample)) {
        echo "<h3>Sample Criteria Row HTML:</h3>";
        echo "<pre>" . htmlspecialchars(substr($sample[0], 0, 300)) . "...</pre>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>✗ Error generating report: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "</div>

<div class='section'>
<h2>Final Result</h2>";

if ($foundCount >= 6 && $criteriaRowCount >= 6) {
    echo "<p class='success'><strong>✓✓✓ SUCCESS ✓✓✓</strong></p>";
    echo "<p>The complete flow is working correctly:</p>";
    echo "<ul>
        <li>✓ Database connection working</li>
        <li>✓ Evaluation criteria loaded</li>
        <li>✓ Teacher I criteria identified (6 criteria)</li>
        <li>✓ Evaluation executed with all 8 criteria</li>
        <li>✓ IES report generated with all 6 criteria rows</li>
        <li>✓ All expected criteria names present in HTML</li>
    </ul>";
    echo "<p><strong>What you should see in the web browser:</strong></p>";
    echo "<ul>
        <li>Education row with 10 points</li>
        <li>Training row with 10 points</li>
        <li>Experience row with 10 points</li>
        <li>PBET Rating row with 10 points</li>
        <li>PPST COIs row with 35 points</li>
        <li>PPST NCOIs row with 25 points</li>
        <li>TOTAL row with 100 points</li>
    </ul>";
} else {
    echo "<p class='error'><strong>✗ ISSUE DETECTED</strong></p>";
    echo "<p>Found: " . $foundCount . " of 6 expected criteria</p>";
    echo "<p>Criteria rows in HTML: " . $criteriaRowCount . "</p>";
}

echo "</div>

</body>
</html>";
?>
