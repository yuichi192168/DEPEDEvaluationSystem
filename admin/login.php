<?php
/**
 * Admin Login Page
 * Secure login for admin access
 * DepEd HRMPSB Evaluation System
 */

// Configure session settings before starting session
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', '28800');

session_start();
require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');

$conn = DBConnection::getConnection();
if (!$conn) {
    die('Database connection failed');
}

$auth = new AuthenticationHelper($conn);

// If already logged in and is admin, redirect to dashboard
if ($auth->isAdmin()) {
    header('Location: index.php');
    exit;
}

$error = '';
$loginAttempted = false;

// Enable error logging for debugging
error_log("=== LOGIN PAGE LOADED ===");
error_log("Current session data: " . print_r($_SESSION, true));
error_log("Is already admin: " . ($auth->isAdmin() ? 'YES' : 'NO'));

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailOrUsername = isset($_POST['email_or_username']) ? trim($_POST['email_or_username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    error_log("Login attempt - Username: {$emailOrUsername}");
    error_log("POST data: " . print_r($_POST, true));
    
    // Check IP blocking
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    if ($auth->isIPBlocked($ipAddress)) {
        $error = 'Too many failed login attempts. Please try again later.';
        $loginAttempted = true;
        error_log("Login blocked - IP: {$ipAddress}");
    } else {
        // Attempt authentication
        $result = $auth->authenticate($emailOrUsername, $password);
        $loginAttempted = true;
        
        error_log("Auth result: " . print_r($result, true));
        
        if ($result['success']) {
            $user = $result['user'];
            
            error_log("Auth successful - User ID: {$user['id']}, Role: {$user['role']}");
            
            // Check if user is admin
            if ($user['role'] !== 'admin') {
                $error = 'Unauthorized: Your account does not have admin access.';
                error_log("Role check failed - User role: {$user['role']}");
            } else {
                // Create session
                $sessionCreated = $auth->createSession($user);
                
                error_log("Session created: " . ($sessionCreated ? 'YES' : 'NO'));
                error_log("Session data after creation: " . print_r($_SESSION, true));
                error_log("Session ID: " . session_id());
                
                // Verify session was created
                if (!isset($_SESSION['user_id'])) {
                    error_log("ERROR: Session data not set after createSession()");
                    $error = 'Session creation failed. Please try again.';
                } else {
                    error_log("Redirecting to index.php");
                    
                    // Redirect to dashboard
                    header('Location: index.php');
                    exit;
                }
            }
        } else {
            $error = $result['message'];
            error_log("Auth failed - Message: {$error}");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - DepEd HRMPSB Evaluation System</title>
    <?php require_once(__DIR__ . '/../includes/favicon.php'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/banners.css">
    <link rel="stylesheet" href="../css/design-system.css">
    <link rel="stylesheet" href="../css/admin-enhanced.css">
    <style>
        body {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-primary-light) 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: var(--spacing-md);
        }

        .login-container {
            background: var(--bg-primary);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            padding: var(--spacing-2xl);
            max-width: 440px;
            width: 100%;
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: var(--spacing-2xl);
        }

        .login-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-primary-light) 100%);
            color: var(--text-inverse);
            border-radius: var(--radius-lg);
            margin-bottom: var(--spacing-lg);
            font-size: 36px;
        }

        .login-header h1 {
            color: var(--text-primary);
            font-size: var(--font-size-3xl);
            margin-bottom: var(--spacing-sm);
            font-weight: 700;
        }

        .login-header p {
            color: var(--text-secondary);
            font-size: var(--font-size-base);
            margin-bottom: var(--spacing-md);
            line-height: var(--line-height-relaxed);
        }

        .admin-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-primary-light) 100%);
            color: var(--text-inverse);
            padding: 0.375rem 1rem;
            border-radius: 20px;
            font-size: var(--font-size-xs);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 1.1rem;
            color: var(--text-muted);
            font-size: 14px;
            pointer-events: none;
            z-index: 1;
        }

        .form-control-login {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-sm);
            font-size: var(--font-size-base);
            font-family: var(--font-family);
            transition: all var(--transition-fast);
            background-color: var(--bg-primary);
            color: var(--text-primary);
            line-height: var(--line-height-normal);
        }

        .form-control-login:focus {
            outline: none;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(200, 48, 48, 0.1);
            background-color: var(--bg-primary);
            color: var(--text-primary);
        }

        .form-control-login::placeholder {
            color: var(--text-muted);
            opacity: 0.8;
        }

        .error-alert {
            display: flex;
            align-items: flex-start;
            gap: var(--spacing-md);
            background: var(--status-danger-bg);
            color: var(--status-danger);
            padding: var(--spacing-md) var(--spacing-lg);
            border-radius: var(--radius-md);
            margin-bottom: var(--spacing-lg);
            border: 1px solid rgba(220, 53, 69, 0.3);
            font-size: var(--font-size-sm);
            line-height: var(--line-height-relaxed);
        }
        }

        .error-icon {
            flex-shrink: 0;
            font-size: var(--icon-lg);
            margin-top: 2px;
        }

        .error-content {
            flex: 1;
            font-size: var(--font-size-sm);
            line-height: var(--line-height-normal);
        }

        .login-button {
            width: 100%;
            padding: var(--spacing-md);
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--primary-red-light) 100%);
            color: var(--text-light);
            border: none;
            border-radius: var(--radius-md);
            font-size: var(--font-size-base);
            font-weight: var(--font-weight-semibold);
            cursor: pointer;
            transition: all var(--transition-normal);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-sm);
        }

        .login-button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .login-button:active:not(:disabled) {
            transform: translateY(0);
        }

        .login-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .security-notice {
            display: flex;
            gap: var(--spacing-md);
            background: rgba(23, 162, 184, 0.1);
            border: 1px solid rgba(23, 162, 184, 0.3);
            padding: var(--spacing-md) var(--spacing-lg);
            border-radius: var(--radius-md);
            margin-bottom: var(--spacing-lg);
            font-size: var(--font-size-sm);
            color: var(--status-info);
            line-height: var(--line-height-normal);
        }

        .security-icon {
            flex-shrink: 0;
            font-size: var(--icon-lg);
        }

        .security-content strong {
            display: block;
            margin-bottom: var(--spacing-xs);
        }

        .remember-me {
            margin-top: var(--spacing-md);
            display: flex;
            align-items: center;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: var(--spacing-sm);
            cursor: pointer;
            accent-color: var(--primary-red);
        }

        .remember-me label {
            font-size: var(--font-size-sm);
            color: var(--text-secondary);
            font-weight: var(--font-weight-regular);
            cursor: pointer;
        }

        .login-footer {
            text-align: center;
            margin-top: var(--spacing-2xl);
            padding-top: var(--spacing-lg);
            border-top: 1px solid var(--border-color);
        }

        .login-footer p {
            color: var(--text-secondary);
            font-size: var(--font-size-sm);
            margin-bottom: var(--spacing-md);
        }

        .main-link {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            color: var(--primary-red);
            text-decoration: none;
            font-weight: var(--font-weight-semibold);
            transition: all var(--transition-normal);
        }

        .main-link:hover {
            color: var(--primary-red-dark);
            transform: translateX(4px);
        }

        .loading {
            display: none;
            text-align: center;
            padding: var(--spacing-lg);
            color: var(--text-secondary);
            font-size: var(--font-size-sm);
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-top-color: var(--primary-red);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: var(--spacing-sm);
            vertical-align: middle;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .footer-credits {
            text-align: center;
            margin-top: var(--spacing-lg);
            padding-top: var(--spacing-lg);
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
            font-size: var(--font-size-xs);
        }

        @media (max-width: 480px) {
            .login-container {
                padding: var(--spacing-lg) var(--spacing-md);
            }

            .login-header h1 {
                font-size: var(--font-size-xl);
            }

            .login-button {
                font-size: var(--font-size-sm);
            }

            .login-icon {
                width: 56px;
                height: 56px;
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h1>Admin Login</h1>
            <p>DepEd HRMPSB Evaluation System</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error-alert">
                <i class="fas fa-exclamation-circle error-icon"></i>
                <div class="error-content">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" class="login-form" id="loginForm">
            <div class="form-group">
                <label for="email_or_username" class="form-label">Email or Username</label>
                <div class="input-wrapper">
                    <input 
                        type="text" 
                        id="email_or_username" 
                        name="email_or_username"
                        class="form-control-login"
                        placeholder="Enter your email or username"
                        required
                        autofocus
                        <?php echo $loginAttempted ? 'value="' . htmlspecialchars($_POST['email_or_username'] ?? '') . '"' : ''; ?>
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        id="password" 
                        name="password"
                        class="form-control-login"
                        placeholder="Enter your password"
                        required
                    >
                </div>
            </div>

            <div class="remember-me">
                <input type="checkbox" id="rememberMe" name="remember_me" value="1">
                <label for="rememberMe">Remember me for 7 days</label>
            </div>

            <button type="submit" class="login-button" id="loginBtn">
                <i class="fas fa-sign-in-alt"></i>
                Sign In
            </button>

            <div class="loading" id="loading">
                <span class="spinner"></span>
                Verifying credentials...
            </div>
        </form>

        <div class="login-footer">
            <p>Return to Main System</p>
            <a href="../index.php" class="main-link">
                <i class="fas fa-arrow-left"></i>
                Back to Evaluation System
            </a>
        </div>

        <div class="footer-credits">
            <p>© 2026 DepEd HRMPSB Evaluation System | Admin Portal</p>
        </div>
    </div>

    <script>
        // Form submission handling
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email_or_username').value.trim();
            const password = document.getElementById('password').value;

            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return;
            }

            // Show loading state
            document.getElementById('loading').style.display = 'block';
            document.getElementById('loginBtn').disabled = true;
        });

        // Clear error message on input
        document.getElementById('email_or_username').addEventListener('focus', function() {
            const error = document.querySelector('.error-alert');
            if (error) {
                error.style.display = 'none';
            }
        });

        document.getElementById('password').addEventListener('focus', function() {
            const error = document.querySelector('.error-alert');
            if (error) {
                error.style.display = 'none';
            }
        });
    </script>
</body>
</html>
