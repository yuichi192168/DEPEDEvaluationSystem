<?php
/**
 * Test Position Group Filtering
 * This script verifies that the cascading dropdown will work correctly
 */

require_once 'classes/AssessmentProcessor.php';
require_once 'config/baseline_library.php';

// Test 1: Get all position groups
echo "=== TEST 1: Position Groups ===\n";
$groups = AssessmentProcessor::getPositionGroups();
foreach ($groups as $groupName => $positions) {
    echo "\n$groupName (" . count($positions) . " positions):\n";
    echo "  - " . implode("\n  - ", $positions) . "\n";
}

// Test 2: Verify all positions in baseline_library are assigned to a group
echo "\n\n=== TEST 2: Position Count Verification ===\n";
$totalPositions = 0;
$positionsByGroup = [];
foreach ($baselineLibrary as $key => $pos) {
    $group = $pos['position_group'] ?? 'unknown';
    if (!isset($positionsByGroup[$group])) {
        $positionsByGroup[$group] = 0;
    }
    $positionsByGroup[$group]++;
    $totalPositions++;
}

echo "Total positions in baseline_library: $totalPositions\n\n";
echo "Positions by group:\n";
foreach ($positionsByGroup as $group => $count) {
    echo "  $group: $count\n";
}

// Test 3: Verify API output format
echo "\n\n=== TEST 3: API Output Format ===\n";
$out = [];
foreach ($groups as $group => $positions) {
    $out[] = ['group' => $group, 'positions' => array_values($positions)];
}
echo json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

// Test 4: Sample filtering - Non Teaching Level I
echo "\n\n=== TEST 4: Sample Filter - Non Teaching Level I ===\n";
if (isset($groups['non-teaching level I'])) {
    echo "Positions in 'non-teaching level I':\n";
    foreach ($groups['non-teaching level I'] as $pos) {
        echo "  - $pos\n";
    }
} else {
    echo "Group 'non-teaching level I' not found!\n";
}

// Test 5: Verify each position has both position_name and position_group
echo "\n\n=== TEST 5: Data Integrity Check ===\n";
$issues = [];
foreach ($baselineLibrary as $key => $pos) {
    if (!isset($pos['position_name']) || !$pos['position_name']) {
        $issues[] = "Position '$key' missing position_name";
    }
    if (!isset($pos['position_group']) || !$pos['position_group']) {
        $issues[] = "Position '$key' missing position_group";
    }
}

if (empty($issues)) {
    echo "✓ All positions have both position_name and position_group\n";
} else {
    echo "✗ Issues found:\n";
    foreach ($issues as $issue) {
        echo "  - $issue\n";
    }
}

echo "\n\n=== TEST COMPLETE ===\n";
?>
