<?php
/**
 * System Verification Test
 * Verifies that all three modules use the same criteria
 */

require_once 'config/evaluation_criteria.php';

echo "=== OFFICIAL CRITERIA SYSTEM VERIFICATION ===\n\n";

// Test 1: Verify Teacher I criteria
echo "TEST 1: Teacher I Positions (TEACHING POSITIONS)\n";
$teacherI = getEvaluationCriteria('TEACHING POSITIONS', 11);
echo "Total Points: " . $teacherI['total_points'] . "\n";
echo "Criteria Count: " . count($teacherI['criteria']) . "\n";
echo "Criterion d: " . $teacherI['criteria']['d']['name'] . " (" . $teacherI['criteria']['d']['max_points'] . " pts)\n";
echo "Criterion e: " . $teacherI['criteria']['e']['name'] . " (" . $teacherI['criteria']['e']['max_points'] . " pts)\n";
echo "Criterion f: " . $teacherI['criteria']['f']['name'] . " (" . $teacherI['criteria']['f']['max_points'] . " pts)\n";
echo "EXPECTED: Total 100, 6 criteria, d=PBET/LET/LEPT(10), e=PPST COIs(35), f=PPST NCOIs(25)\n";
echo "PASS: " . ($teacherI['total_points'] === 100 && $teacherI['criteria']['d']['max_points'] === 10 ? "✅ YES" : "❌ NO") . "\n\n";

// Test 2: School Administration
echo "TEST 2: School Administration Positions\n";
$admin = getEvaluationCriteria('SCHOOL ADMINISTRATION POSITION', 18);
echo "Total Points: " . $admin['total_points'] . "\n";
echo "Criterion d (Performance): " . $admin['criteria']['d']['max_points'] . " pts\n";
echo "Criterion h (Potential): " . $admin['criteria']['h']['max_points'] . " pts\n";
echo "EXPECTED: Total 100, Performance=25, Potential=15\n";
echo "PASS: " . ($admin['total_points'] === 100 && $admin['criteria']['d']['max_points'] === 25 ? "✅ YES" : "❌ NO") . "\n\n";

// Test 3: Related Teaching SG 16-23
echo "TEST 3: Related Teaching SG 16-23\n";
$relatedTeaching = getEvaluationCriteria('RELATED TEACHING POSITION', 20);
echo "Total Points: " . $relatedTeaching['total_points'] . "\n";
echo "Criterion e (Outstanding): " . $relatedTeaching['criteria']['e']['max_points'] . " pts\n";
echo "Criterion f (Application of Ed): " . $relatedTeaching['criteria']['f']['max_points'] . " pts\n";
echo "EXPECTED: Total 100, Outstanding=5, Application of Ed=15\n";
echo "PASS: " . ($relatedTeaching['total_points'] === 100 && $relatedTeaching['criteria']['e']['max_points'] === 5 ? "✅ YES" : "❌ NO") . "\n\n";

// Test 4: Non-Teaching General Services
echo "TEST 4: Non-Teaching General Services\n";
$nonTeachingGS = getEvaluationCriteria('NON-TEACHING LEVEL I', 5, 'general_services');
echo "Total Points: " . $nonTeachingGS['total_points'] . "\n";
echo "Criterion h (Potential): " . $nonTeachingGS['criteria']['h']['max_points'] . " pts\n";
echo "EXPECTED: Total 100, Potential=55\n";
echo "PASS: " . ($nonTeachingGS['total_points'] === 100 && $nonTeachingGS['criteria']['h']['max_points'] === 55 ? "✅ YES" : "❌ NO") . "\n\n";

// Test 5: Non-Teaching SG 10-22
echo "TEST 5: Non-Teaching Level II SG 10-22\n";
$nonTeachingL2 = getEvaluationCriteria('NON-TEACHING LEVEL II', 15);
echo "Total Points: " . $nonTeachingL2['total_points'] . "\n";
echo "Criterion a (Education): " . $nonTeachingL2['criteria']['a']['max_points'] . " pts\n";
echo "Criterion b (Training): " . $nonTeachingL2['criteria']['b']['max_points'] . " pts\n";
echo "Criterion c (Experience): " . $nonTeachingL2['criteria']['c']['max_points'] . " pts\n";
echo "EXPECTED: Total 100, Education=5, Training=10, Experience=15\n";
echo "PASS: " . ($nonTeachingL2['total_points'] === 100 && 
           $nonTeachingL2['criteria']['a']['max_points'] === 5 &&
           $nonTeachingL2['criteria']['b']['max_points'] === 10 &&
           $nonTeachingL2['criteria']['c']['max_points'] === 15 ? "✅ YES" : "❌ NO") . "\n\n";

echo "=== VERIFICATION COMPLETE ===\n";
?>
