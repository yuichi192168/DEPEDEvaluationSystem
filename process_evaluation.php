<?php
/**
 * Process Evaluation Form Submission
 * Saves data to database and generates reports
 */
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'initialize.php';
require_once 'config/baseline_library.php';
require_once 'config/evaluation_criteria.php';
require_once 'includes/banners.php';
require_once 'classes/AssessmentProcessor.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Database connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
if ($conn->connect_error) {
    $_SESSION['banner_message'] = [
        'type' => 'error',
        'message' => 'Database connection failed: ' . $conn->connect_error,
        'auto_hide' => false
    ];
    header('Location: index.php');
    exit;
}

// Set charset
$conn->set_charset("utf8mb4");

// Collect form data
$applicantName = trim($_POST['applicant_name'] ?? '');
$positionApplied = trim($_POST['position_applied'] ?? '');
$positionGroup = trim($_POST['position_group'] ?? '');
$positionKey = trim($_POST['position_key'] ?? '');
$applicationCode = trim($_POST['application_code'] ?? '');
$schoolsDivisionOffice = trim($_POST['schools_division_office'] ?? '');
$contactNumber = trim($_POST['contact_number'] ?? '');
$jobGroupSgLevel = trim($_POST['job_group_sg_level'] ?? '');
$outputFormat = trim($_POST['output_format'] ?? 'html');
$saveToDatabase = isset($_POST['save_to_database']) && $_POST['save_to_database'] == '1';
$saveToCar = isset($_POST['save_to_car']) && $_POST['save_to_car'] == '1';

// Applicant qualifications
$applicantEducationDegree = trim($_POST['applicant_education_degree'] ?? '');
$applicantEducationMastersUnits = intval($_POST['applicant_education_masters_units'] ?? 0);
$applicantEducationDoctoralUnits = intval($_POST['applicant_education_doctoral_units'] ?? 0);
$applicantTraining = floatval($_POST['applicant_training'] ?? 0);
$applicantExperience = floatval($_POST['applicant_experience'] ?? 0);
$applicantPerformance = floatval($_POST['applicant_performance'] ?? 0);
$applicantOutstandingAccomplishments = floatval($_POST['applicant_outstanding_accomplishments'] ?? 0);
$applicantApplicationOfEducation = floatval($_POST['applicant_application_of_education'] ?? 0);
$applicantApplicationOfLd = floatval($_POST['applicant_application_of_ld'] ?? 0);
$applicantPotential = floatval($_POST['applicant_potential'] ?? 0);

// Baseline qualifications
$baselineEducationDegree = trim($_POST['baseline_education_degree'] ?? '');
$baselineEducationMastersUnits = intval($_POST['baseline_education_masters_units'] ?? 0);
$baselineEducationDoctoralUnits = intval($_POST['baseline_education_doctoral_units'] ?? 0);
$baselineTraining = floatval($_POST['baseline_training'] ?? 0);
$baselineExperience = floatval($_POST['baseline_experience'] ?? 0);
$baselinePerformance = floatval($_POST['baseline_performance'] ?? 0);
$baselineOutstandingAccomplishments = floatval($_POST['baseline_outstanding_accomplishments'] ?? 0);
$baselineApplicationOfEducation = floatval($_POST['baseline_application_of_education'] ?? 0);
$baselineApplicationOfLd = floatval($_POST['baseline_application_of_ld'] ?? 0);
$baselinePotential = floatval($_POST['baseline_potential'] ?? 0);

// Validate required fields
$errors = [];
if (empty($applicantName)) $errors[] = 'Applicant name is required';
if (empty($positionApplied)) $errors[] = 'Position applied for is required';

// Check for duplicate application code (if provided)
if (!empty($applicationCode)) {
    $stmt = $conn->prepare("SELECT id FROM comparative_assessment_results WHERE application_code = ? LIMIT 1");
    $stmt->bind_param("s", $applicationCode);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "Application Code '{$applicationCode}' already exists. Please use a unique code.";
    }
    $stmt->close();
}

// Check applicant status - prevent evaluating archived applicants
if (!empty($applicantName)) {
    $stmt = $conn->prepare("SELECT id, archive_status FROM applicants WHERE name = ? LIMIT 1");
    $stmt->bind_param("s", $applicantName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['archive_status'] === 'archived') {
            $errors[] = "This applicant is archived. Please restore the applicant before processing.";
        }
    }
    $stmt->close();
}

if (!empty($errors)) {
    $_SESSION['banner_message'] = [
        'type' => 'error',
        'message' => 'Validation errors: ' . implode(', ', $errors),
        'auto_hide' => false
    ];
    header('Location: index.php');
    exit;
}

// Helper functions for level conversion
function convertEducationToLevel($degree, $mastersUnits, $doctoralUnits) {
    $level = 0;
    $deg = strtolower(trim($degree));
    if ($deg === 'doctorate' || $deg === 'phd' || $deg === 'ph.d') {
        $level = 31;
        if ($doctoralUnits > 0) {
            $level += min(floor($doctoralUnits / 3), 9);
        }
    } else if ($deg === 'master') {
        $level = 21;
        if ($doctoralUnits > 0) {
            $level += min(floor($doctoralUnits / 3), 9);
        }
    } else if ($deg === 'bachelor') {
        $level = 6;
        if ($mastersUnits > 0) {
            $level += min(floor($mastersUnits / 3), 14);
        }
    }
    return $level;
}

function convertTrainingToLevel($hours) {
    return $hours < 8 ? 1 : floor($hours / 8) + 1;
}

function convertExperienceToLevel($months) {
    return $months < 6 ? 1 : floor($months / 6) + 1;
}

function calculateIncrement($appLevel, $baselineLevel) {
    return max(0, $appLevel - $baselineLevel);
}

function convertIncrementToPoints($increment, $weight) {
    $basePoints = 0;
    if ($increment >= 10) $basePoints = 10;
    else if ($increment >= 8) $basePoints = 8;
    else if ($increment >= 6) $basePoints = 6;
    else if ($increment >= 4) $basePoints = 4;
    else if ($increment >= 2) $basePoints = 2;
    else $basePoints = 0;
    
    if ($weight == 20) return $basePoints * 2;
    else if ($weight == 5) return $basePoints / 2;
    else if ($weight == 15) return $basePoints * 1.5;
    else if ($weight == 25) return $basePoints * 2.5;
    return $basePoints;
}

function convertRatingToWeightedPoints($rating, $weight, $maxRating = 5) {
    $r = max(0, min(floatval($rating), $maxRating));
    $w = floatval($weight);
    return $maxRating > 0 ? ($r / $maxRating) * $w : 0;
}

// Calculate levels
$appEduLevel = convertEducationToLevel($applicantEducationDegree, $applicantEducationMastersUnits, $applicantEducationDoctoralUnits);
$baseEduLevel = convertEducationToLevel($baselineEducationDegree, $baselineEducationMastersUnits, $baselineEducationDoctoralUnits);
$appTrainingLevel = convertTrainingToLevel($applicantTraining);
$baseTrainingLevel = convertTrainingToLevel($baselineTraining);
$appExperienceLevel = convertExperienceToLevel($applicantExperience);
$baseExperienceLevel = convertExperienceToLevel($baselineExperience);

// Get position group for weights
$positionGroupName = '';
$salaryGrade = null;
if (!empty($positionKey) && $positionKey !== 'custom') {
    $positions = getAllPositions();
    if (isset($positions[$positionKey])) {
        $positionGroupName = $positions[$positionKey]['position_group'] ?? '';
        $salaryGrade = $positions[$positionKey]['salary_grade'] ?? null;
    }
}

if ($salaryGrade === null && !empty($jobGroupSgLevel)) {
    if (preg_match('/Salary\s*Grade\s*(\d+)/i', $jobGroupSgLevel, $matches)) {
        $salaryGrade = intval($matches[1]);
    }
}

if (empty($positionGroupName) && $positionGroup !== '') {
    if (is_numeric($positionGroup)) {
        $groups = AssessmentProcessor::getPositionGroups();
        $groupNames = array_keys($groups);
        $groupIndex = intval($positionGroup);
        if (isset($groupNames[$groupIndex])) {
            $positionGroupName = $groupNames[$groupIndex];
        }
    } else {
        $positionGroupName = $positionGroup;
    }
}

$category = null;
if ($positionGroupName === 'NON-TEACHING LEVEL I') {
    $category = 'non_general_services';
}

$criteriaConfig = getEvaluationCriteria($positionGroupName, $salaryGrade, $category);

// Default weights (NON-TEACHING LEVEL I)
$defaultWeights = [
    'education' => 5,
    'training' => 5,
    'experience' => 20,
    'performance' => 20,
    'outstanding_accomplishments' => 10,
    'application_of_education' => 10,
    'application_of_ld' => 10,
    'potential' => 20
];

$weights = $defaultWeights;
if (!empty($criteriaConfig) && isset($criteriaConfig['criteria']) && is_array($criteriaConfig['criteria'])) {
    $criteria = $criteriaConfig['criteria'];
    $weights = [
        'education' => $criteria['a']['max_points'] ?? $defaultWeights['education'],
        'training' => $criteria['b']['max_points'] ?? $defaultWeights['training'],
        'experience' => $criteria['c']['max_points'] ?? $defaultWeights['experience'],
        'performance' => $criteria['d']['max_points'] ?? $defaultWeights['performance'],
        'outstanding_accomplishments' => $criteria['e']['max_points'] ?? $defaultWeights['outstanding_accomplishments'],
        'application_of_education' => $criteria['f']['max_points'] ?? $defaultWeights['application_of_education'],
        'application_of_ld' => $criteria['g']['max_points'] ?? $defaultWeights['application_of_ld'],
        'potential' => $criteria['h']['max_points'] ?? $defaultWeights['potential']
    ];
}

// Calculate scores
$educationIncrement = calculateIncrement($appEduLevel, $baseEduLevel);
$trainingIncrement = calculateIncrement($appTrainingLevel, $baseTrainingLevel);
$experienceIncrement = calculateIncrement($appExperienceLevel, $baseExperienceLevel);

$educationScore = convertIncrementToPoints($educationIncrement, $weights['education']);
$trainingScore = convertIncrementToPoints($trainingIncrement, $weights['training']);
$experienceScore = convertIncrementToPoints($experienceIncrement, $weights['experience']);
$performanceScore = convertRatingToWeightedPoints($applicantPerformance, $weights['performance']);
$outstandingAccomplishmentsScore = min($applicantOutstandingAccomplishments, $weights['outstanding_accomplishments']);
$applicationOfEducationScore = min(max(0, $applicantApplicationOfEducation), $weights['application_of_education']);
$applicationOfLdScore = convertRatingToWeightedPoints($applicantApplicationOfLd, $weights['application_of_ld']);
$potentialScore = convertRatingToWeightedPoints($applicantPotential, $weights['potential']);

$totalScore = $educationScore + $trainingScore + $experienceScore + $performanceScore + 
              $outstandingAccomplishmentsScore + $applicationOfEducationScore + 
              $applicationOfLdScore + $potentialScore;

// Start transaction
$conn->begin_transaction();

try {
    $positionId = null;
    $applicantId = null;
    
    // Check if position exists or create new one
    $stmt = $conn->prepare("SELECT id FROM positions WHERE position_name = ? LIMIT 1");
    $stmt->bind_param("s", $positionApplied);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $positionId = $row['id'];
    } else {
        // Insert new position
        $posGroup = 'NON-TEACHING LEVEL I'; // Default
        if (strpos($positionGroupName, 'NON-TEACHING LEVEL II') !== false) $posGroup = 'NON-TEACHING LEVEL II';
        else if (strpos($positionGroupName, 'SCHOOL ADMINISTRATION') !== false) $posGroup = 'SCHOOL ADMINISTRATION';
        else if (strpos($positionGroupName, 'TEACHING') !== false) $posGroup = 'TEACHING';
        else if (strpos($positionGroupName, 'RELATED TEACHING') !== false) $posGroup = 'RELATED TEACHING';
        else if (strpos($positionGroupName, 'HIGHER TEACHING') !== false) $posGroup = 'HIGHER TEACHING';
        
        $stmt = $conn->prepare("INSERT INTO positions (position_name, position_group, description, created_at) VALUES (?, ?, ?, NOW())");
        $description = "Position created from evaluation form";
        $stmt->bind_param("sss", $positionApplied, $posGroup, $description);
        $stmt->execute();
        $positionId = $conn->insert_id;
    }
    $stmt->close();
    
    // Check if applicant exists or create new one
    $stmt = $conn->prepare("SELECT id FROM applicants WHERE name = ? AND position_applied_id = ? LIMIT 1");
    $stmt->bind_param("si", $applicantName, $positionId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $applicantId = $row['id'];
    } else {
        // Insert new applicant
        $posGroup = 'NON-TEACHING LEVEL I'; // Default
        if (strpos($positionGroupName, 'NON-TEACHING LEVEL II') !== false) $posGroup = 'NON-TEACHING LEVEL II';
        else if (strpos($positionGroupName, 'SCHOOL ADMINISTRATION') !== false) $posGroup = 'SCHOOL ADMINISTRATION';
        else if (strpos($positionGroupName, 'TEACHING') !== false) $posGroup = 'TEACHING';
        else if (strpos($positionGroupName, 'RELATED TEACHING') !== false) $posGroup = 'RELATED TEACHING';
        else if (strpos($positionGroupName, 'HIGHER TEACHING') !== false) $posGroup = 'HIGHER TEACHING';
        
        $stmt = $conn->prepare("INSERT INTO applicants (name, position_applied_id, position_group, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sis", $applicantName, $positionId, $posGroup);
        $stmt->execute();
        $applicantId = $conn->insert_id;
    }
    $stmt->close();
    
    // Save or update applicant qualifications
    $stmt = $conn->prepare("SELECT id FROM applicant_qualifications WHERE applicant_id = ? LIMIT 1");
    $stmt->bind_param("i", $applicantId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update existing qualifications
        $stmt = $conn->prepare("UPDATE applicant_qualifications SET 
            education_degree = ?,
            education_masters_units = ?,
            education_doctoral_units = ?,
            training_hours = ?,
            experience_months = ?,
            performance_rating = ?,
            outstanding_accomplishments = ?,
            application_of_education_level = ?,
            application_of_ld_level = ?,
            potential_level = ?,
            updated_at = NOW()
            WHERE applicant_id = ?");
        $stmt->bind_param("siidddiiiii", 
            $applicantEducationDegree,
            $applicantEducationMastersUnits,
            $applicantEducationDoctoralUnits,
            $applicantTraining,
            $applicantExperience,
            $applicantPerformance,
            $applicantOutstandingAccomplishments,
            $applicantApplicationOfEducation,
            $applicantApplicationOfLd,
            $applicantPotential,
            $applicantId
        );
        $stmt->execute();
    } else {
        // Insert new qualifications
        $stmt = $conn->prepare("INSERT INTO applicant_qualifications 
            (applicant_id, education_degree, education_masters_units, education_doctoral_units,
            training_hours, experience_months, performance_rating, outstanding_accomplishments,
            application_of_education_level, application_of_ld_level, potential_level, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("isiidddiiii", 
            $applicantId,
            $applicantEducationDegree,
            $applicantEducationMastersUnits,
            $applicantEducationDoctoralUnits,
            $applicantTraining,
            $applicantExperience,
            $applicantPerformance,
            $applicantOutstandingAccomplishments,
            $applicantApplicationOfEducation,
            $applicantApplicationOfLd,
            $applicantPotential
        );
        $stmt->execute();
    }
    $stmt->close();
    
    // Save or update evaluation record
    $evaluationId = null;
    $stmt = $conn->prepare("SELECT id FROM evaluations WHERE applicant_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("i", $applicantId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update existing evaluation
        $row = $result->fetch_assoc();
        $evaluationId = $row['id'];
        
        $stmt = $conn->prepare("UPDATE evaluations SET 
            position_id = ?,
            position_group = ?,
            total_score = ?,
            evaluation_date = CURDATE(),
            notes = ?,
            status = ?,
            updated_at = NOW()
            WHERE id = ?");
        $posGroup = $applicant['position_group'] ?? 'NON-TEACHING LEVEL I';
        $notes = "Evaluation updated from form";
        $evaluationStatus = 'pending';
        $stmt->bind_param("isddssi", 
            $positionId,
            $posGroup,
            $totalScore,
            $notes,
            $evaluationStatus,
            $evaluationId
        );
        $stmt->execute();
    } else {
        // Insert new evaluation
        $posGroup = $applicant['position_group'] ?? 'NON-TEACHING LEVEL I';
        $stmt = $conn->prepare("INSERT INTO evaluations 
            (applicant_id, position_id, position_group, total_score, evaluation_date, notes, status, created_at)
            VALUES (?, ?, ?, ?, CURDATE(), ?, ?, NOW())");
        $notes = "Evaluation created from form";
        $evaluationStatus = 'pending';
        $stmt->bind_param("iisdss", 
            $applicantId,
            $positionId,
            $posGroup,
            $totalScore,
            $notes,
            $evaluationStatus
        );
        $stmt->execute();
        $evaluationId = $conn->insert_id;
    }
    $stmt->close();
    
    // Delete old evaluation details if updating
    if ($evaluationId) {
        $stmt = $conn->prepare("DELETE FROM evaluation_details WHERE evaluation_id = ?");
        $stmt->bind_param("i", $evaluationId);
        $stmt->execute();
        $stmt->close();
        
        // Insert evaluation details (criteria breakdown)
        $criteria = [
            ['Education', $applicantEducationDegree, $appEduLevel, $baselineEducationDegree, $baseEduLevel, $educationIncrement, $weights['education'], $educationScore],
            ['Training', $applicantTraining . ' hours', $appTrainingLevel, $baselineTraining . ' hours', $baseTrainingLevel, $trainingIncrement, $weights['training'], $trainingScore],
            ['Experience', $applicantExperience . ' months', $appExperienceLevel, $baselineExperience . ' months', $baseExperienceLevel, $experienceIncrement, $weights['experience'], $experienceScore],
            ['Performance Rating', $applicantPerformance . '/5', 0, 'N/A', 0, 0, $weights['performance'], $performanceScore],
            ['Outstanding Accomplishments', $applicantOutstandingAccomplishments, 0, 'N/A', 0, 0, $weights['outstanding_accomplishments'], $outstandingAccomplishmentsScore],
            ['Application of Education', 'Level ' . $applicantApplicationOfEducation, 0, 'N/A', 0, 0, $weights['application_of_education'], $applicationOfEducationScore],
            ['Application of L&D', 'Level ' . $applicantApplicationOfLd, 0, 'N/A', 0, 0, $weights['application_of_ld'], $applicationOfLdScore],
            ['Potential', 'Level ' . $applicantPotential, 0, 'N/A', 0, 0, $weights['potential'], $potentialScore]
        ];
        
        $stmt = $conn->prepare("INSERT INTO evaluation_details 
            (evaluation_id, criterion, applicant_qualification, applicant_level, 
            baseline_qualification, baseline_level, increment, weight, final_score, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        
        foreach ($criteria as $crit) {
            $stmt->bind_param("issiisiid", 
                $evaluationId,
                $crit[0], // criterion (s)
                $crit[1], // applicant_qualification (s)
                $crit[2], // applicant_level (i)
                $crit[3], // baseline_qualification (s)
                $crit[4], // baseline_level (i)
                $crit[5], // increment (i)
                $crit[6], // weight (i)
                $crit[7]  // final_score (d)
            );
            $stmt->execute();
        }
        $stmt->close();
    }
    
    // Check if CAR entry exists for this position/applicant combination
    $stmt = $conn->prepare("SELECT id FROM comparative_assessment_results WHERE position_id = ? AND applicant_id = ? LIMIT 1");
    $stmt->bind_param("ii", $positionId, $applicantId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update existing CAR entry
        $row = $result->fetch_assoc();
        $carId = $row['id'];
        
        $stmt = $conn->prepare("UPDATE comparative_assessment_results SET 
            application_code = ?,
            education_score = ?,
            training_score = ?,
            experience_score = ?,
            performance_score = ?,
            outstanding_accomplishments_score = ?,
            application_of_education_score = ?,
            application_of_ld_score = ?,
            potential_score = ?,
            total_score = ?,
            assessment_date = CURDATE(),
            updated_at = NOW()
            WHERE id = ?");
        $stmt->bind_param("sdddddddddi", 
            $applicationCode,
            $educationScore,
            $trainingScore,
            $experienceScore,
            $performanceScore,
            $outstandingAccomplishmentsScore,
            $applicationOfEducationScore,
            $applicationOfLdScore,
            $potentialScore,
            $totalScore,
            $carId
        );
        $stmt->execute();
    } else {
        // Insert new CAR entry
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
    }
    $stmt->close();
    
    // Commit transaction
    $conn->commit();
    
    $_SESSION['banner_message'] = [
        'type' => 'success',
        'message' => "Evaluation saved successfully! Applicant: $applicantName, Total Score: " . number_format($totalScore, 2),
        'auto_hide' => true
    ];
    
    error_log("=== PROCESS_EVALUATION SUCCESS ===");
    error_log("Applicant: $applicantName, Total Score: $totalScore");
    error_log("Redirecting to view_evaluation_report.php");
    
} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    
    error_log("=== PROCESS_EVALUATION ERROR ===");
    error_log("Exception: " . $e->getMessage());
    
    $_SESSION['banner_message'] = [
        'type' => 'error',
        'message' => 'Database error: ' . $e->getMessage(),
        'auto_hide' => false
    ];
    
    header('Location: index.php');
    exit;
}

$conn->close();

// Store evaluation data in session for report generation
$_SESSION['evaluation_data'] = [
    'applicant_name' => $applicantName,
    'position_applied' => $positionApplied,
    'application_code' => $applicationCode,
    'schools_division_office' => $schoolsDivisionOffice,
    'contact_number' => $contactNumber,
    'job_group_sg_level' => $jobGroupSgLevel,
    'scores' => [
        'education' => $educationScore,
        'training' => $trainingScore,
        'experience' => $experienceScore,
        'performance' => $performanceScore,
        'outstanding_accomplishments' => $outstandingAccomplishmentsScore,
        'application_of_education' => $applicationOfEducationScore,
        'application_of_ld' => $applicationOfLdScore,
        'potential' => $potentialScore,
        'total' => $totalScore
    ]
];

// Redirect based on output format
// Always show report, never redirect back to form
header('Location: view_evaluation_report.php', true, 303); // 303 See Other prevents cache
exit;
?>

