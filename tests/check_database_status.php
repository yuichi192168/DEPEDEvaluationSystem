<?php
/**
 * Database Status Checker
 * Verifies all applicants and evaluation data are properly saved
 */

require_once 'classes/DBConnection.php';

$db = new DBConnection();
$conn = $db->getConnection();

if (!$conn) {
    die("Database connection failed!");
}

echo "=== DATABASE STATUS REPORT ===\n\n";

// 1. Check Applicants
echo "1. APPLICANTS COUNT:\n";
$result = $conn->query("SELECT COUNT(*) as total FROM applicants");
$row = $result->fetch_assoc();
echo "   Total Applicants: " . $row['total'] . "\n";

// Get detailed applicants
$result = $conn->query("
    SELECT a.id, a.name, p.position_name, a.position_group 
    FROM applicants a 
    LEFT JOIN positions p ON a.position_applied_id = p.id 
    ORDER BY a.created_at DESC LIMIT 20
");

if ($result && $result->num_rows > 0) {
    echo "   Recent Applicants (Last 20):\n";
    while ($row = $result->fetch_assoc()) {
        echo "   - ID: {$row['id']}, Name: {$row['name']}, Position: " . ($row['position_name'] ?? 'N/A') . ", Group: {$row['position_group']}\n";
    }
}

// 2. Check Evaluations
echo "\n2. EVALUATIONS COUNT:\n";
$result = $conn->query("SELECT COUNT(*) as total FROM evaluations");
$row = $result->fetch_assoc();
echo "   Total Evaluations: " . $row['total'] . "\n";

// Get detailed evaluations
$result = $conn->query("
    SELECT e.id, a.name, p.position_name, e.total_score, e.status, e.created_at
    FROM evaluations e
    JOIN applicants a ON e.applicant_id = a.id
    LEFT JOIN positions p ON e.position_id = p.id
    ORDER BY e.created_at DESC LIMIT 20
");

if ($result && $result->num_rows > 0) {
    echo "   Recent Evaluations (Last 20):\n";
    while ($row = $result->fetch_assoc()) {
        echo "   - Eval ID: {$row['id']}, Applicant: {$row['name']}, Position: " . ($row['position_name'] ?? 'N/A') . 
             ", Score: {$row['total_score']}, Status: {$row['status']}, Date: {$row['created_at']}\n";
    }
}

// 3. Check CAR Results
echo "\n3. COMPARATIVE ASSESSMENT RESULTS:\n";
$result = $conn->query("SELECT COUNT(*) as total FROM comparative_assessment_results");
$row = $result->fetch_assoc();
echo "   Total CAR Records: " . $row['total'] . "\n";

// Get detailed CAR results
$result = $conn->query("
    SELECT car.id, a.name, p.position_name, car.application_code, car.total_score, car.rank
    FROM comparative_assessment_results car
    JOIN applicants a ON car.applicant_id = a.id
    JOIN positions p ON car.position_id = p.id
    ORDER BY car.position_id, car.rank ASC LIMIT 30
");

if ($result && $result->num_rows > 0) {
    echo "   CAR Results (First 30 Records):\n";
    while ($row = $result->fetch_assoc()) {
        echo "   - Rank: {$row['rank']}, Name: {$row['name']}, Position: {$row['position_name']}, " .
             "Code: {$row['application_code']}, Score: {$row['total_score']}\n";
    }
}

// 4. Check Positions
echo "\n4. POSITIONS COUNT:\n";
$result = $conn->query("SELECT COUNT(*) as total FROM positions");
$row = $result->fetch_assoc();
echo "   Total Positions: " . $row['total'] . "\n";

// Get all positions
$result = $conn->query("
    SELECT p.id, p.position_name, p.salary_grade, p.position_group,
           COUNT(DISTINCT a.id) as applicant_count,
           COUNT(DISTINCT e.id) as evaluation_count,
           COUNT(DISTINCT car.id) as car_count
    FROM positions p
    LEFT JOIN applicants a ON p.id = a.position_applied_id
    LEFT JOIN evaluations e ON p.id = e.position_id
    LEFT JOIN comparative_assessment_results car ON p.id = car.position_id
    GROUP BY p.id, p.position_name, p.salary_grade, p.position_group
    ORDER BY p.id
");

if ($result && $result->num_rows > 0) {
    echo "   Positions with Related Data:\n";
    while ($row = $result->fetch_assoc()) {
        echo "   - Position: {$row['position_name']} (SG {$row['salary_grade']}), Group: {$row['position_group']}, " .
             "Applicants: {$row['applicant_count']}, Evaluations: {$row['evaluation_count']}, " .
             "CAR Results: {$row['car_count']}\n";
    }
}

// 5. Summary
echo "\n=== SUMMARY ===\n";

$result = $conn->query("SELECT COUNT(*) as total FROM applicants");
$applicants = $result->fetch_assoc()['total'];

$result = $conn->query("SELECT COUNT(*) as total FROM evaluations");
$evaluations = $result->fetch_assoc()['total'];

$result = $conn->query("SELECT COUNT(*) as total FROM comparative_assessment_results");
$car_results = $result->fetch_assoc()['total'];

$result = $conn->query("SELECT COUNT(*) as total FROM positions");
$positions = $result->fetch_assoc()['total'];

echo "   Total Positions: $positions\n";
echo "   Total Applicants: $applicants\n";
echo "   Total Evaluations: $evaluations\n";
echo "   Total CAR Results: $car_results\n";

// Data completeness check
if ($applicants > 0 && $evaluations > 0 && $car_results > 0) {
    echo "\n   ✅ DATABASE IS COMPLETE - All required data types present\n";
} else {
    echo "\n   ⚠️ DATABASE IS INCOMPLETE:\n";
    if ($applicants == 0) echo "      - No applicants found\n";
    if ($evaluations == 0) echo "      - No evaluations found\n";
    if ($car_results == 0) echo "      - No CAR results found\n";
}

$conn->close();
?>
