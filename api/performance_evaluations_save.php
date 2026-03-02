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

    $payload = json_decode(file_get_contents('php://input'), true);
    if (!is_array($payload)) {
        throw new RuntimeException('Invalid request payload.');
    }

    $required = ['name', 'current_position', 'position_applied', 'station', 'item_number', 'result'];
    foreach ($required as $field) {
        if (!isset($payload[$field]) || trim((string)$payload[$field]) === '') {
            throw new RuntimeException('Missing required field: ' . $field);
        }
    }

    $currentUser = $auth->getCurrentUser();
    $userId = (int)$currentUser['id'];
    $isAdmin = $auth->isAdmin();

    $normalizedData = [
        'name' => trim((string)$payload['name']),
        'current_position' => trim((string)$payload['current_position']),
        'position_applied' => trim((string)$payload['position_applied']),
        'station' => trim((string)$payload['station']),
        'item_number' => trim((string)$payload['item_number']),
        'result' => strtoupper(trim((string)$payload['result'])),
        'performance_payload' => isset($payload['performance_payload']) && is_array($payload['performance_payload'])
            ? $payload['performance_payload']
            : [],
    ];

    $manager = new PerformanceEvaluationManager($conn);

    $evaluationId = isset($payload['id']) ? (int)$payload['id'] : 0;

    if ($evaluationId > 0) {
        if (!$isAdmin) {
            http_response_code(403);
            $response['message'] = 'Unauthorized: Admin access required for editing';
            echo json_encode($response);
            exit;
        }

        if ($manager->hasDuplicate($normalizedData['name'], $normalizedData['item_number'], $userId, true, $evaluationId)) {
            throw new RuntimeException('Duplicate evaluation for same name and item number.');
        }

        $ok = $manager->updateEvaluation($evaluationId, $normalizedData);
        if (!$ok) {
            throw new RuntimeException('Failed to update evaluation record.');
        }

        $response['success'] = true;
        $response['message'] = 'Evaluation record updated successfully.';
        $response['id'] = $evaluationId;
        echo json_encode($response);
        exit;
    }

    if ($manager->hasDuplicate($normalizedData['name'], $normalizedData['item_number'], $userId, $isAdmin)) {
        throw new RuntimeException('Duplicate evaluation for same name and item number.');
    }

    $newId = $manager->createEvaluation($normalizedData, $userId);

    $response['success'] = true;
    $response['message'] = 'Evaluation record saved successfully.';
    $response['id'] = $newId;
    echo json_encode($response);
} catch (Throwable $e) {
    http_response_code(400);
    $response['message'] = $e->getMessage();
    echo json_encode($response);
}
