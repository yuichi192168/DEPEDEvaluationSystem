<?php
/**
 * Position Groups Loading System - Comprehensive Test
 */

require_once __DIR__ . '/initialize.php';

echo "╔════════════════════════════════════════════════════════════════════════╗\n";
echo "║            POSITION GROUPS LOADING SYSTEM TEST                         ║\n";
echo "╚════════════════════════════════════════════════════════════════════════╝\n\n";

// 1. Check API endpoint
echo "1. CHECKING API ENDPOINT\n";
echo "   Testing: api/get_position_groups.php\n";
$api_output = shell_exec('php ' . escapeshellarg(__DIR__ . '/api/get_position_groups.php'));
if ($api_output) {
    $groups = json_decode($api_output, true);
    if (is_array($groups) && count($groups) > 0) {
        echo "   ✓ API returns valid JSON\n";
        echo "   ✓ Total groups: " . count($groups) . "\n";
        
        foreach ($groups as $idx => $group) {
            $posCount = isset($group['positions']) ? count($group['positions']) : 0;
            echo "     [$idx] {$group['group']}: $posCount positions\n";
        }
    } else {
        echo "   ✗ API did not return valid array\n";
    }
} else {
    echo "   ✗ API did not return any output\n";
}

// 2. Check index.php has required functions
echo "\n2. CHECKING index.php JAVASCRIPT FUNCTIONS\n";
$index = file_get_contents(__DIR__ . '/index.php');

$hasLoadFunc = strpos($index, 'function loadPositionGroups()') !== false;
$hasPopulateFunc = strpos($index, 'function populatePositionsForGroup') !== false;
$hasChangeListener = strpos($index, "gsel.addEventListener('change'") !== false;
$hasDOMCheck = strpos($index, 'document.readyState') !== false;

echo ($hasLoadFunc ? '   ✓' : '   ✗') . " loadPositionGroups() function exists\n";
echo ($hasPopulateFunc ? '   ✓' : '   ✗') . " populatePositionsForGroup() function exists\n";
echo ($hasChangeListener ? '   ✓' : '   ✗') . " Change listener for position_group_select\n";
echo ($hasDOMCheck ? '   ✓' : '   ✗') . " DOM ready check (document.readyState)\n";

// 3. Check HTML structure
echo "\n3. CHECKING HTML FORM STRUCTURE\n";
$hasGroupSelect = strpos($index, 'id="position_group_select"') !== false;
$hasPositionKey = strpos($index, 'id="position_key"') !== false;

if (preg_match('/<select[^>]*id="position_group_select"[^>]*>([^<]*)<option/i', $index, $m)) {
    echo "   ✓ position_group_select element exists\n";
}
if (preg_match('/<select[^>]*id="position_key"[^>]*>([^<]*)<option/i', $index, $m)) {
    echo "   ✓ position_key element exists\n";
}

// 4. Test the baseline library
echo "\n4. CHECKING BASELINE POSITIONS DATA\n";
require_once __DIR__ . '/config/baseline_library.php';
if (isset($BASELINE_LIBRARY) && is_array($BASELINE_LIBRARY)) {
    echo "   ✓ Baseline library loaded\n";
    echo "   ✓ Total positions in library: " . count($BASELINE_LIBRARY) . "\n";
    
    // Group positions by position_group
    $groupsByName = [];
    foreach ($BASELINE_LIBRARY as $k => $pos) {
        $g = $pos['position_group'] ?? 'Unknown';
        if (!isset($groupsByName[$g])) $groupsByName[$g] = 0;
        $groupsByName[$g]++;
    }
    
    echo "   ✓ Positions by group:\n";
    foreach ($groupsByName as $g => $count) {
        echo "     - $g: $count positions\n";
    }
} else {
    echo "   ✗ Baseline library could not be loaded\n";
}

// 5. Check AssessmentProcessor
echo "\n5. CHECKING ASSESSMENT PROCESSOR\n";
require_once __DIR__ . '/classes/AssessmentProcessor.php';
if (class_exists('AssessmentProcessor')) {
    echo "   ✓ AssessmentProcessor class found\n";
    if (method_exists('AssessmentProcessor', 'getPositionGroups')) {
        echo "   ✓ getPositionGroups() method exists\n";
        $groups = AssessmentProcessor::getPositionGroups();
        if (is_array($groups)) {
            echo "   ✓ getPositionGroups() returns array\n";
            echo "   ✓ Total groups: " . count($groups) . "\n";
        }
    }
}

echo "\n╔════════════════════════════════════════════════════════════════════════╗\n";
echo "║                         TEST COMPLETE                                  ║\n";
echo "║                                                                        ║\n";
echo "║  EXPECTED FLOW:                                                        ║\n";
echo "║  1. Page loads and checks DOM ready state                             ║\n";
echo "║  2. loadPositionGroups() is called                                     ║\n";
echo "║  3. API fetch returns groups array                                    ║\n";
echo "║  4. Groups are populated in position_group_select dropdown             ║\n";
echo "║  5. Change listener is attached to position_group_select              ║\n";
echo "║  6. User selects a group → populatePositionsForGroup is called        ║\n";
echo "║  7. Positions are populated in position_key dropdown                  ║\n";  
echo "║  8. User selects a position → setSelectedPosition is called           ║\n";
echo "║  9. Baseline data is loaded for selected position                     ║\n";
echo "║                                                                        ║\n";
echo "║  Test status: All checks should show ✓ for full functionality         ║\n";
echo "╚════════════════════════════════════════════════════════════════════════╝\n";
?>
