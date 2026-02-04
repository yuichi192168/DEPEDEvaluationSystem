<?php
/**
 * Admin Credentials Setup Script
 * Verifies and creates/updates admin user with correct password hash
 * DepEd HRMPSB Evaluation System
 */

require_once(__DIR__ . '/classes/DBConnection.php');

$conn = DBConnection::getConnection();
if (!$conn) {
    die('Database connection failed');
}

$username = 'admin';
$email = 'admin@deped.gov.ph';
$plainPassword = 'admin123';
$fullName = 'System Administrator';

// Generate fresh bcrypt hash
$passwordHash = password_hash($plainPassword, PASSWORD_BCRYPT);

echo "=== Admin Credentials Setup ===\n\n";

echo "Username: " . $username . "\n";
echo "Email: " . $email . "\n";
echo "Password: " . $plainPassword . "\n";
echo "Full Name: " . $fullName . "\n\n";

echo "Generated Password Hash: " . $passwordHash . "\n\n";

// Check if users table exists
$checkTable = "SHOW TABLES LIKE 'users'";
$tableResult = $conn->query($checkTable);

if ($tableResult && $tableResult->num_rows === 0) {
    echo "[ERROR] Users table does not exist. Run database migration first.\n";
    $conn->close();
    exit;
}

echo "[OK] Users table exists.\n\n";

// Check if admin user exists
$checkUser = "SELECT id, username, email, role, status FROM users WHERE username = ?";
$stmt = $conn->prepare($checkUser);
if ($stmt) {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo "[FOUND] Admin user exists:\n";
        echo "  ID: " . $user['id'] . "\n";
        echo "  Username: " . $user['username'] . "\n";
        echo "  Email: " . $user['email'] . "\n";
        echo "  Role: " . $user['role'] . "\n";
        echo "  Status: " . $user['status'] . "\n\n";
        
        // Update password
        $updateQuery = "UPDATE users SET password = ? WHERE username = ?";
        $updateStmt = $conn->prepare($updateQuery);
        if ($updateStmt) {
            $updateStmt->bind_param('ss', $passwordHash, $username);
            if ($updateStmt->execute()) {
                echo "[SUCCESS] Password updated successfully.\n";
                echo "Admin user is now set up with correct credentials.\n\n";
                
                // Verify password works
                $verifyQuery = "SELECT password FROM users WHERE username = ?";
                $verifyStmt = $conn->prepare($verifyQuery);
                if ($verifyStmt) {
                    $verifyStmt->bind_param('s', $username);
                    $verifyStmt->execute();
                    $verifyResult = $verifyStmt->get_result();
                    if ($verifyResult->num_rows > 0) {
                        $row = $verifyResult->fetch_assoc();
                        $passwordVerify = password_verify($plainPassword, $row['password']);
                        echo "[VERIFY] Password verification: " . ($passwordVerify ? "SUCCESS" : "FAILED") . "\n";
                    }
                    $verifyStmt->close();
                }
            } else {
                echo "[ERROR] Failed to update password: " . $updateStmt->error . "\n";
            }
            $updateStmt->close();
        }
    } else {
        echo "[NOT FOUND] Admin user does not exist. Creating now...\n\n";
        
        // Insert admin user
        $insertQuery = "INSERT INTO users (username, email, password, full_name, role, status) VALUES (?, ?, ?, ?, 'admin', 'active')";
        $insertStmt = $conn->prepare($insertQuery);
        if ($insertStmt) {
            $insertStmt->bind_param('ssss', $username, $email, $passwordHash, $fullName);
            if ($insertStmt->execute()) {
                echo "[SUCCESS] Admin user created successfully.\n";
                echo "Admin ID: " . $insertStmt->insert_id . "\n\n";
                
                // Verify password works
                $verifyQuery = "SELECT password FROM users WHERE username = ?";
                $verifyStmt = $conn->prepare($verifyQuery);
                if ($verifyStmt) {
                    $verifyStmt->bind_param('s', $username);
                    $verifyStmt->execute();
                    $verifyResult = $verifyStmt->get_result();
                    if ($verifyResult->num_rows > 0) {
                        $row = $verifyResult->fetch_assoc();
                        $passwordVerify = password_verify($plainPassword, $row['password']);
                        echo "[VERIFY] Password verification: " . ($passwordVerify ? "SUCCESS" : "FAILED") . "\n";
                    }
                    $verifyStmt->close();
                }
            } else {
                echo "[ERROR] Failed to create admin user: " . $insertStmt->error . "\n";
            }
            $insertStmt->close();
        }
    }
    $stmt->close();
} else {
    echo "[ERROR] Database error: " . $conn->error . "\n";
}

echo "\n=== Setup Complete ===\n";
echo "You can now login with:\n";
echo "  Username: admin\n";
echo "  Password: admin123\n";
echo "\nLocation: /admin/login.php\n";

$conn->close();
?>
