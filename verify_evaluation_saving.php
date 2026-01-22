<?php
/**
 * Verify Evaluation Saving Works
 * This script tests if evaluations are being saved to the database correctly
 */

require_once 'classes/DBConnection.php';

echo "<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Calibri, sans-serif; margin: 20px; }
        .section { background: #f5f5f5; padding: 15px; margin: 15px 0; border-left: 4px solid #E04040; border-radius: 4px; }
        .success { color: #4CAF50; font-weight: bold; }
        .error { color: #f44336; font-weight: bold; }
        .info { color: #0c5460; }
        h2 { color: #E04040; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #E04040; color: white; }
    </style>
</head>
<body>";

echo "<h1>🔍 Evaluation Saving Verification Report</h1>";
echo "<p>Date: " . date('F j, Y H:i:s') . "</p>";

try {
    $db = new DBConnection();
    $conn = $db->getConnection();
    
    echo "<div class='section'>";
    echo "<h2>1. Database Connection</h2>";
    if ($conn && $conn->ping()) {
        echo "<p class='success'>✓ Database connected successfully</p>";
    } else {
        echo "<p class='error'>✗ Failed to connect to database</p>";
    }
    echo "</div>";
    
    // Check Evaluations table
    echo "<div class='section'>";
    echo "<h2>2. Evaluations Table</h2>";
    $result = $conn->query("SELECT COUNT(*) as total FROM evaluations");
    $row = $result->fetch_assoc();
    $evalCount = $row['total'];
    echo "<p>Total Evaluations Saved: <strong>$evalCount</strong></p>";
    
    if ($evalCount > 0) {
        echo "<p class='success'>✓ Evaluations are being saved to the database</p>";
        
        echo "<h3>Recent Evaluations:</h3>";
        $result = $conn->query("
            SELECT e.id, a.name, p.position_name, e.total_score, e.status, e.created_at
            FROM evaluations e
            JOIN applicants a ON e.applicant_id = a.id
            LEFT JOIN positions p ON e.position_id = p.id
            ORDER BY e.created_at DESC LIMIT 10
        ");
        
        echo "<table>";
        echo "<tr><th>ID</th><th>Applicant</th><th>Position</th><th>Score</th><th>Status</th><th>Date Created</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['position_name'] ?? 'N/A') . "</td>";
            echo "<td>" . $row['total_score'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='error'>✗ No evaluations found. Data not being saved yet.</p>";
    }
    echo "</div>";
    
    // Check CAR Results table
    echo "<div class='section'>";
    echo "<h2>3. CAR (Comparative Assessment Results) Table</h2>";
    $result = $conn->query("SELECT COUNT(*) as total FROM comparative_assessment_results");
    $row = $result->fetch_assoc();
    $carCount = $row['total'];
    echo "<p>Total CAR Results Saved: <strong>$carCount</strong></p>";
    
    if ($carCount > 0) {
        echo "<p class='success'>✓ CAR results are being saved to the database</p>";
        
        echo "<h3>Recent CAR Results:</h3>";
        $result = $conn->query("
            SELECT car.id, a.name, p.position_name, car.total_score, car.rank
            FROM comparative_assessment_results car
            JOIN applicants a ON car.applicant_id = a.id
            JOIN positions p ON car.position_id = p.id
            ORDER BY car.id DESC LIMIT 10
        ");
        
        echo "<table>";
        echo "<tr><th>ID</th><th>Applicant</th><th>Position</th><th>Score</th><th>Rank</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['position_name']) . "</td>";
            echo "<td>" . $row['total_score'] . "</td>";
            echo "<td>" . ($row['rank'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='error'>✗ No CAR results found. CAR saving not working yet.</p>";
        echo "<p class='info'>💡 Tip: Submit an evaluation form to generate CAR results.</p>";
    }
    echo "</div>";
    
    // Check Applicants table
    echo "<div class='section'>";
    echo "<h2>4. Applicants Table</h2>";
    $result = $conn->query("SELECT COUNT(*) as total FROM applicants");
    $row = $result->fetch_assoc();
    $appCount = $row['total'];
    echo "<p>Total Applicants Saved: <strong>$appCount</strong></p>";
    
    if ($appCount > 0) {
        echo "<p class='success'>✓ Applicants are being saved to the database</p>";
    }
    echo "</div>";
    
    // Summary
    echo "<div class='section'>";
    echo "<h2>5. Summary</h2>";
    
    if ($evalCount > 0 && $carCount > 0 && $appCount > 0) {
        echo "<p class='success'>✓ ALL SYSTEMS WORKING CORRECTLY</p>";
        echo "<p>The evaluation system is properly saving data to:</p>";
        echo "<ul>";
        echo "<li>✓ Applicants database</li>";
        echo "<li>✓ Evaluations database</li>";
        echo "<li>✓ Comparative Assessment Results (CAR) database</li>";
        echo "</ul>";
    } else {
        echo "<p class='info'>⚠️ SYSTEM PARTIALLY WORKING</p>";
        echo "<p>Next Steps:</p>";
        echo "<ol>";
        echo "<li>Open <a href='index.php'>the evaluation form</a></li>";
        echo "<li>Fill in all required fields</li>";
        echo "<li>Click 'Generate Evaluation Report'</li>";
        echo "<li>The data will be automatically saved to the database</li>";
        echo "<li>Refresh this page to see the saved data</li>";
        echo "</ol>";
    }
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='section'>";
    echo "<p class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

echo "</body></html>";

$conn->close();
?>
