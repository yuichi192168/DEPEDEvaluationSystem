<?php
/**
 * Quick test to verify form loads and position groups are populated
 * This file checks basic form DOM structure
 */

require_once __DIR__ . '/initialize.php';

// Check if form elements exist
$check_results = [];

// Read the form file
$form_content = file_get_contents(__DIR__ . '/index.php');

// Check for critical elements
$checks = [
    'position_group_select exists' => strpos($form_content, 'id="position_group_select"') !== false,
    'position_key exists' => strpos($form_content, 'id="position_key"') !== false,
    'applicant_training_hours exists' => strpos($form_content, 'id="applicant_training_hours"') !== false,
    'applicant_experience_months exists' => strpos($form_content, 'id="applicant_experience_months"') !== false,
    'contact_number field exists' => strpos($form_content, 'id="contact_number"') !== false,
    'contact_number NOT required' => strpos($form_content, 'id="contact_number"') !== false && 
                                     (strpos(substr($form_content, strpos($form_content, 'id="contact_number"'), 200), 'required') === false),
    'loadPositionGroups function exists' => strpos($form_content, 'function loadPositionGroups()') !== false,
    'DOMContentLoaded wrapper exists' => strpos($form_content, 'DOMContentLoaded') !== false,
    'API fallback exists' => strpos($form_content, 'fallback') !== false && strpos($form_content, 'positionGroups') !== false,
];

// Check validator
$validator_content = file_get_contents(__DIR__ . '/js/form-validation.js');
$checks['contact_number removed from validator'] = strpos($validator_content, "'contact_number'") === false;

echo "=== FORM LOAD TEST RESULTS ===\n\n";
foreach ($checks as $check => $result) {
    echo ($result ? '✓' : '✗') . " $check\n";
}

// Test API endpoint
echo "\n=== API TEST ===\n";
$output = shell_exec('php ' . escapeshellarg(__DIR__ . '/api/get_position_groups.php'));
$groups = json_decode($output, true);
if ($groups && is_array($groups) && count($groups) > 0) {
    echo "✓ API returns valid JSON with " . count($groups) . " position groups\n";
    echo "  Groups: " . implode(', ', array_column($groups, 'group')) . "\n";
} else {
    echo "✗ API failed to return position groups\n";
}

echo "\n=== TEST COMPLETE ===\n";
?>
