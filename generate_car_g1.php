<?php
/**
 * Generate Annex G-1 (Comparative Assessment Results) - Consolidated Master List
 * Pulls all evaluations for a specific position and generates consolidated ranking report
 */

require_once 'config/database.php';
require_once 'classes/DBConnection.php';
require_once 'classes/CARReportGeneratorG1.php';
require_once 'classes/HRMPSBEvaluator.php';

$conn = DBConnection::getConnection();

$message = '';
$error = '';

// Get all positions for selection
$positions = [];
try {
    $result = $conn->query("SELECT DISTINCT position_name, position_group, salary_grade, item_number, id FROM positions ORDER BY position_name");
    while ($row = $result->fetch_assoc()) {
        $positions[] = $row;
    }
} catch (Exception $e) {
    $error = 'Error retrieving positions: ' . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $positionId = $_POST['position_id'] ?? null;
    $generateFormat = $_POST['generate_format'] ?? 'html';
    
    if (!$positionId) {
        $error = 'Please select a position.';
    } else {
        // Get position details
        $posStmt = $conn->prepare("SELECT * FROM positions WHERE id = ?");
        $posStmt->bind_param("i", $positionId);
        $posStmt->execute();
        $posResult = $posStmt->get_result();
        $positionData = $posResult->fetch_assoc();
        
        if (!$positionData) {
            $error = 'Position not found.';
        } else {
            // Get all evaluations for this position with related data
            $evalStmt = $conn->prepare("
                SELECT 
                    e.id,
                    e.applicant_id,
                    e.total_score,
                    a.name as applicant_name,
                    aq.performance_rating,
                    aq.potential_level
                FROM evaluations e
                JOIN applicants a ON e.applicant_id = a.id
                LEFT JOIN applicant_qualifications aq ON a.id = aq.applicant_id
                WHERE e.position_id = ?
                ORDER BY e.total_score DESC
            ");
            $evalStmt->bind_param("i", $positionId);
            $evalStmt->execute();
            $evalResult = $evalStmt->get_result();
            
            $evaluations = [];
            while ($evalRow = $evalResult->fetch_assoc()) {
                // Get detailed evaluation scores for each criterion
                $detailStmt = $conn->prepare("
                    SELECT criterion, final_score
                    FROM evaluation_details
                    WHERE evaluation_id = ?
                ");
                $detailStmt->bind_param("i", $evalRow['id']);
                $detailStmt->execute();
                $detailResult = $detailStmt->get_result();
                
                $criteria = [];
                while ($detail = $detailResult->fetch_assoc()) {
                    $criterion = strtolower(str_replace(' ', '_', $detail['criterion']));
                    $criteria[$criterion] = [
                        'final_score' => floatval($detail['final_score'])
                    ];
                }
                
                // Add performance and potential from qualifications
                $criteria['performance'] = ['final_score' => floatval($evalRow['performance_rating'] ?? 0)];
                $criteria['potential'] = ['final_score' => floatval($evalRow['potential_level'] ?? 0)];
                
                $evaluations[] = [
                    'applicant_id' => $evalRow['applicant_id'],
                    'applicant_name' => $evalRow['applicant_name'],
                    'total_score' => floatval($evalRow['total_score']),
                    'criteria' => $criteria
                ];
            }
            
            if (empty($evaluations)) {
                $error = 'No evaluations found for this position.';
            } else {
                // Get HRMPSB members if stored
                $members = [];
                $membersStmt = $conn->prepare("
                    SELECT * FROM hrmpsb_members 
                    WHERE is_active = 1
                    ORDER BY member_order
                    LIMIT 4
                ");
                $membersStmt->execute();
                $membersResult = $membersStmt->get_result();
                while ($member = $membersResult->fetch_assoc()) {
                    $members[] = [
                        'name' => $member['name'],
                        'title' => $member['position']
                    ];
                }
                
                // Prepare additional data
                $additionalData = [
                    'position_name' => $positionData['position_name'],
                    'position_group' => $positionData['position_group'],
                    'salary_grade' => $positionData['salary_grade'],
                    'item_number' => $positionData['item_number'],
                    'schools_division_office' => $_POST['schools_division_office'] ?? 'City Schools Division of Cabuyao',
                    'hrmpsb_members' => $members,
                    'certificate_text' => $_POST['certificate_text'] ?? ''
                ];
                
                // Generate CAR G-1
                $generator = new CARReportGeneratorG1($positionData['position_group']);
                $carOutput = $generator->generateCAR($evaluations, $additionalData);
                
                // Handle output format
                if ($generateFormat === 'pdf') {
                    // For PDF generation, would need additional library like TCPDF
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment; filename="CAR_G1_' . date('Ymd_His') . '.pdf"');
                    // Would use TCPDF here to convert HTML to PDF
                    echo $carOutput;
                } elseif ($generateFormat === 'html') {
                    // Display HTML
                    header('Content-Type: text/html; charset=UTF-8');
                    echo $carOutput;
                    exit;
                }
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Annex G-1 - Comparative Assessment Results</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="manifest" href="images/site.webmanifest">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        
        .form-section h2 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
            font-size: 13px;
        }
        
        select,
        textarea,
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 13px;
            font-family: inherit;
        }
        
        select:focus,
        textarea:focus,
        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }
        
        textarea {
            min-height: 80px;
            resize: vertical;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-row.full {
            grid-template-columns: 1fr;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #d0d0d0;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        
        .alert-error {
            background: #ffebee;
            color: #c62828;
            border-left: 4px solid #c62828;
        }
        
        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #2e7d32;
        }
        
        .help-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Generate Annex G-1</h1>
        <p class="subtitle">Comparative Assessment Results - Consolidated Master List</p>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-section">
                <h2>Position Selection</h2>
                
                <div class="form-group">
                    <label for="position_id">Select Position *</label>
                    <select id="position_id" name="position_id" required>
                        <option value="">-- Select Position --</option>
                        <?php foreach ($positions as $pos): ?>
                            <option value="<?php echo htmlspecialchars($pos['id']); ?>" data-salary-grade="<?php echo htmlspecialchars($pos['salary_grade']); ?>" data-position-group="<?php echo htmlspecialchars($pos['position_group']); ?>">
                                <?php echo htmlspecialchars($pos['position_name']); ?> 
                                (SG <?php echo htmlspecialchars($pos['salary_grade']); ?>, Group <?php echo $pos['position_group']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="help-text">Select the position for which you want to generate the consolidated assessment results</span>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="selected_salary_grade">Salary Grade:</label>
                        <input type="text" id="selected_salary_grade" name="selected_salary_grade" readonly>
                    </div>
                    <div class="form-group">
                        <label for="selected_position_group">Position Group:</label>
                        <input type="text" id="selected_position_group" name="selected_position_group" readonly>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h2>Report Details</h2>
                
                <div class="form-group">
                    <label for="schools_division_office">Schools Division Office</label>
                    <input type="text" id="schools_division_office" name="schools_division_office" 
                           value="City Schools Division of Cabuyao">
                    <span class="help-text">Enter the Schools Division Office conducting the assessment</span>
                </div>
                
                <div class="form-group">
                    <label for="certificate_text">Certification Text (Optional)</label>
                    <textarea id="certificate_text" name="certificate_text" 
                              placeholder="Leave blank to use default certification text"></textarea>
                    <span class="help-text">Custom certification message to appear before signature lines</span>
                </div>
            </div>
            
            <div class="form-section">
                <h2>Output Format</h2>
                
                <div class="form-group">
                    <label for="generate_format">Export Format *</label>
                    <select id="generate_format" name="generate_format" required>
                        <option value="html">HTML (View in Browser)</option>
                        <option value="pdf">PDF Document</option>
                    </select>
                    <span class="help-text">Select the format for your Annex G-1 report</span>
                </div>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn-primary">Generate Annex G-1</button>
                <button type="reset" class="btn-secondary">Clear Form</button>
            </div>
        </form>
    </div>
    
    <script>
        // Auto-populate salary grade and position group when position is selected
        document.getElementById('position_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const salaryGrade = selectedOption.getAttribute('data-salary-grade');
            const positionGroup = selectedOption.getAttribute('data-position-group');
            
            if (salaryGrade && positionGroup) {
                document.getElementById('selected_salary_grade').value = 'SG ' + salaryGrade;
                document.getElementById('selected_position_group').value = 'Group ' + positionGroup;
            } else {
                document.getElementById('selected_salary_grade').value = '';
                document.getElementById('selected_position_group').value = '';
            }
        });
    </script>
</body>
</html>
