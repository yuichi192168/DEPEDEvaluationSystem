<?php
/**
 * Diagnostic Script: Check if evaluations exist in database
 * This helps determine if the issue is no data or display issue
 */

require_once(__DIR__ . '/classes/DBConnection.php');
require_once(__DIR__ . '/classes/AuthenticationHelper.php');

session_start();

try {
    $conn = DBConnection::getConnection();
    
    echo "<h1>Database Evaluation Status Check</h1>";
    
    // Check authentication
    $auth = new AuthenticationHelper($conn);
    if (!$auth->isAdmin()) {
        echo "<p style='color: red;'>ERROR: You must be logged in as admin to view this page.</p>";
        exit;
    }
    
    // Count total applicants
    $result = $conn->query("SELECT COUNT(*) as count FROM applicants WHERE archive_status = 'active'");
    $activeCount = $result->fetch_assoc()['count'];
    
    // Count total evaluations
    $result = $conn->query("SELECT COUNT(*) as count FROM evaluations");
    $evalCount = $result->fetch_assoc()['count'];
    
    // Count active applicants WITH evaluations
    $result = $conn->query("
        SELECT COUNT(DISTINCT a.id) as count
        FROM applicants a
        INNER JOIN evaluations e ON a.id = e.applicant_id
        WHERE a.archive_status = 'active'
    ");
    $activeWithEval = $result->fetch_assoc()['count'];
    
    echo "<div style='padding: 20px; background: #f8f9fa; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>Summary</h3>";
    echo "<ul style='font-size: 16px; line-height: 2;'>";
    echo "<li><strong>Active Applicants in DB:</strong> " . $activeCount . "</li>";
    echo "<li><strong>Total Evaluations in DB:</strong> " . $evalCount . "</li>";
    echo "<li><strong>Active Applicants WITH Evaluations:</strong> " . $activeWithEval . "</li>";
    echo "</ul>";
    echo "</div>";
    
    if ($activeWithEval === 0) {
        echo "<div style='padding: 20px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px; margin: 20px 0;'>";
        echo "<strong style='color: #856404;'>⚠️ WARNING: No Active Applicants Have Evaluations!</strong>";
        echo "<p style='color: #856404;'>This explains why the IES is not displaying. You need to create evaluations for applicants first.</p>";
        echo "</div>";
    }
    
    // Show sample of active applicants
    echo "<h3>Sample Active Applicants</h3>";
    echo "<table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>";
    echo "<thead><tr style='background: #f8f9fa;'>";
    echo "<th style='padding: 10px; text-align: left; border: 1px solid #ddd;'>ID</th>";
    echo "<th style='padding: 10px; text-align: left; border: 1px solid #ddd;'>Name</th>";
    echo "<th style='padding: 10px; text-align: left; border: 1px solid #ddd;'>Position Applied</th>";
    echo "<th style='padding: 10px; text-align: left; border: 1px solid #ddd;'>Has Evaluation</th>";
    echo "</tr></thead>";
    echo "<tbody>";
    
    $result = $conn->query("
        SELECT 
            a.id, 
            a.name, 
            p.position_name,
            (SELECT COUNT(*) FROM evaluations WHERE applicant_id = a.id) as eval_count
        FROM applicants a
        LEFT JOIN positions p ON a.position_applied_id = p.id
        WHERE a.archive_status = 'active'
        LIMIT 10
    ");
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . $row['id'] . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . ($row['position_name'] ?: '-') . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>";
        if ($row['eval_count'] > 0) {
            echo "<span style='color: green;'>✓ Yes (" . $row['eval_count'] . ")</span>";
        } else {
            echo "<span style='color: red;'>✗ No</span>";
        }
        echo "</td>";
        echo "</tr>";
    }
    
    echo "</tbody></table>";
    
    // Show sample evaluations that exist
    echo "<h3>Sample Evaluations (Last 10)</h3>";
    echo "<table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>";
    echo "<thead><tr style='background: #f8f9fa;'>";
    echo "<th style='padding: 10px; text-align: left; border: 1px solid #ddd;'>ID</th>";
    echo "<th style='padding: 10px; text-align: left; border: 1px solid #ddd;'>Applicant ID</th>";
    echo "<th style='padding: 10px; text-align: left; border: 1px solid #ddd;'>Total Score</th>";
    echo "<th style='padding: 10px; text-align: left; border: 1px solid #ddd;'>Status</th>";
    echo "<th style='padding: 10px; text-align: left; border: 1px solid #ddd;'>Created At</th>";
    echo "</tr></thead>";
    echo "<tbody>";
    
    $result = $conn->query("
        SELECT e.id, e.applicant_id, e.total_score, e.status, e.created_at
        FROM evaluations e
        ORDER BY e.created_at DESC
        LIMIT 10
    ");
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . $row['id'] . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . $row['applicant_id'] . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . $row['total_score'] . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . $row['status'] . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . $row['created_at'] . "</td>";
        echo "</tr>";
    }
    
    echo "</tbody></table>";
    
    echo "<p style='margin-top: 30px; padding: 15px; background: #e7f3ff; border-radius: 4px;'>";
    echo "<strong>ℹ️ How to test the IES display:</strong><br>";
    echo "1. Go to Admin Dashboard > Applicants (Active tab)<br>";
    echo "2. Click the 'View' button on an applicant that shows 'Yes' above<br>";
    echo "3. The Individual Evaluation Sheet should display in a modal<br>";
    echo "4. Open browser console (F12) and check for any error messages<br>";
    echo "</p>";
    
} catch (Exception $e) {
    echo "<div style='color: red; padding: 20px; background: #f8d7da; border-radius: 4px;'>";
    echo "<strong>ERROR:</strong> " . $e->getMessage();
    echo "</div>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Status Check</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }
        h1, h3 {
            color: #E04040;
        }
    </style>
</head>
</html>
