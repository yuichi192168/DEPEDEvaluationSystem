<?php
require_once 'initialize.php';

$conn = DBConnection::getInstance();

echo "<h2>Applicants with Missing Position Data</h2>";

$query = "SELECT a.id, a.name, a.position_applied_id, p.position_name, p.position_group 
          FROM applicants a
          LEFT JOIN positions p ON a.position_applied_id = p.id
          WHERE p.id IS NULL OR p.position_name IS NULL OR p.position_group IS NULL
          ORDER BY a.id";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Applicant ID</th><th>Name</th><th>position_applied_id</th><th>Position Name</th><th>Position Group</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['position_applied_id']}</td>";
        echo "<td>" . ($row['position_name'] ?? 'NULL') . "</td>";
        echo "<td>" . ($row['position_group'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    echo "<br><strong>Total applicants with missing position data: {$result->num_rows}</strong>";
} else {
    echo "<p style='color: green;'>✓ All applicants have valid position data!</p>";
}

echo "<h3>Position IDs in Use by Applicants:</h3>";
$posQuery = "SELECT DISTINCT position_applied_id FROM applicants ORDER BY position_applied_id";
$posResult = $conn->query($posQuery);
$usedIds = [];
while ($row = $posResult->fetch_assoc()) {
    $usedIds[] = $row['position_applied_id'];
}
echo "<p>" . implode(', ', $usedIds) . "</p>";

echo "<h3>Valid Position IDs in Positions Table:</h3>";
$validQuery = "SELECT id, position_name, position_group FROM positions ORDER BY id LIMIT 50";
$validResult = $conn->query($validQuery);
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Position Name</th><th>Position Group</th></tr>";
while ($row = $validResult->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['position_name']}</td>";
    echo "<td>{$row['position_group']}</td>";
    echo "</tr>";
}
echo "</table>";
?>
