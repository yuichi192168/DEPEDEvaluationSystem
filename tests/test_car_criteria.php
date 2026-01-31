<?php
/**
 * Test: Verify Comparative Assessment criteria mapping
 */

require_once 'config/evaluation_criteria.php';

// Test the getCriteriaMappings function
$dbToKey = [
    'education_score' => 'a',
    'training_score' => 'b',
    'experience_score' => 'c',
    'performance_score' => 'd',
    'outstanding_accomplishments_score' => 'e',
    'application_of_education_score' => 'f',
    'application_of_ld_score' => 'g',
    'potential_score' => 'h'
];

function getCriteriaMappings($positionGroup, $salaryGrade = null, $category = null) {
    $dbToKey = [
        'education_score' => 'a',
        'training_score' => 'b',
        'experience_score' => 'c',
        'performance_score' => 'd',
        'outstanding_accomplishments_score' => 'e',
        'application_of_education_score' => 'f',
        'application_of_ld_score' => 'g',
        'potential_score' => 'h'
    ];
    
    $criteria = getEvaluationCriteria($positionGroup, $salaryGrade, $category);
    if (!$criteria || empty($criteria['criteria'])) {
        return [];
    }
    
    $mappings = [];
    foreach ($dbToKey as $dbCol => $key) {
        if (isset($criteria['criteria'][$key])) {
            $mappings[] = [
                'db_column' => $dbCol,
                'key' => $key,
                'criteria_name' => $criteria['criteria'][$key]['name'],
                'max_points' => $criteria['criteria'][$key]['max_points']
            ];
        }
    }
    
    return $mappings;
}

echo "<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial; margin: 20px; }
        .test { background: #f9f9f9; border: 1px solid #ccc; padding: 15px; margin: 15px 0; }
        .good { color: green; }
        .bad { color: red; }
        table { border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #e8e8e8; }
    </style>
</head>
<body>
<h1>Comparative Assessment Criteria Mapping Test</h1>";

echo "<div class='test'>";
echo "<h2>Teacher I (TEACHING POSITIONS, SG 11)</h2>";

$mappings = getCriteriaMappings('TEACHING POSITIONS', 11, null);

echo "<p>Criteria found: <strong>" . count($mappings) . "</strong></p>";

if (count($mappings) >= 6) {
    echo "<p class='good'>✓ All expected criteria present</p>";
} else {
    echo "<p class='bad'>✗ Missing criteria</p>";
}

echo "<table>";
echo "<tr><th>Criteria Name</th><th>DB Column</th><th>Max Points</th></tr>";
foreach ($mappings as $m) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($m['criteria_name']) . "</td>";
    echo "<td>" . $m['db_column'] . "</td>";
    echo "<td>" . $m['max_points'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Check for the specific criteria we want
$expectedCriteria = [
    'PBET, LET, or LEPT Rating',
    'PPST COIs',
    'PPST NCOIs'
];

echo "<p><strong>Checking for specific criteria:</strong></p>";
foreach ($expectedCriteria as $expected) {
    $found = false;
    foreach ($mappings as $m) {
        if (strpos($m['criteria_name'], $expected) !== false) {
            $found = true;
            break;
        }
    }
    if ($found) {
        echo "<p class='good'>✓ Found: $expected</p>";
    } else {
        echo "<p class='bad'>✗ Missing: $expected</p>";
    }
}

echo "</div>";

echo "</body></html>";
?>
