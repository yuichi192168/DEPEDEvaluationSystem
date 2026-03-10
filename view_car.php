<?php
/**
 * View Comparative Assessment Results (Annex G-1)
 * Displays consolidated ranking table for all applicants
 */

require_once 'classes/CARReportGenerator.php';
require_once 'classes/CARReportGeneratorG2.php';
require_once 'classes/EvaluationStorage.php';
require_once 'classes/DBConnection.php';
require_once 'config/baseline_library.php';

// Handle POST request to generate CAR
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $positionName = $_POST['position_name'] ?? '';
    $salaryGrade = $_POST['salary_grade'] ?? '';
    $itemNumber = $_POST['item_number'] ?? '';
    $schoolsDivisionOffice = $_POST['schools_division_office'] ?? '';
    $annexType = $_POST['annex_type'] ?? 'g1'; // 'g1' or 'g2'
    
    // Get HRMPSB members
    $hrmpsbMembers = [];
    if (isset($_POST['hrmpsb_members']) && is_array($_POST['hrmpsb_members'])) {
        foreach ($_POST['hrmpsb_members'] as $member) {
            if (!empty($member['name'])) {
                $hrmpsbMembers[] = [
                    'name' => $member['name'],
                    'title' => $member['title'] ?? 'HRMPSB Member'
                ];
            }
        }
    }
    
    // Get Secretariat (for Annex G-2)
    $secretariat = [];
    if (isset($_POST['secretariat']) && is_array($_POST['secretariat'])) {
        foreach ($_POST['secretariat'] as $member) {
            if (!empty($member['name'])) {
                $secretariat[] = [
                    'name' => $member['name'],
                    'title' => $member['title'] ?? 'Secretariat Member'
                ];
            }
        }
    }
    
    // Get Appointing Authority (for Annex G-2)
    $appointingAuthority = [];
    if (isset($_POST['appointing_authority']) && is_array($_POST['appointing_authority'])) {
        $auth = $_POST['appointing_authority'];
        if (!empty($auth['name'])) {
            $appointingAuthority = [
                'name' => $auth['name'],
                'title' => $auth['title'] ?? 'Schools Division Superintendent'
            ];
        }
    }
    
    // Get position group from position name
    $positionGroup = 'A';
    $positionId = null;
    if (!empty($positionName)) {
        $baseline = getBaselineForPosition('custom');
        foreach (getAllPositions() as $key => $pos) {
            if (stripos($pos['position_name'], $positionName) !== false || stripos($positionName, $pos['position_name']) !== false) {
                $positionGroup = $pos['position_group'];
                $positionId = $pos['id'];
                break;
            }
        }
    }
    
    // Get comparative assessment results from storage
    $storage = new EvaluationStorage();
    
    // First try to get from comparative_assessment_results table
    $evaluations = [];
    if ($positionId) {
        $evaluations = $storage->getComparativeAssessmentResults($positionId);
    }
    
    // Fallback to evaluation details if no CAR results found
    if (empty($evaluations)) {
        $evaluations = $storage->getEvaluationsByPosition($positionName);
    }
    
    if (empty($evaluations)) {
        die('No evaluations found for position: ' . htmlspecialchars($positionName) . '<br><br><a href="view_car.php">Go back</a>');
    }
    
    // Prepare additional data
    $additionalData = [
        'position_name' => $positionName,
        'position_group' => $positionGroup,
        'salary_grade' => $salaryGrade,
        'item_number' => $itemNumber,
        'schools_division_office' => $schoolsDivisionOffice,
        'hrmpsb_members' => $hrmpsbMembers,
        'secretariat' => $secretariat,
        'appointing_authority' => $appointingAuthority
    ];
    
    // Generate CAR report based on annex type
    $outputFormat = $_POST['output_format'] ?? 'html';
    
    if ($annexType === 'g2') {
        // Annex G-2 (Consolidated for Administrative Officers)
        $carGenerator = new CARReportGeneratorG2();
        if ($outputFormat === 'html') {
            header('Content-Type: text/html; charset=UTF-8');
            echo $carGenerator->generateCAR($evaluations, $additionalData);
        } else {
            header('Content-Type: text/plain; charset=UTF-8');
            echo $carGenerator->generateTextCAR($evaluations, $additionalData);
        }
    } else {
        // Annex G-1 (General)
        $carGenerator = new CARReportGenerator();
        if ($outputFormat === 'html') {
            header('Content-Type: text/html; charset=UTF-8');
            echo $carGenerator->generateCAR($evaluations, $additionalData);
        } else {
            header('Content-Type: text/plain; charset=UTF-8');
            echo $carGenerator->generateTextCAR($evaluations, $additionalData);
        }
    }
    
    exit;
}

// Get available positions with evaluations
$storage = new EvaluationStorage();
$db = new DBConnection();
$conn = $db->conn;

$positionsQuery = "
    SELECT DISTINCT p.position_name, p.position_group, p.salary_grade, p.item_number,
           COUNT(e.id) as evaluation_count
    FROM positions p
    LEFT JOIN evaluations e ON p.id = e.position_id
    LEFT JOIN applicants a ON e.applicant_id = a.id
    WHERE a.archive_status = 'active' OR a.id IS NULL
    GROUP BY p.id
    HAVING evaluation_count > 0
    ORDER BY p.position_name
";

$positionsResult = $conn->query($positionsQuery);
$positions = [];
while ($row = $positionsResult->fetch_assoc()) {
    $positions[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparative Assessment Results (CAR) - Annex G-1</title>
    <?php require_once(__DIR__ . '/includes/favicon.php'); ?>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #055489 0%, #E06060 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(224, 64, 64, 0.25);
            padding: 30px;
        }
        h1 {
            color: #333333;
            text-align: center;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .subtitle {
            text-align: center;
            color: #666666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #E0E0E0;
            border-radius: 8px;
            border-left: 4px solid #055489;
        }
        .form-section h2 {
            color: #055489;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333333;
            font-weight: 600;
            font-size: 14px;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            border: 2px solid #cbd5e1;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input:focus,
        select:focus {
            outline: none;
            border-color: #055489;
        }
        .hrmpsb-members {
            margin-top: 20px;
        }
        .member-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }
        .help-text {
            font-size: 12px;
            color: #666666;
            margin-top: 5px;
            font-style: italic;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 30px;
        }
        button {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #055489 0%, #E06060 100%);
            color: #ffffff;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(224, 64, 64, 0.4);
        }
        .positions-list {
            margin-top: 20px;
        }
        .position-item {
            padding: 10px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .position-item:hover {
            border-color: #055489;
            background: #F0F0F0;
        }
        .position-item strong {
            color: #055489;
        }
        .info-box {
            background: #cbd5e1;
            border-left: 4px solid #055489;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .info-box strong {
            color: #055489;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Comparative Assessment Results (CAR)</h1>
        <p class="subtitle">Annex G-1 - DepEd Order No. 007, s. 2023</p>
        
        <div class="info-box">
            <strong>Instructions:</strong> Select a position and annex type to generate the consolidated ranking table showing all evaluated applicants ranked by total score.
            <ul style="margin: 10px 0 0 20px; font-size: 12px;">
                <li><strong>Annex G-1:</strong> General Comparative Assessment Results (Portrait)</li>
                <li><strong>Annex G-2:</strong> Consolidated format for Administrative Officers and non-teaching roles (Landscape, Top 5 highlighting, tie-breaking alerts, additional signatories)</li>
            </ul>
        </div>
        
        <form method="POST" action="view_car.php" id="carForm">
            <div class="form-section">
                <h2>Report Type</h2>
                <div class="form-group">
                    <label for="annex_type">Annex Type *</label>
                    <select id="annex_type" name="annex_type" required onchange="toggleAnnexFields()">
                        <option value="g1">Annex G-1 (General CAR)</option>
                        <option value="g2">Annex G-2 (Consolidated - Administrative Officers)</option>
                    </select>
                    <span class="help-text">G-2 includes Top 5 highlighting, tie-breaking alerts, and additional signatories</span>
                </div>
            </div>
            
            <div class="form-section">
                <h2>Position Information</h2>
                <div class="form-group">
                    <label for="position_name">Position Name *</label>
                    <input type="text" id="position_name" name="position_name" required
                           placeholder="Information and Communications Technology">
                    <span class="help-text">Enter the position name to generate CAR for all evaluated applicants</span>
                </div>
                
                <?php if (!empty($positions)): ?>
                <div class="positions-list">
                    <strong>Available Positions with Evaluations:</strong>
                    <?php foreach ($positions as $pos): ?>
                    <div class="position-item" onclick="document.getElementById('position_name').value='<?php echo htmlspecialchars($pos['position_name']); ?>'; document.getElementById('salary_grade').value='<?php echo htmlspecialchars($pos['salary_grade'] ?? ''); ?>'; document.getElementById('item_number').value='<?php echo htmlspecialchars($pos['item_number'] ?? ''); ?>';">
                        <strong><?php echo htmlspecialchars($pos['position_name']); ?></strong> 
                        (<?php echo $pos['evaluation_count']; ?> evaluation(s))
                        <?php if (!empty($pos['salary_grade'])): ?>
                            - SG <?php echo htmlspecialchars($pos['salary_grade']); ?>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="salary_grade">Salary Grade</label>
                    <input type="text" id="salary_grade" name="salary_grade"
                           placeholder="SG 11">
                </div>
                <div class="form-group">
                    <label for="item_number">Item Number</label>
                    <input type="text" id="item_number" name="item_number"
                           placeholder="Item #1">
                </div>
                <div class="form-group">
                    <label for="schools_division_office">Schools Division Office</label>
                    <input type="text" id="schools_division_office" name="schools_division_office"
                           value="City Schools Division of Cabuyao">
                </div>
            </div>
            
            <div class="form-section">
                <h2>HRMPSB Members (for Certification)</h2>
                <div class="help-text">Add at least 3 board members for signature lines</div>
                <div class="hrmpsb-members" id="hrmpsbMembers">
                    <?php for ($i = 0; $i < 3; $i++): ?>
                    <div class="member-row">
                        <input type="text" name="hrmpsb_members[<?php echo $i; ?>][name]" 
                               placeholder="Member Name" <?php if ($i === 0) echo 'value="HRMPSB Chair"'; ?>>
                        <input type="text" name="hrmpsb_members[<?php echo $i; ?>][title]" 
                               placeholder="Title" <?php if ($i === 0) echo 'value="HRMPSB Chair"'; else echo 'value="HRMPSB Member"'; ?>>
                    </div>
                    <?php endfor; ?>
                </div>
                <button type="button" onclick="addMemberRow('hrmpsbMembers')" style="margin-top: 10px; padding: 8px 15px; font-size: 14px;">+ Add Member</button>
            </div>
            
            <div class="form-section" id="g2OnlyFields" style="display: none;">
                <h2>Additional Signatories (Annex G-2 Only)</h2>
                
                <div class="form-group">
                    <label>Secretariat Members</label>
                    <div class="help-text">Add Secretariat members for Annex G-2 certification</div>
                    <div class="hrmpsb-members" id="secretariatMembers">
                        <?php for ($i = 0; $i < 2; $i++): ?>
                        <div class="member-row">
                            <input type="text" name="secretariat[<?php echo $i; ?>][name]" 
                                   placeholder="Secretariat Member Name">
                            <input type="text" name="secretariat[<?php echo $i; ?>][title]" 
                                   placeholder="Title" value="Secretariat Member">
                        </div>
                        <?php endfor; ?>
                    </div>
                    <button type="button" onclick="addMemberRow('secretariatMembers')" style="margin-top: 10px; padding: 8px 15px; font-size: 14px;">+ Add Secretariat Member</button>
                </div>
                
                <div class="form-group">
                    <label>Appointing Authority / Superintendent</label>
                    <div class="help-text">Required for Annex G-2 final approval</div>
                    <div class="member-row">
                        <input type="text" name="appointing_authority[name]" 
                               placeholder="Superintendent Name" style="flex: 2;">
                        <input type="text" name="appointing_authority[title]" 
                               placeholder="Title" value="Schools Division Superintendent" style="flex: 1;">
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h2>Output Options</h2>
                <div class="form-group">
                    <label for="output_format">Output Format *</label>
                    <select id="output_format" name="output_format" required>
                        <option value="html">HTML (View in Browser)</option>
                        <option value="text">Plain Text (.txt)</option>
                    </select>
                </div>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn-primary">Generate Annex G-1 (CAR)</button>
                <button type="button" class="btn-secondary" onclick="window.location.href='index.php'">Back to Evaluation</button>
            </div>
        </form>
    </div>
    
    <script>
        let memberCount = 3;
        let secretariatCount = 2;
        
        function addMemberRow(containerId) {
            const container = document.getElementById(containerId);
            let count, namePrefix, titleValue;
            
            if (containerId === 'hrmpsbMembers') {
                count = memberCount++;
                namePrefix = 'hrmpsb_members';
                titleValue = 'HRMPSB Member';
            } else if (containerId === 'secretariatMembers') {
                count = secretariatCount++;
                namePrefix = 'secretariat';
                titleValue = 'Secretariat Member';
            }
            
            const row = document.createElement('div');
            row.className = 'member-row';
            row.innerHTML = `
                <input type="text" name="${namePrefix}[${count}][name]" placeholder="Member Name">
                <input type="text" name="${namePrefix}[${count}][title]" placeholder="Title" value="${titleValue}">
            `;
            container.appendChild(row);
        }
        
        function toggleAnnexFields() {
            const annexType = document.getElementById('annex_type').value;
            const g2Fields = document.getElementById('g2OnlyFields');
            
            if (annexType === 'g2') {
                g2Fields.style.display = 'block';
            } else {
                g2Fields.style.display = 'none';
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleAnnexFields();
        });
    </script>
</body>
</html>

