<?php
/**
 * Insert 4 Sample Applicants for Comparative Assessment Results Testing
 */

// Database configuration
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'deped_evaluation';

$inserted = 0;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
        
        if ($db->connect_error) {
            throw new Exception("Database connection failed: " . $db->connect_error);
        }
        
        // Get position IDs
        $posResult = $db->query("SELECT id FROM positions LIMIT 2");
        $positions = [];
        while ($row = $posResult->fetch_assoc()) {
            $positions[] = $row['id'];
        }
        
        if (count($positions) < 2) {
            throw new Exception("Need at least 2 positions in database");
        }
        
        // Clear existing sample data
        $db->query("DELETE FROM comparative_assessment_results WHERE application_code LIKE 'SAMPLE-%'");
        $db->query("DELETE FROM evaluations WHERE applicant_id IN (SELECT id FROM applicants WHERE name LIKE 'SAMPLE%')");
        $db->query("DELETE FROM applicants WHERE name LIKE 'SAMPLE%'");
        
        // Sample applicants data
        $samples = [
            [
                'name' => 'SAMPLE APPLICANT 1 - Maria Santos',
                'position_id' => $positions[0],
                'code' => 'SAMPLE-001',
                'education' => 10.0,
                'training' => 5.0,
                'experience' => 15.0,
                'performance' => 9.0,
                'app_education' => 8.0,
                'app_learning' => 7.0,
                'potential' => 9.0,
                'accomplishments' => 4.0
            ],
            [
                'name' => 'SAMPLE APPLICANT 2 - Juan Dela Cruz',
                'position_id' => $positions[0],
                'code' => 'SAMPLE-002',
                'education' => 9.0,
                'training' => 4.0,
                'experience' => 13.0,
                'performance' => 8.5,
                'app_education' => 7.5,
                'app_learning' => 6.5,
                'potential' => 8.0,
                'accomplishments' => 3.0
            ],
            [
                'name' => 'SAMPLE APPLICANT 3 - Ana Reyes',
                'position_id' => $positions[1],
                'code' => 'SAMPLE-003',
                'education' => 8.5,
                'training' => 3.5,
                'experience' => 12.0,
                'performance' => 8.0,
                'app_education' => 7.0,
                'app_learning' => 6.0,
                'potential' => 7.5,
                'accomplishments' => 2.5
            ],
            [
                'name' => 'SAMPLE APPLICANT 4 - Carlos Mendoza',
                'position_id' => $positions[1],
                'code' => 'SAMPLE-004',
                'education' => 7.5,
                'training' => 2.5,
                'experience' => 10.0,
                'performance' => 7.5,
                'app_education' => 6.5,
                'app_learning' => 5.5,
                'potential' => 7.0,
                'accomplishments' => 2.0
            ]
        ];
        
        foreach ($samples as $sample) {
            // Insert applicant
            $stmt = $db->prepare("INSERT INTO applicants (name, position_applied_id, position_group) VALUES (?, ?, 'A')");
            $stmt->bind_param("si", $sample['name'], $sample['position_id']);
            $stmt->execute();
            $applicant_id = $db->insert_id;
            
            // Calculate total score
            $total_score = ($sample['education'] * 0.15) + 
                          ($sample['training'] * 0.05) + 
                          ($sample['experience'] * 0.20) + 
                          ($sample['performance'] * 0.25) + 
                          ($sample['app_education'] * 0.10) + 
                          ($sample['app_learning'] * 0.10) + 
                          ($sample['potential'] * 0.10) + 
                          ($sample['accomplishments'] * 0.05);
            
            // Get position details
            $posStmt = $db->prepare("SELECT position_name, salary_grade, item_number FROM positions WHERE id = ?");
            $posStmt->bind_param("i", $sample['position_id']);
            $posStmt->execute();
            $posResult = $posStmt->get_result();
            $posRow = $posResult->fetch_assoc();
            
            // Insert into evaluations
            $evalStmt = $db->prepare(
                "INSERT INTO evaluations 
                 (applicant_id, position_id, position_group, education_score, training_score, experience_score, 
                  performance_score, application_of_education_score, application_of_learning_score, potential_score, 
                  outstanding_accomplishments_score, total_score) 
                 VALUES (?, ?, 'A', ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $evalStmt->bind_param(
                "iidddddddd",
                $applicant_id,
                $sample['position_id'],
                $sample['education'],
                $sample['training'],
                $sample['experience'],
                $sample['performance'],
                $sample['app_education'],
                $sample['app_learning'],
                $sample['potential'],
                $sample['accomplishments'],
                $total_score
            );
            $evalStmt->execute();
            
            // Insert into CAR
            $carStmt = $db->prepare(
                "INSERT INTO comparative_assessment_results 
                 (position_id, applicant_id, application_code, education_score, training_score, experience_score,
                  performance_score, application_of_education_score, application_of_learning_score, potential_score,
                  outstanding_accomplishments_score, total_score, background_yes, for_appointment, assessment_date)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 1, NOW())"
            );
            $carStmt->bind_param(
                "iisdddddddd",
                $sample['position_id'],
                $applicant_id,
                $sample['code'],
                $sample['education'],
                $sample['training'],
                $sample['experience'],
                $sample['performance'],
                $sample['app_education'],
                $sample['app_learning'],
                $sample['potential'],
                $sample['accomplishments'],
                $total_score
            );
            $carStmt->execute();
            
            $inserted++;
        }
        
        // Generate rankings for each position
        foreach ($positions as $pos_id) {
            $db->query(
                "UPDATE comparative_assessment_results car 
                 SET rank = (
                    SELECT COUNT(*) FROM comparative_assessment_results car2 
                    WHERE car2.position_id = car.position_id 
                    AND car2.total_score > car.total_score
                 ) + 1
                 WHERE position_id = $pos_id AND application_code LIKE 'SAMPLE-%'"
            );
        }
        
        $db->close();
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Insert Sample Data</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .success {
            padding: 20px; 
            background: #d4edda; 
            border: 1px solid #c3e6cb; 
            border-radius: 4px; 
            margin: 20px 0;
            color: #155724;
        }
        .error {
            padding: 20px; 
            background: #f8d7da; 
            border: 1px solid #f5c6cb; 
            border-radius: 4px; 
            margin: 20px 0;
            color: #721c24;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 5px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-success {
            background: #28a745;
        }
        .btn-success:hover {
            background: #218838;
        }
        .button-group {
            text-align: center;
            margin-top: 20px;
        }
        .info {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        li {
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Insert Sample Applicants</h1>
        
        <?php if ($error): ?>
            <div class="error">
                <h3>❌ Error</h3>
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>
        
        <?php if ($inserted > 0): ?>
            <div class="success">
                <h3>✅ Sample Data Inserted Successfully</h3>
                <p><strong><?php echo $inserted; ?> applicants</strong> have been inserted with sample evaluation data.</p>
                <ul>
                    <li>Applicants: SAMPLE APPLICANT 1 through 4</li>
                    <li>Application Codes: SAMPLE-001 through SAMPLE-004</li>
                    <li>Positions: Distributed across 2 positions</li>
                    <li>Rankings: Auto-calculated based on scores</li>
                    <li>Scores: Realistic HRMPSB evaluation data</li>
                </ul>
            </div>
        <?php else: ?>
            <div class="info">
                <h3>ℹ️ About Sample Data</h3>
                <p>This will insert 4 test applicants for testing the Comparative Assessment Results (CAR) display:</p>
                <ul>
                    <li><strong>Maria Santos</strong> - Position 1 - Score: 9.38</li>
                    <li><strong>Juan Dela Cruz</strong> - Position 1 - Score: 8.73</li>
                    <li><strong>Ana Reyes</strong> - Position 2 - Score: 8.08</li>
                    <li><strong>Carlos Mendoza</strong> - Position 2 - Score: 7.25</li>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="button-group">
            <?php if ($inserted === 0): ?>
                <form method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-success">Insert 4 Sample Applicants</button>
                </form>
            <?php endif; ?>
            <a href="comparative_assessment_results.php?view=all" class="btn">View CAR Results</a>
            <a href="index.php" class="btn">Back to Evaluation Form</a>
        </div>
    </div>
</body>
</html>
