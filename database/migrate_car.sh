#!/bin/bash
# CAR System Database Migration Script
# Run this to add the CAR table to your existing database

# Update the following with your database credentials
DB_HOST="localhost"
DB_USER="root"
DB_PASS=""
DB_NAME="deped_evaluation"

# Create the comparative_assessment_results table
mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME << EOF

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

-- Sample data for testing (optional)
-- Insert sample results for demonstration
INSERT INTO comparative_assessment_results 
(position_id, applicant_id, application_code, education_score, training_score, experience_score, 
 performance_score, outstanding_accomplishments_score, application_of_education_score, 
 application_of_ld_score, potential_score, total_score, rank, remarks, background_yes, 
 background_no, for_appointment, for_probation, assessment_date)
SELECT 1, a.id, 'ICT-2026-001', 8.50, 12.00, 15.00, 13.80, 0, 0, 0, 13.80, 63.10, 1, 
        'Highly qualified candidate', 1, 0, 1, 0, CURDATE()
FROM applicants a WHERE a.name LIKE '%Trinidad%' LIMIT 1;

INSERT INTO comparative_assessment_results 
(position_id, applicant_id, application_code, education_score, training_score, experience_score, 
 performance_score, outstanding_accomplishments_score, application_of_education_score, 
 application_of_ld_score, potential_score, total_score, rank, remarks, background_yes, 
 background_no, for_appointment, for_probation, assessment_date)
SELECT 1, a.id, 'ICT-2026-003', 5.00, 8.00, 15.00, 0.00, 0, 0, 0, 17.83, 30.83, 2, 
        'Needs improvement in performance', 1, 0, 0, 1, CURDATE()
FROM applicants a WHERE a.name LIKE '%Opiña%' LIMIT 1;

EOF

echo "CAR table migration completed successfully!"
