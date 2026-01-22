<?php
/**
 * Sample Data Insertion for Testing
 * This script inserts sample evaluation data to test the CAR system
 */

require_once 'classes/DBConnection.php';
require_once 'classes/ComparativeAssessmentReport.php';

$db = new DBConnection();
$conn = $db->getConnection();

if (!$conn) {
    die("Database connection failed!");
}

echo "=== SAMPLE DATA INSERTION FOR CAR SYSTEM ===\n\n";

// 1. Insert sample positions (if not exist)
echo "1. Checking Positions...\n";
$result = $conn->query("SELECT COUNT(*) as count FROM positions");
$posCount = $result->fetch_assoc()['count'];

if ($posCount === 0) {
    echo "   Inserting sample positions...\n";
    $positions = [
        ['School Principal IV', 'A', 'SG 28', 'SP-001', 'School Principal - Contract of Service'],
        ['Assistant Principal II', 'B', 'SG 25', 'AP-001', 'Assistant Principal - Contract of Service'],
        ['Teacher III', 'C', 'SG 15', 'T-001', 'Teacher III - Contract of Service']
    ];
    
    foreach ($positions as $pos) {
        $stmt = $conn->prepare("INSERT INTO positions (position_name, position_group, salary_grade, item_number, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $pos[0], $pos[1], $pos[2], $pos[3], $pos[4]);
        $stmt->execute();
        $stmt->close();
        echo "   ✓ Inserted: {$pos[0]}\n";
    }
} else {
    echo "   Positions already exist: $posCount\n";
}

// 2. Get position IDs
$result = $conn->query("SELECT id, position_name FROM positions LIMIT 3");
$positions = [];
while ($row = $result->fetch_assoc()) {
    $positions[] = $row;
}

if (count($positions) === 0) {
    die("Error: No positions available\n");
}

// 3. Insert sample applicants
echo "\n2. Checking Applicants...\n";
$result = $conn->query("SELECT COUNT(*) as count FROM applicants");
$appCount = $result->fetch_assoc()['count'];

if ($appCount === 0) {
    echo "   Inserting sample applicants...\n";
    $applicantNames = [
        // For Position 1
        ['Juan Santos', 1, 'A'],
        ['Maria Garcia', 1, 'A'],
        ['Pedro Reyes', 1, 'A'],
        // For Position 2
        ['Alex Johnson', 2, 'B'],
        ['Rosa Martinez', 2, 'B'],
        ['Carlos Brown', 2, 'B'],
        // For Position 3
        ['Beth Adams', 3, 'C'],
        ['David Wilson', 3, 'C'],
        ['Emma Davis', 3, 'C']
    ];
    
    foreach ($applicantNames as $app) {
        $stmt = $conn->prepare("INSERT INTO applicants (name, position_applied_id, position_group) VALUES (?, ?, ?)");
        $stmt->bind_param('sis', $app[0], $app[1], $app[2]);
        $stmt->execute();
        $stmt->close();
        echo "   ✓ Inserted: {$app[0]}\n";
    }
} else {
    echo "   Applicants already exist: $appCount\n";
}

// 4. Get applicant IDs
$result = $conn->query("SELECT id, name FROM applicants LIMIT 9");
$applicants = [];
while ($row = $result->fetch_assoc()) {
    $applicants[] = $row;
}

// 5. Insert CAR results
echo "\n3. Checking CAR Results...\n";
$result = $conn->query("SELECT COUNT(*) as count FROM comparative_assessment_results");
$carCount = $result->fetch_assoc()['count'];

if ($carCount === 0) {
    echo "   Inserting sample CAR results...\n";
    
    $car = new ComparativeAssessmentReport();
    
    // Sample data for each applicant
    $sampleScores = [
        // Position 1 applicants
        ['CoS-001', 10.0, 8.0, 5.0, 15.98, 0.0, 0.0, 0.0, 0.0, 38.98],
        ['CoS-002', 0.0, 5.0, 8.0, 0.0, 0.0, 0.0, 0.0, 0.0, 13.0],
        ['CoS-003', 6.0, 4.0, 3.0, 8.25, 0.0, 0.0, 0.0, 0.0, 21.25],
        // Position 2 applicants
        ['CoS-004', 9.0, 7.0, 5.0, 14.0, 0.0, 0.0, 0.0, 0.0, 35.0],
        ['CoS-005', 7.0, 9.0, 5.0, 12.0, 0.0, 0.0, 0.0, 0.0, 33.0],
        ['CoS-006', 5.0, 3.0, 2.0, 6.0, 0.0, 0.0, 0.0, 0.0, 16.0],
        // Position 3 applicants
        ['CoS-007', 8.0, 5.0, 4.0, 10.0, 0.0, 0.0, 0.0, 0.0, 27.0],
        ['CoS-008', 6.0, 6.0, 3.0, 8.0, 0.0, 0.0, 0.0, 0.0, 23.0],
        ['CoS-009', 4.0, 2.0, 2.0, 4.0, 0.0, 0.0, 0.0, 0.0, 12.0]
    ];
    
    $positionIndex = 0;
    foreach ($applicants as $index => $app) {
        if ($index > 0 && $index % 3 === 0) {
            $positionIndex++;
        }
        
        $posId = $positions[$positionIndex]['id'];
        $scores = $sampleScores[$index];
        
        $scoreData = [
            'application_code' => $scores[0],
            'education' => $scores[1],
            'training' => $scores[2],
            'experience' => $scores[3],
            'performance' => $scores[4],
            'outstanding_accomplishments' => $scores[5],
            'application_of_education' => $scores[6],
            'application_of_ld' => $scores[7],
            'potential' => $scores[8],
            'total_score' => $scores[9]
        ];
        
        $success = $car->saveResult($posId, $app['id'], $scoreData);
        if ($success) {
            echo "   ✓ Inserted CAR result for {$app['name']} (Position: {$positions[$positionIndex]['position_name']})\n";
        } else {
            echo "   ✗ Failed to insert CAR result for {$app['name']}\n";
        }
    }
    
    // Generate rankings for all positions
    echo "\n   Generating rankings...\n";
    foreach ($positions as $pos) {
        $car->generateRankings($pos['id']);
        echo "   ✓ Rankings generated for: {$pos['position_name']}\n";
    }
} else {
    echo "   CAR Results already exist: $carCount\n";
}

// 6. Display Summary
echo "\n=== DATABASE SUMMARY ===\n";

$result = $conn->query("SELECT COUNT(*) as total FROM positions");
$posCount = $result->fetch_assoc()['total'];

$result = $conn->query("SELECT COUNT(*) as total FROM applicants");
$appCount = $result->fetch_assoc()['total'];

$result = $conn->query("SELECT COUNT(*) as total FROM comparative_assessment_results");
$carCount = $result->fetch_assoc()['total'];

echo "Positions: $posCount\n";
echo "Applicants: $appCount\n";
echo "CAR Results: $carCount\n";

if ($posCount > 0 && $appCount > 0 && $carCount > 0) {
    echo "\n✅ DATABASE IS READY FOR TESTING\n";
    echo "   Access the CAR page at: /DEPEDEvaluationSystem/comparative_assessment_results.php?view=all\n";
}

// 7. Display detailed results
echo "\n=== DETAILED CAR RESULTS ===\n";
$result = $conn->query("
    SELECT car.rank, a.name, p.position_name, car.application_code, 
           car.total_score, car.education_score, car.training_score
    FROM comparative_assessment_results car
    JOIN applicants a ON car.applicant_id = a.id
    JOIN positions p ON car.position_id = p.id
    ORDER BY p.position_name, car.rank
");

if ($result && $result->num_rows > 0) {
    echo "\n";
    while ($row = $result->fetch_assoc()) {
        echo "Rank {$row['rank']}: {$row['name']} (Position: {$row['position_name']})\n";
        echo "  Code: {$row['application_code']}, Total: {$row['total_score']}, Education: {$row['education_score']}, Training: {$row['training_score']}\n";
    }
}

$conn->close();

echo "\n=== DONE ===\n";
?>
