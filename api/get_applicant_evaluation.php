<?php
/**
 * API Endpoint: Get Full Applicant Evaluation Details (IES)
 * GET /api/get_applicant_evaluation.php?id=1
 * PROTECTED: Admin authentication required
 * Returns complete Individual Evaluation Sheet data
 */

session_start();
require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');

header('Content-Type: application/json');

$response = ['success' => false, 'applicant' => null, 'evaluation' => null, 'details' => [], 'history' => []];

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
    
    // Get applicant info
    $stmt = $conn->prepare("
        SELECT a.*, p.position_name, p.salary_grade
        FROM applicants a
        LEFT JOIN positions p ON a.position_applied_id = p.id
        WHERE a.id = ?
        LIMIT 1
    ");
    $stmt->bind_param('i', $applicantId);
    $stmt->execute();
    $result = $stmt->get_result();
    $applicant = $result->fetch_assoc();
    $stmt->close();
    
    if (!$applicant) {
        $response['message'] = 'Applicant not found';
        echo json_encode($response);
        exit;
    }
    
    // Get latest evaluation
    $stmt = $conn->prepare("
        SELECT e.*, p.position_name
        FROM evaluations e
        LEFT JOIN positions p ON e.position_id = p.id
        WHERE e.applicant_id = ?
        ORDER BY e.created_at DESC
        LIMIT 1
    ");
    $stmt->bind_param('i', $applicantId);
    $stmt->execute();
    $result = $stmt->get_result();
    $evaluation = $result->fetch_assoc();
    $stmt->close();
    
    // Get evaluation details (criteria breakdown)
    $details = [];
    if ($evaluation) {
        $stmt = $conn->prepare("
            SELECT * FROM evaluation_details
            WHERE evaluation_id = ?
            ORDER BY id ASC
        ");
        $stmt->bind_param('i', $evaluation['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $details[] = $row;
        }
        $stmt->close();
    }
    
    // Get qualifications
    $stmt = $conn->prepare("
        SELECT * FROM applicant_qualifications
        WHERE applicant_id = ?
        LIMIT 1
    ");
    $stmt->bind_param('i', $applicantId);
    $stmt->execute();
    $result = $stmt->get_result();
    $qualifications = $result->fetch_assoc();
    $stmt->close();
    
    // Get archive history if available
    $history = [];
    $stmt = $conn->prepare("
        SELECT * FROM archived_applicants_audit
        WHERE applicant_id = ?
        ORDER BY archived_at DESC
        LIMIT 10
    ");
    $stmt->bind_param('i', $applicantId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
    $stmt->close();
    
    $response['success'] = true;
    $response['applicant'] = $applicant;
    $response['evaluation'] = $evaluation;
    $response['details'] = $details;
    $response['qualifications'] = $qualifications;
    $response['history'] = $history;
    
    echo json_encode($response);
    
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
    echo json_encode($response);
}
?>
