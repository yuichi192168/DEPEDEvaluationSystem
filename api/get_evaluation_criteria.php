<?php
/**
 * API Endpoint: Get Evaluation Criteria
 * Returns dynamic evaluation criteria based on position group and salary grade
 */
require_once __DIR__ . '/../config/evaluation_criteria.php';

header('Content-Type: application/json');

$positionGroup = $_GET['position_group'] ?? null;
$salaryGrade = isset($_GET['salary_grade']) ? intval($_GET['salary_grade']) : null;
$category = $_GET['category'] ?? null;

if (!$positionGroup) {
    echo json_encode(['error' => 'position_group parameter required'], JSON_PRETTY_PRINT);
    exit;
}

$criteria = getEvaluationCriteria($positionGroup, $salaryGrade, $category);

if (!$criteria) {
    echo json_encode(['error' => 'Criteria not found for position group'], JSON_PRETTY_PRINT);
    exit;
}

echo json_encode(formatCriteriaForJSON($criteria), JSON_PRETTY_PRINT);
?>
