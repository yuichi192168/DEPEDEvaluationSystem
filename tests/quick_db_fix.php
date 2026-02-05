<?php
/**
 * Quick Database Fix - One Page Reference
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>🔧 Quick Database Fix</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        h1 { color: #667eea; margin: 0 0 30px 0; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; }
        .card { padding: 20px; border-radius: 8px; border-left: 4px solid #667eea; }
        .card h3 { margin: 0 0 15px 0; color: #333; }
        .card ol { margin: 0; padding-left: 20px; }
        .card li { margin: 8px 0; }
        .success { background: #d4edda; border-color: #28a745; }
        .error { background: #f8d7da; border-color: #dc3545; }
        .info { background: #d1ecf1; border-color: #17a2b8; }
        .warning { background: #fff3cd; border-color: #ffc107; }
        .button-group { display: flex; gap: 10px; margin-top: 15px; flex-wrap: wrap; }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #667eea; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: #333; }
        .code { background: #f5f5f5; padding: 15px; border-radius: 5px; border: 1px solid #ddd; font-family: monospace; margin: 10px 0; }
        .issue { margin: 15px 0; padding: 10px; background: #fff3cd; border-left: 3px solid #ffc107; }
        .issue strong { display: block; margin-bottom: 5px; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔧 Database Connection Fix - Quick Start</h1>
    
    <div class="grid">
        <div class="card error">
            <h3>❌ Problem</h3>
            <p><strong>Error:</strong> "Target machine actively refused it"</p>
            <p><strong>Cause:</strong> MySQL is not running</p>
        </div>
        
        <div class="card success">
            <h3>✅ Solution</h3>
            <ol>
                <li>Open XAMPP Control Panel</li>
                <li>Click "Start" next to MySQL</li>
                <li>Wait for green indicator</li>
                <li>Refresh this page</li>
            </ol>
        </div>
    </div>

    <hr style="margin: 30px 0; border: none; border-top: 2px solid #ddd;">

    <div class="grid">
        <div class="card info">
            <h3>🧪 Test Connection</h3>
            <p>Before continuing, verify your connection works:</p>
            <div class="button-group">
                <a href="test_connection.php" class="btn btn-primary" target="_blank">Test Now</a>
            </div>
        </div>

        <div class="card info">
            <h3>⚙️ Setup Database</h3>
            <p>Create or initialize database:</p>
            <div class="button-group">
                <a href="database_setup.php" class="btn btn-success" target="_blank">Setup Wizard</a>
            </div>
        </div>
    </div>

    <hr style="margin: 30px 0; border: none; border-top: 2px solid #ddd;">

    <div class="card warning">
        <h3>⚙️ Configuration Check</h3>
        <p><strong>Current Settings (from initialize.php):</strong></p>
        <?php
        require_once(__DIR__ . '/initialize.php');
        echo '<div class="code">';
        echo "Server:  " . DB_SERVER . "<br>";
        echo "Port:    " . DB_PORT . "<br>";
        echo "User:    " . DB_USERNAME . "<br>";
        echo "Pass:    " . (DB_PASSWORD ? "***" : "(empty)") . "<br>";
        echo "DB:      " . DB_NAME . "<br>";
        echo '</div>';
        ?>
    </div>

    <hr style="margin: 30px 0; border: none; border-top: 2px solid #ddd;">

    <div style="background: #f5f5f5; padding: 20px; border-radius: 8px;">
        <h3>📋 Common Issues & Fixes</h3>
        
        <div class="issue">
            <strong>❌ "Connection refused"</strong>
            <p>MySQL not running → Start MySQL in XAMPP Control Panel</p>
        </div>

        <div class="issue">
            <strong>❌ "Access denied"</strong>
            <p>Wrong credentials → Check DB_USERNAME and DB_PASSWORD in initialize.php</p>
        </div>

        <div class="issue">
            <strong>❌ "Unknown database"</strong>
            <p>Database missing → Use Setup Wizard to create database</p>
        </div>

        <div class="issue">
            <strong>❌ "Connection timeout"</strong>
            <p>Wrong port → Try changing DB_PORT from 3306 to 3307 (or vice versa) in initialize.php</p>
        </div>
    </div>

    <hr style="margin: 30px 0; border: none; border-top: 2px solid #ddd;">

    <div style="text-align: center;">
        <h3>Ready to Go?</h3>
        <div class="button-group" style="justify-content: center;">
            <a href="test_connection.php" class="btn btn-primary" target="_blank">1️⃣ Test Connection</a>
            <a href="database_setup.php" class="btn btn-success" target="_blank">2️⃣ Setup Database</a>
            <a href="index.php" class="btn btn-warning">3️⃣ Go to App</a>
        </div>
    </div>

    <hr style="margin: 30px 0; border: none; border-top: 2px solid #ddd;">

    <div style="background: #e7f3ff; padding: 15px; border-radius: 5px; border-left: 4px solid #2196F3;">
        <h3>📖 Full Documentation</h3>
        <p>For detailed troubleshooting and configuration options, see: <strong>DATABASE_FIX_COMPLETE.md</strong></p>
    </div>
</div>
</body>
</html>
