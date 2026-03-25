<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__ . '/../classes/DBConnection.php');

$code = isset($_REQUEST['application_code']) ? trim($_REQUEST['application_code']) : '';
$applicantName = isset($_REQUEST['applicant_name']) ? trim($_REQUEST['applicant_name']) : '';

if ($code === '' && $applicantName === '') {
    echo json_encode(['success' => false, 'message' => 'No duplicate-check data provided']);
    exit;
}

$conn = DBConnection::getConnection();

$codeExists = false;
$nameCodeExists = false;

if ($code !== '') {
    $stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM comparative_assessment_results WHERE application_code = ?");
    $stmt->bind_param('s', $code);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $codeExists = ($row && intval($row['cnt']) > 0);
    $stmt->close();
}

if ($code !== '' && $applicantName !== '') {
    $stmt = $conn->prepare(
        "SELECT COUNT(*) AS cnt
         FROM comparative_assessment_results car
         INNER JOIN applicants a ON a.id = car.applicant_id
         WHERE car.application_code = ?
           AND LOWER(TRIM(a.name)) = LOWER(TRIM(?))"
    );
    $stmt->bind_param('ss', $code, $applicantName);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $nameCodeExists = ($row && intval($row['cnt']) > 0);
    $stmt->close();
}

echo json_encode([
    'success' => true,
    'exists' => $codeExists,
    'code_exists' => $codeExists,
    'name_code_exists' => $nameCodeExists,
    'message' => $nameCodeExists
        ? 'Duplicate applicant name and application code found.'
        : ($codeExists ? 'Application code already exists.' : 'No duplicate found.')
]);

?>
