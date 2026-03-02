-- Database Migration: Reclassification RBAC Performance Evaluations
-- Compatible with current users table (users.id INT)

-- IMPORTANT:
-- Use this only if performance_evaluations table does NOT exist yet.
-- If table already exists and has data, use ALTER statements instead of DROP.
-- DROP TABLE IF EXISTS performance_evaluations;

CREATE TABLE IF NOT EXISTS performance_evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    current_position VARCHAR(255) NOT NULL,
    position_applied VARCHAR(255) NOT NULL,
    station VARCHAR(255) NULL,
    item_number VARCHAR(100) NOT NULL,
    result ENUM('PASSED', 'FAILED', 'N/A') NOT NULL DEFAULT 'N/A',
    performance_payload LONGTEXT NULL,
    created_by_user_id INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_performance_evaluations_user
        FOREIGN KEY (created_by_user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY uq_perf_name_item_user (name, item_number, created_by_user_id),
    INDEX idx_perf_name (name),
    INDEX idx_perf_created_by (created_by_user_id),
    INDEX idx_perf_result (result),
    INDEX idx_perf_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional sample row
-- INSERT INTO performance_evaluations
-- (name, current_position, position_applied, station, item_number, result, performance_payload, created_by_user_id)
-- SELECT
--   'Sample Teacher',
--   'Teacher II',
--   'Teacher III',
--   'Central Elementary School',
--   'ITEM-001',
--   'PASSED',
--   '{"notes":"sample"}',
--   id
-- FROM users
-- WHERE role = 'admin'
-- LIMIT 1;

-- Verify
-- DESCRIBE performance_evaluations;
-- SHOW CREATE TABLE performance_evaluations;


-- NOTE:
-- Qualification Standards (QS), performance rules, and salary grades are hardcoded
-- in the PHP application (reclassification_form.php), not stored in database tables.
