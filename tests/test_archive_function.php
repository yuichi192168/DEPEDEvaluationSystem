<?php
/**
 * Archive Function Test Page
 * Tests the archive functionality with different applicant names
 */

session_start();
require_once(__DIR__ . '/classes/DBConnection.php');
require_once(__DIR__ . '/classes/AuthenticationHelper.php');
require_once(__DIR__ . '/classes/ApplicantManager.php');

$conn = DBConnection::getConnection();
if (!$conn) {
    die('Database connection failed');
}

// Require admin authentication
$auth = new AuthenticationHelper($conn);
$auth->requireAdmin('/admin/login');

$manager = new ApplicantManager($conn);

// Get a few test applicants
$testApplicants = $manager->getActiveApplicants('', '', 10, 0);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive Function Test</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="manifest" href="images/site.webmanifest">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .test-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }

        .test-section h2 {
            color: #667eea;
            font-size: 18px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        thead {
            background: #667eea;
            color: white;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            font-weight: 600;
            font-size: 14px;
        }

        td {
            font-size: 14px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-working {
            background: #d4edda;
            color: #155724;
        }

        .status-testing {
            background: #fff3cd;
            color: #856404;
        }

        .log-console {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 13px;
            max-height: 400px;
            overflow-y: auto;
            margin-top: 20px;
        }

        .log-entry {
            margin-bottom: 8px;
            padding: 4px;
            border-left: 3px solid transparent;
        }

        .log-info {
            border-left-color: #4CAF50;
        }

        .log-error {
            border-left-color: #f44336;
            color: #ff6b6b;
        }

        .log-success {
            border-left-color: #2196F3;
            color: #4fc3f7;
        }

        .code-block {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
        }

        .special-char {
            background: #fff3cd;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-vial"></i> Archive Function Test</h1>
        <p class="subtitle">Testing archive functionality with data attributes approach</p>

        <div class="test-section">
            <h2><i class="fas fa-info-circle"></i> Test Information</h2>
            <p><strong>Purpose:</strong> Verify that the archive function works correctly with all applicant names, including those with special characters.</p>
            <p><strong>Method:</strong> Using HTML5 data attributes (<code>data-id</code> and <code>data-name</code>) to safely pass parameters.</p>
            <div class="code-block">
                &lt;button data-id="<?php echo $id; ?>" data-name="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>"<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;onclick="archiveApplicant(this.dataset.id, this.dataset.name)"&gt;
            </div>
        </div>

        <div class="test-section">
            <h2><i class="fas fa-users"></i> Test Applicants (<?php echo count($testApplicants); ?> loaded)</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Special Characters</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($testApplicants as $applicant): 
                        $hasSpecialChars = preg_match('/[\'"\&<>]/', $applicant['name']);
                        $specialChars = [];
                        if (strpos($applicant['name'], "'") !== false) $specialChars[] = "apostrophe (')";
                        if (strpos($applicant['name'], '"') !== false) $specialChars[] = 'quotes (")';
                        if (strpos($applicant['name'], '&') !== false) $specialChars[] = "ampersand (&)";
                    ?>
                    <tr>
                        <td><strong>#<?php echo $applicant['id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($applicant['name']); ?></td>
                        <td>
                            <?php if ($hasSpecialChars): ?>
                                <span class="special-char"><?php echo implode(', ', $specialChars); ?></span>
                            <?php else: ?>
                                <span style="color: #999;">None</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status status-testing">Test Ready</span>
                        </td>
                        <td>
                            <button class="btn btn-danger" 
                                data-id="<?php echo $applicant['id']; ?>" 
                                data-name="<?php echo htmlspecialchars($applicant['name'], ENT_QUOTES); ?>"
                                onclick="testArchive(this.dataset.id, this.dataset.name)">
                                <i class="fas fa-flask"></i> Test Archive
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="test-section">
            <h2><i class="fas fa-terminal"></i> Test Console Log</h2>
            <div class="log-console" id="logConsole">
                <div class="log-entry log-info">Console ready. Click "Test Archive" to begin testing...</div>
            </div>
        </div>

        <div class="test-section">
            <h2><i class="fas fa-clipboard-check"></i> Quick Actions</h2>
            <button class="btn btn-success" onclick="clearLog()">
                <i class="fas fa-eraser"></i> Clear Log
            </button>
            <button class="btn btn-success" onclick="location.href='admin/applicants.php'">
                <i class="fas fa-arrow-left"></i> Back to Applicants
            </button>
            <button class="btn btn-success" onclick="location.href='admin/dashboard.php'">
                <i class="fas fa-tachometer-alt"></i> Back to Dashboard
            </button>
        </div>
    </div>

    <script>
        function addLog(message, type = 'info') {
            const console = document.getElementById('logConsole');
            const timestamp = new Date().toLocaleTimeString();
            const entry = document.createElement('div');
            entry.className = `log-entry log-${type}`;
            entry.innerHTML = `[${timestamp}] ${message}`;
            console.appendChild(entry);
            console.scrollTop = console.scrollHeight;
        }

        function clearLog() {
            const console = document.getElementById('logConsole');
            console.innerHTML = '<div class="log-entry log-info">Console cleared.</div>';
        }

        function testArchive(id, name) {
            addLog(`========== TEST START ==========`, 'info');
            addLog(`Testing archive for: "${name}" (ID: ${id})`, 'info');
            
            // Log parameter types
            addLog(`Parameter types - ID: ${typeof id}, Name: ${typeof name}`, 'info');
            
            // Parse ID to integer
            const applicantId = parseInt(id, 10);
            
            if (isNaN(applicantId) || applicantId <= 0) {
                addLog(`❌ FAILED: Invalid applicant ID: ${id}`, 'error');
                addLog(`========== TEST END ==========\n`, 'error');
                return;
            }
            
            addLog(`✓ ID validation passed: ${applicantId}`, 'success');
            addLog(`✓ Name received: "${name}"`, 'success');
            
            // Test with actual API call
            const reason = 'Test archive reason';
            
            addLog(`Sending POST request to API...`, 'info');
            
            fetch('api/archive_applicant.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    applicant_id: applicantId,
                    reason: reason,
                    archived_by: 'Test Admin'
                })
            })
            .then(response => {
                addLog(`Response status: ${response.status}`, 'info');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    addLog(`✓ SUCCESS: ${data.message}`, 'success');
                    addLog(`Archive operation completed successfully!`, 'success');
                    addLog(`========== TEST PASSED ==========\n`, 'success');
                    
                    // Reload page after 2 seconds
                    setTimeout(() => location.reload(), 2000);
                } else {
                    addLog(`❌ FAILED: ${data.message}`, 'error');
                    addLog(`========== TEST FAILED ==========\n`, 'error');
                }
            })
            .catch(error => {
                addLog(`❌ ERROR: ${error.message}`, 'error');
                addLog(`========== TEST FAILED ==========\n`, 'error');
            });
        }

        // Test on page load
        addLog('Page loaded successfully', 'success');
        addLog('Data attributes method enabled', 'success');
        addLog(`Total applicants loaded: <?php echo count($testApplicants); ?>`, 'info');
    </script>
</body>
</html>
