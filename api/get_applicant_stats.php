<?php
/**
 * API Endpoint: Get Applicant Statistics
 * GET /api/get_applicant_stats.php
 * PROTECTED: Admin authentication required
 */

session_start();
require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');
require_once(__DIR__ . '/../classes/ApplicantManager.php');

header('Content-Type: application/json');

$response = ['success' => false, 'stats' => null];

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
    
    $manager = new ApplicantManager($conn);
    
    $stats = $manager->getStatistics();
    
    $response['success'] = true;
    $response['stats'] = $stats;
    
    echo json_encode($response);
    
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
    echo json_encode($response);
}
?>
