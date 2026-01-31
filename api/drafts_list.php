<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__ . '/../classes/DBConnection.php');

$conn = DBConnection::getConnection();
if (!$conn) {
    echo json_encode(['success'=>false,'message'=>'DB connection failed']); exit;
}

$sql = "SELECT id, session_id, application_code, created_at, updated_at FROM drafts ORDER BY updated_at DESC LIMIT 200";
$res = $conn->query($sql);
$rows = [];
if ($res) {
    while ($r = $res->fetch_assoc()) $rows[] = $r;
}

echo json_encode(['success'=>true,'drafts'=>$rows]);

?>
