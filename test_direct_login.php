<?php
/**
 * Direct Login Test
 * Tests actual login flow with real credentials
 */

// Start output buffering to prevent header issues
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Direct Login Flow Test</h1>";
echo "<hr>";

// Simulate login POST
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_POST['email_or_username'] = 'admin';
$_POST['password'] = 'admin123';

echo "<h2>Step 1: Simulating Login POST</h2>";
echo "POST data:<br>";
echo "- email_or_username: <strong>" . htmlspecialchars($_POST['email_or_username']) . "</strong><br>";
echo "- password: <strong>" . str_repeat('*', strlen($_POST['password'])) . "</strong><br>";
echo "<hr>";

// Configure session
echo "<h2>Step 2: Configuring Session</h2>";
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', '28800');
echo "Session settings configured<br>";
echo "- cookie_httponly: " . ini_get('session.cookie_httponly') . "<br>";
echo "- cookie_samesite: " . ini_get('session.cookie_samesite') . "<br>";
echo "- gc_maxlifetime: " . ini_get('session.gc_maxlifetime') . "<br>";
echo "<hr>";

session_start();
echo "<h2>Step 3: Session Started</h2>";
echo "Session ID: " . session_id() . "<br>";
echo "<hr>";

require_once(__DIR__ . '/classes/DBConnection.php');
require_once(__DIR__ . '/classes/AuthenticationHelper.php');

echo "<h2>Step 4: Classes Loaded</h2>";
echo "DBConnection and AuthenticationHelper loaded<br>";
echo "<hr>";

$conn = DBConnection::getConnection();
if (!$conn) {
    die('Database connection failed');
}

echo "<h2>Step 5: Database Connected</h2>";
echo "Connection successful<br>";
echo "<hr>";

$auth = new AuthenticationHelper($conn);
echo "<h2>Step 6: AuthenticationHelper Created</h2>";
echo "Ready to authenticate<br>";
echo "<hr>";

// Perform authentication
$emailOrUsername = $_POST['email_or_username'];
$password = $_POST['password'];

echo "<h2>Step 7: Attempting Authentication</h2>";
$result = $auth->authenticate($emailOrUsername, $password);

echo "Authentication result:<br>";
echo "<pre>";
print_r($result);
echo "</pre>";

if ($result['success']) {
    echo "<div style='background: #d4edda; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px;'>";
    echo "<strong>✓ AUTHENTICATION SUCCESSFUL</strong><br>";
    echo "</div><br>";
    
    echo "<h2>Step 8: Creating Session</h2>";
    $user = $result['user'];
    $sessionCreated = $auth->createSession($user);
    
    echo "Session creation result: " . ($sessionCreated ? 'SUCCESS' : 'FAILED') . "<br><br>";
    
    echo "Session data after creation:<br>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    
    echo "<h2>Step 9: Checking isAdmin()</h2>";
    $isAdmin = $auth->isAdmin();
    echo "isAdmin() result: " . ($isAdmin ? '<strong style="color: green;">TRUE</strong>' : '<strong style="color: red;">FALSE</strong>') . "<br><br>";
    
    echo "<h2>Step 10: Checking Role</h2>";
    echo "User role from result: <strong>" . $user['role'] . "</strong><br>";
    echo "Session role: <strong>" . ($_SESSION['role'] ?? 'NOT SET') . "</strong><br><br>";
    
    if ($user['role'] !== 'admin') {
        echo "<div style='background: #f8d7da; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px;'>";
        echo "<strong>✗ ROLE CHECK FAILED</strong><br>";
        echo "User role is '{$user['role']}' but should be 'admin'<br>";
        echo "</div>";
    } else {
        echo "<div style='background: #d4edda; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px;'>";
        echo "<strong>✓ ROLE CHECK PASSED</strong><br>";
        echo "User has admin role<br>";
        echo "</div><br>";
        
        echo "<h2>Step 11: Where Would Redirect Go?</h2>";
        $redirectUrl = 'index.php';
        $currentDir = dirname($_SERVER['SCRIPT_FILENAME']);
        $indexPath = $currentDir . '/admin/index.php';
        
        echo "Would redirect to: <strong>{$redirectUrl}</strong><br>";
        echo "Full path would be: <strong>{$indexPath}</strong><br>";
        echo "File exists: " . (file_exists($indexPath) ? 'YES' : 'NO') . "<br><br>";
        
        echo "<div style='background: #d1ecf1; padding: 20px; border: 1px solid #bee5eb; border-radius: 5px;'>";
        echo "<h3>✓ LOGIN WOULD SUCCEED</h3>";
        echo "<p>All checks passed. The login should work in the real form.</p>";
        echo "<p><a href='admin/login.php' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Try Actual Login Page</a></p>";
        echo "</div>";
    }
    
} else {
    echo "<div style='background: #f8d7da; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px;'>";
    echo "<strong>✗ AUTHENTICATION FAILED</strong><br>";
    echo "Error: " . $result['message'] . "<br>";
    echo "</div>";
    
    echo "<h2>Debug: Check User in Database</h2>";
    $query = "SELECT id, username, email, role, status FROM users WHERE username = 'admin'";
    $dbResult = $conn->query($query);
    if ($dbResult && $dbResult->num_rows > 0) {
        $user = $dbResult->fetch_assoc();
        echo "<pre>";
        print_r($user);
        echo "</pre>";
    } else {
        echo "User 'admin' not found in database!<br>";
    }
}

ob_end_flush();
?>
