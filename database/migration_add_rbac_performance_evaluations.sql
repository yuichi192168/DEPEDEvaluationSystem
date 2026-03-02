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


-- ============================================================
-- Baseline table for Qualification Standards + Performance Rules
-- ============================================================
CREATE TABLE IF NOT EXISTS reclassification_position_baselines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position_name VARCHAR(100) NOT NULL,
    form_type ENUM('form1', 'form2') NOT NULL,
    education TEXT NOT NULL,
    training TEXT NOT NULL,
    experience TEXT NOT NULL,
    eligibility TEXT NOT NULL,
    competency TEXT NULL,
    coi_vs INT NOT NULL DEFAULT 0,
    ncoi_vs INT NOT NULL DEFAULT 0,
    coi_o INT NOT NULL DEFAULT 0,
    ncoi_o INT NOT NULL DEFAULT 0,
    salary_grade INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_position_name (position_name),
    INDEX idx_form_type (form_type),
    INDEX idx_salary_grade (salary_grade)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Seed baseline records (upsert-safe)
INSERT INTO reclassification_position_baselines
(
    position_name,
    form_type,
    education,
    training,
    experience,
    eligibility,
    competency,
    coi_vs,
    ncoi_vs,
    coi_o,
    ncoi_o,
    salary_grade
)
VALUES
(
    'Teacher II',
    'form1',
    'Bachelor''s degree in Education; or Bachelor''s degree in relevant subject or learning area with at least 18 professional units in Education',
    '8 hours of training in any of or a cumulative of the following: Curriculum, Pedagogy, Subject Specialization acquired within the last 5 years',
    '1 year teaching experience',
    'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).',
    '',
    6,
    4,
    0,
    0,
    12
),
(
    'Teacher III',
    'form1',
    'Bachelor''s degree in Education; or Bachelor''s degree in relevant subject or learning area with at least 18 professional units in Education',
    '16 hours of training in any of or a cumulative of the following: Curriculum, Pedagogy, Subject Specialization acquired within the last 5 years',
    '2 years teaching experience',
    'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).',
    '',
    12,
    8,
    0,
    0,
    13
),
(
    'Teacher IV',
    'form1',
    'Bachelor''s degree in Education; or Bachelor''s degree in relevant subject or learning area with at least 18 professional units in Education',
    '16 hours of training in Curriculum, Pedagogy, Subject Specialization within the last 5 years; or completion of NEAP-requisite professional development program for Career Stage II',
    '3 years teaching experience',
    'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).',
    '',
    21,
    16,
    0,
    0,
    14
),
(
    'Teacher V',
    'form1',
    'Bachelor''s degree in Education; or Bachelor''s degree in relevant subject or learning area with at least 18 professional units in Education',
    '24 hours of training in Curriculum, Pedagogy, Subject Specialization within the last 5 years; or completion of NEAP-requisite professional development program',
    '3 years teaching experience',
    'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).',
    '',
    0,
    0,
    6,
    4,
    15
),
(
    'Teacher VI',
    'form1',
    'Bachelor''s degree in Education; or Bachelor''s degree in relevant subject or learning area with at least 18 professional units in Education',
    '24 hours of training in Curriculum, Pedagogy, Subject Specialization, Instructional Supervision within the last 5 years; or completion of NEAP-requisite professional development program',
    '4 years teaching experience',
    'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).',
    '',
    0,
    4,
    12,
    4,
    16
),
(
    'Teacher VII',
    'form1',
    'Bachelor''s degree in Education; or Bachelor''s degree in relevant subject or learning area with at least 18 professional units in Education',
    '32 hours of training in Curriculum, Pedagogy, Subject Specialization, Instructional Supervision within the last 5 years; or completion of NEAP-requisite professional development program',
    '4 years teaching experience',
    'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).',
    '',
    0,
    6,
    18,
    6,
    17
),
(
    'Master Teacher I',
    'form1',
    'Master''s degree in Education, or Educational Leadership, or Educational Management, or relevant subject or learning area',
    '24 hours of training in any of or a cumulative of the following: Curriculum, Pedagogy, Subject Specialization and 8 hours of training in Instructional Supervision acquired within the last 5 years; or completion of NEAP-requisite professional development program for Career Stage III (Highly Proficient Teacher)',
    '5 years teaching experience',
    'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).',
    '',
    0,
    8,
    21,
    8,
    18
),
(
    'Master Teacher II',
    'form2',
    'Master''s degree in Education, or Educational Leadership, or Educational Management, or relevant subject or learning area',
    '24 hours of training in any of or a cumulative of the following: Curriculum, Pedagogy, Subject Specialization and 8 hours of training in Instructional Supervision acquired within the last 5 years; or completion of NEAP-requisite professional development program for Career Stage III (Highly Proficient Teacher)',
    '5 years teaching experience and 1 year relevant experience in instructional supervision and technical assistance to teachers',
    'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).',
    '',
    0,
    5,
    10,
    5,
    19
),
(
    'Master Teacher III',
    'form2',
    'Master''s degree in Education, or Educational Leadership, or Educational Management, or relevant subject or learning area',
    '24 hours of training in any of or a cumulative of the following: Curriculum, Pedagogy, Subject Specialization and 8 hours of training in Instructional Supervision acquired within the last 5 years; or completion of NEAP-requisite professional development program for Career Stage IV (Distinguished Teacher)',
    '5 years teaching experience and 2 years relevant experience in instructional supervision and technical assistance to teachers',
    'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).',
    '',
    0,
    8,
    21,
    8,
    20
)
ON DUPLICATE KEY UPDATE
    form_type = VALUES(form_type),
    education = VALUES(education),
    training = VALUES(training),
    experience = VALUES(experience),
    eligibility = VALUES(eligibility),
    competency = VALUES(competency),
    coi_vs = VALUES(coi_vs),
    ncoi_vs = VALUES(ncoi_vs),
    coi_o = VALUES(coi_o),
    ncoi_o = VALUES(ncoi_o),
    salary_grade = VALUES(salary_grade),
    updated_at = CURRENT_TIMESTAMP;


-- Verify baselines
-- DESCRIBE reclassification_position_baselines;
-- SELECT position_name, form_type, salary_grade, coi_vs, ncoi_vs, coi_o, ncoi_o
-- FROM reclassification_position_baselines
-- ORDER BY salary_grade ASC;


-- Database Migration: Make created_by_user_id nullable for guest submissions
-- This allows unauthenticated users to submit evaluations

-- Step 1: Drop the existing UNIQUE constraint that includes created_by_user_id
ALTER TABLE performance_evaluations 
DROP INDEX uq_perf_name_item_user;

-- Step 2: Make created_by_user_id nullable
ALTER TABLE performance_evaluations 
MODIFY COLUMN created_by_user_id INT NULL;

-- Step 3: Recreate the UNIQUE constraint without created_by_user_id
-- This allows multiple guest submissions with the same name/item combination
ALTER TABLE performance_evaluations 
ADD CONSTRAINT uq_perf_name_item UNIQUE (name, item_number);

-- Step 4: Optionally add an index for better query performance on guest records
ALTER TABLE performance_evaluations 
ADD INDEX idx_perf_guest_records (created_by_user_id);

-- Verify the changes
-- DESCRIBE performance_evaluations;
-- SHOW CREATE TABLE performance_evaluations;
