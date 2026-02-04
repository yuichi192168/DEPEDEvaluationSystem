<?php
/**
 * API Endpoint: Calculate Levels from Inputs
 */
header('Content-Type: application/json');

require_once __DIR__ . '/../classes/HRMPSBEvaluator.php';

$evaluator = new HRMPSBEvaluator($_GET['position_group'] ?? 'NON-TEACHING LEVEL I');

$result = [];

// Education Level
if (isset($_GET['education_degree'])) {
    $mastersUnits = intval($_GET['masters_units'] ?? 0);
    $doctoralUnits = intval($_GET['doctoral_units'] ?? 0);
    $result['education_level'] = $evaluator->convertEducationToLevel(
        $_GET['education_degree'],
        $mastersUnits,
        $doctoralUnits
    );
}

// Training Level
if (isset($_GET['training_hours'])) {
    $result['training_level'] = $evaluator->convertTrainingToLevel(floatval($_GET['training_hours']));
}

// Experience Level
if (isset($_GET['experience_months'])) {
    $result['experience_level'] = $evaluator->convertExperienceToLevel(floatval($_GET['experience_months']));
}

echo json_encode($result);
?>

