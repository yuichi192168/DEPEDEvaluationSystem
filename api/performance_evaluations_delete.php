<?php
session_start();

require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');
require_once(__DIR__ . '/../classes/PerformanceEvaluationManager.php');

header('Content-Type: application/json');

$response = ['success' => false];

try {
    $conn = DBConnection::getConnection();
    $auth = new AuthenticationHelper($conn);

    if (!$auth->isAuthenticated()) {
        http_response_code(401);
        $response['message'] = 'Unauthorized: Login required';
        echo json_encode($response);
        exit;
    }

    if (!$auth->isAdmin()) {
        http_response_code(403);
        $response['message'] = 'Unauthorized: Admin access required for deleting';
        echo json_encode($response);
        exit;
    }

    $payload = json_decode(file_get_contents('php://input'), true);
    $id = isset($payload['id']) ? (int)$payload['id'] : 0;

    if ($id <= 0) {
        throw new RuntimeException('Invalid evaluation ID.');
    }

    $manager = new PerformanceEvaluationManager($conn);
    if (!$manager->deleteEvaluation($id)) {
        throw new RuntimeException('Failed to delete evaluation record.');
    }

    $response['success'] = true;
    $response['message'] = 'Evaluation record deleted successfully.';
    echo json_encode($response);
} catch (Throwable $e) {
    http_response_code(400);
    $response['message'] = $e->getMessage();
    echo json_encode($response);
}
