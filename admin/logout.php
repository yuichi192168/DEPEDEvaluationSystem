<?php
/**
 * Admin Logout
 * Securely logs out admin user
 */

session_start();
require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');

$conn = DBConnection::getConnection();
$auth = new AuthenticationHelper($conn);

// Log out the user
$auth->logout();

// Redirect to login page
header('Location: login.php?logged_out=1');
exit;
?>
