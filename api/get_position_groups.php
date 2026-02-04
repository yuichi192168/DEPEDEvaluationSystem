<?php
require_once __DIR__ . '/../classes/AssessmentProcessor.php';
header('Content-Type: application/json');

// Return ordered array of groups for easier UI consumption
$groups = AssessmentProcessor::getPositionGroups();
$out = [];
foreach ($groups as $group => $positions) {
	$out[] = ['group' => $group, 'positions' => array_values($positions)];
}

echo json_encode($out, JSON_PRETTY_PRINT);

?>
