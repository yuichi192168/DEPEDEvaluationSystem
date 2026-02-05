<?php
require 'config/baseline_library.php';

echo "Total positions in baseline library: " . count($baselineLibrary) . "\n";
echo "\nPositions by Group:\n";

$groupCounts = ['A' => 0, 'B' => 0, 'C' => 0];
foreach ($baselineLibrary as $pos) {
    $groupCounts[$pos['position_group']]++;
}

foreach ($groupCounts as $group => $count) {
    echo "  Group $group: $count positions\n";
}

echo "\nPositions by Salary Grade:\n";
$sgCounts = [];
foreach ($baselineLibrary as $pos) {
    $sg = $pos['salary_grade'];
    if (!isset($sgCounts[$sg])) {
        $sgCounts[$sg] = 0;
    }
    $sgCounts[$sg]++;
}

krsort($sgCounts);
foreach ($sgCounts as $sg => $count) {
    echo "  SG $sg: $count positions\n";
}

echo "\nAll positions available for selection in dropdowns.\n";
?>
