<?php
// Migration helper: add audit_logs table to schema.sql and create table in DB
require_once __DIR__ . '/../initialize.php';

$schemaFile = __DIR__ . '/../database/schema.sql';
$auditSql = "\n-- Table: audit_logs\nCREATE TABLE IF NOT EXISTS audit_logs (\n    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,\n    action VARCHAR(100) NOT NULL,\n    object_type VARCHAR(100) DEFAULT NULL,\n    object_id VARCHAR(100) DEFAULT NULL,\n    user_id INT DEFAULT NULL,\n    session_id VARCHAR(128) DEFAULT NULL,\n    ip_address VARCHAR(45) DEFAULT NULL,\n    meta JSON DEFAULT NULL,\n    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n    INDEX idx_action (action),\n    INDEX idx_object_type (object_type),\n    INDEX idx_user_id (user_id),\n    INDEX idx_created_at (created_at)\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n";

// Append to schema.sql if not present
$contents = @file_get_contents($schemaFile);
if ($contents === false) {
    echo "ERROR: Cannot read schema file at $schemaFile\n";
    exit(1);
}
if (strpos($contents, 'CREATE TABLE IF NOT EXISTS audit_logs') === false) {
    file_put_contents($schemaFile, rtrim($contents, "\n") . "\n\n" . $auditSql);
    echo "Appended audit_logs DDL to schema.sql\n";
} else {
    echo "schema.sql already contains audit_logs DDL\n";
}

// Now run the DDL against the database
$mysqli = @new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, intval(DB_PORT));
if ($mysqli->connect_errno) {
    echo "ERROR: DB connection failed: " . $mysqli->connect_error . "\n";
    exit(1);
}
if ($mysqli->query($auditSql)) {
    echo "audit_logs table ensured in database.\n";
} else {
    echo "ERROR: Failed to create audit_logs table: " . $mysqli->error . "\n";
}
$mysqli->close();

?>