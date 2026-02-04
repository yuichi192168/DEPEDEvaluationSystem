<?php
/**
 * Detailed Login Test Page
 * Tests login with debugging output
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

echo "<h1>Login Test - Detailed Debug</h1>";
echo "<hr>";

// Step 1: Check PHP version
echo "<h2>1. PHP Environment</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Session ID: " . session_id() . "<br>";
echo "Session Status: " . session_status() . " (1=disabled, 2=enabled)<br>";
echo "<hr>";

// Step 2: Check DB Connection
echo "<h2>2. Database Connection</h2>";
require_once(__DIR__ . '/classes/DBConnection.php');
$conn = DBConnection::getConnection();
if ($conn) {
    echo "✓ Connected to database: " . (defined('DB_NAME') ? DB_NAME : 'unknown') . "<br>";
    echo "✓ Connection object: " . get_class($conn) . "<br>";
} else {
    echo "✗ Database connection FAILED<br>";
    die();
}
echo "<hr>";

// Step 3: Check AuthenticationHelper
echo "<h2>3. AuthenticationHelper Class</h2>";
require_once(__DIR__ . '/classes/AuthenticationHelper.php');
$auth = new AuthenticationHelper($conn);
echo "✓ AuthenticationHelper loaded<br>";
echo "✓ Class methods available: " . implode(', ', get_class_methods($auth)) . "<br>";
echo "<hr>";

// Step 4: Simulate Login
echo "<h2>4. Simulated Login Test</h2>";
$username = 'admin';
$password = 'admin123';

echo "Testing credentials:<br>";
echo "- Username: <strong>{$username}</strong><br>";
echo "- Password: <strong>{$password}</strong><br><br>";

// Test authentication
$result = $auth->authenticate($username, $password);

echo "Authentication Result:<br>";
echo "<pre>";
print_r($result);
echo "</pre><br>";

if ($result['success']) {
    echo "<div style='background: #d4edda; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px;'>";
    echo "<strong>✓ AUTHENTICATION SUCCESSFUL</strong><br>";
    echo "User ID: " . $result['user']['id'] . "<br>";
    echo "Username: " . $result['user']['username'] . "<br>";
    echo "Role: " . $result['user']['role'] . "<br>";
    echo "</div><br>";
    
    // Step 5: Test session creation
    echo "<h2>5. Session Creation Test</h2>";
    $sessionCreated = $auth->createSession($result['user']);
    echo "Session created: " . ($sessionCreated ? 'YES' : 'NO') . "<br><br>";
    
    echo "Session data:<br>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre><br>";
    
    // Step 6: Test isAdmin()
    echo "<h2>6. isAdmin() Check</h2>";
    $isAdmin = $auth->isAdmin();
    echo "isAdmin() returned: " . ($isAdmin ? 'TRUE' : 'FALSE') . "<br>";
    
    if ($isAdmin) {
        echo "<div style='background: #d4edda; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px;'>";
        echo "<strong>✓ ADMIN CHECK PASSED</strong><br>";
        echo "You should be able to access admin/index.php<br>";
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px;'>";
        echo "<strong>✗ ADMIN CHECK FAILED</strong><br>";
        echo "Session role: " . ($_SESSION['role'] ?? 'NOT SET') . "<br>";
        echo "</div>";
    }
    
} else {
    echo "<div style='background: #f8d7da; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px;'>";
    echo "<strong>✗ AUTHENTICATION FAILED</strong><br>";
    echo "Error: " . $result['message'] . "<br>";
    echo "</div>";
}

echo "<hr>";

// Step 7: Direct form test
echo "<h2>7. Manual Login Form Test</h2>";
echo "<p>Try logging in with this form:</p>";

if (isset($_POST['test_login'])) {
    $testUser = $_POST['test_user'] ?? '';
    $testPass = $_POST['test_pass'] ?? '';
    
    echo "<div style='background: #e7f3ff; padding: 15px; border: 1px solid #b3d9ff; border-radius: 5px; margin-bottom: 15px;'>";
    echo "Form submitted with:<br>";
    echo "Username: <strong>{$testUser}</strong><br>";
    echo "Password: <strong>" . str_repeat('*', strlen($testPass)) . "</strong><br>";
    
    $testResult = $auth->authenticate($testUser, $testPass);
    
    if ($testResult['success']) {
        $auth->createSession($testResult['user']);
        echo "<br><strong style='color: green;'>✓ LOGIN SUCCESS!</strong><br>";
        echo "<a href='admin/index.php' style='display: inline-block; margin-top: 10px; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;'>Go to Admin Dashboard</a>";
    } else {
        echo "<br><strong style='color: red;'>✗ LOGIN FAILED</strong><br>";
        echo "Error: {$testResult['message']}";
    }
    echo "</div>";
}

?>

<form method="POST" style="background: #f8f9fa; padding: 20px; border-radius: 5px; max-width: 400px;">
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Username:</label>
        <input type="text" name="test_user" value="admin" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Password:</label>
        <input type="password" name="test_pass" value="admin123" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
    </div>
    <button type="submit" name="test_login" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
        Test Login
    </button>
</form>

<hr>

<h2>8. Quick Links</h2>
<ul>
    <li><a href="admin/login.php">Go to Admin Login Page</a></li>
    <li><a href="admin/index.php">Go to Admin Dashboard (requires login)</a></li>
    <li><a href="diagnose_login_system.php">Run Full Diagnostics</a></li>
</ul>
