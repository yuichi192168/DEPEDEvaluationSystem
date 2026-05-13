<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__ . '/../classes/DBConnection.php');

$code = isset($_REQUEST['application_code']) ? trim($_REQUEST['application_code']) : '';
$applicantName = isset($_REQUEST['applicant_name']) ? trim($_REQUEST['applicant_name']) : '';
$positionApplied = isset($_REQUEST['position_applied']) ? trim($_REQUEST['position_applied']) : '';
$evaluationDate = !empty($_REQUEST['evaluation_date']) ? trim($_REQUEST['evaluation_date']) : date('Y-m-d');
$evaluationPeriodStart = date('Y-m-01', strtotime($evaluationDate));
$evaluationPeriodEnd = date('Y-m-t', strtotime($evaluationDate));

if ($code === '' && $applicantName === '') {
    echo json_encode(['success' => false, 'message' => 'No duplicate-check data provided']);
    exit;
}

$conn = DBConnection::getConnection();

$codeExists = false;
$periodDuplicateExists = false;

if ($code !== '') {
    $stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM comparative_assessment_results WHERE application_code = ?");
    $stmt->bind_param('s', $code);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $codeExists = ($row && intval($row['cnt']) > 0);
    $stmt->close();
}

if ($applicantName !== '' && $positionApplied !== '') {
    $stmt = $conn->prepare(
        "SELECT COUNT(*) AS cnt
         FROM comparative_assessment_results car
         INNER JOIN applicants a ON a.id = car.applicant_id
         INNER JOIN positions p ON p.id = car.position_id
         WHERE LOWER(TRIM(a.name)) = LOWER(TRIM(?))
           AND LOWER(TRIM(p.position_name)) = LOWER(TRIM(?))
           AND car.assessment_date BETWEEN ? AND ?"
    );
    $stmt->bind_param('ssss', $applicantName, $positionApplied, $evaluationPeriodStart, $evaluationPeriodEnd);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $periodDuplicateExists = ($row && intval($row['cnt']) > 0);
    $stmt->close();
}

echo json_encode([
    'success' => true,
    'exists' => $codeExists,
    'code_exists' => $codeExists,
    'period_duplicate_exists' => $periodDuplicateExists,
    'name_code_exists' => $periodDuplicateExists,
    'message' => $periodDuplicateExists
        ? 'Duplicate applicant evaluation found for this period.'
        : ($codeExists ? 'Application code already exists.' : 'No duplicate found.')
]);

?>
