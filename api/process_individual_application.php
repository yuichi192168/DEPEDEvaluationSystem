<?php
require_once __DIR__ . '/../classes/AssessmentProcessor.php';

// Simple API endpoint to process an applicant JSON
// Usage: POST JSON body with fields required by AssessmentProcessor::processApplication

header('Content-Type: application/json');

$raw = file_get_contents('php://input');
if (empty($raw)) {
    echo json_encode(['error'=>'Send applicant JSON in request body']);
    exit;
}

$data = json_decode($raw, true);
if ($data === null) {
    echo json_encode(['error'=>'Invalid JSON']);
    exit;
}

$applicant = $data['applicant'] ?? $data;
$position = $data['position'] ?? ($applicant['applied_position'] ?? null);
$salaryGrade = isset($data['salary_grade']) ? intval($data['salary_grade']) : (isset($applicant['salary_grade']) ? intval($applicant['salary_grade']) : null);
$isGeneralServices = isset($data['is_general_services']) ? boolval($data['is_general_services']) : (!empty($applicant['is_general_services']));
if (!$position) {
    echo json_encode(['error'=>'position is required (example: "Teacher I")']);
    exit;
}

$out = AssessmentProcessor::processApplication($applicant, $position);
// attach provided salary grade / general services into output for clarity
if ($salaryGrade !== null) $out['salary_grade'] = $salaryGrade;
$out['is_general_services'] = $isGeneralServices ? true : false;

echo json_encode($out, JSON_PRETTY_PRINT);

?>
