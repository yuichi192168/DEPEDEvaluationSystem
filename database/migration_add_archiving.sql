-- Database Migration: Add Archiving Support
-- Adds archive functionality to the applicants table
-- Execute this migration to enable applicant archiving

-- Add archive_status column to applicants table if it doesn't exist
ALTER TABLE applicants 
ADD COLUMN IF NOT EXISTS archive_status ENUM('active', 'archived') DEFAULT 'active' AFTER position_group,
ADD COLUMN IF NOT EXISTS archived_at TIMESTAMP NULL DEFAULT NULL AFTER archive_status,
ADD COLUMN IF NOT EXISTS archive_reason VARCHAR(255) NULL AFTER archived_at,
ADD INDEX idx_archive_status (archive_status);

-- Create archived_applicants_audit table to track archiving history
CREATE TABLE IF NOT EXISTS archived_applicants_audit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT NOT NULL,
    applicant_name VARCHAR(255) NOT NULL,
    action ENUM('archived', 'restored') NOT NULL,
    reason VARCHAR(255) NULL,
    archived_by VARCHAR(255) NULL,
    archived_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT NULL,
    FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE CASCADE,
    INDEX idx_applicant_id (applicant_id),
    INDEX idx_action (action),
    INDEX idx_archived_at (archived_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
