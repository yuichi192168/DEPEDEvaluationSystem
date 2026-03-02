<?php
/**
 * Login API Endpoint
 * Authenticates users and returns session info with role
 */

session_start();
require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$conn = DBConnection::getConnection();
if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$auth = new AuthenticationHelper($conn);

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST; // Fallback to form data
}

$emailOrUsername = isset($input['username']) ? trim($input['username']) : '';
$password = isset($input['password']) ? $input['password'] : '';

if (empty($emailOrUsername) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Username and password are required']);
    exit;
}

// Check IP blocking
$ipAddress = $_SERVER['REMOTE_ADDR'];
if ($auth->isIPBlocked($ipAddress)) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many failed login attempts. Please try again later.']);
    exit;
}

// Attempt authentication
$result = $auth->authenticate($emailOrUsername, $password);

if ($result['success']) {
    $user = $result['user'];
    
    // Create session
    $sessionCreated = $auth->createSession($user);
    
    if (!$sessionCreated || !isset($_SESSION['user_id'])) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Session creation failed']);
        exit;
    }
    
    // Return success with user info and role
    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'full_name' => $user['full_name'] ?? $user['username'],
            'role' => $user['role']
        ],
        'isAdmin' => ($user['role'] === 'admin')
    ]);
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => $result['message']]);
}
