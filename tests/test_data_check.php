<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'classes/DBConnection.php';

$conn = DBConnection::getConnection();

echo "<h2>Database Status Check</h2>";

// Check tables
$tables = ['positions', 'applicants', 'evaluations', 'evaluation_details'];
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    $exists = $result && $result->num_rows > 0;
    echo "Table '$table': " . ($exists ? "✓ EXISTS" : "✗ MISSING") . "<br>";
}

// Check data counts
echo "<h3>Data Counts:</h3>";
$queries = [
    'Positions' => "SELECT COUNT(*) as count FROM positions",
    'Applicants' => "SELECT COUNT(*) as count FROM applicants",
    'Evaluations' => "SELECT COUNT(*) as count FROM evaluations",
    'Evaluation Details' => "SELECT COUNT(*) as count FROM evaluation_details"
];

foreach ($queries as $label => $query) {
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        echo "$label: " . $row['count'] . "<br>";
    } else {
        echo "$label: Query failed<br>";
    }
}

// Check positions with results
echo "<h3>Positions with Evaluation Results:</h3>";
$query = "
    SELECT p.id, p.position_name, COUNT(e.id) as result_count
    FROM positions p
    LEFT JOIN evaluations e ON p.id = e.position_id
    GROUP BY p.id
    HAVING result_count > 0
    ORDER BY p.position_name
";

$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Position Name</th><th>Results</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['position_name']}</td>";
        echo "<td>{$row['result_count']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No positions with evaluation results found.<br>";
    echo "Query used: " . htmlspecialchars($query) . "<br>";
}
?>