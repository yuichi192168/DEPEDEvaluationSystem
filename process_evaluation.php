<?php
/**
 * Process Evaluation Request
 * DepEd HRMPSB Evaluation System
 */

session_start();

require_once 'includes/banners.php';
require_once 'classes/HRMPSBEvaluator.php';
require_once 'classes/IESReportGenerator.php';
require_once 'classes/IESExport.php';
require_once 'classes/ComparativeAssessmentReport.php';
require_once 'config/baseline_library.php';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
    // Initialize position variables
    $positionGroup = 'TEACHING POSITIONS'; // default
    $salaryGrade = null;
    $category = null;
    
    // Parse position group and salary grade from job_group_sg_level form field
    // Format: "Group TEACHING POSITIONS / Salary Grade 11" or similar
    $jobGroupInput = $_POST['job_group_sg_level'] ?? '';
    if (!empty($jobGroupInput)) {
        // Try to parse: "Group {POSITION_GROUP} / Salary Grade {SG}"
        preg_match('/Group\s+(.+?)\s*\/\s*Salary Grade\s+(\d+)/', $jobGroupInput, $matches);
        if (!empty($matches)) {
            $positionGroup = trim($matches[1]);
            $salaryGrade = intval($matches[2]);
        }
    }
    
    // Check if position_key is provided and load baseline (this can override parsed values)
    $positionKey = $_POST['position_key'] ?? 'custom';
    $baselineFromLibrary = null;
    
    if ($positionKey !== 'custom') {
        $baselineFromLibrary = getBaselineForPosition($positionKey);
        // Override position group if from library
        if ($baselineFromLibrary && isset($baselineFromLibrary['position_group'])) {
            $positionGroup = $baselineFromLibrary['position_group'];
        }
        if ($baselineFromLibrary && isset($baselineFromLibrary['salary_grade'])) {
            $salaryGrade = $baselineFromLibrary['salary_grade'];
        }
        if ($baselineFromLibrary && isset($baselineFromLibrary['category'])) {
            $category = $baselineFromLibrary['category'];
        }
    }
    
    // Initialize evaluator with position group and salary grade
    $evaluator = new HRMPSBEvaluator($positionGroup, $salaryGrade, $category);
    
    // Prepare applicant data
    $applicantData = [
        'name' => $_POST['applicant_name'] ?? '',
        'position' => $_POST['position_applied'] ?? '',
        'education' => [
            'degree' => $_POST['applicant_education_degree'] ?? '',
            'masters_units' => intval($_POST['applicant_education_masters_units'] ?? 0),
            'doctoral_units' => intval($_POST['applicant_education_doctoral_units'] ?? 0)
        ],
        'training' => floatval($_POST['applicant_training'] ?? 0),
        'experience' => floatval($_POST['applicant_experience'] ?? 0), // in months
        'performance' => floatval($_POST['applicant_performance'] ?? 0),
        'outstanding_accomplishments' => intval($_POST['applicant_outstanding_accomplishments'] ?? 0),
        'application_of_education' => intval($_POST['applicant_application_of_education'] ?? 0),
        'application_of_ld' => intval($_POST['applicant_application_of_ld'] ?? 0),
        'potential' => intval($_POST['applicant_potential'] ?? 0)
    ];
    
    // Prepare baseline data (use library baseline if available, otherwise use form data)
    if ($baselineFromLibrary) {
        $baselineData = [
            'education' => $baselineFromLibrary['education'],
            'training' => floatval($baselineFromLibrary['training'] ?? 0),
            'experience' => floatval($baselineFromLibrary['experience'] ?? 0),
            'performance' => floatval($baselineFromLibrary['performance'] ?? 0),
            'outstanding_accomplishments' => intval($baselineFromLibrary['outstanding_accomplishments'] ?? 0),
            'application_of_education' => intval($baselineFromLibrary['application_of_education'] ?? 0),
            'application_of_ld' => intval($baselineFromLibrary['application_of_ld'] ?? 0),
            'potential' => intval($baselineFromLibrary['potential'] ?? 0)
        ];
        
        // Allow manual override if form fields are filled
        if (!empty($_POST['baseline_education_degree'])) {
            $baselineData['education']['degree'] = $_POST['baseline_education_degree'];
        }
        if (isset($_POST['baseline_education_masters_units']) && $_POST['baseline_education_masters_units'] !== '') {
            $baselineData['education']['masters_units'] = intval($_POST['baseline_education_masters_units']);
        }
        if (isset($_POST['baseline_education_doctoral_units']) && $_POST['baseline_education_doctoral_units'] !== '') {
            $baselineData['education']['doctoral_units'] = intval($_POST['baseline_education_doctoral_units']);
        }
        if (isset($_POST['baseline_training']) && $_POST['baseline_training'] !== '') {
            $baselineData['training'] = floatval($_POST['baseline_training']);
        }
        if (isset($_POST['baseline_experience']) && $_POST['baseline_experience'] !== '') {
            $baselineData['experience'] = floatval($_POST['baseline_experience']);
        }
    } else {
        // Use form data
        $baselineData = [
            'education' => [
                'degree' => $_POST['baseline_education_degree'] ?? '',
                'masters_units' => intval($_POST['baseline_education_masters_units'] ?? 0),
                'doctoral_units' => intval($_POST['baseline_education_doctoral_units'] ?? 0)
            ],
            'training' => floatval($_POST['baseline_training'] ?? 0),
            'experience' => floatval($_POST['baseline_experience'] ?? 0), // in months
            'performance' => floatval($_POST['baseline_performance'] ?? 0),
            'outstanding_accomplishments' => intval($_POST['baseline_outstanding_accomplishments'] ?? 0),
            'application_of_education' => intval($_POST['baseline_application_of_education'] ?? 0),
            'application_of_ld' => intval($_POST['baseline_application_of_ld'] ?? 0),
            'potential' => intval($_POST['baseline_potential'] ?? 0)
        ];
    }
    
    // Perform evaluation
    $evaluation = $evaluator->evaluateApplicant($applicantData, $baselineData);
    
    // Save evaluation to database if requested
    $evaluationId = null;
    if (isset($_POST['save_to_database']) && $_POST['save_to_database'] === '1') {
        try {
            require_once 'classes/EvaluationStorage.php';
            $storage = new EvaluationStorage();
            $additionalDataForStorage = [
                'application_code' => $_POST['application_code'] ?? '',
                'schools_division_office' => $_POST['schools_division_office'] ?? '',
                'contact_number' => $_POST['contact_number'] ?? '',
                'job_group_sg_level' => $_POST['job_group_sg_level'] ?? '',
                'hrmpsb_chair' => $_POST['hrmpsb_chair'] ?? '',
                'notes' => $_POST['evaluation_notes'] ?? ''
            ];
            $evaluationId = $storage->saveEvaluation($evaluation, $additionalDataForStorage);
            // Evaluation saved successfully
        } catch (Exception $e) {
            // Log error but continue with report generation
            error_log("Failed to save evaluation: " . $e->getMessage());
        }
    }
    
    // Save to Comparative Assessment Results if requested
    if (isset($_POST['save_to_car']) && $_POST['save_to_car'] === '1') {
        try {
            $car = new ComparativeAssessmentReport();
            
            // Get or create applicant and position IDs
            $positionId = $_POST['position_id'] ?? null;
            $applicantId = $_POST['applicant_id'] ?? null;
            
            // If IDs not provided, create them
            if (!$positionId || !$applicantId) {
                $conn = DBConnection::getConnection();
                
                if (!$applicantId) {
                    // Create or find applicant
                    $applicantName = $_POST['applicant_name'] ?? 'Unknown';
                    $carPositionGroup = $_POST['position_group'] ?? 'A';
                    
                    $query = "SELECT id FROM applicants WHERE name = ? LIMIT 1";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('s', $applicantName);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $applicantId = $row['id'];
                    } else {
                        // Create new applicant
                        $query = "INSERT INTO applicants (name, position_group) VALUES (?, ?)";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param('ss', $applicantName, $carPositionGroup);
                        $stmt->execute();
                        $applicantId = $conn->insert_id;
                    }
                    $stmt->close();
                }
                
                if (!$positionId) {
                    // Create or find position
                    $positionName = $_POST['position_applied'] ?? 'Unknown';
                    
                    $query = "SELECT id FROM positions WHERE position_name = ? LIMIT 1";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('s', $positionName);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $positionId = $row['id'];
                    } else {
                        // Create new position
                        $carPositionGroup = $_POST['position_group'] ?? 'A';
                        $query = "INSERT INTO positions (position_name, position_group) VALUES (?, ?)";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param('ss', $positionName, $carPositionGroup);
                        $stmt->execute();
                        $positionId = $conn->insert_id;
                    }
                    $stmt->close();
                }
            }
            
            // Prepare scores for CAR
            $scores = [
                'application_code' => $_POST['application_code'] ?? '',
                'education' => $evaluation['individual_scores']['education'] ?? 0,
                'training' => $evaluation['individual_scores']['training'] ?? 0,
                'experience' => $evaluation['individual_scores']['experience'] ?? 0,
                'performance' => $evaluation['individual_scores']['performance'] ?? 0,
                'outstanding_accomplishments' => $evaluation['individual_scores']['outstanding_accomplishments'] ?? 0,
                'application_of_education' => $evaluation['individual_scores']['application_of_education'] ?? 0,
                'application_of_ld' => $evaluation['individual_scores']['application_of_ld'] ?? 0,
                'potential' => $evaluation['individual_scores']['potential'] ?? 0,
                'total_score' => $evaluation['total_score'] ?? 0,
                'background_yes' => $_POST['background_yes'] ?? false,
                'background_no' => $_POST['background_no'] ?? false,
                'for_appointment' => $_POST['for_appointment'] ?? false,
                'for_probation' => $_POST['for_probation'] ?? false
            ];
            
            $remarks = $_POST['car_remarks'] ?? '';
            $assessmentDate = $_POST['assessment_date'] ?? date('Y-m-d');
            
            // Pass evaluationId to fetch actual scores from evaluation_details
            if ($car->saveResult($positionId, $applicantId, $scores, $remarks, $assessmentDate, $evaluationId)) {
                $car->generateRankings($positionId);
                // CAR saved successfully
            }
        } catch (Exception $e) {
            error_log("Failed to save to CAR: " . $e->getMessage());
        }
    }
    
    // Prepare additional data for IES report
    $additionalData = [
        'application_code' => $_POST['application_code'] ?? '',
        'schools_division_office' => $_POST['schools_division_office'] ?? '',
        'contact_number' => $_POST['contact_number'] ?? '',
        'job_group_sg_level' => $_POST['job_group_sg_level'] ?? '',
        'hrmpsb_chair' => $_POST['hrmpsb_chair'] ?? '',
        'position_group' => $positionGroup,
        'salary_grade' => $salaryGrade, // Use the numeric salary grade already extracted above
        'category' => $category // Use the category already extracted above
    ];
    
    // If baseline from library, get salary grade
    if ($baselineFromLibrary && isset($baselineFromLibrary['salary_grade'])) {
        $additionalData['salary_grade'] = $baselineFromLibrary['salary_grade'];
    }
    if ($baselineFromLibrary && isset($baselineFromLibrary['category'])) {
        $additionalData['category'] = $baselineFromLibrary['category'];
    }
    
    // Output format
    $outputFormat = $_POST['output_format'] ?? 'html';
    
    // Handle export formats
    if (in_array($outputFormat, ['word', 'pdf', 'excel'])) {
        $exporter = new IESExport();
        $exporter->setPositionGroup($positionGroup);
        if (isset($additionalData['salary_grade'])) {
            $exporter->setSalaryGrade($additionalData['salary_grade']);
        }
        if (isset($additionalData['category'])) {
            $exporter->setCategory($additionalData['category']);
        }
        
        switch ($outputFormat) {
            case 'word':
                $exporter->exportToWord($evaluation, $additionalData);
                break;
            case 'pdf':
                $exporter->exportToPDF($evaluation, $additionalData);
                break;
            case 'excel':
                $exporter->exportToExcel($evaluation, $additionalData);
                break;
        }
        exit;
    }
    
    // Generate IES Report (HTML or Text)
    $reportGenerator = new IESReportGenerator();
    $reportGenerator->setPositionGroup($positionGroup);
    if (isset($additionalData['salary_grade'])) {
        $reportGenerator->setSalaryGrade($additionalData['salary_grade']);
    }
    if (isset($additionalData['category'])) {
        $reportGenerator->setCategory($additionalData['category']);
    }
    
    // Set success banner
    setBannerMessage('success', 'Evaluation saved successfully! ✓ Showing Individual Evaluation Sheet.', true);
    
    if ($outputFormat === 'html') {
        header('Content-Type: text/html; charset=UTF-8');
        echo $reportGenerator->generateIES($evaluation, $additionalData);
    } else {
        header('Content-Type: text/plain; charset=UTF-8');
        echo $reportGenerator->generateTextIES($evaluation, $additionalData);
    }
    
    exit;
    
    } catch (Exception $e) {
        // Log the error
        error_log("Evaluation processing error: " . $e->getMessage());
        
        // Set error banner and redirect to form
        setBannerMessage('error', 'Error processing evaluation: ' . htmlspecialchars($e->getMessage()), false);
        header('Location: index.php');
        exit;
    }
}
exit;

