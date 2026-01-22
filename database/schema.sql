-- DepEd HRMPSB Evaluation System Database Schema
-- MySQL/MariaDB Database Structure

CREATE DATABASE IF NOT EXISTS deped_evaluation CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE deped_evaluation;

-- Table: positions
CREATE TABLE IF NOT EXISTS positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position_name VARCHAR(255) NOT NULL,
    position_group ENUM('A', 'B', 'C') NOT NULL,
    salary_grade VARCHAR(50),
    item_number VARCHAR(100),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_position_group (position_group)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: applicants
CREATE TABLE IF NOT EXISTS applicants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    position_applied_id INT,
    position_group ENUM('A', 'B', 'C') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (position_applied_id) REFERENCES positions(id) ON DELETE SET NULL,
    INDEX idx_name (name),
    INDEX idx_position_group (position_group)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: applicant_qualifications
CREATE TABLE IF NOT EXISTS applicant_qualifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT NOT NULL,
    -- Education
    education_degree VARCHAR(100),
    education_masters_units INT DEFAULT 0,
    education_doctoral_units INT DEFAULT 0,
    -- Training
    training_hours DECIMAL(10,2) DEFAULT 0,
    -- Experience
    experience_months DECIMAL(10,2) DEFAULT 0,
    -- Performance
    performance_rating DECIMAL(5,2) DEFAULT 0,
    -- Outstanding Accomplishments
    outstanding_accomplishments INT DEFAULT 0,
    -- Application of Education
    application_of_education_level INT DEFAULT 0,
    -- Application of L&D
    application_of_ld_level INT DEFAULT 0,
    -- Potential
    potential_level INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE CASCADE,
    INDEX idx_applicant_id (applicant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: baseline_qualifications
CREATE TABLE IF NOT EXISTS baseline_qualifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position_id INT NOT NULL,
    -- Education
    education_degree VARCHAR(100),
    education_masters_units INT DEFAULT 0,
    education_doctoral_units INT DEFAULT 0,
    -- Training
    training_hours DECIMAL(10,2) DEFAULT 0,
    -- Experience
    experience_months DECIMAL(10,2) DEFAULT 0,
    -- Performance
    performance_rating DECIMAL(5,2) DEFAULT 0,
    -- Outstanding Accomplishments
    outstanding_accomplishments INT DEFAULT 0,
    -- Application of Education
    application_of_education_level INT DEFAULT 0,
    -- Application of L&D
    application_of_ld_level INT DEFAULT 0,
    -- Potential
    potential_level INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE CASCADE,
    INDEX idx_position_id (position_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: evaluations
CREATE TABLE IF NOT EXISTS evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT NOT NULL,
    position_id INT,
    position_group ENUM('A', 'B', 'C') NOT NULL,
    total_score DECIMAL(10,2) DEFAULT 0,
    evaluation_date DATE NOT NULL,
    evaluator_name VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE CASCADE,
    FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE SET NULL,
    INDEX idx_applicant_id (applicant_id),
    INDEX idx_position_id (position_id),
    INDEX idx_evaluation_date (evaluation_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: evaluation_details
CREATE TABLE IF NOT EXISTS evaluation_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluation_id INT NOT NULL,
    criterion VARCHAR(100) NOT NULL,
    applicant_qualification TEXT,
    applicant_level INT DEFAULT 0,
    baseline_qualification TEXT,
    baseline_level INT DEFAULT 0,
    increment INT DEFAULT 0,
    weight INT DEFAULT 0,
    points DECIMAL(10,2) DEFAULT 0,
    final_score DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (evaluation_id) REFERENCES evaluations(id) ON DELETE CASCADE,
    INDEX idx_evaluation_id (evaluation_id),
    INDEX idx_criterion (criterion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: comparative_assessment_results
CREATE TABLE IF NOT EXISTS comparative_assessment_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position_id INT NOT NULL,
    applicant_id INT NOT NULL,
    application_code VARCHAR(100),
    education_score DECIMAL(10,2) DEFAULT 0,
    training_score DECIMAL(10,2) DEFAULT 0,
    experience_score DECIMAL(10,2) DEFAULT 0,
    performance_score DECIMAL(10,2) DEFAULT 0,
    outstanding_accomplishments_score DECIMAL(10,2) DEFAULT 0,
    application_of_education_score DECIMAL(10,2) DEFAULT 0,
    application_of_ld_score DECIMAL(10,2) DEFAULT 0,
    potential_score DECIMAL(10,2) DEFAULT 0,
    total_score DECIMAL(10,2) DEFAULT 0,
    rank INT,
    remarks TEXT,
    background_yes BOOLEAN DEFAULT FALSE,
    background_no BOOLEAN DEFAULT FALSE,
    for_appointment BOOLEAN DEFAULT FALSE,
    for_probation BOOLEAN DEFAULT FALSE,
    assessment_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE CASCADE,
    FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE CASCADE,
    INDEX idx_position_id (position_id),
    INDEX idx_applicant_id (applicant_id),
    INDEX idx_total_score (total_score),
    INDEX idx_rank (rank),
    UNIQUE KEY unique_position_applicant (position_id, applicant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample Data: Positions
INSERT INTO positions (position_name, position_group, description) VALUES
('Information and Communications Technology', 'A', 'Non-Teaching Level 1 - General'),
('Administrative Officer IV', 'B', 'Non-Teaching Level 2'),
('Senior Education Program Specialist', 'B', 'Non-Teaching Level 2'),
('Principal', 'C', 'School Administration');

-- Sample Data: Baseline Qualifications for ICT Position
INSERT INTO baseline_qualifications (position_id, education_degree, training_hours, experience_months)
SELECT id, 'Bachelor', 0, 0
FROM positions
WHERE position_name = 'Information and Communications Technology'
LIMIT 1;

