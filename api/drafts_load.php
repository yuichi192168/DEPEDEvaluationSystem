<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__ . '/../classes/DBConnection.php');

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
if ($id <= 0) { echo json_encode(['success'=>false,'message'=>'Invalid id']); exit; }

$conn = DBConnection::getConnection();
if (!$conn) { echo json_encode(['success'=>false,'message'=>'DB connection failed']); exit; }

$sql = "SELECT data FROM drafts WHERE id={$id} LIMIT 1";
$res = $conn->query($sql);
if (!$res || $res->num_rows == 0) { echo json_encode(['success'=>false,'message'=>'Draft not found']); exit; }

$row = $res->fetch_assoc();
$data = json_decode($row['data'], true);

// Store in session for server-side restore
$_SESSION['loaded_draft'] = $data;

// Log audit
$auditSql = "CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(128) NOT NULL,
    object_type VARCHAR(64),
    object_id VARCHAR(128),
    session_id VARCHAR(128),
    meta JSON,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($auditSql);
$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$meta = $conn->real_escape_string(json_encode(['loaded_from_draft_id'=>$id]));
$conn->query("INSERT INTO audit_logs (action,object_type,object_id,session_id,meta,ip_address) VALUES ('draft_loaded','draft','{$id}','".session_id()."','{$meta}','{$ip}')");

echo json_encode(['success'=>true,'data'=>$data]);

?>
