<?php
/**
 * Database Configuration Wrapper
 * DepEd HRMPSB Evaluation System
 *
 * This file is kept for backward compatibility. All new code should use
 * `classes/DBConnection.php` directly.
 */

require_once __DIR__ . '/../classes/DBConnection.php';

/**
 * Legacy helper to get a mysqli connection.
 * Internally uses the DBConnection class which reads settings
 * from initialize.php (local or InfinityFree).
 */
function getDBConnection(): mysqli {
    $db = new DBConnection();
    return $db->conn;
}
?>

