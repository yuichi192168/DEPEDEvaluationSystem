<?php
require_once 'initialize.php';

$conn = DBConnection::getInstance();

echo "<h2>Fixing Applicants with Missing Position Data</h2>";

// First, find applicants with NULL or invalid position_applied_id
$checkQuery = "SELECT a.id, a.name, a.position_applied, a.position_applied_id, a.position_group
               FROM applicants a
               LEFT JOIN positions p ON a.position_applied_id = p.id
               WHERE p.id IS NULL";

$result = $conn->query($checkQuery);

if ($result->num_rows > 0) {
    echo "<p>Found {$result->num_rows} applicants with invalid position references. Fixing...</p>";
    
    while ($row = $result->fetch_assoc()) {
        $applicantId = $row['id'];
        $positionName = $row['position_applied'];
        
        // Try to find matching position by name
        $findPosQuery = "SELECT id, position_group FROM positions WHERE position_name = ? LIMIT 1";
        $stmt = $conn->prepare($findPosQuery);
        $stmt->bind_param('s', $positionName);
        $stmt->execute();
        $posResult = $stmt->get_result();
        
        if ($posResult->num_rows > 0) {
            $pos = $posResult->fetch_assoc();
            
            // Update applicant with correct position_applied_id
            $updateQuery = "UPDATE applicants SET position_applied_id = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param('ii', $pos['id'], $applicantId);
            $updateStmt->execute();
            
            echo "✓ Fixed applicant #{$applicantId} ({$row['name']}) - linked to position '{$positionName}' (ID: {$pos['id']})<br>";
        } else {
            echo "⚠ Applicant #{$applicantId} ({$row['name']}) - position '{$positionName}' not found in positions table<br>";
        }
    }
    
    echo "<br><p style='color: green;'><strong>Fix completed!</strong></p>";
} else {
    echo "<p style='color: green;'>✓ All applicants already have valid position references!</p>";
}

echo "<br><a href='admin/dashboard.php'>← Back to Admin Dashboard</a>";
?>
