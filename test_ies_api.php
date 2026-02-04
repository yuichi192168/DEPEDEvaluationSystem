<?php
/**
 * Test API: get_applicant_evaluation.php
 * Used to verify IES data retrieval for both active and archived applicants
 */

require_once 'classes/DBConnection.php';
require_once 'classes/AuthenticationHelper.php';

$conn = DBConnection::getConnection();
if (!$conn) {
    echo "Database connection failed";
    exit;
}

// Get a test applicant ID (active)
$result = $conn->query("SELECT id, name, archive_status FROM applicants WHERE archive_status = 'active' LIMIT 1");
$activeApplicant = $result ? $result->fetch_assoc() : null;

// Get a test applicant ID (archived)
$result = $conn->query("SELECT id, name, archive_status FROM applicants WHERE archive_status = 'archived' LIMIT 1");
$archivedApplicant = $result ? $result->fetch_assoc() : null;

?>
<!DOCTYPE html>
<html>
<head>
    <title>IES API Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .test-section { margin: 20px 0; padding: 20px; border: 1px solid #ddd; border-radius: 4px; }
        .test-section h3 { margin-top: 0; }
        .success { color: green; }
        .error { color: red; }
        .info { color: #0066cc; }
        button { padding: 8px 16px; margin: 5px; cursor: pointer; background: #E04040; color: white; border: none; border-radius: 4px; }
        button:hover { background: #c83030; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Individual Evaluation Sheet (IES) API Test</h1>
        
        <div class="test-section">
            <h3>Active Applicant Test</h3>
            <?php if ($activeApplicant): ?>
                <p class="success">✓ Found active applicant: <strong><?php echo htmlspecialchars($activeApplicant['name']); ?></strong> (ID: <?php echo $activeApplicant['id']; ?>)</p>
                <button onclick="testAPI(<?php echo $activeApplicant['id']; ?>)">Test API for Active Applicant</button>
                <div id="activeResult"></div>
            <?php else: ?>
                <p class="error">✗ No active applicants found</p>
            <?php endif; ?>
        </div>

        <div class="test-section">
            <h3>Archived Applicant Test</h3>
            <?php if ($archivedApplicant): ?>
                <p class="success">✓ Found archived applicant: <strong><?php echo htmlspecialchars($archivedApplicant['name']); ?></strong> (ID: <?php echo $archivedApplicant['id']; ?>)</p>
                <button onclick="testAPI(<?php echo $archivedApplicant['id']; ?>)">Test API for Archived Applicant</button>
                <div id="archivedResult"></div>
            <?php else: ?>
                <p class="error">✗ No archived applicants found</p>
            <?php endif; ?>
        </div>

        <div class="test-section">
            <h3>Test Instructions</h3>
            <p class="info">
                1. Click the "Test API" button for the applicant type you want to test<br/>
                2. The response will appear below showing whether data was retrieved successfully<br/>
                3. Check the browser console (F12) for detailed API logs<br/>
                4. If data is missing, check if evaluations exist for that applicant
            </p>
        </div>
    </div>

    <script>
        function testAPI(applicantId) {
            const resultDiv = document.getElementById(applicantId === <?php echo $activeApplicant['id'] ?? 0; ?> ? 'activeResult' : 'archivedResult');
            
            resultDiv.innerHTML = '<p style="text-align: center; color: #999;"><i class="fas fa-spinner fa-spin"></i> Loading...</p>';
            
            console.log('Testing API for applicant ID:', applicantId);
            
            fetch('./api/get_applicant_evaluation.php?id=' + applicantId)
            .then(response => {
                console.log('API Response Status:', response.status);
                console.log('Response Headers:', {
                    'content-type': response.headers.get('content-type')
                });
                return response.json();
            })
            .then(data => {
                console.log('API Response Data:', data);
                
                let html = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                
                if (data.success) {
                    html = '<p class="success">✓ API Call Successful</p>' + html;
                    
                    if (data.evaluation) {
                        html += '<p class="success">✓ Evaluation found</p>';
                    } else {
                        html += '<p style="color: #ff9800;">⚠ No evaluation found</p>';
                    }
                    
                    if (data.details && data.details.length > 0) {
                        html += '<p class="success">✓ ' + data.details.length + ' evaluation details found</p>';
                    }
                } else {
                    html = '<p class="error">✗ API Error: ' + (data.message || 'Unknown error') + '</p>' + html;
                }
                
                resultDiv.innerHTML = html;
            })
            .catch(error => {
                console.error('API Error:', error);
                resultDiv.innerHTML = '<p class="error">✗ Error: ' + error.message + '</p><pre>' + error.stack + '</pre>';
            });
        }
    </script>
</body>
</html>
