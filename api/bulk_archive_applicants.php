<?php
/**
 * API Endpoint: Bulk Archive Applicants
 * POST /api/bulk_archive_applicants.php
 * PROTECTED: Admin authentication required
 */

session_start();
require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');
require_once(__DIR__ . '/../classes/ApplicantManager.php');

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid request'];

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
    
    $currentUser = $auth->getCurrentUser();
    
    // Get POST data
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (empty($input['applicant_ids']) || !is_array($input['applicant_ids'])) {
        $response['message'] = 'Missing or invalid applicant IDs';
        echo json_encode($response);
        exit;
    }
    
    $manager = new ApplicantManager($conn);
    
    $applicantIds = array_map('intval', $input['applicant_ids']);
    $reason = isset($input['reason']) ? trim($input['reason']) : '';
    $archivedBy = $currentUser['full_name'] ?? $currentUser['username'];
    
    $result = $manager->bulkArchive($applicantIds, $reason, $archivedBy);
    echo json_encode($result);
    
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
    echo json_encode($response);
}
?>
