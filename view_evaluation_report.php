<?php
/**
 * View Evaluation Report
 * Displays the generated evaluation report
 */
session_start();

require_once 'initialize.php';
require_once 'includes/banners.php';

// Check if evaluation data exists
if (!isset($_SESSION['evaluation_data'])) {
    $_SESSION['banner'] = [
        'type' => 'error',
        'message' => 'No evaluation data found. Please submit the form first.'
    ];
    header('Location: index.php');
    exit;
}

$data = $_SESSION['evaluation_data'];
$scores = $data['scores'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Report - <?php echo htmlspecialchars($data['applicant_name']); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #E04040 0%, #E06060 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(224, 64, 64, 0.25);
            padding: 30px;
        }
        h1 { color: #E04040; text-align: center; margin-bottom: 10px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 30px; }
        .success-banner {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .info-section h3 { color: #E04040; margin-bottom: 15px; }
        .info-row { display: flex; margin-bottom: 10px; }
        .info-label { font-weight: 600; width: 200px; color: #333; }
        .info-value { color: #666; }
        .scores-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .scores-table th, .scores-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .scores-table th { background: #E04040; color: white; }
        .scores-table tr:nth-child(even) { background: #f8f9fa; }
        .scores-table .total-row { background: #E04040 !important; color: white; font-weight: bold; }
        .btn-group { display: flex; gap: 10px; justify-content: center; margin-top: 30px; }
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary { background: linear-gradient(135deg, #E04040 0%, #E06060 100%); color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
    </style>
</head>
<body>
    <div class="container">
        <h1>Individual Evaluation Sheet (IES)</h1>
        <p class="subtitle">DepEd HRMPSB Evaluation System</p>
        
        <div class="success-banner">
            ✓ Evaluation has been saved to the database successfully!
        </div>
        
        <div class="info-section">
            <h3>Applicant Information</h3>
            <div class="info-row">
                <span class="info-label">Applicant Name:</span>
                <span class="info-value"><?php echo htmlspecialchars($data['applicant_name']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Position Applied:</span>
                <span class="info-value"><?php echo htmlspecialchars($data['position_applied']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Application Code:</span>
                <span class="info-value"><?php echo htmlspecialchars($data['application_code'] ?: 'N/A'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Schools Division Office:</span>
                <span class="info-value"><?php echo htmlspecialchars($data['schools_division_office']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Job Group / Salary Grade:</span>
                <span class="info-value"><?php echo htmlspecialchars($data['job_group_sg_level']); ?></span>
            </div>
        </div>
        
        <div class="info-section">
            <h3>Evaluation Scores</h3>
            <table class="scores-table">
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Education</td>
                        <td><?php echo number_format($scores['education'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Training</td>
                        <td><?php echo number_format($scores['training'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Experience</td>
                        <td><?php echo number_format($scores['experience'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Performance</td>
                        <td><?php echo number_format($scores['performance'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Outstanding Accomplishments</td>
                        <td><?php echo number_format($scores['outstanding_accomplishments'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Application of Education</td>
                        <td><?php echo number_format($scores['application_of_education'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Application of L&D</td>
                        <td><?php echo number_format($scores['application_of_ld'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Potential</td>
                        <td><?php echo number_format($scores['potential'], 2); ?></td>
                    </tr>
                    <tr class="total-row">
                        <td>TOTAL SCORE</td>
                        <td><?php echo number_format($scores['total'], 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="btn-group">
            <a href="index.php" class="btn btn-primary">New Evaluation</a>
            <a href="comparative_assessment_results.php?view=all" class="btn btn-primary">View All Results</a>
            <a href="#" onclick="window.print(); return false;" class="btn btn-secondary">Print Report</a>
        </div>
    </div>
</body>
</html>