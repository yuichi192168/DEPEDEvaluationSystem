<?php
/**
 * API Endpoint: Get Applicant Details
 * GET /api/get_applicant_details.php?id=1
 * PROTECTED: Admin authentication required
 */

session_start();
require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');
require_once(__DIR__ . '/../classes/ApplicantManager.php');

header('Content-Type: application/json');

$response = ['success' => false, 'applicant' => null, 'history' => []];

try {
    $conn = DBConnection::getConnection();
    
    // Require admin authentication
    $auth = new AuthenticationHelper($conn);
    if (!$auth->isAdmin()) {
        $response['message'] = 'Unauthorized: Admin access required';
        http_response_code(403);
        echo json_encode($response);
        exit;
    }
    
    if (empty($_GET['id'])) {
        $response['message'] = 'Missing applicant ID';
        echo json_encode($response);
        exit;
    }
    
    $applicantId = intval($_GET['id']);
    
    $manager = new ApplicantManager($conn);
    
    $applicant = $manager->getApplicantDetails($applicantId);
    if (!$applicant) {
        $response['message'] = 'Applicant not found';
        echo json_encode($response);
        exit;
    }
    
    $history = $manager->getArchiveHistory($applicantId);
    
    $response['success'] = true;
    $response['applicant'] = $applicant;
    $response['history'] = $history;
    
    echo json_encode($response);
    
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
    echo json_encode($response);
}
?>
