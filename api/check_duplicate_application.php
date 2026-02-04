<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__ . '/../classes/DBConnection.php');

$code = isset($_REQUEST['application_code']) ? trim($_REQUEST['application_code']) : '';
if ($code === '') {
    echo json_encode(['success' => false, 'message' => 'No application_code provided']);
    exit;
}

$conn = DBConnection::getConnection();
$escaped = $conn->real_escape_string($code);

$sql = "SELECT COUNT(*) as cnt FROM comparative_assessment_results WHERE application_code = '{$escaped}'";
$res = $conn->query($sql);
$row = $res ? $res->fetch_assoc() : null;
$exists = ($row && intval($row['cnt']) > 0);

echo json_encode(['success' => true, 'exists' => $exists]);

?>
