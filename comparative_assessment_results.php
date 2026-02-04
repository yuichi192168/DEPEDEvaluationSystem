<?php
/**
 * Comparative Assessment Results (CAR) Display Page
 * Shows all applicants ranked by their assessment scores in official DepEd HRMPSB format
 */



// Debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Debug: Check if files exist
$required_files = [
    'includes/banners.php',
    'classes/ComparativeAssessmentReport.php',
    'classes/DBConnection.php',
    'config/evaluation_criteria.php',
    'config/baseline_library.php'
];

foreach ($required_files as $file) {
    if (!file_exists($file)) {
        die("<div style='background:#ffcccc;padding:20px;margin:20px;border:2px solid red;'>
            <h3>Missing Required File</h3>
            <p>File not found: <strong>$file</strong></p>
        </div>");
    }
}

require_once 'includes/banners.php';
require_once 'classes/ComparativeAssessmentReport.php';
require_once 'classes/DBConnection.php';
require_once 'config/evaluation_criteria.php';
require_once 'config/baseline_library.php';

// Debug database connection
try {
    $conn = DBConnection::getConnection();
    if (!$conn) {
        die("<div style='background:#ffcccc;padding:20px;margin:20px;border:2px solid red;'>
            <h3>Database Connection Failed</h3>
            <p>Unable to connect to database</p>
        </div>");
    }
} catch (Exception $e) {
    die("<div style='background:#ffcccc;padding:20px;margin:20px;border:2px solid red;'>
        <h3>Database Error</h3>
        <p>" . $e->getMessage() . "</p>
    </div>");
}

$car = new ComparativeAssessmentReport();
$positionId = $_GET['position_id'] ?? null;
$viewMode = $_GET['view'] ?? 'position'; // 'position' or 'all' or 'ies'

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
$positionsCount = 0;
$positionsResultOk = $positionsResult ? true : false;
if ($positionsResult) {
    $positionsCount = $positionsResult->num_rows;
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

// Load IES data for 'ies' view mode
$iesData = [];
if ($viewMode === 'ies') {
    $query = "
        SELECT 
            a.id as applicant_id,
            a.name,
            COALESCE(p.position_name, e.position_group) as position_name,
            e.id as evaluation_id,
            e.total_score,
            e.evaluation_date,
            ed.criterion,
            ed.applicant_qualification,
            ed.applicant_level,
            ed.baseline_qualification,
            ed.baseline_level,
            ed.weight,
            ed.increment,
            ed.final_score
        FROM applicants a
        LEFT JOIN evaluations e ON a.id = e.applicant_id
        LEFT JOIN positions p ON e.position_id = p.id
        LEFT JOIN evaluation_details ed ON e.id = ed.evaluation_id
        WHERE e.id IS NOT NULL
        ORDER BY a.name, e.evaluation_date DESC
    ";
    
    $result = $conn->query($query);
    if ($result) {
        $currentApplicant = null;
        while ($row = $result->fetch_assoc()) {
            $applicantId = $row['applicant_id'];
            
            if ($applicantId !== $currentApplicant) {
                if (!isset($iesData[$applicantId])) {
                    $iesData[$applicantId] = [
                        'name' => $row['name'],
                        'position_name' => $row['position_name'],
                        'evaluations' => []
                    ];
                }
                $currentApplicant = $applicantId;
            }
            
            $evalId = $row['evaluation_id'];
            if ($evalId && !isset($iesData[$applicantId]['evaluations'][$evalId])) {
                $iesData[$applicantId]['evaluations'][$evalId] = [
                    'evaluation_id' => $evalId,
                    'position' => $row['position_name'],
                    'total_score' => $row['total_score'],
                    'evaluation_date' => $row['evaluation_date'],
                    'details' => []
                ];
            }
            
            if ($evalId && $row['criterion']) {
                $iesData[$applicantId]['evaluations'][$evalId]['details'][] = [
                    'criterion' => $row['criterion'],
                    'applicant_qualification' => $row['applicant_qualification'],
                    'applicant_level' => $row['applicant_level'],
                    'baseline_qualification' => $row['baseline_qualification'],
                    'baseline_level' => $row['baseline_level'],
                    'weight' => $row['weight'],
                    'increment' => $row['increment'],
                    'final_score' => $row['final_score']
                ];
            }
        }
    }
}

// DEBUG COMMENTS
echo "<!-- DEBUG: positionId = " . ($positionId ?: 'NULL') . " -->\n";
echo "<!-- DEBUG: viewMode = $viewMode -->\n";
echo "<!-- DEBUG: positionsResult = " . ($positionsResultOk ? 'YES' : 'NO') . " -->\n";
echo "<!-- DEBUG: positionsCount = $positionsCount -->\n";
echo "<!-- DEBUG: Found " . count($positions) . " positions -->\n";
if (count($positions) > 0) {
    echo "<!-- DEBUG: First position: " . htmlspecialchars(json_encode($positions[0])) . " -->\n";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparative Assessment Result (CAR) - DepEd HRMPSB</title>
    <!-- Favicon -->
    <?php

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $baseUrl = $protocol . $host . '/DEPEDEvaluationSystemV2/';
    // $cacheBuster = '?v=' . (file_exists(__DIR__ . '/images/favicon.ico') ? filemtime(__DIR__ . '/images/favicon.ico') : time());
    // ?>
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseUrl; ?>images/apple-touch-icon.png<?php echo $cacheBuster; ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $baseUrl; ?>images/favicon-32x32.png<?php echo $cacheBuster; ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $baseUrl; ?>images/favicon-16x16.png<?php echo $cacheBuster; ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>images/favicon.ico<?php echo $cacheBuster; ?>">
    <link rel="manifest" href="<?php echo $baseUrl; ?>images/site.webmanifest<?php echo $cacheBuster; ?>">
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
            background: #E04040;
        }
        
        .btn-action.print:hover {
            background: #c73030;
        }
        
        .btn-action.export {
            background: #E04040;
        }
        
        .btn-action.export:hover {
            background: #c73030;
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
            <a href="comparative_assessment_results.php?view=ies" class="nav-btn" 
               style="<?php echo ($viewMode === 'ies') ? 'background: #333; font-weight: bold;' : ''; ?>">
               View IES Data
            </a>
        </div>
        
        <!-- Title Section -->
        <div class="car-title">
            <h1>COMPARATIVE ASSESSMENT RESULT</h1>
            <p style="text-align: center; font-size: 11px; margin-top: 5px;">Annex I</p>
        </div>
        
        <!-- <?php
        echo "<div style='background:#f0f0f0;padding:10px;margin:10px 0;border:1px solid #ccc;font-size:12px;'>";
        echo "<strong>Debug Info:</strong><br>";
        echo "View Mode: $viewMode<br>";
        echo "Position ID: " . ($positionId ?: 'Not set') . "<br>";
        echo "Positions in database: " . count($positions) . "<br>";
        if ($viewMode === 'all') {
            echo "All view mode selected<br>";
            if (isset($groupedResults)) {
                echo "Grouped results count: " . count($groupedResults) . "<br>";
            } else {
                echo "Grouped results not set<br>";
            }
        } elseif ($viewMode === 'position' && $positionId) {
            echo "Specific position view with ID: $positionId<br>";
            echo "Results count: " . count($results) . "<br>";
        } elseif ($viewMode === 'position') {
            echo "Position list view (no position selected)<br>";
        }
        echo "</div>";
        ?> -->
        
        <div id="main-content">
            <?php if ($viewMode === 'all'): ?>
                <!-- ALL APPLICANTS VIEW -->
                <?php if (count($groupedResults) > 0): ?>
                    <div style="margin-bottom: 20px;">
                        <h1 style="color: #333; font-size: 18px; border-bottom: 3px solid #666; padding-bottom: 12px; margin-bottom: 20px;">
                            All Applicants by Position
                        </h1>
                    </div>
                    
                    <?php foreach ($groupedResults as $posName => $posData): ?>
                        <div style="page-break-inside: avoid; margin-bottom: 35px; background: #fff; border: 1px solid #e0e0e0; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                            <!-- Position Header -->
                            <div style="background: #f5f5f5; color: #333; padding: 15px; border-bottom: 2px solid #ddd; border-left: 4px solid #666;">
                                <h2 style="font-size: 15px; font-weight: bold; margin: 0;">
                                    <?php echo htmlspecialchars($posName); ?>
                                </h2>
                                <div style="font-size: 12px; margin-top: 5px; color: #666;">
                                    Total Applicants: <strong><?php echo count($posData['applicants']); ?></strong>
                                </div>
                            </div>
                            
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
                            
                            <div style="overflow-x: auto; padding: 15px;">
                                <table class="results-table" style="margin: 0;">
                                    <thead>
                                        <tr style="background: #f9f9f9; border-bottom: 2px solid #ddd;">
                                            <th style="width: 5%; border: 1px solid #e0e0e0; color: #333; background: #f5f5f5;">Rank</th>
                                            <th style="width: 20%; border: 1px solid #e0e0e0; color: #333; background: #f5f5f5; text-align: left;">Name</th>
                                            <th style="width: 12%; border: 1px solid #e0e0e0; color: #333; background: #f5f5f5;">Application Code</th>
                                            <?php foreach ($criteriaMap as $mapping): ?>
                                                <th style="width: 7%; border: 1px solid #e0e0e0; color: #333; background: #f5f5f5;"><?php echo htmlspecialchars($mapping['criteria_name']); ?></th>
                                            <?php endforeach; ?>
                                            <th style="width: 8%; border: 1px solid #e0e0e0; color: #333; background: #f5f5f5;">Total Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($posData['applicants'] as $index => $row): ?>
                                            <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s;">
                                                <td style="background: #fafafa; font-weight: 600; color: #666; border: 1px solid #e0e0e0;"><?php echo $row['rank'] ?? ($index + 1); ?></td>
                                                <td class="name-column" style="text-align: left; font-weight: 500; color: #333; border: 1px solid #e0e0e0;"><?php echo htmlspecialchars($row['name']); ?></td>
                                                <td class="code-column" style="font-family: 'Courier New', monospace; font-size: 11px; color: #777; border: 1px solid #e0e0e0;"><?php echo htmlspecialchars($row['application_code'] ?? '—'); ?></td>
                                                <?php foreach ($criteriaMap as $mapping): ?>
                                                    <td class="score-column" style="background: #fafafa; color: #333; border: 1px solid #e0e0e0;"><?php echo number_format($row[$mapping['db_column']] ?? 0, 2); ?></td>
                                                <?php endforeach; ?>
                                                <td class="total-column" style="background: #f0f0f0; color: #333; font-weight: bold; font-size: 13px; border: 1px solid #e0e0e0; border-left: 3px solid #666;"><?php echo number_format($row['total_score'] ?? 0, 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-message">
                        <h3>No Applicants Found</h3>
                        <p>There are no applicants in the system yet.</p>
                        <p><a href="index.php" class="nav-btn" style="display:inline-block;margin-top:10px;">
                            Go to Evaluation Form to create evaluations
                        </a></p>
                    </div>
                <?php endif; ?>
                
            <?php elseif ($viewMode === 'position' && $positionId && !empty($results)): ?>
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
                <?php if ($viewMode !== 'all'): ?>
                <div class="action-buttons">
                    <button class="btn-action" onclick="refreshResults()">Refresh</button>
                    <?php if ($positionId): ?>
                        <!-- <button class="btn-action print" onclick="window.print()">Print</button> -->
                        <button class="btn-action export" onclick="exportToCSV()">Export CSV</button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Evaluation Criteria Reference - Collapsible -->
            <div style="margin: 20px 0; padding: 15px; background: #f5f5f5; border-left: 4px solid #E04040; border-radius: 4px;">
                <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;" onclick="toggleCriteriaTable()">
                    <h3 style="color: #E04040; margin: 0; font-size: 13px; flex: 1;">Evaluation Criteria and Maximum Points</h3>
                    <span id="criteriaToggleIcon" style="color: #E04040; font-size: 18px; font-weight: bold;">+</span>
                </div>
                <table id="criteriaTable" style="width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 12px; display: none;">
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
            <?php elseif ($viewMode === 'position' && $positionId && empty($results)): ?>
                <!-- POSITION SELECTED BUT NO DATA -->
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
                </div>
                
                <div class="empty-message">
                    <h3>No Results for Selected Position</h3>
                    <p>There are no assessment results for this position yet.</p>
                    <p><a href="index.php" class="nav-btn" style="display:inline-block;margin-top:10px;">
                        Go to Evaluation Form to evaluate applicants
                    </a></p>
                </div>
                
            <?php elseif ($viewMode === 'position'): ?>
                <!-- POSITION LIST VIEW (NO POSITION SELECTED) -->
                <?php if (count($positions) > 0): ?>
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
                    
                    <div style="padding:20px;text-align:center;color:#666;">
                        <p>Select a position from the dropdown above to view comparative assessment results.</p>
                    </div>
                <?php else: ?>
                    <div class="empty-message">
                        <h3>No Positions Available</h3>
                        <p>There are no positions with evaluation results yet.</p>
                        <p><a href="index.php" class="nav-btn" style="display:inline-block;margin-top:10px;">
                            Go to Evaluation Form to start evaluations
                        </a></p>
                    </div>
                <?php endif; ?>
                
            <?php elseif ($viewMode === 'ies'): ?>
                <!-- IES DATA VIEW -->
                <?php if (count($iesData) > 0): ?>
                    <div style="margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap; margin-bottom: 15px;">
                            <h1 style="color: #333; font-size: 18px; border-bottom: 3px solid #666; padding-bottom: 12px; margin: 0; flex: 1;">
                                Individual Evaluation Sheets (IES)
                            </h1>
                        </div>
                        <!-- Search Box -->
                        <div style="margin-bottom: 15px;">
                            <input type="text" id="applicantSearch" placeholder="Search applicant name..." 
                                   style="padding: 10px; width: 100%; max-width: 400px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px;"
                                   onkeyup="filterApplicants()">
                            <div style="font-size: 12px; color: #666; margin-top: 5px;">
                                Found: <span id="applicantCount"><?php echo count($iesData); ?></span> applicant(s)
                            </div>
                        </div>
                    </div>
                    
                    <div id="iesContainer">
                        <?php foreach ($iesData as $applicantId => $applicant): ?>
                            <div class="applicant-ies-card" data-applicant-name="<?php echo htmlspecialchars(strtolower($applicant['name'])); ?>" 
                                 style="page-break-inside: avoid; margin-bottom: 40px; background: #fff; border: 1px solid #e0e0e0; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                                <!-- Applicant Header -->
                                <div style="background: #f5f5f5; color: #333; padding: 15px; border-bottom: 2px solid #ddd; border-left: 4px solid #666; display: flex; justify-content: space-between; align-items: flex-start; gap: 15px;">
                                    <div style="flex: 1;">
                                        <h2 style="font-size: 15px; font-weight: bold; margin: 0;">
                                            <?php echo htmlspecialchars($applicant['name']); ?>
                                        </h2>
                                        <div style="font-size: 12px; margin-top: 5px; color: #666;">
                                            Position: <strong><?php echo htmlspecialchars($applicant['position_name'] ?? 'N/A'); ?></strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Evaluation Details -->
                                <div style="padding: 15px;">
                                    <?php foreach ($applicant['evaluations'] as $evalId => $evaluation): ?>
                                        <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e0e0e0;">
                                            <div style="background: #f9f9f9; padding: 10px; border-radius: 4px; margin-bottom: 12px;">
                                                <div style="font-size: 12px; color: #666;">
                                                    <strong>Evaluation Date:</strong> <?php echo htmlspecialchars($evaluation['evaluation_date']); ?> | 
                                                    <strong>Total Score:</strong> <span style="font-weight: bold; color: #333;">
                                                        <?php echo number_format($evaluation['total_score'], 2); ?>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div style="overflow-x: auto;">
                                                <table class="results-table" style="margin: 0; font-size: 11px;">
                                                    <thead>
                                                        <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
                                                            <th style="width: 15%; border: 1px solid #e0e0e0; text-align: left; padding: 8px;">Criterion</th>
                                                            <th style="width: 12%; border: 1px solid #e0e0e0; text-align: center;">Applicant Level</th>
                                                            <th style="width: 12%; border: 1px solid #e0e0e0; text-align: center;">Baseline Level</th>
                                                            <th style="width: 8%; border: 1px solid #e0e0e0; text-align: center;">Weight</th>
                                                            <th style="width: 8%; border: 1px solid #e0e0e0; text-align: center;">Increment</th>
                                                            <th style="width: 10%; border: 1px solid #e0e0e0; text-align: center;">Final Score</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($evaluation['details'] as $detail): ?>
                                                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                                                <td style="border: 1px solid #e0e0e0; padding: 6px; background: #fafafa;">
                                                                    <?php echo htmlspecialchars($detail['criterion']); ?>
                                                                </td>
                                                                <td style="border: 1px solid #e0e0e0; padding: 6px; text-align: center;">
                                                                    <?php echo htmlspecialchars($detail['applicant_level']); ?>
                                                                </td>
                                                                <td style="border: 1px solid #e0e0e0; padding: 6px; text-align: center;">
                                                                    <?php echo htmlspecialchars($detail['baseline_level']); ?>
                                                                </td>
                                                                <td style="border: 1px solid #e0e0e0; padding: 6px; text-align: center;">
                                                                    <?php echo number_format($detail['weight'] ?? 0, 2); ?>
                                                                </td>
                                                                <td style="border: 1px solid #e0e0e0; padding: 6px; text-align: center;">
                                                                    <?php echo number_format($detail['increment'] ?? 0, 2); ?>
                                                                </td>
                                                                <td style="border: 1px solid #e0e0e0; padding: 6px; text-align: center; background: #f0f0f0; font-weight: bold;">
                                                                    <?php echo number_format($detail['final_score'] ?? 0, 2); ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-message">
                        <h3>No IES Data Available</h3>
                        <p>No individual evaluation sheets have been created yet.</p>
                        <p><a href="index.php" class="nav-btn" style="display:inline-block;margin-top:10px;">
                            Go to Evaluation Form to create evaluations
                        </a></p>
                    </div>
                <?php endif; ?>
                
            <?php else: ?>
                <!-- FALLBACK - SHOULD NOT REACH HERE -->
                <div class="empty-message">
                    <h3>Unknown View Mode</h3>
                    <p>Please use the navigation buttons above to select a view mode.</p>
                </div>
            <?php endif; ?>
        </div>
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
                // Show banner message instead of alert
                const b = document.createElement('div');
                b.className = 'banner banner-warning auto-hide';
                b.innerHTML = `<div class="banner-content"><span class="banner-icon">⚠</span><span class="banner-text">No data to export</span><button class="banner-close" aria-label="Close">&times;</button></div>`;
                document.body.insertBefore(b, document.body.firstChild);
                const closeBtn = b.querySelector('.banner-close'); if (closeBtn) closeBtn.addEventListener('click', () => b.remove());
                setTimeout(() => { try { b.remove(); } catch(e){} }, 5200);
                return;
            }
            
            // Hide criteria reference section during export
            const criteriaSection = document.querySelector('[style*="margin: 20px 0; padding: 15px; background: #f5f5f5"]');
            let criteriaWasVisible = false;
            if (criteriaSection) {
                criteriaWasVisible = criteriaSection.style.display !== 'none';
                criteriaSection.style.display = 'none';
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
            
            // Restore criteria section visibility
            if (criteriaSection && criteriaWasVisible) {
                criteriaSection.style.display = '';
            }
        }

        function exportIEStoCSV() {
            const container = document.getElementById('iesContainer');
            if (!container) {
                const b = document.createElement('div');
                b.className = 'banner banner-warning auto-hide';
                b.innerHTML = `<div class="banner-content"><span class="banner-icon">⚠</span><span class="banner-text">No IES data to export</span><button class="banner-close" aria-label="Close">&times;</button></div>`;
                document.body.insertBefore(b, document.body.firstChild);
                const closeBtn = b.querySelector('.banner-close'); if (closeBtn) closeBtn.addEventListener('click', () => b.remove());
                setTimeout(() => { try { b.remove(); } catch(e){} }, 5200);
                return;
            }
            
            let csv = [];
            csv.push(['Applicant Name', 'Position', 'Criterion', 'Applicant Level', 'Baseline Level', 'Weight', 'Increment', 'Final Score', 'Evaluation Date', 'Total Score'].map(v => `"${v}"`).join(','));
            
            const tables = container.querySelectorAll('.results-table');
            let applicantName = '';
            let positionName = '';
            let evaluationDate = '';
            let totalScore = '';
            
            const applicantBlocks = container.querySelectorAll('[style*="margin-bottom: 40px"]');
            
            applicantBlocks.forEach((block, blockIdx) => {
                const nameEl = block.querySelector('h2');
                const posEl = block.querySelector('[style*="Position:"]');
                applicantName = nameEl ? nameEl.innerText : '';
                positionName = posEl ? posEl.innerText.replace('Position: ', '').split('\n')[0] : '';
                
                const evalSections = block.querySelectorAll('[style*="margin-bottom: 20px; padding-bottom: 20px"]');
                evalSections.forEach((evalSection, evalIdx) => {
                    const dateScoreDiv = evalSection.querySelector('[style*="background: #f9f9f9"]');
                    if (dateScoreDiv) {
                        const text = dateScoreDiv.innerText;
                        const dateMatch = text.match(/Evaluation Date:\s*(\d{4}-\d{2}-\d{2})/);
                        const scoreMatch = text.match(/Total Score:\s*([\d.]+)/);
                        evaluationDate = dateMatch ? dateMatch[1] : '';
                        totalScore = scoreMatch ? scoreMatch[1] : '';
                    }
                    
                    const table = evalSection.querySelector('.results-table');
                    if (table) {
                        const rows = table.querySelectorAll('tbody tr');
                        rows.forEach(row => {
                            const cols = row.querySelectorAll('td');
                            if (cols.length > 0) {
                                const csvRow = [
                                    applicantName,
                                    positionName,
                                    cols[0].innerText,  // Criterion
                                    cols[1].innerText,  // Applicant Level
                                    cols[2].innerText,  // Baseline Level
                                    cols[3].innerText,  // Weight
                                    cols[4].innerText,  // Increment
                                    cols[5].innerText,  // Final Score
                                    evaluationDate,
                                    totalScore
                                ];
                                csv.push(csvRow.map(v => `"${v.replace(/"/g, '""')}"`).join(','));
                            }
                        });
                    }
                });
            });
            
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `IES-${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
        // Toggle evaluation criteria table visibility
        function toggleCriteriaTable() {
            const table = document.getElementById('criteriaTable');
            const icon = document.getElementById('criteriaToggleIcon');
            
            if (table.style.display === 'none') {
                table.style.display = 'table';
                icon.textContent = '−';
            } else {
                table.style.display = 'none';
                icon.textContent = '+';
            }
        }

        // Filter applicants by name
        function filterApplicants() {
            const searchInput = document.getElementById('applicantSearch').value.toLowerCase();
            const applicantCards = document.querySelectorAll('.applicant-ies-card');
            let visibleCount = 0;
            
            applicantCards.forEach(card => {
                const applicantName = card.getAttribute('data-applicant-name');
                if (applicantName.includes(searchInput)) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            document.getElementById('applicantCount').textContent = visibleCount;
        }

        // Download individual IES as HTML/PDF with preview
        function downloadIES(applicantName, applicantId) {
            const cards = document.querySelectorAll('.applicant-ies-card');
            let applicantData = null;
            let tableHtml = '';
            let totalScore = 0;
            
            // Extract applicant data
            cards.forEach(card => {
                if (card.getAttribute('data-applicant-name') === applicantName.toLowerCase()) {
                    const tables = card.querySelectorAll('.results-table');
                    const positionEl = card.querySelector('[style*="Position:"]');
                    const position = positionEl ? positionEl.innerText.replace('Position: ', '').split('\n')[0] : '';
                    
                    tables.forEach((table) => {
                        const rows = table.querySelectorAll('tbody tr');
                        rows.forEach((row) => {
                            const cells = row.querySelectorAll('td');
                            if (cells.length >= 6) {
                                const criterion = cells[0].innerText.trim();
                                const applicantLevel = cells[1].innerText.trim();
                                const baselineLevel = cells[2].innerText.trim();
                                const weight = cells[3].innerText.trim();
                                const increment = cells[4].innerText.trim();
                                const finalScore = cells[5].innerText.trim();
                                
                                // Format details and computation for IES
                                const details = `Applicant: ${applicantLevel} | Baseline: ${baselineLevel}`;
                                const computation = increment;
                                
                                tableHtml += `
                                    <tr>
                                        <td style="border: 1px solid #000; padding: 8px; text-align: left;">${criterion}</td>
                                        <td style="border: 1px solid #000; padding: 8px; text-align: center; width: 60px;">${weight}</td>
                                        <td style="border: 1px solid #000; padding: 8px; text-align: left; font-size: 11px;">${details}</td>
                                        <td style="border: 1px solid #000; padding: 8px; text-align: center; font-size: 11px;">${computation}</td>
                                        <td style="border: 1px solid #000; padding: 8px; text-align: center; width: 60px;">${finalScore}</td>
                                    </tr>
                                `;
                                totalScore += parseFloat(finalScore) || 0;
                            }
                        });
                    });
                    
                    applicantData = { name: applicantName, position: position };
                }
            });
            
            // Check if data was found
            if (!applicantData) {
                alert('Could not find applicant data. Please try again.');
                return;
            }
            
            // Create professional IES HTML
            const htmlContent = `
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Individual Evaluation Sheet - ${applicantName}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Calibri', 'Arial', sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .page {
            background: white;
            width: 8.5in;
            height: 11in;
            margin: 0 auto 20px;
            padding: 0.75in;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .annex {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .title {
            text-align: center;
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        
        .subtitle {
            text-align: center;
            font-size: 11px;
            margin-bottom: 15px;
            color: #666;
        }
        
        .info-section {
            margin-bottom: 12px;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .info-line {
            margin-bottom: 3px;
            display: flex;
            gap: 30px;
        }
        
        .info-line label {
            font-weight: bold;
            min-width: 140px;
        }
        
        .info-line input {
            border: none;
            border-bottom: 1px solid #000;
            flex: 1;
            padding: 2px 0;
            font-size: 11px;
        }
        
        .section-title {
            background: #e0e0e0;
            padding: 8px;
            margin-top: 12px;
            margin-bottom: 0;
            font-weight: bold;
            text-align: center;
            font-size: 12px;
            border: 1px solid #000;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
            font-size: 11px;
        }
        
        th {
            background: #e8e8e8;
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
        }
        
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        
        .total-row {
            background: #f5f5f5;
            font-weight: bold;
        }
        
        .total-row td {
            text-align: center;
        }
        
        .attestation {
            font-size: 10px;
            line-height: 1.3;
            margin-top: 15px;
            text-align: justify;
        }
        
        .signature-section {
            margin-top: 25px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            font-size: 10px;
        }
        
        .sig-block {
            text-align: center;
        }
        
        .sig-line {
            border-bottom: 1px solid #000;
            height: 35px;
            margin-bottom: 3px;
        }
        
        .sig-label {
            font-size: 10px;
            line-height: 1.2;
        }
        
        .attested-block {
            margin-top: 20px;
            font-size: 10px;
        }
        
        .controls {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }
        
        button {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            color: white;
        }
        
        .print-button {
            background: #4CAF50;
        }
        
        .print-button:hover {
            background: #45a049;
        }
        
        .export-button {
            background: #2196F3;
        }
        
        .export-button:hover {
            background: #0b7dda;
        }
        
        @media print {
            body { background: white; padding: 0; }
            .page { box-shadow: none; margin: 0; width: 100%; height: 100%; padding: 0.5in; }
            .controls { display: none; }
        }
    </style>
    <script>
        function exportToWord() {
            // Get the HTML content from the current preview window
            const htmlContent = document.documentElement.outerHTML;
            const nameField = document.querySelector('input[readonly][value]');
            const applicantName = nameField ? nameField.value : 'Applicant';
            
            // Create form and submit to backend
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'export_ies_word.php';
            form.style.display = 'none';
            
            const htmlInput = document.createElement('input');
            htmlInput.type = 'hidden';
            htmlInput.name = 'html_content';
            htmlInput.value = htmlContent;
            
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'applicant_name';
            nameInput.value = applicantName;
            
            form.appendChild(htmlInput);
            form.appendChild(nameInput);
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    </' + 'script>
</head>
<body>
    <div class="controls">
        <button class="print-button" onclick="window.print()">Print</button>
        <button class="export-button" onclick="exportToWord()">Export to Word</button>
    </div>
    
    <div class="page">
        <div class="annex">Annex G</div>
        
        <div class="title">INDIVIDUAL EVALUATION SHEET (IES)</div>
        <div class="subtitle">DepEd Human Resource Management and Professional Selection Board</div>
        
        <div class="info-section">
            <div class="info-line">
                <label>Name of Applicant:</label>
                <input type="text" value="${applicantName}" readonly>
                <label style="margin-left: 20px;">Application Code:</label>
                <input type="text" style="max-width: 100px;">
            </div>
            <div class="info-line">
                <label>Position Applied for:</label>
                <input type="text" value="${applicantData?.position || ''}" readonly>
            </div>
            <div class="info-line">
                <label>Schools Division Office:</label>
                <input type="text" value="City Schools Division of Cabuyao" readonly>
            </div>
            <div class="info-line">
                <label>Contact Number:</label>
                <input type="text" style="max-width: 200px;">
                <label style="margin-left: 20px;">Job Group/SG-Level:</label>
                <input type="text" style="max-width: 150px;">
            </div>
        </div>
        
        <div class="section-title">Applicant's Actual Qualifications</div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 20%;">Criteria</th>
                    <th style="width: 10%; text-align: center;">Weight Allocation</th>
                    <th style="width: 30%;">Details of Applicant's Qualifications<br><span style="font-size: 9px;">(Relevant documents submitted; additional requirements, notes or HRMPSB comments)</span></th>
                    <th style="width: 15%; text-align: center;">Computation</th>
                    <th style="width: 12%; text-align: center;">Actual Score</th>
                </tr>
            </thead>
            <tbody>
                ${tableHtml}
                <tr class="total-row">
                    <td colspan="2" style="text-align: left;"><strong>TOTAL</strong></td>
                    <td></td>
                    <td style="text-align: center;"><strong>100</strong></td>
                    <td style="text-align: center;"><strong>${totalScore.toFixed(2)}</strong></td>
                </tr>
            </tbody>
        </table>
        
        <div class="attestation">
            <p style="margin-top: 12px; margin-bottom: 8px;">I hereby attest to the conduct of the application code and assessment process in accordance with the applicable guidelines, and knowledge, upon discussion with the Human Resource Merit Promotion and Selection Board (HRMPSB), the results of the comparative assessment and the points given to me based on my qualifications and submitted documentary requirements for the Information and Communications Technology under Contract of Service.</p>
            
            <p style="margin-bottom: 8px;">Furthermore, I hereby affix my signature in this Form to attest to the objectives and judicious conduct of the HRMPSB evaluation through Open Ranking System.</p>
        </div>
        
        <div class="signature-section">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">
                    <strong>Name and Signature of Applicant</strong><br>
                    Date: _________________
                </div>
            </div>
            <div class="sig-block">
                <div class="attested-block">
                    <p style="margin-bottom: 3px;"><strong>Attested:</strong></p>
                </div>
                <div class="sig-line" style="margin-top: 5px;"></div>
                <div class="sig-label">
                    <strong>RANDY D. PUNZALAN, CESO VI</strong><br>
                    HRMPSB Chair
                </div>
            </div>
        </div>
    </' + 'body>
</' + 'html>
            `;
            
            // Open in new window for preview and show success message
            const previewWindow = window.open('', '_blank');
            previewWindow.document.write(htmlContent);
            previewWindow.document.close();
            
            const banner = document.createElement('div');
            banner.className = 'banner banner-success auto-hide';
            banner.innerHTML = `<div class="banner-content"><span class="banner-icon">✓</span><span class="banner-text">IES for ${applicantName} opened for preview and printing</span><button class="banner-close" aria-label="Close">&times;</button></div>`;
            document.body.insertBefore(banner, document.body.firstChild);
            const closeBtn = banner.querySelector('.banner-close');
            if (closeBtn) closeBtn.addEventListener('click', () => banner.remove());
            setTimeout(() => { try { banner.remove(); } catch(e){} }, 5200);
        }
        
        // Export IES to Word directly from main page
        function exportIESToWord(applicantName, applicantId) {
            const cards = document.querySelectorAll('.applicant-ies-card');
            let applicantData = null;
            let tableHtml = '';
            let totalScore = 0;
            
            // Extract applicant data
            cards.forEach(card => {
                if (card.getAttribute('data-applicant-name') === applicantName.toLowerCase()) {
                    const tables = card.querySelectorAll('.results-table');
                    const positionEl = card.querySelector('[style*="Position:"]');
                    const position = positionEl ? positionEl.innerText.replace('Position: ', '').split('\n')[0] : '';
                    
                    tables.forEach((table) => {
                        const rows = table.querySelectorAll('tbody tr');
                        rows.forEach((row) => {
                            const cells = row.querySelectorAll('td');
                            if (cells.length >= 6) {
                                const criterion = cells[0].innerText.trim();
                                const applicantLevel = cells[1].innerText.trim();
                                const baselineLevel = cells[2].innerText.trim();
                                const weight = cells[3].innerText.trim();
                                const increment = cells[4].innerText.trim();
                                const finalScore = cells[5].innerText.trim();
                                
                                // Format details and computation for IES
                                const details = `Applicant: ${applicantLevel} | Baseline: ${baselineLevel}`;
                                const computation = increment;
                                
                                tableHtml += `<tr>
                                    <td>${criterion}</td>
                                    <td style="text-align: center;">${weight}</td>
                                    <td>${details}</td>
                                    <td style="text-align: center;">${computation}</td>
                                    <td style="text-align: center;">${finalScore}</td>
                                </tr>`;
                                totalScore += parseFloat(finalScore) || 0;
                            }
                        });
                    });
                    
                    applicantData = { name: applicantName, position: position };
                }
            });
            
            if (!applicantData) {
                alert('Could not find applicant data');
                return;
            }
            
            // Create clean HTML for Word export matching the official DepEd IES format
            const htmlContent = `<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>IES - ${applicantName}</title>
    <style>
        body { font-family: 'Calibri', Arial, sans-serif; margin: 0.5in; font-size: 11pt; }
        h1 { text-align: center; font-size: 14pt; margin: 10px 0; }
        .subtitle { text-align: center; font-size: 11pt; margin-bottom: 15px; }
        .info-section { margin-bottom: 15px; }
        .info-line { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #e8e8e8; font-weight: bold; text-align: center; }
        .total-row { background: #f5f5f5; font-weight: bold; }
        .attestation { margin: 15px 0; text-align: justify; font-size: 10pt; line-height: 1.4; }
        .sig-section { margin-top: 20px; }
        .sig-line { border-bottom: 1px solid #000; margin: 30px 0 5px 0; }
    </style>
</head>
<body>
    <div style="position: absolute; top: 10px; right: 10px; font-weight: bold;">Annex G</div>
    
    <h1>INDIVIDUAL EVALUATION SHEET (IES)</h1>
    <div class="subtitle">DepEd Human Resource Management and Professional Selection Board</div>
    
    <div class="info-section">
        <div class="info-line"><strong>Name of Applicant:</strong> ${applicantName}</div>
        <div class="info-line"><strong>Application Code:</strong> _______________________</div>
        <div class="info-line"><strong>Position Applied for:</strong> ${applicantData.position}</div>
        <div class="info-line"><strong>Schools Division Office:</strong> City Schools Division of Cabuyao</div>
        <div class="info-line"><strong>Contact Number:</strong> _______________________ <strong>Job Group/SG-Level:</strong> _______________________</div>
    </div>
    
    <h3>Applicant's Actual Qualifications</h3>
    
    <table>
        <thead>
            <tr>
                <th style="width: 20%;">Criteria</th>
                <th style="width: 10%;">Weight Allocation</th>
                <th style="width: 30%;">Details of Applicant's Qualifications</th>
                <th style="width: 15%;">Computation</th>
                <th style="width: 12%;">Actual Score</th>
            </tr>
        </thead>
        <tbody>
            ${tableHtml}
            <tr class="total-row">
                <td colspan="2" style="text-align: center;">TOTAL</td>
                <td></td>
                <td style="text-align: center;">100</td>
                <td style="text-align: center;">${totalScore.toFixed(2)}</td>
            </tr>
        </tbody>
    </table>
    
    <div class="attestation">
        <p>I hereby attest to the conduct of the application code and assessment process in accordance with the applicable guidelines, and knowledge, upon discussion with the Human Resource Merit Promotion and Selection Board (HRMPSB), the results of the comparative assessment and the points given to me based on my qualifications and submitted documentary requirements.</p>
        <p style="margin-top: 8px;">Furthermore, I hereby affix my signature in this Form to attest to the objectives and judicious conduct of the HRMPSB evaluation through Open Ranking System.</p>
    </div>
    
    <div class="sig-section">
        <div class="sig-line"></div>
        <div><strong>Name and Signature of Applicant</strong></div>
        <div>Date: _______________________</div>
        
        <div style="margin-top: 30px;"><strong>Attested:</strong></div>
        <div class="sig-line"></div>
        <div><strong>RANDY D. PUNZALAN, CESO VI</strong></div>
        <div>HRMPSB Chair</div>
    </div>
</body>
</html>`;
            
            // Create form and submit to backend
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'export_ies_word.php';
            form.style.display = 'none';
            
            const htmlInput = document.createElement('input');
            htmlInput.type = 'hidden';
            htmlInput.name = 'html_content';
            htmlInput.value = htmlContent;
            
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'applicant_name';
            nameInput.value = applicantName;
            
            form.appendChild(htmlInput);
            form.appendChild(nameInput);
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    </script>
</body>
</html>