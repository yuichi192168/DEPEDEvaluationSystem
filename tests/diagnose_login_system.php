<?php
/**
 * Login System Diagnostic Script
 * Verifies all components needed for admin login to work correctly
 * DepEd HRMPSB Evaluation System
 */

require_once(__DIR__ . '/classes/DBConnection.php');

$diagnostics = [
    'database_connection' => false,
    'users_table_exists' => false,
    'login_audit_table_exists' => false,
    'admin_user_exists' => false,
    'admin_password_valid' => false,
    'admin_role_correct' => false,
    'admin_status_active' => false,
    'authentication_class_works' => false,
];

echo "╔════════════════════════════════════════════════════════════════════════╗\n";
echo "║             LOGIN SYSTEM DIAGNOSTIC REPORT                              ║\n";
echo "║             DepEd HRMPSB Evaluation System                              ║\n";
echo "╚════════════════════════════════════════════════════════════════════════╝\n\n";

// 1. Test Database Connection
echo "1. DATABASE CONNECTION\n";
echo "─────────────────────────────────────────────────────────────────────────\n";

$conn = DBConnection::getConnection();
if ($conn && $conn->ping()) {
    echo "✓ Database connection successful\n";
    $diagnostics['database_connection'] = true;
    echo "  Database: " . (defined('DB_NAME') ? DB_NAME : 'unknown') . "\n";
    echo "  Server: " . (defined('DB_SERVER') ? DB_SERVER : 'unknown') . "\n";
} else {
    echo "✗ Database connection FAILED\n";
    echo "  Check: classes/DBConnection.php configuration\n";
    echo "  Check: Database is running (XAMPP MySQL service)\n";
    exit(1);
}

echo "\n";

// 2. Check Users Table
echo "2. DATABASE TABLES\n";
echo "─────────────────────────────────────────────────────────────────────────\n";

$checkUsersTable = "SHOW TABLES LIKE 'users'";
$result = $conn->query($checkUsersTable);

if ($result && $result->num_rows > 0) {
    echo "✓ Users table exists\n";
    $diagnostics['users_table_exists'] = true;
    
    // Get table structure
    $structureResult = $conn->query("DESCRIBE users");
    if ($structureResult) {
        $columns = [];
        while ($row = $structureResult->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
        echo "  Columns: " . implode(', ', $columns) . "\n";
    }
} else {
    echo "✗ Users table DOES NOT EXIST\n";
    echo "  Action: Run database migration\n";
    echo "  File: database/migration_add_user_authentication.sql\n";
}

$checkAuditTable = "SHOW TABLES LIKE 'login_audit'";
$result2 = $conn->query($checkAuditTable);

if ($result2 && $result2->num_rows > 0) {
    echo "✓ Login audit table exists\n";
    $diagnostics['login_audit_table_exists'] = true;
} else {
    echo "✗ Login audit table DOES NOT EXIST\n";
    echo "  Action: Run database migration\n";
}

echo "\n";

// 3. Check Admin User
echo "3. ADMIN USER VERIFICATION\n";
echo "─────────────────────────────────────────────────────────────────────────\n";

if ($diagnostics['users_table_exists']) {
    $checkAdmin = "SELECT id, username, email, role, status, password FROM users WHERE username = 'admin'";
    $result3 = $conn->query($checkAdmin);
    
    if ($result3 && $result3->num_rows > 0) {
        $user = $result3->fetch_assoc();
        echo "✓ Admin user exists\n";
        $diagnostics['admin_user_exists'] = true;
        
        echo "  ID: " . $user['id'] . "\n";
        echo "  Username: " . $user['username'] . "\n";
        echo "  Email: " . $user['email'] . "\n";
        echo "  Role: " . $user['role'] . "\n";
        echo "  Status: " . $user['status'] . "\n";
        echo "  Password Hash: " . substr($user['password'], 0, 20) . "...\n";
        
        // Check password validity
        $testPassword = 'admin123';
        if (password_verify($testPassword, $user['password'])) {
            echo "✓ Password hash is VALID for 'admin123'\n";
            $diagnostics['admin_password_valid'] = true;
        } else {
            echo "✗ Password hash INVALID for 'admin123'\n";
            echo "  Expected hash starting with: $2y$10$\n";
            echo "  Actual hash type: " . substr($user['password'], 0, 4) . "\n";
            echo "  Action: Run setup_admin_credentials.php to regenerate\n";
        }
        
        // Check role
        if ($user['role'] === 'admin') {
            echo "✓ User role is ADMIN\n";
            $diagnostics['admin_role_correct'] = true;
        } else {
            echo "✗ User role is NOT ADMIN (found: " . $user['role'] . ")\n";
            echo "  Action: Update user role in database\n";
        }
        
        // Check status
        if ($user['status'] === 'active') {
            echo "✓ User status is ACTIVE\n";
            $diagnostics['admin_status_active'] = true;
        } else {
            echo "✗ User status is NOT ACTIVE (found: " . $user['status'] . ")\n";
            echo "  Action: Update user status in database\n";
        }
    } else {
        echo "✗ Admin user DOES NOT EXIST\n";
        echo "  Action: Run setup_admin_credentials.php to create\n";
        
        // Show existing users
        $existingUsers = $conn->query("SELECT username, email, role FROM users LIMIT 10");
        if ($existingUsers && $existingUsers->num_rows > 0) {
            echo "\n  Existing users in database:\n";
            while ($u = $existingUsers->fetch_assoc()) {
                echo "    - " . $u['username'] . " (" . $u['role'] . ")\n";
            }
        } else {
            echo "\n  No users in database at all.\n";
        }
    }
} else {
    echo "⚠ Skipped: Users table does not exist\n";
}

echo "\n";

// 4. Test Authentication Helper
echo "4. AUTHENTICATION HELPER CLASS\n";
echo "─────────────────────────────────────────────────────────────────────────\n";

try {
    require_once(__DIR__ . '/classes/AuthenticationHelper.php');
    $auth = new AuthenticationHelper($conn);
    echo "✓ AuthenticationHelper class loads successfully\n";
    
    // Test authentication method
    if ($diagnostics['admin_user_exists'] && $diagnostics['admin_password_valid']) {
        $result = $auth->authenticate('admin', 'admin123');
        
        if ($result['success']) {
            echo "✓ Authentication test PASSED\n";
            echo "  User: " . $result['user']['username'] . "\n";
            echo "  Role: " . $result['user']['role'] . "\n";
            $diagnostics['authentication_class_works'] = true;
        } else {
            echo "✗ Authentication test FAILED\n";
            echo "  Message: " . $result['message'] . "\n";
        }
    } else {
        echo "⚠ Skipped: Admin user or password not valid\n";
    }
} catch (Exception $e) {
    echo "✗ AuthenticationHelper class ERROR\n";
    echo "  Message: " . $e->getMessage() . "\n";
}

echo "\n";

// 5. Check File Permissions
echo "5. FILE PERMISSIONS\n";
echo "─────────────────────────────────────────────────────────────────────────\n";

$filesToCheck = [
    'admin/login.php' => 'Login page',
    'admin/index.php' => 'Admin dashboard',
    'classes/AuthenticationHelper.php' => 'Authentication class',
    'classes/DBConnection.php' => 'Database connection',
    'css/design-system.css' => 'Design system CSS',
];

foreach ($filesToCheck as $file => $description) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        $readable = is_readable($path);
        echo ($readable ? "✓" : "✗") . " " . $file . " (" . $description . ")\n";
    } else {
        echo "✗ " . $file . " (FILE NOT FOUND)\n";
    }
}

echo "\n";

// 6. Summary Report
echo "6. DIAGNOSTIC SUMMARY\n";
echo "─────────────────────────────────────────────────────────────────────────\n";

$passedChecks = array_filter($diagnostics);
$totalChecks = count($diagnostics);
$percentage = round((count($passedChecks) / $totalChecks) * 100);

echo "Status: " . $percentage . "% (" . count($passedChecks) . "/" . $totalChecks . " checks passed)\n\n";

if ($percentage === 100) {
    echo "✓✓✓ ALL CHECKS PASSED ✓✓✓\n";
    echo "Login system is configured correctly.\n";
    echo "You should be able to login with:\n";
    echo "  Username: admin\n";
    echo "  Password: admin123\n";
    echo "  URL: admin/login.php\n";
} elseif ($percentage >= 75) {
    echo "⚠ MOSTLY WORKING (Minor issues detected)\n";
    echo "Login may work but review failed checks above.\n";
} elseif ($percentage >= 50) {
    echo "⚠ PARTIAL ISSUES (Multiple problems detected)\n";
    echo "Login likely won't work. Follow recommended actions above.\n";
} else {
    echo "✗✗✗ CRITICAL ISSUES ✗✗✗\n";
    echo "Login system is not functional.\n";
    echo "Required actions:\n";
    echo "1. Run database migration\n";
    echo "2. Run setup_admin_credentials.php\n";
    echo "3. Check database connection\n";
}

echo "\n";

// 7. Detailed Issue Report
$issues = [];
foreach ($diagnostics as $check => $passed) {
    if (!$passed) {
        $issues[$check] = true;
    }
}

if (!empty($issues)) {
    echo "7. REQUIRED ACTIONS\n";
    echo "─────────────────────────────────────────────────────────────────────────\n";
    
    if (!$diagnostics['database_connection']) {
        echo "→ FIX DATABASE CONNECTION:\n";
        echo "  1. Ensure XAMPP MySQL is running\n";
        echo "  2. Check DBConnection.php credentials\n";
        echo "  3. Verify database exists\n\n";
    }
    
    if (!$diagnostics['users_table_exists']) {
        echo "→ CREATE USERS TABLE:\n";
        echo "  1. Open: database/migration_add_user_authentication.sql\n";
        echo "  2. Run in phpMyAdmin or MySQL CLI\n";
        echo "  3. Command: mysql -u root deped_evaluation < migration_add_user_authentication.sql\n\n";
    }
    
    if (!$diagnostics['admin_user_exists']) {
        echo "→ CREATE ADMIN USER:\n";
        echo "  1. Run: setup_admin_credentials.php\n";
        echo "  2. Or execute in MySQL:\n";
        echo "  3. INSERT INTO users VALUES ('admin', 'admin@deped.gov.ph', '[hash]', 'System Administrator', 'admin', 'active')\n\n";
    }
    
    if (!$diagnostics['admin_password_valid']) {
        echo "→ REGENERATE PASSWORD HASH:\n";
        echo "  1. Run: setup_admin_credentials.php\n";
        echo "  2. Verify using: php setup_admin_credentials.php\n\n";
    }
    
    if (!$diagnostics['admin_role_correct'] || !$diagnostics['admin_status_active']) {
        echo "→ UPDATE USER RECORD:\n";
        echo "  UPDATE users SET role='admin', status='active' WHERE username='admin';\n\n";
    }
}

echo "═══════════════════════════════════════════════════════════════════════════\n";
echo "Diagnostic Report Complete - " . date('Y-m-d H:i:s') . "\n";
echo "═══════════════════════════════════════════════════════════════════════════\n";

$conn->close();
?>
