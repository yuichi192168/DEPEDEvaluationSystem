<?php
/**
 * Baseline Qualification Standards Library
 * Based on DepEd Order No. 007, s. 2023
 * 
 * This library contains standard baseline levels for common DepEd positions.
 * Note: These should be verified against CSC-approved QS for specific stations.
 */

$baselineLibrary = [
    // Non-Teaching Level 1 Positions
    'ict' => [
        'position_name' => 'Information and Communications Technology',
        'position_group' => 'A',
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0], // Level 6
        'training' => 0, // Level 1 (0 hrs)
        'experience' => 0, // Level 1 (0 months)
        'performance' => 0,
        'outstanding_accomplishments' => 0,
        'application_of_education' => 0,
        'application_of_ld' => 0,
        'potential' => 0
    ],
    'admin_aide' => [
        'position_name' => 'Administrative Aide',
        'position_group' => 'A',
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0], // Level 6
        'training' => 0, // Level 1
        'experience' => 0, // Level 1
        'performance' => 0,
        'outstanding_accomplishments' => 0,
        'application_of_education' => 0,
        'application_of_ld' => 0,
        'potential' => 0
    ],
    'teacher_i' => [
        'position_name' => 'Teacher I',
        'position_group' => 'A',
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0], // Level 6
        'training' => 0, // Level 1
        'experience' => 0, // Level 1
        'performance' => 0,
        'outstanding_accomplishments' => 0,
        'application_of_education' => 0,
        'application_of_ld' => 0,
        'potential' => 0
    ],
    
    // Non-Teaching Level 2 Positions
    'admin_officer_iv' => [
        'position_name' => 'Administrative Officer IV',
        'position_group' => 'B',
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0], // Level 6
        'training' => 8, // Level 2 (8 hrs) - verify actual requirement
        'experience' => 12, // Level 3 (1 year) - verify actual requirement
        'performance' => 0,
        'outstanding_accomplishments' => 0,
        'application_of_education' => 0,
        'application_of_ld' => 0,
        'potential' => 0
    ],
    'seps' => [
        'position_name' => 'Senior Education Program Specialist',
        'position_group' => 'B',
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0], // Level 6
        'training' => 8, // Level 2
        'experience' => 12, // Level 3
        'performance' => 0,
        'outstanding_accomplishments' => 0,
        'application_of_education' => 0,
        'application_of_ld' => 0,
        'potential' => 0
    ],
    
    // School Administration Positions
    'principal_i' => [
        'position_name' => 'Principal I',
        'position_group' => 'C',
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0], // Level 21
        'training' => 32, // Level 5 (32 hrs) - verify actual requirement
        'experience' => 48, // Level 9 (4 years) - verify actual requirement
        'performance' => 0,
        'outstanding_accomplishments' => 0,
        'application_of_education' => 0,
        'application_of_ld' => 0,
        'potential' => 0
    ],
    'principal_ii' => [
        'position_name' => 'Principal II',
        'position_group' => 'C',
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0], // Level 21
        'training' => 40, // Level 6
        'experience' => 60, // Level 11 (5 years)
        'performance' => 0,
        'outstanding_accomplishments' => 0,
        'application_of_education' => 0,
        'application_of_ld' => 0,
        'potential' => 0
    ],
    
    // Default/Custom Position
    'custom' => [
        'position_name' => 'Custom Position',
        'position_group' => 'A',
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0,
        'experience' => 0,
        'performance' => 0,
        'outstanding_accomplishments' => 0,
        'application_of_education' => 0,
        'application_of_ld' => 0,
        'potential' => 0
    ]
];

/**
 * Get baseline for a position
 */
function getBaselineForPosition($positionKey) {
    global $baselineLibrary;
    return $baselineLibrary[$positionKey] ?? $baselineLibrary['custom'];
}

/**
 * Get all available positions
 */
function getAllPositions() {
    global $baselineLibrary;
    return $baselineLibrary;
}

// Return library if required directly
if (!function_exists('getBaselineForPosition')) {
    return $baselineLibrary;
}
