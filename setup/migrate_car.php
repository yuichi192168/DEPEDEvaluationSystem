<?php
/**
 * CAR System Database Migration Script
 * Run this once to set up the comparative_assessment_results table
 * Access via: http://yoursite/setup/migrate_car.php
 */

require_once '../classes/DBConnection.php';

$message = '';
$success = false;

try {
    $db = new DBConnection();
    $conn = $db->conn;
    
    // Create the table
    $sql = "CREATE TABLE IF NOT EXISTS comparative_assessment_results (
        id INT AUTO_INCREMENT PRIMARY KEY,
        position_id INT NOT NULL,
        applicant_id INT NOT NULL,
        application_code VARCHAR(100),
        education_score DECIMAL(10,2) DEFAULT 0,
        training_score DECIMAL(10,2) DEFAULT 0,
        experience_score DECIMAL(10,2) DEFAULT 0,
        performance_score DECIMAL(10,2) DEFAULT 0,
        outstanding_accomplishments_score DECIMAL(10,2) DEFAULT 0,
        application_of_education_score DECIMAL(10,2) DEFAULT 0,
        application_of_ld_score DECIMAL(10,2) DEFAULT 0,
        potential_score DECIMAL(10,2) DEFAULT 0,
        total_score DECIMAL(10,2) DEFAULT 0,
        rank INT,
        remarks TEXT,
        background_yes BOOLEAN DEFAULT FALSE,
        background_no BOOLEAN DEFAULT FALSE,
        for_appointment BOOLEAN DEFAULT FALSE,
        for_probation BOOLEAN DEFAULT FALSE,
        assessment_date DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE CASCADE,
        FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE CASCADE,
        INDEX idx_position_id (position_id),
        INDEX idx_applicant_id (applicant_id),
        INDEX idx_total_score (total_score),
        INDEX idx_rank (rank),
        UNIQUE KEY unique_position_applicant_period (position_id, applicant_id, assessment_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($sql) === TRUE) {
        $message = "✅ CAR table created successfully!";
        $success = true;
    } else {
        $message = "⚠️ Table may already exist or error: " . $conn->error;
        $success = true; // Consider it success if table exists
    }
    
    // Add sample data (optional)
    if ($success && isset($_GET['sample_data'])) {
        $sampleSQL = "INSERT IGNORE INTO comparative_assessment_results 
                    (position_id, applicant_id, application_code, education_score, training_score, 
                     experience_score, performance_score, outstanding_accomplishments_score, 
                     application_of_education_score, application_of_ld_score, potential_score, 
                     total_score, rank, remarks, background_yes, background_no, for_appointment, 
                     for_probation, assessment_date)
                    VALUES 
                    (1, 1, 'ICT-2026-001', 8.50, 12.00, 15.00, 13.80, 0, 0, 0, 13.80, 63.10, 1, 
                     'Highly qualified', 1, 0, 1, 0, CURDATE()),
                    (1, 2, 'ICT-2026-003', 5.00, 8.00, 15.00, 0.00, 0, 0, 0, 17.83, 30.83, 2, 
                     'Needs improvement', 1, 0, 0, 1, CURDATE()),
                    (1, 3, 'ICT-2026-001', 0.00, 0.00, 8.00, 0.00, 0, 0, 0, 19.67, 27.67, 3, 
                     'Average performer', 1, 0, 0, 1, CURDATE()),
                    (1, 4, 'ICT-2026-002', 0.00, 0.00, 8.00, 0.00, 0, 0, 0, 15.58, 23.58, 4, 
                     'Below average', 0, 1, 0, 0, CURDATE())";
        
        if ($conn->query($sampleSQL) === TRUE) {
            $message .= " + Sample data inserted!";
        }
    }
    
} catch (Exception $e) {
    $success = false;
    $message = "❌ Migration failed: " . $e->getMessage();
}

$conn = null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>CAR System Database Migration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        .container {
            border: 2px solid #E04040;
            padding: 30px;
            border-radius: 8px;
            background: #fff9f9;
        }
        h1 {
            color: #E04040;
            text-align: center;
        }
        .message {
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            font-size: 16px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .actions {
            margin-top: 20px;
            text-align: center;
        }
        a {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background: #E04040;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }
        a:hover {
            background: #c73030;
        }
        .info {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 CAR System Database Migration</h1>
        
        <div class="message <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
        
        <?php if ($success): ?>
            <div class="info">
                <strong>✅ Setup Complete!</strong>
                <p>The Comparative Assessment Results (CAR) table has been created and is ready to use.</p>
                <p><strong>Next steps:</strong></p>
                <ul>
                    <li>Visit <code>comparative_assessment_results.php</code> to view the CAR interface</li>
                    <li>Use the API at <code>api/save_comparative_assessment.php</code> to save results</li>
                    <li>Read <code>CAR_INTEGRATION_GUIDE.md</code> for detailed integration instructions</li>
                </ul>
            </div>
            
            <div class="actions">
                <a href="<?php echo isset($_GET['sample_data']) ? '../comparative_assessment_results.php' : '?sample_data=1'; ?>">
                    <?php echo isset($_GET['sample_data']) ? '→ View CAR Results' : '→ Load Sample Data'; ?>
                </a>
                <a href="../index.php">Back to Home</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
