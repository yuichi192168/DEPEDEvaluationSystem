<?php
/**
 * Quick Diagnostic: Check IES availability by status
 */

require_once 'classes/DBConnection.php';

$conn = DBConnection::getConnection();
if (!$conn) die("Connection failed");

echo "<h2>IES Availability Diagnostic</h2>";

// Count active applicants with evaluations
$result = $conn->query("
    SELECT 
        COUNT(DISTINCT a.id) as active_with_eval,
        COUNT(DISTINCT CASE WHEN e.id IS NULL THEN a.id END) as active_no_eval
    FROM applicants a
    LEFT JOIN evaluations e ON a.id = e.applicant_id
    WHERE a.archive_status = 'active'
");
$activeStats = $result->fetch_assoc();
echo "<p><strong>Active Applicants:</strong></p>";
echo "<ul>";
echo "<li>With evaluations: " . $activeStats['active_with_eval'] . "</li>";
echo "<li>Without evaluations: " . $activeStats['active_no_eval'] . "</li>";
echo "</ul>";

// Count archived applicants with evaluations
$result = $conn->query("
    SELECT 
        COUNT(DISTINCT a.id) as archived_with_eval,
        COUNT(DISTINCT CASE WHEN e.id IS NULL THEN a.id END) as archived_no_eval
    FROM applicants a
    LEFT JOIN evaluations e ON a.id = e.applicant_id
    WHERE a.archive_status = 'archived'
");
$archivedStats = $result->fetch_assoc();
echo "<p><strong>Archived Applicants:</strong></p>";
echo "<ul>";
echo "<li>With evaluations: " . $archivedStats['archived_with_eval'] . "</li>";
echo "<li>Without evaluations: " . $archivedStats['archived_no_eval'] . "</li>";
echo "</ul>";

// Show sample active applicant with evaluation
echo "<p><strong>Sample Active Applicant (with evaluation):</strong></p>";
$result = $conn->query("
    SELECT a.id, a.name, e.id as eval_id, e.total_score
    FROM applicants a
    LEFT JOIN evaluations e ON a.id = e.applicant_id
    WHERE a.archive_status = 'active' AND e.id IS NOT NULL
    LIMIT 1
");
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "<pre>" . print_r($row, true) . "</pre>";
} else {
    echo "<p style='color: red;'>No active applicants with evaluations found!</p>";
}

// Show sample archived applicant with evaluation
echo "<p><strong>Sample Archived Applicant (with evaluation):</strong></p>";
$result = $conn->query("
    SELECT a.id, a.name, a.archive_status, e.id as eval_id, e.total_score
    FROM applicants a
    LEFT JOIN evaluations e ON a.id = e.applicant_id
    WHERE a.archive_status = 'archived' AND e.id IS NOT NULL
    LIMIT 1
");
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "<pre>" . print_r($row, true) . "</pre>";
} else {
    echo "<p style='color: red;'>No archived applicants with evaluations found!</p>";
}
?>
<style>
    body { font-family: Arial; padding: 20px; background: #f5f5f5; }
    h2 { color: #333; }
    p { margin: 10px 0; }
    pre { background: white; padding: 15px; border-radius: 4px; border-left: 4px solid #E04040; }
</style>
