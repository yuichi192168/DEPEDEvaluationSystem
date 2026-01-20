<?php
/**
 * Database Configuration
 * DepEd HRMPSB Evaluation System
 */

define(constant_name: 'DB_HOST', value: '127.0.0.1');
define(constant_name: 'DB_USER', value: 'root');
define(constant_name: 'DB_PASS', value: '');
define(constant_name: 'DB_NAME', value: 'deped_evaluation');
define(constant_name: 'DB_PORT', value: '3307');

// Create connection
function getDBConnection(): mysqli {
    $conn = new mysqli(hostname: DB_HOST, username: DB_USER, password: DB_PASS, database: DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}
?>

