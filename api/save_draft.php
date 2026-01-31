<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__ . '/../classes/DBConnection.php');

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data || !is_array($data)) {
    echo json_encode(['success' => false, 'message' => 'Invalid payload']);
    exit;
}

$conn = DBConnection::getConnection();
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Ensure drafts table exists
$createSql = "CREATE TABLE IF NOT EXISTS drafts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(128) NOT NULL,
    application_code VARCHAR(128),
    data JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_session (session_id),
    INDEX idx_application_code (application_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($createSql);

$session_id = session_id() ?: bin2hex(random_bytes(8));
$appCode = isset($data['application_code']) ? $conn->real_escape_string($data['application_code']) : null;
$json = $conn->real_escape_string(json_encode($data));

// Try to update existing draft by session_id or application_code
if ($appCode) {
    $sql = "SELECT id FROM drafts WHERE application_code='{$appCode}' LIMIT 1";
    $res = $conn->query($sql);
    if ($res && $res->num_rows) {
        $row = $res->fetch_assoc();
        $upd = "UPDATE drafts SET data='${json}', updated_at = NOW() WHERE id=" . intval($row['id']);
        $ok = $conn->query($upd);
        // audit
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
        $meta = $conn->real_escape_string(json_encode(['updated_from_application_code'=>$appCode]));
        $conn->query("INSERT INTO audit_logs (action,object_type,object_id,session_id,meta,ip_address) VALUES ('draft_updated','draft','".intval(
            $row['id'])."','{$session_id}','{$meta}','{$ip}')");

        echo json_encode(['success' => (bool)$ok, 'message' => 'Draft updated', 'id' => $row['id']]);
        exit;
    }
}

// Fallback to session-based upsert
$sql = "SELECT id FROM drafts WHERE session_id='${session_id}' LIMIT 1";
$res = $conn->query($sql);
if ($res && $res->num_rows) {
    $row = $res->fetch_assoc();
    $upd = "UPDATE drafts SET data='${json}', application_code=" . ($appCode ? "'{$appCode}'" : "NULL") . ", updated_at = NOW() WHERE id=" . intval($row['id']);
    $ok = $conn->query($upd);
    // audit
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
    $meta = $conn->real_escape_string(json_encode(['updated_from_session'=>true]));
    $conn->query("INSERT INTO audit_logs (action,object_type,object_id,session_id,meta,ip_address) VALUES ('draft_updated','draft','".intval(
        $row['id'])."','{$session_id}','{$meta}','{$ip}')");

    echo json_encode(['success' => (bool)$ok, 'message' => 'Draft updated', 'id' => $row['id']]);
    exit;
}

// Insert new draft
$ins = "INSERT INTO drafts (session_id, application_code, data) VALUES ('{$session_id}', " . ($appCode ? "'{$appCode}'" : "NULL") . ", '{$json}')";
$ok = $conn->query($ins);
if ($ok) {
    $newId = $conn->insert_id;
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
    $meta = $conn->real_escape_string(json_encode(['source'=>'save_draft','payload_size'=>strlen($raw)]));
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $insAudit = "INSERT INTO audit_logs (action, object_type, object_id, session_id, meta, ip_address) VALUES ('draft_saved','draft','{$newId}','{$session_id}','{$meta}','{$ip}')";
    $conn->query($insAudit);

    echo json_encode(['success' => true, 'message' => 'Draft saved', 'id' => $newId]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save draft: ' . $conn->error]);
}

?>
