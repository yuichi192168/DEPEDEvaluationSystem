<?php
require_once 'initialize.php';

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
$conn->set_charset("utf8mb4");

$query = "SELECT id, application_code, total_score, experience_score, application_of_education_score FROM comparative_assessment_results ORDER BY id";
$result = $conn->query($query);

if (!$result) {
    die("Query error: " . $conn->error);
}

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║          Current Database Scores (comparative_assessment_results) ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

printf("%-4s | %-20s | %-8s | %-5s | %-5s\n", "ID", "Code", "Total", "Exp", "AoE");
echo str_repeat("-", 50) . "\n";

$count = 0;
while ($row = $result->fetch_assoc()) {
    echo sprintf(
        "%-4d | %-20s | %8.2f | %5.2f | %5.2f\n",
        $row['id'],
        $row['application_code'],
        $row['total_score'],
        $row['experience_score'],
        $row['application_of_education_score']
    );
    $count++;
    if ($count >= 20) break;
}

$conn->close();
?>
