<!DOCTYPE html>
<html>
<head>
    <title>PHP Error Log Viewer</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        h1 { color: #4ec9b0; }
        .log-entry { padding: 10px; margin: 5px 0; background: #252526; border-left: 3px solid #007acc; }
        .error { border-left-color: #f48771; }
        .warning { border-left-color: #cca700; }
        .info { border-left-color: #4ec9b0; }
        .refresh { padding: 10px 20px; background: #0e639c; color: white; border: none; cursor: pointer; margin-bottom: 20px; }
        .clear { padding: 10px 20px; background: #c5000b; color: white; border: none; cursor: pointer; margin-left: 10px; }
    </style>
</head>
<body>
    <h1>📋 PHP Error Log - Live View</h1>
    
    <button class="refresh" onclick="location.reload()">🔄 Refresh</button>
    <button class="clear" onclick="if(confirm('Clear log?')) location.href='?clear=1'">🗑️ Clear Log</button>
    
    <hr>
    
    <?php
    // Find error log location
    $errorLogPaths = [
        'C:\xampp\php\logs\php_error_log',
        'C:\xampp\apache\logs\error.log',
        ini_get('error_log'),
        __DIR__ . '/logs/php_errors.log'
    ];
    
    $logFile = null;
    foreach ($errorLogPaths as $path) {
        if ($path && file_exists($path)) {
            $logFile = $path;
            break;
        }
    }
    
    if (isset($_GET['clear']) && $logFile) {
        file_put_contents($logFile, '');
        echo "<p style='color: #4ec9b0;'>✓ Log cleared</p>";
        echo "<meta http-equiv='refresh' content='1;url=view_error_log.php'>";
    }
    
    if ($logFile) {
        echo "<p><strong>Log file:</strong> " . htmlspecialchars($logFile) . "</p>";
        echo "<p><strong>Size:</strong> " . number_format(filesize($logFile)) . " bytes</p>";
        echo "<hr>";
        
        // Read last 100 lines
        $lines = file($logFile);
        $lines = array_slice($lines, -100);
        
        if (empty($lines)) {
            echo "<p style='color: #cca700;'>No log entries found.</p>";
        } else {
            echo "<p><strong>Showing last " . count($lines) . " lines:</strong></p>";
            
            foreach (array_reverse($lines) as $line) {
                $class = 'log-entry';
                if (stripos($line, 'error') !== false || stripos($line, 'fatal') !== false) {
                    $class .= ' error';
                } elseif (stripos($line, 'warning') !== false) {
                    $class .= ' warning';
                } else {
                    $class .= ' info';
                }
                
                echo "<div class='{$class}'>" . htmlspecialchars($line) . "</div>";
            }
        }
    } else {
        echo "<p style='color: #f48771;'>✗ Error log not found. Checked paths:</p>";
        echo "<ul>";
        foreach ($errorLogPaths as $path) {
            echo "<li>" . htmlspecialchars($path) . " - " . (file_exists($path) ? 'EXISTS' : 'NOT FOUND') . "</li>";
        }
        echo "</ul>";
    }
    ?>
    
    <hr>
    <p><a href="admin/login.php" style="color: #4ec9b0;">← Back to Login</a></p>
    
    <script>
        // Auto-refresh every 3 seconds
        setTimeout(function() {
            location.reload();
        }, 3000);
    </script>
</body>
</html>
