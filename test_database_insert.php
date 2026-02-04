<?php
/**
 * Test Database Insert Script
 * Tests database connectivity and inserts sample data
 */

require_once 'initialize.php';

echo "<h2>Database Connection Test</h2>";

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
if ($conn->connect_error) {
    die("<p style='color:red;'>Connection failed: " . $conn->connect_error . "</p>");
}
echo "<p style='color:green;'>✓ Database connection successful.</p>";

$conn->set_charset("utf8mb4");

// Check if tables exist
$tables = ['positions', 'applicants', 'comparative_assessment_results'];
echo "<h3>Table Check:</h3>";
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "<p style='color:green;'>✓ Table '$table' exists.</p>";
    } else {
        echo "<p style='color:red;'>✗ Table '$table' does NOT exist.</p>";
    }
}

echo "<h2>Insert Test Data</h2>";

// Start transaction
$conn->begin_transaction();

try {
    // Insert test position
    $positionName = 'Test Position ' . date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO positions (position_name, position_group, description, created_at) VALUES (?, 'A', 'Test Description', NOW())");
    $stmt->bind_param("s", $positionName);
    $stmt->execute();
    $positionId = $conn->insert_id;
    echo "<p style='color:green;'>✓ Position inserted successfully. ID: $positionId</p>";
    $stmt->close();

    // Insert test applicant
    $applicantName = 'Test Applicant ' . date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO applicants (name, position_applied_id, position_group, created_at) VALUES (?, ?, 'A', NOW())");
    $stmt->bind_param("si", $applicantName, $positionId);
    $stmt->execute();
    $applicantId = $conn->insert_id;
    echo "<p style='color:green;'>✓ Applicant inserted successfully. ID: $applicantId</p>";
    $stmt->close();

    // Insert test comparative assessment result
    $applicationCode = 'TEST-' . rand(1000, 9999);
    $educationScore = 5.0;
    $trainingScore = 2.5;
    $experienceScore = 10.0;
    $performanceScore = 16.0;
    $outstandingAccomplishmentsScore = 8.0;
    $applicationOfEducationScore = 6.0;
    $applicationOfLdScore = 6.0;
    $potentialScore = 16.0;
    $totalScore = $educationScore + $trainingScore + $experienceScore + $performanceScore + 
                  $outstandingAccomplishmentsScore + $applicationOfEducationScore + 
                  $applicationOfLdScore + $potentialScore;

    $stmt = $conn->prepare("INSERT INTO comparative_assessment_results 
        (position_id, applicant_id, application_code, education_score, training_score, 
        experience_score, performance_score, outstanding_accomplishments_score, 
        application_of_education_score, application_of_ld_score, potential_score, 
        total_score, assessment_date, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), NOW())");
    
    $stmt->bind_param("iisddddddddd", 
        $positionId,
        $applicantId,
        $applicationCode,
        $educationScore,
        $trainingScore,
        $experienceScore,
        $performanceScore,
        $outstandingAccomplishmentsScore,
        $applicationOfEducationScore,
        $applicationOfLdScore,
        $potentialScore,
        $totalScore
    );
    $stmt->execute();
    $carId = $conn->insert_id;
    echo "<p style='color:green;'>✓ Comparative Assessment Result inserted successfully. ID: $carId</p>";
    $stmt->close();

    // Commit transaction
    $conn->commit();
    echo "<p style='color:green; font-weight:bold;'>✓ All test data inserted successfully!</p>";

} catch (Exception $e) {
    $conn->rollback();
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}

// Display all data from comparative_assessment_results
echo "<h2>Comparative Assessment Results</h2>";
$query = "SELECT 
    car.id,
    car.application_code,
    car.education_score,
    car.training_score,
    car.experience_score,
    car.performance_score,
    car.outstanding_accomplishments_score,
    car.application_of_education_score,
    car.application_of_ld_score,
    car.potential_score,
    car.total_score,
    car.assessment_date,
    a.name AS applicant_name,
    p.position_name
FROM comparative_assessment_results car
LEFT JOIN applicants a ON car.applicant_id = a.id
LEFT JOIN positions p ON car.position_id = p.id
ORDER BY car.created_at DESC
LIMIT 10";

$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #E04040; color: white;'>
            <th>ID</th>
            <th>Applicant</th>
            <th>Position</th>
            <th>App Code</th>
            <th>Edu</th>
            <th>Train</th>
            <th>Exp</th>
            <th>Perf</th>
            <th>OA</th>
            <th>AoE</th>
            <th>AoLD</th>
            <th>Pot</th>
            <th>Total</th>
            <th>Date</th>
          </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['applicant_name']}</td>
                <td>{$row['position_name']}</td>
                <td>{$row['application_code']}</td>
                <td>" . number_format($row['education_score'], 2) . "</td>
                <td>" . number_format($row['training_score'], 2) . "</td>
                <td>" . number_format($row['experience_score'], 2) . "</td>
                <td>" . number_format($row['performance_score'], 2) . "</td>
                <td>" . number_format($row['outstanding_accomplishments_score'], 2) . "</td>
                <td>" . number_format($row['application_of_education_score'], 2) . "</td>
                <td>" . number_format($row['application_of_ld_score'], 2) . "</td>
                <td>" . number_format($row['potential_score'], 2) . "</td>
                <td style='font-weight:bold; color:#E04040;'>" . number_format($row['total_score'], 2) . "</td>
                <td>{$row['assessment_date']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No data found in comparative_assessment_results table.</p>";
}

$conn->close();

echo "<br><br><a href='index.php' style='padding: 10px 20px; background: #E04040; color: white; text-decoration: none; border-radius: 5px;'>← Back to Evaluation Form</a>";
echo " <a href='comparative_assessment_results.php?view=all' style='padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px;'>View All Results</a>";
?>