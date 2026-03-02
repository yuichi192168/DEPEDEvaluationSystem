<?php
session_start();

require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');
require_once(__DIR__ . '/../classes/PerformanceEvaluationManager.php');

header('Content-Type: application/json');

$response = [
    'success' => false,
    'records' => [],
];

try {
    $conn = DBConnection::getConnection();
    $auth = new AuthenticationHelper($conn);

    if (!$auth->isAuthenticated()) {
        http_response_code(401);
        $response['message'] = 'Unauthorized: Login required';
        echo json_encode($response);
        exit;
    }

    $currentUser = $auth->getCurrentUser();
    $search = isset($_GET['search']) ? trim((string)$_GET['search']) : '';
    $manager = new PerformanceEvaluationManager($conn);

    $records = $manager->listEvaluations((int)$currentUser['id'], $auth->isAdmin(), $search);

    $response['success'] = true;
    $response['records'] = $records;
    $response['current_user'] = [
        'id' => (int)$currentUser['id'],
        'role' => (string)$currentUser['role'],
        'name' => (string)($currentUser['full_name'] ?? $currentUser['username'] ?? ''),
    ];

    echo json_encode($response);
} catch (Throwable $e) {
    http_response_code(500);
    $response['message'] = 'Error loading records: ' . $e->getMessage();
    echo json_encode($response);
}
