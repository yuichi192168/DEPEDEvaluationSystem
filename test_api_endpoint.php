<?php
/**
 * API Test Tool: Test get_applicant_evaluation.php endpoint
 * This page allows you to directly test the API and see what it returns
 */

require_once(__DIR__ . '/classes/DBConnection.php');
require_once(__DIR__ . '/classes/AuthenticationHelper.php');

session_start();

$auth = new AuthenticationHelper(DBConnection::getConnection());
if (!$auth->isAdmin()) {
    http_response_code(403);
    die('Access denied: Admin login required');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>API Test Tool - get_applicant_evaluation</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: #E04040;
            margin-bottom: 10px;
        }
        .description {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .controls {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #E04040;
            box-shadow: 0 0 5px rgba(224, 64, 64, 0.3);
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #E04040;
            color: white;
        }
        .btn-primary:hover {
            background: #c83030;
        }
        .btn-primary:active {
            transform: scale(0.98);
        }
        .results {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .results h2 {
            margin-top: 0;
            color: #333;
        }
        .response-section {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            word-break: break-all;
            max-height: 500px;
            overflow-y: auto;
            font-size: 12px;
        }
        .success {
            color: #28a745;
            font-weight: bold;
        }
        .error {
            color: #dc3545;
            font-weight: bold;
        }
        .info {
            color: #17a2b8;
        }
        .loading {
            text-align: center;
            padding: 30px;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #E04040;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .applicants-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .applicants-table th,
        .applicants-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .applicants-table th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .applicants-table tr:hover {
            background: #f8f9fa;
        }
        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
            background: #E04040;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-small:hover {
            background: #c83030;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-flask"></i> API Test Tool</h1>
        <p>Test the <code>get_applicant_evaluation.php</code> endpoint to debug IES display issues</p>
        
        <div class="description">
            <strong><i class="fas fa-info-circle"></i> How to use this tool:</strong><br>
            1. Select an applicant from the dropdown<br>
            2. Click "Test API" to fetch their evaluation data<br>
            3. Review the raw JSON response to see what data is being returned<br>
            4. Check the browser console (F12) for any JavaScript errors
        </div>
        
        <div class="controls">
            <form id="testForm" onsubmit="return testApi(event)">
                <div class="form-group">
                    <label for="applicantId">
                        <i class="fas fa-user"></i> Select Applicant (Active)
                    </label>
                    <select id="applicantId" name="applicantId" required>
                        <option value="">Loading applicants...</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-play"></i> Test API
                </button>
            </form>
        </div>
        
        <div id="results"></div>
    </div>
    
    <script>
        // Load applicants when page loads
        document.addEventListener('DOMContentLoaded', loadApplicants);
        
        function loadApplicants() {
            console.log('Loading active applicants...');
            fetch('api/get_applicant_evaluation.php?id=0')  // Dummy call to validate auth
                .then(r => r.json())
                .catch(() => {
                    // Try to load applicants list
                    loadApplicantsList();
                });
            
            // Load applicants directly from a simpler endpoint
            loadApplicantsList();
        }
        
        function loadApplicantsList() {
            console.log('Fetching applicants list...');
            
            // Use XMLHttpRequest to get applicants directly from PHP
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'api/get_applicant_evaluation.php?action=list', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.applicants) {
                            populateApplicants(data.applicants);
                        }
                    } catch(e) {
                        console.error('Parse error:', e);
                        // Fall back to showing a text input
                        const select = document.getElementById('applicantId');
                        select.innerHTML = '<option value="">Enter applicant ID manually</option>';
                        select.style.display = 'none';
                        
                        const input = document.createElement('input');
                        input.type = 'number';
                        input.id = 'applicantId';
                        input.name = 'applicantId';
                        input.placeholder = 'Enter applicant ID';
                        input.required = true;
                        select.parentNode.replaceChild(input, select);
                    }
                }
            };
            xhr.onerror = function() {
                console.error('Failed to load applicants');
                const select = document.getElementById('applicantId');
                select.innerHTML = '<option value="">Enter applicant ID manually</option>';
                select.style.display = 'none';
                
                const input = document.createElement('input');
                input.type = 'number';
                input.id = 'applicantId';
                input.name = 'applicantId';
                input.placeholder = 'Enter applicant ID';
                input.required = true;
                select.parentNode.replaceChild(input, select);
            };
            xhr.send();
        }
        
        function populateApplicants(applicants) {
            const select = document.getElementById('applicantId');
            select.innerHTML = '<option value="">Select an applicant...</option>';
            applicants.forEach(app => {
                const option = document.createElement('option');
                option.value = app.id;
                option.textContent = `ID ${app.id}: ${app.name}`;
                select.appendChild(option);
            });
        }
        
        async function testApi(event) {
            event.preventDefault();
            
            const applicantId = document.getElementById('applicantId').value;
            if (!applicantId) {
                alert('Please select an applicant');
                return false;
            }
            
            console.log(`=== Testing API for applicant ${applicantId} ===`);
            
            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = `
                <div class="loading">
                    <div class="spinner"></div>
                    <p>Fetching evaluation data for applicant ${applicantId}...</p>
                </div>
            `;
            
            try {
                console.log(`Calling: api/get_applicant_evaluation.php?id=${applicantId}`);
                const response = await fetch(`api/get_applicant_evaluation.php?id=${applicantId}`);
                console.log('Response status:', response.status);
                
                const data = await response.json();
                console.log('Response data:', data);
                
                // Display results
                resultsDiv.innerHTML = `
                    <div class="results">
                        <h2><i class="fas fa-code"></i> API Response</h2>
                        
                        <div>
                            <strong>Status Code:</strong> <span class="${response.ok ? 'success' : 'error'}">${response.status}</span>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>Success:</strong> <span class="${data.success ? 'success' : 'error'}">${data.success ? 'YES' : 'NO'}</span>
                        </div>
                        
                        ${data.message ? `<div style="margin-bottom: 15px; color: ${data.success ? '#666' : '#dc3545'};"><strong>Message:</strong> ${data.message}</div>` : ''}
                        
                        <h3>Raw JSON Response:</h3>
                        <div class="response-section">${JSON.stringify(data, null, 2)}</div>
                        
                        <h3>Data Summary:</h3>
                        <ul>
                            <li><strong>Applicant Found:</strong> ${data.applicant ? 'YES (' + data.applicant.name + ')' : 'NO'}</li>
                            <li><strong>Has Evaluation:</strong> ${data.evaluation ? 'YES (Score: ' + data.evaluation.total_score + '/100)' : 'NO'}</li>
                            <li><strong>Evaluation Details Count:</strong> ${data.details ? data.details.length : 0} criteria</li>
                            <li><strong>Has Qualifications:</strong> ${data.qualifications ? 'YES' : 'NO'}</li>
                            <li><strong>Archive History Entries:</strong> ${data.history ? data.history.length : 0}</li>
                        </ul>
                    </div>
                `;
                
            } catch (error) {
                console.error('Test failed:', error);
                resultsDiv.innerHTML = `
                    <div class="results">
                        <h2><i class="fas fa-exclamation-circle" style="color: red;"></i> Error</h2>
                        <p class="error">${error.message}</p>
                        <div class="response-section">${error.stack}</div>
                    </div>
                `;
            }
            
            return false;
        }
    </script>
</body>
</html>
