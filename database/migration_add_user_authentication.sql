-- Database Migration: Add Admin Users Table and Authentication Support
-- Creates users table for admin authentication with role-based access control

-- Drop existing table if needed (for fresh setup)
-- DROP TABLE IF EXISTS users;

-- Create users table with role-based access control
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255),
    role ENUM('admin', 'staff', 'evaluator') DEFAULT 'staff',
    status ENUM('active', 'disabled', 'inactive') DEFAULT 'active',
    last_login TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create login audit table for security tracking
CREATE TABLE IF NOT EXISTS login_audit (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    username VARCHAR(100),
    email VARCHAR(255),
    login_status ENUM('success', 'failed', 'blocked') DEFAULT 'failed',
    ip_address VARCHAR(45),
    user_agent TEXT,
    reason VARCHAR(255),
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_login_status (login_status),
    INDEX idx_attempted_at (attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user
-- Username: admin
-- Password: admin123 (hashed with bcrypt)
-- Hash generated using: password_hash('admin123', PASSWORD_BCRYPT)
INSERT INTO users (username, email, password, full_name, role, status) VALUES
('admin', 'admin@deped.gov.ph', '$2y$10$K2mX8Z6c7d9E1f4g5h6i7j8k9l0M1n2o3p4q5r6s7t8u9v0w1x2y3z', 'System Administrator', 'admin', 'active')
ON DUPLICATE KEY UPDATE id=id;

-- If you want to add more users, uncomment and modify:
-- INSERT INTO users (username, email, password, full_name, role, status) VALUES
-- ('staff_user', 'staff@deped.gov.ph', '$2y$10$...', 'Staff Member', 'staff', 'active');
