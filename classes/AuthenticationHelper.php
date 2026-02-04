<?php
/**
 * Authentication Helper Class
 * Manages user authentication, login, logout, and session management
 * DepEd HRMPSB Evaluation System
 */

if (!defined('DB_SERVER')) {
    require_once(__DIR__ . "/../initialize.php");
}

class AuthenticationHelper {
    
    private $conn;
    private $sessionTimeout = 28800; // 8 hours in seconds (increased for better UX)
    
    public function __construct($mysqli_connection) {
        $this->conn = $mysqli_connection;
    }
    
    /**
     * Authenticate user with email/username and password
     * @param string $emailOrUsername Email or username
     * @param string $password Plain text password
     * @return array Result with success status and message
     */
    public function authenticate($emailOrUsername, $password) {
        $result = [
            'success' => false,
            'message' => 'Invalid credentials',
            'user' => null
        ];
        
        // Validate input
        if (empty($emailOrUsername) || empty($password)) {
            $this->logLoginAttempt(null, $emailOrUsername, 'failed', 'Empty credentials');
            return $result;
        }
        
        // Find user by email or username
        $query = "SELECT id, username, email, password, role, status, full_name 
                  FROM users 
                  WHERE (email = ? OR username = ?) AND status = 'active'";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            $result['message'] = 'Database error';
            return $result;
        }
        
        $stmt->bind_param('ss', $emailOrUsername, $emailOrUsername);
        $stmt->execute();
        $queryResult = $stmt->get_result();
        
        if ($queryResult->num_rows === 0) {
            $this->logLoginAttempt(null, $emailOrUsername, 'failed', 'User not found or inactive');
            $stmt->close();
            return $result;
        }
        
        $user = $queryResult->fetch_assoc();
        $stmt->close();
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            $this->logLoginAttempt($user['id'], $emailOrUsername, 'failed', 'Invalid password');
            return $result;
        }
        
        // Log successful login
        $this->logLoginAttempt($user['id'], $emailOrUsername, 'success', 'Login successful');
        
        // Update last login timestamp
        $updateQuery = "UPDATE users SET last_login = NOW() WHERE id = ?";
        $updateStmt = $this->conn->prepare($updateQuery);
        if ($updateStmt) {
            $updateStmt->bind_param('i', $user['id']);
            $updateStmt->execute();
            $updateStmt->close();
        }
        
        // Return success
        $result['success'] = true;
        $result['message'] = 'Login successful';
        $result['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'role' => $user['role']
        ];
        
        return $result;
    }
    
    /**
     * Create authenticated session
     * @param array $user User data
     * @return bool Success status
     */
    public function createSession($user) {
        if (empty($user) || !isset($user['id'])) {
            return false;
        }
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login_time'] = time();
        $_SESSION['last_activity'] = time();
        
        return true;
    }
    
    /**
     * Check if user is authenticated
     * @return bool Is authenticated
     */
    public function isAuthenticated() {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']);
    }
    
    /**
     * Check if user is admin
     * @return bool Is admin
     */
    public function isAdmin() {
        return $this->isAuthenticated() && $_SESSION['role'] === 'admin';
    }
    
    /**
     * Check if user is authenticated and admin
     * If not, redirect to login
     * @param string $redirectUrl URL to redirect to on failure
     * @return bool Is admin
     */
    public function requireAdmin($redirectUrl = 'login.php') {
        if (!$this->isAdmin()) {
            // Build proper redirect URL
            // If relative path, make it relative to current directory
            if ($redirectUrl[0] !== '/' && strpos($redirectUrl, 'http') !== 0) {
                // For admin pages, redirect to login in same directory
                $redirectUrl = dirname($_SERVER['PHP_SELF']) . '/' . $redirectUrl;
            }
            header('Location: ' . $redirectUrl);
            exit;
        }
        
        // Check session timeout
        $this->checkSessionTimeout();
        
        // Update last activity time
        $_SESSION['last_activity'] = time();
        
        return true;
    }
    
    /**
     * Check session timeout
     * @return bool Is session still valid
     */
    public function checkSessionTimeout() {
        if (!isset($_SESSION['login_time'])) {
            return false;
        }
        
        $currentTime = time();
        $loginTime = $_SESSION['login_time'];
        
        if (($currentTime - $loginTime) > $this->sessionTimeout) {
            $this->logout();
            return false;
        }
        
        return true;
    }
    
    /**
     * Logout user
     * @return void
     */
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            // You could optionally log the logout action
            $this->logLoginAttempt($_SESSION['user_id'], $_SESSION['username'], 'success', 'Logout');
        }
        
        // Destroy session
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        session_destroy();
    }
    
    /**
     * Get current authenticated user
     * @return array|null User data or null
     */
    public function getCurrentUser() {
        if (!$this->isAuthenticated()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'email' => $_SESSION['email'] ?? null,
            'full_name' => $_SESSION['full_name'] ?? null,
            'role' => $_SESSION['role'] ?? null
        ];
    }
    
    /**
     * Log login attempt for audit
     * @param int $userId User ID (null for failed attempts)
     * @param string $emailOrUsername Email or username attempted
     * @param string $status Login status (success, failed, blocked)
     * @param string $reason Reason for attempt result
     * @return bool Success
     */
    private function logLoginAttempt($userId, $emailOrUsername, $status, $reason = '') {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $query = "INSERT INTO login_audit (user_id, username, email, login_status, ip_address, user_agent, reason) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param('issssss', $userId, $emailOrUsername, $emailOrUsername, $status, $ip, $userAgent, $reason);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    /**
     * Hash a password using bcrypt
     * @param string $password Plain text password
     * @return string Hashed password
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }
    
    /**
     * Get login audit logs
     * @param int $limit Number of records to fetch
     * @return array Audit logs
     */
    public function getLoginAuditLogs($limit = 50) {
        $query = "SELECT id, user_id, username, email, login_status, ip_address, reason, attempted_at 
                  FROM login_audit 
                  ORDER BY attempted_at DESC 
                  LIMIT ?";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return [];
        }
        
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $logs = [];
        while ($row = $result->fetch_assoc()) {
            $logs[] = $row;
        }
        
        $stmt->close();
        return $logs;
    }
    
    /**
     * Get failed login attempts for IP address
     * @param string $ipAddress IP address to check
     * @param int $minutes Time window in minutes
     * @return int Number of failed attempts
     */
    public function getFailedLoginAttempts($ipAddress, $minutes = 15) {
        $query = "SELECT COUNT(*) as count 
                  FROM login_audit 
                  WHERE ip_address = ? 
                  AND login_status = 'failed' 
                  AND attempted_at > DATE_SUB(NOW(), INTERVAL ? MINUTE)";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return 0;
        }
        
        $stmt->bind_param('si', $ipAddress, $minutes);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return isset($row['count']) ? (int)$row['count'] : 0;
    }
    
    /**
     * Block login attempts if too many failures
     * @param string $ipAddress IP address to check
     * @return bool Is IP blocked
     */
    public function isIPBlocked($ipAddress) {
        $failedAttempts = $this->getFailedLoginAttempts($ipAddress, 15);
        return $failedAttempts >= 5; // Block after 5 failed attempts in 15 minutes
    }
}
?>
