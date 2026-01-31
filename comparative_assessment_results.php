<?php
/**
 * Comparative Assessment Results (CAR) Display Page
 * Shows all applicants ranked by their assessment scores in official DepEd HRMPSB format
 */

session_start();

require_once 'includes/banners.php';
require_once 'classes/ComparativeAssessmentReport.php';
require_once 'config/evaluation_criteria.php';
require_once 'config/baseline_library.php';

$car = new ComparativeAssessmentReport();
$positionId = $_GET['position_id'] ?? null;
$viewMode = $_GET['view'] ?? 'position'; // 'position' or 'all'

/**
 * Map database score columns to position-specific criteria
 * Returns array of ['db_column' => 'score_column', 'criteria_name' => 'name', 'max_points' => points]
 */
function getCriteriaMappings($positionGroup, $salaryGrade = null, $category = null) {
    $dbToKey = [
        'education_score' => 'a',
        'training_score' => 'b',
        'experience_score' => 'c',
        'performance_score' => 'd',
        'outstanding_accomplishments_score' => 'e',
        'application_of_education_score' => 'f',
        'application_of_ld_score' => 'g',
        'potential_score' => 'h'
    ];
    
    $criteria = getEvaluationCriteria($positionGroup, $salaryGrade, $category);
    if (!$criteria || empty($criteria['criteria'])) {
        return [];
    }
    
    $mappings = [];
    foreach ($dbToKey as $dbCol => $key) {
        if (isset($criteria['criteria'][$key])) {
            $mappings[] = [
                'db_column' => $dbCol,
                'key' => $key,
                'criteria_name' => $criteria['criteria'][$key]['name'],
                'max_points' => $criteria['criteria'][$key]['max_points']
            ];
        }
    }
    
    return $mappings;
}

// Get positions with results
$positionsResult = $car->getPositionsWithResults();
$positions = [];
if ($positionsResult) {
    while ($row = $positionsResult->fetch_assoc()) {
        $positions[] = $row;
    }
}

// Get current position details and results
$results = [];
$positionDetails = null;
$groupedResults = []; // For 'all' view mode

// Determine what to display based on view mode
if ($viewMode === 'all') {
    // Display ALL applicants across ALL positions
    $allResult = $car->getAllResults();
    if ($allResult) {
        $currentPosition = null;
        while ($row = $allResult->fetch_assoc()) {
            $pos = $row['position_name'];
            if ($pos !== $currentPosition) {
                if (!isset($groupedResults[$pos])) {
                    $groupedResults[$pos] = [
                        'position_name' => $pos,
                        'applicants' => []
                    ];
                }
                $currentPosition = $pos;
            }
            $groupedResults[$pos]['applicants'][] = $row;
        }
    }
} else if ($positionId) {
    // Display specific position
    $car->generateRankings($positionId);
    
    $result = $car->getResultsByPosition($positionId);
    if ($result) {
        $firstRow = true;
        while ($row = $result->fetch_assoc()) {
            if ($firstRow) {
                $positionDetails = [
                    'id' => $row['id'] ?? $positionId,
                    'position_name' => $row['position_name'] ?? '',
                    'salary_grade' => $row['salary_grade'] ?? '',
                    'item_number' => $row['item_number'] ?? ''
                ];
                $firstRow = false;
            }
            $results[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparative Assessment Result (CAR) - DepEd HRMPSB</title>
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
            font-family: 'Calibri', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #E04040 0%, #E06060 100%);
            padding: 15px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 6px;
            box-shadow: 0 8px 32px rgba(224, 64, 64, 0.2);
            padding: 25px;
        }
        
        .navigation {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .nav-btn {
            padding: 10px 18px;
            background: #E04040;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .nav-btn:hover {
            background: #c73030;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(224, 64, 64, 0.3);
        }
        
        .nav-btn.secondary {
            background: #666;
        }
        
        .nav-btn.secondary:hover {
            background: #555;
        }
        
        /* Official DepEd CAR Template Styling */
        .car-title {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #333;
        }
        
        .car-title h1 {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            letter-spacing: 0.5px;
            margin: 5px 0;
        }
        
        .position-header {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
            margin-bottom: 15px;
            font-size: 12px;
            line-height: 1.6;
        }
        
        .position-left {
            border-right: 1px solid #999;
            padding-right: 20px;
        }
        
        .header-row {
            display: flex;
            margin-bottom: 4px;
        }
        
        .header-label {
            font-weight: bold;
            min-width: 140px;
            color: #000;
        }
        
        .header-value {
            flex: 1;
            color: #333;
        }
        
        .deliberation-date {
            font-size: 11px;
            text-align: right;
            padding-right: 0;
        }
        
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 12px;
        }
        
        .results-table thead {
            background: #e8e8e8;
            border: 1px solid #333;
        }
        
        .results-table th {
            border: 1px solid #333;
            padding: 8px 4px;
            text-align: center;
            font-weight: bold;
            color: #000;
            font-size: 11px;
            word-break: break-word;
        }
        
        .results-table td {
            border: 1px solid #999;
            padding: 6px 4px;
            text-align: center;
            height: 24px;
        }
        
        .results-table .name-column {
            text-align: left;
            padding-left: 8px;
        }
        
        .results-table .code-column {
            text-align: center;
            font-family: 'Courier New', monospace;
            font-size: 11px;
        }
        
        .results-table .score-column {
            text-align: right;
            padding-right: 6px;
        }
        
        .results-table .total-column {
            background: #f0f0f0;
            font-weight: bold;
            text-align: right;
            padding-right: 6px;
        }
        
        .results-table tbody tr:nth-child(even) {
            background: #fafafa;
        }
        
        .results-table tbody tr:hover {
            background: #f5f5f5;
        }
        
        /* Signature section */
        .signatures {
            margin-top: 35px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        
        .sig-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-top: 40px;
            font-size: 11px;
        }
        
        .sig-line {
            text-align: center;
            border-top: 1px solid #333;
            padding-top: 8px;
            min-height: 50px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }
        
        .sig-label {
            font-weight: bold;
            text-align: center;
            margin-top: 5px;
            font-size: 10px;
            line-height: 1.4;
        }
        
        .prepared-by {
            text-align: left;
            margin-top: 25px;
            font-size: 11px;
            font-weight: bold;
        }
        
        /* Selector section */
        .selector-section {
            background: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .selector-row {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .selector-row label {
            font-weight: 600;
            color: #333;
            font-size: 13px;
        }
        
        .selector-row select {
            padding: 8px 10px;
            border: 1px solid #999;
            border-radius: 3px;
            font-size: 13px;
            font-family: 'Calibri', 'Segoe UI', sans-serif;
            cursor: pointer;
            flex: 0 1 auto;
            min-width: 350px;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .btn-action {
            padding: 8px 16px;
            background: #E04040;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: background 0.3s;
        }
        
        .btn-action:hover {
            background: #c73030;
        }
        
        .btn-action.print {
            background: #4CAF50;
        }
        
        .btn-action.print:hover {
            background: #45a049;
        }
        
        .btn-action.export {
            background: #2196F3;
        }
        
        .btn-action.export:hover {
            background: #0b7dda;
        }
        
        .empty-message {
            padding: 40px;
            text-align: center;
            color: #999;
            font-size: 14px;
        }
        
        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm;
            }
            
            body {
                background: white;
                padding: 0;
            }
            
            .container {
                box-shadow: none;
                padding: 0;
                margin: 0;
                max-width: 100%;
                border-radius: 0;
            }
            
            .navigation,
            .selector-section,
            .action-buttons {
                display: none;
            }
            
            .results-table {
                page-break-inside: avoid;
            }
            
            .car-title {
                page-break-after: avoid;
            }
            
            .signatures {
                page-break-before: avoid;
            }
        }
    </style>
    <link rel="stylesheet" href="css/banners.css">
</head>
<body>
    <div class="container">
        <?php displayBannerFromSession(); ?>
        <!-- Navigation -->
        <div class="navigation">
            <a href="index.php" class="nav-btn secondary">Back to Evaluation Form</a>
            <a href="comparative_assessment_results.php?view=all" class="nav-btn" 
               style="<?php echo ($viewMode === 'all') ? 'background: #333; font-weight: bold;' : ''; ?>">
               View All Applicants
            </a>
            <a href="comparative_assessment_results.php" class="nav-btn" 
               style="<?php echo ($viewMode === 'position') ? 'background: #333; font-weight: bold;' : ''; ?>">
               View by Position
            </a>
        </div>
        
        <!-- Title Section -->
        <div class="car-title">
            <h1>COMPARATIVE ASSESSMENT RESULT</h1>
            <p style="text-align: center; font-size: 11px; margin-top: 5px;">Annex I</p>
        </div>
        
        <?php if ($viewMode === 'all' && count($groupedResults) > 0): ?>
            <!-- ALL APPLICANTS VIEW -->
            <div style="margin-bottom: 20px;">
                <div class="action-buttons">
                    <button class="btn-action" onclick="refreshResults()">Refresh</button>
                    <button class="btn-action print" onclick="window.print()">Print</button>
                    <button class="btn-action export" onclick="exportToCSV()">Export CSV</button>
                </div>
            </div>
            
            <?php foreach ($groupedResults as $posName => $posData): ?>
                <div style="page-break-inside: avoid; margin-bottom: 30px;">
                    <h2 style="font-size: 14px; color: #E04040; border-bottom: 2px solid #E04040; padding-bottom: 8px; margin-bottom: 15px;">
                        Position: <?php echo htmlspecialchars($posName); ?>
                    </h2>
                    
                    <?php
                    // Get position group and salary grade for first applicant to determine criteria
                    $posGroup = null;
                    $salGrade = null;
                    $posCategory = null;
                    
                    if (!empty($posData['applicants'])) {
                        $firstApplicant = $posData['applicants'][0];
                        
                        // Try to get position group from applicant data
                        if (isset($firstApplicant['position_group']) && !empty($firstApplicant['position_group'])) {
                            $posGroup = $firstApplicant['position_group'];
                        }
                        
                        // If not available, try to infer from position name
                        if (!$posGroup && isset($firstApplicant['position_name'])) {
                            $posName = strtoupper($firstApplicant['position_name']);
                            if (strpos($posName, 'TEACHER') !== false) {
                                $posGroup = 'TEACHING POSITIONS';
                            } elseif (strpos($posName, 'PRINCIPAL') !== false || strpos($posName, 'DIRECTOR') !== false || strpos($posName, 'ADMIN') !== false || strpos($posName, 'SUPERVISOR') !== false) {
                                $posGroup = 'SCHOOL ADMINISTRATION POSITION'; // Use config key, not 'ADMINISTRATIVE POSITIONS'
                            } else {
                                $posGroup = 'NON-TEACHING LEVEL I';
                            }
                        }
                        
                        // Get salary grade
                        if (isset($firstApplicant['salary_grade'])) {
                            $salGrade = intval($firstApplicant['salary_grade']);
                        }
                        if (isset($firstApplicant['category'])) {
                            $posCategory = $firstApplicant['category'];
                        }
                    }
                    
                    // Fallback to default if still not set
                    if (!$posGroup) {
                        $posGroup = 'NON-TEACHING LEVEL I';
                    }
                    
                    $criteriaMap = getCriteriaMappings($posGroup, $salGrade, $posCategory);
                    
                    // If no criteria found, log for debugging
                    if (empty($criteriaMap)) {
                        error_log("WARNING: No criteria found for posGroup=$posGroup, salGrade=$salGrade, posCategory=$posCategory");
                    }
                    ?>
                    
                    <table class="results-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">Rank</th>
                                <th style="width: 20%;">NAME</th>
                                <th style="width: 10%;">APPLICATION CODE</th>
                                <?php foreach ($criteriaMap as $mapping): ?>
                                    <th style="width: 7%;"><?php echo htmlspecialchars($mapping['criteria_name']); ?></th>
                                <?php endforeach; ?>
                                <th style="width: 8%;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posData['applicants'] as $index => $row): ?>
                                <tr>
                                    <td><?php echo $row['rank'] ?? ($index + 1); ?></td>
                                    <td class="name-column"><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td class="code-column"><?php echo htmlspecialchars($row['application_code'] ?? ''); ?></td>
                                    <?php foreach ($criteriaMap as $mapping): ?>
                                        <td class="score-column"><?php echo number_format($row[$mapping['db_column']] ?? 0, 2); ?></td>
                                    <?php endforeach; ?>
                                    <td class="total-column"><?php echo number_format($row['total_score'] ?? 0, 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
            
        <?php elseif ($viewMode === 'position' && $positionId && $positionDetails): ?>
            <!-- Position Header -->
            <div class="position-header">
                <div class="position-left">
                    <div class="header-row">
                        <span class="header-label">Position:</span>
                        <span class="header-value"><?php echo htmlspecialchars($positionDetails['position_name']); ?> under Contract of Service</span>
                    </div>
                    <div class="header-row">
                        <span class="header-label">Office/Bureau/Service/Unit where the vacancy exists:</span>
                        <span class="header-value"><?php echo htmlspecialchars($positionDetails['position_name']); ?> Unit</span>
                    </div>
                </div>
                <div class="deliberation-date">
                    <div class="header-row">
                        <span class="header-label">Plantilla Item Number:</span>
                        <span class="header-value"><?php echo htmlspecialchars($positionDetails['item_number'] ?? 'n/a'); ?></span>
                    </div>
                    <div class="header-row">
                        <span class="header-label">Date of Final Deliberation:</span>
                        <span class="header-value"><?php echo date('F j, Y'); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Selector Section -->
            <div class="selector-section">
                <div class="selector-row">
                    <label for="positionSelect">Select Position to View:</label>
                    <select id="positionSelect" onchange="changePosition()">
                        <option value="">-- Select a Position --</option>
                        <?php foreach ($positions as $pos): ?>
                            <option value="<?php echo htmlspecialchars($pos['id']); ?>" 
                                    <?php echo ($positionId == $pos['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($pos['position_name']); ?> 
                                (<?php echo $pos['result_count']; ?> applicant<?php echo $pos['result_count'] != 1 ? 's' : ''; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="action-buttons">
                    <button class="btn-action" onclick="refreshResults()">Refresh</button>
                    <button class="btn-action print" onclick="window.print()">Print</button>
                    <button class="btn-action export" onclick="exportToCSV()">Export CSV</button>
                </div>
            </div>
            
            <!-- Evaluation Criteria Reference -->
            <div style="margin: 20px 0; padding: 15px; background: #f5f5f5; border-left: 4px solid #E04040; border-radius: 4px;">
                <h3 style="color: #E04040; margin-bottom: 12px; font-size: 13px;">Evaluation Criteria and Maximum Points</h3>
                <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="background: #eeeeee;">
                            <th style="padding: 8px; text-align: left; border: 1px solid #ccc;">Criteria</th>
                            <th style="padding: 8px; text-align: center; border: 1px solid #ccc; width: 100px;">Max Points</th>
                            <th style="padding: 8px; text-align: left; border: 1px solid #ccc;">Scoring Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Load evaluation criteria to get position-specific names
                        require_once 'config/evaluation_criteria.php';
                        
                        // Map generic criterion keys to scoring methods
                        $scoringMethods = [
                            'a' => 'Increment scoring based on levels',
                            'b' => 'Increment scoring based on levels',
                            'c' => 'Increment scoring based on levels',
                            'd' => 'Rating / 5 × Max Points',
                            'e' => 'Direct points (capped at max)',
                            'f' => 'Rating / 5 × Max Points',
                            'g' => 'Rating / 5 × Max Points',
                            'h' => 'Rating / 5 × Max Points'
                        ];
                        
                        // Determine position group from position details
                        $positionGroup = 'NON-TEACHING LEVEL I'; // Default
                        $salaryGrade = null;
                        $category = null;
                        
                        if ($positionDetails && isset($positionDetails['position_name'])) {
                            // Try to find position in baseline library to get group and salary grade
                            require_once 'config/baseline_library.php';
                            $positions_lib = getAllPositions();
                            foreach ($positions_lib as $pos) {
                                if ($pos['position_name'] === $positionDetails['position_name']) {
                                    $positionGroup = $pos['position_group'] ?? 'NON-TEACHING LEVEL I';
                                    $salaryGrade = $pos['salary_grade'] ?? null;
                                    break;
                                }
                            }
                        }
                        
                        // Get position-specific criteria - AUTHORITATIVE SOURCE
                        $positionCriteria = getEvaluationCriteria($positionGroup, $salaryGrade, $category);
                        
                        // Verify criteria were loaded - no fallback allowed
                        if (!$positionCriteria || empty($positionCriteria['criteria'])) {
                            echo '<tr><td colspan="3" style="padding: 20px; text-align: center; color: red; font-weight: bold;">ERROR: Evaluation criteria not available for position group: ' . htmlspecialchars($positionGroup) . '</td></tr>';
                        } else {
                            $totalMaxPoints = $positionCriteria['total_points'] ?? 100;
                        
                            // Display criteria with position-specific names and max points
                            foreach ($positionCriteria['criteria'] as $key => $criterionDef) {
                                $maxPoints = $criterionDef['max_points'] ?? 0;
                                $criteriaName = $criterionDef['name'] ?? "Criteria $key";
                                $scoringMethod = $scoringMethods[$key] ?? "See detailed rules";
                                echo '<tr>';
                                echo '<td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($criteriaName) . '</td>';
                                echo '<td style="padding: 8px; border: 1px solid #ddd; text-align: center; font-weight: bold;">' . $maxPoints . '</td>';
                                echo '<td style="padding: 8px; border: 1px solid #ddd; font-size: 11px; color: #555;">' . htmlspecialchars($scoringMethod) . '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr style="background: #eeeeee; font-weight: bold;">
                            <td style="padding: 8px; border: 1px solid #ccc;">TOTAL MAXIMUM POINTS</td>
                            <td style="padding: 8px; border: 1px solid #ccc; text-align: center;"><?php echo $totalMaxPoints; ?></td>
                            <td style="padding: 8px; border: 1px solid #ccc;"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <?php if (count($results) > 0): ?>
                <?php 
                    // Show success/info banner when CAR results are loaded
                    $resultCount = count($results);
                    $positionName = $positionDetails['position_name'] ?? 'Unknown Position';
                    showInfoBanner("Showing " . $resultCount . " applicant" . ($resultCount !== 1 ? "s" : "") . " for <strong>$positionName</strong>");
                ?>
                <!-- Comparative Assessment Result Table -->
                <?php
                // Determine position group and salary grade from position details or results data
                $positionGroup = null;
                $salaryGrade = null;
                $positionCategory = null;
                
                // Try to get from first result row which should have this data
                if (!empty($results)) {
                    $firstResult = $results[0];
                    if (isset($firstResult['position_group']) && !empty($firstResult['position_group'])) {
                        $positionGroup = $firstResult['position_group'];
                    }
                    if (isset($firstResult['salary_grade'])) {
                        $salaryGrade = intval($firstResult['salary_grade']);
                    }
                    if (isset($firstResult['category'])) {
                        $positionCategory = $firstResult['category'];
                    }
                }
                
                // If not found in results, try position library
                if (!$positionGroup && $positionDetails && isset($positionDetails['position_name'])) {
                    $positions_lib = getAllPositions();
                    foreach ($positions_lib as $pos) {
                        if ($pos['position_name'] === $positionDetails['position_name']) {
                            $positionGroup = $pos['position_group'] ?? null;
                            $salaryGrade = $pos['salary_grade'] ?? null;
                            if (isset($pos['category'])) {
                                $positionCategory = $pos['category'];
                            }
                            break;
                        }
                    }
                }
                
                // If still not found, infer from position name
                if (!$positionGroup && $positionDetails && isset($positionDetails['position_name'])) {
                    $posName = strtoupper($positionDetails['position_name']);
                    if (strpos($posName, 'TEACHER') !== false) {
                        $positionGroup = 'TEACHING POSITIONS';
                    } elseif (strpos($posName, 'PRINCIPAL') !== false || strpos($posName, 'DIRECTOR') !== false || strpos($posName, 'ADMIN') !== false || strpos($posName, 'SUPERVISOR') !== false) {
                        $positionGroup = 'SCHOOL ADMINISTRATION POSITION'; // Use config key, not 'ADMINISTRATIVE POSITIONS'
                    }
                }
                
                // Fallback to default
                if (!$positionGroup) {
                    $positionGroup = 'NON-TEACHING LEVEL I';
                }
                
                $criteriaHeaderMap = getCriteriaMappings($positionGroup, $salaryGrade, $positionCategory);
                
                // If no criteria found, log for debugging
                if (empty($criteriaHeaderMap)) {
                    error_log("WARNING: No criteria found for positionGroup=$positionGroup, salaryGrade=$salaryGrade, positionCategory=$positionCategory");
                }
                ?>
                <table class="results-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">Rank</th>
                            <th style="width: 20%;">NAME</th>
                            <th style="width: 10%;">APPLICATION CODE</th>
                            <?php foreach ($criteriaHeaderMap as $mapping): ?>
                                <th style="width: 7%;"><?php echo htmlspecialchars($mapping['criteria_name']); ?></th>
                            <?php endforeach; ?>
                            <th style="width: 8%;">Total</th>
                            <th style="width: 6%;">Remarks</th>
                            <th style="width: 6%;">For Background Yes</th>
                            <th style="width: 6%;">For Background No</th>
                            <th style="width: 8%;">For Appointment</th>
                            <th style="width: 8%;">For Probation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $index => $row): ?>
                            <tr>
                                <td><?php echo $row['rank'] ?? ($index + 1); ?></td>
                                <td class="name-column"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td class="code-column"><?php echo htmlspecialchars($row['application_code'] ?? ''); ?></td>
                                <?php foreach ($criteriaHeaderMap as $mapping): ?>
                                    <td class="score-column"><?php echo number_format($row[$mapping['db_column']] ?? 0, 2); ?></td>
                                <?php endforeach; ?>
                                <td class="total-column"><?php echo number_format($row['total_score'] ?? 0, 2); ?></td>
                                <td style="text-align: center;"><?php echo htmlspecialchars($row['remarks'] ?? ''); ?></td>
                                <td style="text-align: center;"><?php echo ($row['background_yes'] ? '✓' : ''); ?></td>
                                <td style="text-align: center;"><?php echo ($row['background_no'] ? '✓' : ''); ?></td>
                                <td style="text-align: center;"><?php echo ($row['for_appointment'] ? '✓' : ''); ?></td>
                                <td style="text-align: center;"><?php echo ($row['for_probation'] ? '✓' : ''); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <!-- Signature Section -->
                <div class="signatures">
                    <div class="prepared-by">Prepared by the HRMPSB</div>
                    
                    <div class="sig-group">
                        <div>
                            <div class="sig-line"></div>
                            <div class="sig-label">RANDY D. PUNZALAN, CESO VI<br>Assistant Schools Division Superintendent<br>Chairperson</div>
                        </div>
                        <div>
                            <div class="sig-line"></div>
                            <div class="sig-label">CHRISTOPHER R. DIAZ, CESO V<br>Schools Division Superintendent<br>Appointing Authority</div>
                        </div>
                    </div>
                    
                    <div class="sig-group">
                        <div>
                            <div class="sig-line"></div>
                            <div class="sig-label">JOSE CHARLIE S. ALOQUIN, PhD<br>In-Charge, SGOD Chief<br>Member</div>
                        </div>
                        <div>
                            <div class="sig-line"></div>
                            <div class="sig-label">ATTY. JERICA CLARA S. MACHADO - DELA PEÑA<br>Attorney III<br>Member</div>
                        </div>
                    </div>
                    
                    <div class="sig-group">
                        <div>
                            <div class="sig-line"></div>
                            <div class="sig-label">AUBREY ANNE A. TABLAN<br>Administrative Assistant III<br>Rep., National Employees' Union (1st Level)</div>
                        </div>
                        <div>
                            <div class="sig-line"></div>
                            <div class="sig-label">NOEL G. SEQUITO, EdD<br>Administrative Officer V<br>Member</div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px;">
                        <div class="sig-line"></div>
                        <div class="sig-label">JHOANNA M. MANZANERO<br>Administrative Officer IV<br>Member</div>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-message">
                    <p>No assessment results found for this position.</p>
                    <p style="font-size: 12px; margin-top: 10px; color: #666;">Create evaluations to generate comparative assessment results.</p>
                </div>
            <?php endif; ?>
            
        <?php elseif ($viewMode === 'all' && count($groupedResults) === 0): ?>
            <?php showWarningBanner("No applicants found in the database. Please complete evaluations first to see Comparative Assessment Results."); ?>
            <div class="empty-message">
                <p>No applicants found. <a href="index.php">Go back to complete evaluations</a></p>
            </div>
            
        <?php elseif ($viewMode === 'position' || ($viewMode === 'position' && !$positionId)): ?>
            <?php showInfoBanner("Select a position below to view Comparative Assessment Results for that position group."); ?>
            <!-- Selector Section (when no position selected or view by position mode) -->
            <div class="selector-section">
                <div class="selector-row">
                    <label for="positionSelect">Select Position to View:</label>
                    <select id="positionSelect" onchange="changePosition()">
                        <option value="">-- Select a Position --</option>
                        <?php foreach ($positions as $pos): ?>
                            <option value="<?php echo htmlspecialchars($pos['id']); ?>">
                                <?php echo htmlspecialchars($pos['position_name']); ?> 
                                (<?php echo $pos['result_count']; ?> applicant<?php echo $pos['result_count'] != 1 ? 's' : ''; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="action-buttons">
                    <button class="btn-action" onclick="refreshResults()">Refresh</button>
                </div>
            </div>
            
            <div class="empty-message">
                <p>Select a position above to view comparative assessment results.</p>
            </div>
            
        <?php else: ?>
            <div class="empty-message">
                <p>No data available. Please select a view mode above.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function changePosition() {
            const positionId = document.getElementById('positionSelect').value;
            if (positionId) {
                window.location.href = `comparative_assessment_results.php?position_id=${positionId}`;
            }
        }
        
        function refreshResults() {
            location.reload();
        }
        
        function exportToCSV() {
            const table = document.querySelector('.results-table');
            if (!table) {
                alert('No data to export');
                return;
            }
            
            let csv = [];
            const rows = table.querySelectorAll('tr');
            
            rows.forEach(row => {
                const cols = row.querySelectorAll('td, th');
                const csvRow = [];
                cols.forEach(col => {
                    csvRow.push('"' + col.innerText.replace(/"/g, '""') + '"');
                });
                csv.push(csvRow.join(','));
            });
            
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `CAR-${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>
