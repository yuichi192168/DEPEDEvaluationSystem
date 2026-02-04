<?php
/**
 * API Endpoint: Get Applicants
 * GET /api/get_applicants.php?status=active&search=&group=&limit=50&offset=0
 * PROTECTED: Admin authentication required
 */

session_start();
require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');
require_once(__DIR__ . '/../classes/ApplicantManager.php');

header('Content-Type: application/json');

$response = ['success' => false, 'applicants' => [], 'total' => 0];

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
    
    $status = isset($_GET['status']) ? trim($_GET['status']) : 'active'; // 'active' or 'archived'
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $group = isset($_GET['group']) ? trim($_GET['group']) : '';
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    
    $manager = new ApplicantManager($conn);
    
    if ($status === 'active') {
        $applicants = $manager->getActiveApplicants($search, $group, $limit, $offset);
        $total = $manager->getActiveApplicantsCount($search, $group);
    } else {
        $applicants = $manager->getArchivedApplicants($search, $group, $limit, $offset);
        $total = $manager->getArchivedApplicantsCount($search, $group);
    }
    
    $response['success'] = true;
    $response['applicants'] = $applicants;
    $response['total'] = $total;
    
    echo json_encode($response);
    
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
    echo json_encode($response);
}
?>
