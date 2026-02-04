<?php
/**
 * Database Connection Test
 * Tests connection to MySQL and displays detailed information
 */

echo "=== Database Connection Test ===\n\n";

// Load configuration
require_once(__DIR__ . "/initialize.php");

echo "Configuration Settings:\n";
echo "- Server: " . DB_SERVER . "\n";
echo "- Port: " . DB_PORT . "\n";
echo "- Username: " . DB_USERNAME . "\n";
echo "- Database: " . DB_NAME . "\n";
echo "- Password: " . (DB_PASSWORD ? "***" : "No password") . "\n\n";

// Test connection
echo "Attempting connection...\n";

$conn = @new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, intval(DB_PORT));

if ($conn->connect_errno) {
    echo "❌ CONNECTION FAILED\n";
    echo "Error Code: " . $conn->connect_errno . "\n";
    echo "Error Message: " . $conn->connect_error . "\n\n";
    
    echo "Troubleshooting steps:\n";
    echo "1. Ensure MySQL is running\n";
    echo "2. Check if the host '" . DB_SERVER . "' is accessible\n";
    echo "3. Check if port " . DB_PORT . " is correct\n";
    echo "4. Verify username '" . DB_USERNAME . "' exists\n";
    echo "5. Check MySQL service in XAMPP Control Panel\n";
    
} else {
    echo "✅ CONNECTION SUCCESSFUL!\n\n";
    
    // Get MySQL version
    $result = $conn->query("SELECT VERSION()");
    if ($result) {
        $row = $result->fetch_row();
        echo "MySQL Version: " . $row[0] . "\n";
    }
    
    // Check if database exists
    $result = $conn->query("SHOW DATABASES LIKE '" . DB_NAME . "'");
    if ($result && $result->num_rows > 0) {
        echo "Database Status: ✅ EXISTS\n";
        
        // List tables
        $result = $conn->query("SHOW TABLES");
        echo "Tables in database:\n";
        while ($row = $result->fetch_row()) {
            echo "  - " . $row[0] . "\n";
        }
    } else {
        echo "Database Status: ❌ DATABASE DOES NOT EXIST\n";
    }
    
    $conn->close();
}
?>
