<?php
/**
 * Database Setup & Verification Tool
 * Guides through MySQL connection setup and database initialization
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Setup & Verification</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
        h1 { color: #333; }
        .section { margin: 20px 0; padding: 15px; background: #f9f9f9; border-left: 4px solid #007bff; }
        .step { margin: 10px 0; padding: 10px; background: white; border: 1px solid #ddd; }
        .success { background: #d4edda; border-color: #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .warning { background: #fff3cd; border-color: #ffeaa7; color: #856404; }
        .info { background: #d1ecf1; border-color: #bee5eb; color: #0c5460; }
        code { background: #f4f4f4; padding: 2px 5px; border-radius: 3px; }
        .button { padding: 8px 15px; margin: 5px; cursor: pointer; border: none; border-radius: 3px; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .test-result { margin: 10px 0; padding: 10px; border-radius: 3px; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔧 Database Connection Setup & Verification</h1>
    
    <?php
    // Require configuration
    require_once(__DIR__ . "/initialize.php");
    
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    
    echo '<div class="section info">';
    echo '<h3>Current Configuration:</h3>';
    echo '<p><strong>Server:</strong> ' . DB_SERVER . '</p>';
    echo '<p><strong>Port:</strong> ' . DB_PORT . '</p>';
    echo '<p><strong>Username:</strong> ' . DB_USERNAME . '</p>';
    echo '<p><strong>Database:</strong> ' . DB_NAME . '</p>';
    echo '</div>';
    
    // Test Connection
    echo '<div class="section">';
    echo '<h3>1️⃣ Test Connection</h3>';
    
    $conn = @new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, intval(DB_PORT));
    
    if ($conn->connect_errno) {
        // Try alternate port
        $alt_port = (DB_PORT == 3306) ? 3307 : 3306;
        $conn = @new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, $alt_port);
        
        if ($conn->connect_errno) {
            echo '<div class="test-result error">';
            echo '<strong>❌ Connection Failed</strong><br>';
            echo 'Error: ' . $conn->connect_error . '<br><br>';
            echo '<strong>Solutions:</strong><ul>';
            echo '<li><strong>Step 1:</strong> Open XAMPP Control Panel</li>';
            echo '<li><strong>Step 2:</strong> Click "Start" button next to MySQL</li>';
            echo '<li><strong>Step 3:</strong> Wait for it to say "Running"</li>';
            echo '<li><strong>Step 4:</strong> Refresh this page</li>';
            echo '<li><strong>Step 5:</strong> If still failing, try changing port from ' . DB_PORT . ' to ' . $alt_port . ' in initialize.php</li>';
            echo '</ul>';
            echo '</div>';
        } else {
            echo '<div class="test-result warning">';
            echo '<strong>⚠️ Connection succeeded on alternate port: ' . $alt_port . '</strong><br>';
            echo '<p>Please update <code>initialize.php</code> and change DB_PORT from ' . DB_PORT . ' to ' . $alt_port . '</p>';
            echo '</div>';
        }
    } else {
        echo '<div class="test-result success">';
        echo '<strong>✅ Connection Successful!</strong>';
        echo '</div>';
        
        // Check MySQL version
        $result = $conn->query("SELECT VERSION()");
        if ($result) {
            $row = $result->fetch_row();
            echo '<p><strong>MySQL Version:</strong> ' . $row[0] . '</p>';
        }
        
        // Check database
        $result = $conn->query("SELECT COUNT(*) as count FROM information_schema.SCHEMATA WHERE SCHEMA_NAME='" . DB_NAME . "'");
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            echo '<div class="test-result success">';
            echo '<strong>✅ Database exists</strong>';
            echo '</div>';
            
            // Check tables
            $result = $conn->query("SHOW TABLES");
            $table_count = $result->num_rows;
            
            if ($table_count > 0) {
                echo '<p><strong>Tables found: ' . $table_count . '</strong></p>';
                echo '<ul>';
                while ($row = $result->fetch_row()) {
                    echo '<li>' . $row[0] . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<div class="test-result warning">';
                echo '<strong>⚠️ Database exists but no tables found</strong><br>';
                echo '<p><a href="?action=initialize" class="button btn-primary">Initialize Database</a></p>';
                echo '</div>';
            }
        } else {
            echo '<div class="test-result warning">';
            echo '<strong>⚠️ Database does not exist</strong><br>';
            echo '<p><a href="?action=create_db" class="button btn-primary">Create Database</a></p>';
            echo '</div>';
        }
        
        $conn->close();
    }
    
    echo '</div>';
    
    // Handle actions
    if ($action == 'create_db') {
        echo '<div class="section">';
        echo '<h3>Creating Database...</h3>';
        
        $conn = @new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, '', intval(DB_PORT));
        
        if (!$conn->connect_errno) {
            if ($conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME)) {
                echo '<div class="test-result success">';
                echo '<strong>✅ Database created successfully!</strong><br>';
                echo '<p><a href="?action=initialize" class="button btn-success">Next: Initialize Tables</a></p>';
                echo '</div>';
            } else {
                echo '<div class="test-result error">';
                echo '<strong>❌ Error creating database:</strong> ' . $conn->error;
                echo '</div>';
            }
            $conn->close();
        } else {
            echo '<div class="test-result error">';
            echo '<strong>❌ Cannot connect to MySQL to create database</strong>';
            echo '</div>';
        }
        echo '</div>';
    }
    
    if ($action == 'initialize') {
        echo '<div class="section">';
        echo '<h3>Initializing Database Tables...</h3>';
        
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, intval(DB_PORT));
        
        if (!$conn->connect_errno) {
            $schema_file = __DIR__ . '/database/schema.sql';
            
            if (file_exists($schema_file)) {
                $sql = file_get_contents($schema_file);
                
                // Split SQL into individual statements
                $statements = array_filter(array_map('trim', explode(';', $sql)));
                
                $success_count = 0;
                foreach ($statements as $statement) {
                    if (!empty($statement)) {
                        if ($conn->query($statement)) {
                            $success_count++;
                        } else {
                            echo '<div class="test-result warning">';
                            echo 'Note: ' . $conn->error . '<br>';
                            echo '</div>';
                        }
                    }
                }
                
                echo '<div class="test-result success">';
                echo '<strong>✅ Database initialized!</strong><br>';
                echo '<p>Executed ' . $success_count . ' table creation statements</p>';
                echo '<p><a href="index.php" class="button btn-success">Go to Evaluation System</a></p>';
                echo '</div>';
            } else {
                echo '<div class="test-result error">';
                echo '<strong>❌ Schema file not found:</strong> ' . $schema_file;
                echo '</div>';
            }
            $conn->close();
        } else {
            echo '<div class="test-result error">';
            echo '<strong>❌ Cannot connect to database</strong>';
            echo '</div>';
        }
        echo '</div>';
    }
    
    // Quick troubleshooting guide
    echo '<div class="section">';
    echo '<h3>📋 Troubleshooting Guide</h3>';
    echo '<ul>';
    echo '<li><strong>"Target machine actively refused it":</strong> MySQL is not running. Start it in XAMPP Control Panel.</li>';
    echo '<li><strong>"Access denied for user":</strong> Wrong username or password. Check initialize.php.</li>';
    echo '<li><strong>"Connection timeout":</strong> Wrong host or port. Verify DB_SERVER and DB_PORT.</li>';
    echo '<li><strong>"Unknown database":</strong> Create the database using button above.</li>';
    echo '</ul>';
    echo '</div>';
    ?>
</div>
</body>
</html>
