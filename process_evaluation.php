<?php
/**
 * Process Evaluation Request
 * DepEd HRMPSB Evaluation System
 */

require_once 'classes/HRMPSBEvaluator.php';
require_once 'classes/IESReportGenerator.php';
require_once 'classes/IESExport.php';
require_once 'config/baseline_library.php';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get position group
    $positionGroup = $_POST['position_group'] ?? 'A';
    
    // Check if position_key is provided and load baseline
    $positionKey = $_POST['position_key'] ?? 'custom';
    $baselineFromLibrary = null;
    
    if ($positionKey !== 'custom') {
        $baselineFromLibrary = getBaselineForPosition($positionKey);
        // Override position group if from library
        if ($baselineFromLibrary && isset($baselineFromLibrary['position_group'])) {
            $positionGroup = $baselineFromLibrary['position_group'];
        }
    }
    
    // Initialize evaluator
    $evaluator = new HRMPSBEvaluator($positionGroup);
    
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
    
    // Prepare additional data for IES report
    $additionalData = [
        'application_code' => $_POST['application_code'] ?? '',
        'schools_division_office' => $_POST['schools_division_office'] ?? '',
        'contact_number' => $_POST['contact_number'] ?? '',
        'job_group_sg_level' => $_POST['job_group_sg_level'] ?? '',
        'hrmpsb_chair' => $_POST['hrmpsb_chair'] ?? ''
    ];
    
    // Output format
    $outputFormat = $_POST['output_format'] ?? 'html';
    
    // Handle export formats
    if (in_array($outputFormat, ['word', 'pdf', 'excel'])) {
        $exporter = new IESExport();
        
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
    
    if ($outputFormat === 'html') {
        header('Content-Type: text/html; charset=UTF-8');
        echo $reportGenerator->generateIES($evaluation, $additionalData);
    } else {
        header('Content-Type: text/plain; charset=UTF-8');
        echo $reportGenerator->generateTextIES($evaluation, $additionalData);
    }
    
    exit;
}

// If not POST, redirect to form
header('Location: index.php');
exit;

