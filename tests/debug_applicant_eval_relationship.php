<?php
/**
 * Debug Script: Check Applicant-Evaluation Relationships
 * Helps identify why evaluations aren't matching with active applicants
 */

require_once(__DIR__ . '/classes/DBConnection.php');
require_once(__DIR__ . '/classes/AuthenticationHelper.php');

session_start();

try {
    $conn = DBConnection::getConnection();
    
    $auth = new AuthenticationHelper($conn);
    if (!$auth->isAdmin()) {
        http_response_code(403);
        die('Access denied');
    }
    
    echo "<h1>Applicant-Evaluation Relationship Debug</h1>";
    
    // Show all applicants with their status
    echo "<h2>All Applicants</h2>";
    echo "<table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>";
    echo "<thead><tr style='background: #f8f9fa;'>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>ID</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Name</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Archive Status</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Position Group</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Has Evaluation</th>";
    echo "</tr></thead><tbody>";
    
    $result = $conn->query("
        SELECT 
            a.id,
            a.name,
            a.archive_status,
            a.position_group,
            (SELECT COUNT(*) FROM evaluations WHERE applicant_id = a.id) as eval_count
        FROM applicants a
        ORDER BY a.archive_status, a.id
    ");
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . $row['id'] . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>";
        echo "<span style='padding: 4px 8px; border-radius: 4px; " . 
             ($row['archive_status'] === 'active' ? "background: #d4edda; color: #155724;" : "background: #f8d7da; color: #721c24;") . "'>";
        echo htmlspecialchars($row['archive_status']);
        echo "</span></td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>Group " . htmlspecialchars($row['position_group']) . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>";
        echo ($row['eval_count'] > 0 ? "<span style='color: green;'>✓ YES (" . $row['eval_count'] . ")</span>" : "<span style='color: red;'>✗ NO</span>");
        echo "</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
    
    // Show all evaluations
    echo "<h2>All Evaluations</h2>";
    echo "<table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>";
    echo "<thead><tr style='background: #f8f9fa;'>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Eval ID</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Applicant ID</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Applicant Name</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Applicant Status</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Score</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Created At</th>";
    echo "</tr></thead><tbody>";
    
    $result = $conn->query("
        SELECT 
            e.id,
            e.applicant_id,
            a.name,
            a.archive_status,
            e.total_score,
            e.created_at
        FROM evaluations e
        LEFT JOIN applicants a ON e.applicant_id = a.id
        ORDER BY e.id DESC
    ");
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . $row['id'] . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . $row['applicant_id'] . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($row['name'] ?? 'NULL') . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>";
        if ($row['archive_status']) {
            echo "<span style='padding: 4px 8px; border-radius: 4px; " . 
                 ($row['archive_status'] === 'active' ? "background: #d4edda; color: #155724;" : "background: #f8d7da; color: #721c24;") . "'>";
            echo htmlspecialchars($row['archive_status']);
            echo "</span>";
        } else {
            echo "<span style='color: red;'>⚠️ ORPHANED (No applicant)</span>";
        }
        echo "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . $row['total_score'] . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . $row['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
    
    // Summary
    echo "<h2>Summary</h2>";
    $totalApps = $conn->query("SELECT COUNT(*) as count FROM applicants")->fetch_assoc()['count'];
    $activeApps = $conn->query("SELECT COUNT(*) as count FROM applicants WHERE archive_status = 'active'")->fetch_assoc()['count'];
    $archivedApps = $conn->query("SELECT COUNT(*) as count FROM applicants WHERE archive_status = 'archived'")->fetch_assoc()['count'];
    $totalEvals = $conn->query("SELECT COUNT(*) as count FROM evaluations")->fetch_assoc()['count'];
    $activeWithEvals = $conn->query("
        SELECT COUNT(DISTINCT a.id) as count
        FROM applicants a
        INNER JOIN evaluations e ON a.id = e.applicant_id
        WHERE a.archive_status = 'active'
    ")->fetch_assoc()['count'];
    
    echo "<ul style='font-size: 16px; line-height: 2;'>";
    echo "<li><strong>Total Applicants:</strong> " . $totalApps . "</li>";
    echo "<li><strong>Active Applicants:</strong> " . $activeApps . "</li>";
    echo "<li><strong>Archived Applicants:</strong> " . $archivedApps . "</li>";
    echo "<li><strong>Total Evaluations:</strong> " . $totalEvals . "</li>";
    echo "<li><strong>Active Applicants with Evaluations:</strong> " . $activeWithEvals . "</li>";
    echo "</ul>";
    
    if ($totalEvals > 0 && $activeWithEvals === 0) {
        echo "<div style='padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px; margin-top: 20px;'>";
        echo "<strong>⚠️ ISSUE FOUND:</strong> Evaluations exist but none are linked to ACTIVE applicants.<br>";
        echo "The evaluations are either:<br>";
        echo "1. Linked to archived applicants<br>";
        echo "2. Linked to non-existent applicants (orphaned)<br>";
        echo "<br>Solution: Create evaluations for active applicants or restore archived applicants that have evaluations.";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red; padding: 20px; background: #f8d7da; border-radius: 4px;'>";
    echo "<strong>ERROR:</strong> " . $e->getMessage();
    echo "</div>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Applicant-Evaluation Debug</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }
        h1, h2 {
            color: #E04040;
        }
    </style>
</head>
</html>
