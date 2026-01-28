<?php
/**
 * Baseline Qualification Standards Library
 * Based on DepEd Order No. 007, s. 2023
 * 
 * This library contains standard baseline levels for common DepEd positions.
 * Note: These should be verified against CSC-approved QS for specific stations.
 */

$baselineLibrary = [
    // ========== SCHOOL ADMINISTRATION POSITIONS ==========
    'principal_iv' => [
        'position_name' => 'School Principal IV',
        'position_group' => 'C',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'principal_iii' => [
        'position_name' => 'School Principal III',
        'position_group' => 'C',
        'salary_grade' => 21,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'ass_principal_iii' => [
        'position_name' => 'Assistant School Principal III',
        'position_group' => 'C',
        'salary_grade' => 20,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'principal_ii' => [
        'position_name' => 'School Principal II',
        'position_group' => 'C',
        'salary_grade' => 20,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'special_principal_ii' => [
        'position_name' => 'Special School Principal II',
        'position_group' => 'C',
        'salary_grade' => 20,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'ass_principal_ii' => [
        'position_name' => 'Assistant School Principal II',
        'position_group' => 'C',
        'salary_grade' => 19,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'head_teacher_vi' => [
        'position_name' => 'Head Teacher VI',
        'position_group' => 'C',
        'salary_grade' => 19,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'principal_i' => [
        'position_name' => 'School Principal I',
        'position_group' => 'C',
        'salary_grade' => 19,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'special_principal_i' => [
        'position_name' => 'Special School Principal I',
        'position_group' => 'C',
        'salary_grade' => 19,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'ass_principal_i' => [
        'position_name' => 'Assistant School Principal I',
        'position_group' => 'C',
        'salary_grade' => 18,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'ass_special_principal' => [
        'position_name' => 'Assistant Special School Principal',
        'position_group' => 'C',
        'salary_grade' => 18,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'head_teacher_v' => [
        'position_name' => 'Head Teacher V',
        'position_group' => 'B',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 18, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'head_teacher_iv' => [
        'position_name' => 'Head Teacher IV',
        'position_group' => 'B',
        'salary_grade' => 17,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 18, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'head_teacher_iii' => [
        'position_name' => 'Head Teacher III',
        'position_group' => 'B',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 18, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'head_teacher_ii' => [
        'position_name' => 'Head Teacher II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'head_teacher_i' => [
        'position_name' => 'Head Teacher I',
        'position_group' => 'B',
        'salary_grade' => 14,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    
    // ========== RELATED TEACHING POSITIONS ==========
    'chief_eps' => [
        'position_name' => 'Chief Education Program Specialist',
        'position_group' => 'C',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'edu_program_supervisor' => [
        'position_name' => 'Education Program Supervisor',
        'position_group' => 'C',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'public_schools_dist_supervisor' => [
        'position_name' => 'Public Schools District Supervisor',
        'position_group' => 'C',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'supervising_eps' => [
        'position_name' => 'Supervising Education Program Specialist',
        'position_group' => 'C',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'senior_eps' => [
        'position_name' => 'Senior Education Program Specialist',
        'position_group' => 'B',
        'salary_grade' => 19,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'senior_science_specialist' => [
        'position_name' => 'Senior Science Research Specialist',
        'position_group' => 'B',
        'salary_grade' => 19,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 18, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'voc_inst_supervisor_iii' => [
        'position_name' => 'Vocational Instruction Supervisor III',
        'position_group' => 'B',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'voc_inst_supervisor_ii' => [
        'position_name' => 'Vocational Instruction Supervisor II',
        'position_group' => 'B',
        'salary_grade' => 17,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'edu_program_specialist_ii' => [
        'position_name' => 'Education Program Specialist II',
        'position_group' => 'B',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'guidance_coordinator_iii' => [
        'position_name' => 'Guidance Coordinator III',
        'position_group' => 'B',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'science_specialist_ii' => [
        'position_name' => 'Science Research Specialist II',
        'position_group' => 'B',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 18, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'science_technician_iv' => [
        'position_name' => 'Science Research Technician IV',
        'position_group' => 'B',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'voc_inst_supervisor_i' => [
        'position_name' => 'Vocational Instruction Supervisor I',
        'position_group' => 'B',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'guidance_coordinator_ii' => [
        'position_name' => 'Guidance Coordinator II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'school_farming_coord_iii' => [
        'position_name' => 'School Farming Coordinator III',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'teacher_credentials_eval_ii' => [
        'position_name' => 'Teacher Credentials Evaluator II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'guidance_counselor_iii' => [
        'position_name' => 'Guidance Counselor III',
        'position_group' => 'A',
        'salary_grade' => 13,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'guidance_counselor_i' => [
        'position_name' => 'Guidance Counselor I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'teacher_i' => [
        'position_name' => 'Teacher I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    
    // ========== NON-TEACHING POSITIONS ==========
    'attorney_v' => [
        'position_name' => 'Attorney V',
        'position_group' => 'C',
        'salary_grade' => 25,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'chief_accountant' => [
        'position_name' => 'Chief Accountant',
        'position_group' => 'C',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'chief_admin_officer' => [
        'position_name' => 'Chief Administrative Officer',
        'position_group' => 'C',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'chief_edu_supervisor' => [
        'position_name' => 'Chief Education Supervisor',
        'position_group' => 'C',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'chief_health_program_officer' => [
        'position_name' => 'Chief Health Program Officer',
        'position_group' => 'C',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'engineer_v' => [
        'position_name' => 'Engineer V',
        'position_group' => 'C',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'ict_officer_iii' => [
        'position_name' => 'Information Technology Officer III',
        'position_group' => 'C',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'internal_auditor_v' => [
        'position_name' => 'Internal Auditor V',
        'position_group' => 'C',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'planning_officer_v' => [
        'position_name' => 'Planning Officer V',
        'position_group' => 'C',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'project_dev_officer_v' => [
        'position_name' => 'Project Development Officer V',
        'position_group' => 'C',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'teachers_camp_superintendent' => [
        'position_name' => 'Teachers\' Camp Superintendent',
        'position_group' => 'C',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'attorney_iv' => [
        'position_name' => 'Attorney IV',
        'position_group' => 'B',
        'salary_grade' => 23,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'medical_officer_iv' => [
        'position_name' => 'Medical Officer IV',
        'position_group' => 'B',
        'salary_grade' => 23,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'voc_admin_ii' => [
        'position_name' => 'Vocational School Administrator II',
        'position_group' => 'B',
        'salary_grade' => 23,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_aide' => [
        'position_name' => 'Administrative Aide',
        'position_group' => 'A',
        'salary_grade' => 10,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'ict' => [
        'position_name' => 'Information and Communications Technology',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    
    // ========== ADDITIONAL NON-TEACHING POSITIONS ==========
    'attorney_v' => [
        'position_name' => 'Attorney V',
        'position_group' => 'C',
        'salary_grade' => 25,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'accountant_iv' => [
        'position_name' => 'Accountant IV',
        'position_group' => 'C',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'ass_teachers_camp_superintendent' => [
        'position_name' => 'Assistant Teacher\'s Camp Superintendent',
        'position_group' => 'C',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'dept_legis_liaison_specialist' => [
        'position_name' => 'Department Legislative Liaison Specialist',
        'position_group' => 'C',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'engineer_iv' => [
        'position_name' => 'Engineer IV',
        'position_group' => 'C',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'ict_officer_ii' => [
        'position_name' => 'Information Technology Officer II',
        'position_group' => 'C',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'internal_auditor_iv' => [
        'position_name' => 'Internal Auditor IV',
        'position_group' => 'C',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'project_dev_officer_iv' => [
        'position_name' => 'Project Development Officer IV',
        'position_group' => 'C',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'project_eval_officer_iv' => [
        'position_name' => 'Project Evaluation Officer IV',
        'position_group' => 'C',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'security_officer_iv' => [
        'position_name' => 'Security Officer IV',
        'position_group' => 'C',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'supervising_admin_officer' => [
        'position_name' => 'Supervising Administrative Officer',
        'position_group' => 'C',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'supervising_health_program_officer' => [
        'position_name' => 'Supervising Health Program Officer',
        'position_group' => 'C',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'voc_school_admin_i' => [
        'position_name' => 'Vocational School Administrator I',
        'position_group' => 'C',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'attorney_iii' => [
        'position_name' => 'Attorney III',
        'position_group' => 'B',
        'salary_grade' => 21,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'medical_officer_iii' => [
        'position_name' => 'Medical Officer III',
        'position_group' => 'B',
        'salary_grade' => 21,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'dentist_iii' => [
        'position_name' => 'Dentist III',
        'position_group' => 'B',
        'salary_grade' => 20,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'accountant_iii' => [
        'position_name' => 'Accountant III',
        'position_group' => 'B',
        'salary_grade' => 19,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'architect_iii' => [
        'position_name' => 'Architect III',
        'position_group' => 'B',
        'salary_grade' => 19,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'engineer_iii' => [
        'position_name' => 'Engineer III',
        'position_group' => 'B',
        'salary_grade' => 19,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'info_sys_analyst_iii' => [
        'position_name' => 'Information Systems Analyst III',
        'position_group' => 'B',
        'salary_grade' => 19,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'ict_officer_i' => [
        'position_name' => 'Information Technology Officer I',
        'position_group' => 'B',
        'salary_grade' => 19,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_officer_v' => [
        'position_name' => 'Administrative Officer V',
        'position_group' => 'B',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'attorney_ii' => [
        'position_name' => 'Attorney II',
        'position_group' => 'B',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'computer_programmer_iii' => [
        'position_name' => 'Computer Programmer III',
        'position_group' => 'B',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'guidance_services_specialist_ii' => [
        'position_name' => 'Guidance Services Specialist II',
        'position_group' => 'B',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'health_education_officer_iii' => [
        'position_name' => 'Health Education And Promotion Officer III',
        'position_group' => 'B',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'internal_auditor_iii' => [
        'position_name' => 'Internal Auditor III',
        'position_group' => 'B',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'librarian_iii' => [
        'position_name' => 'Librarian III',
        'position_group' => 'B',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'medical_officer_ii' => [
        'position_name' => 'Medical Officer II',
        'position_group' => 'B',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'nutritionist_dietitian_iii' => [
        'position_name' => 'Nutritionist-Dietitian III',
        'position_group' => 'B',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'planning_officer_iii' => [
        'position_name' => 'Planning Officer III',
        'position_group' => 'B',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'project_dev_officer_iii' => [
        'position_name' => 'Project Development Officer III',
        'position_group' => 'B',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'senior_admin_assistant_v' => [
        'position_name' => 'Senior Administrative Assistant V',
        'position_group' => 'B',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'special_investigator_iii' => [
        'position_name' => 'Special Investigator III',
        'position_group' => 'B',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'statistician_iii' => [
        'position_name' => 'Statistician III',
        'position_group' => 'B',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'computer_maint_technologist_iii' => [
        'position_name' => 'Computer Maintenance Technologist III',
        'position_group' => 'B',
        'salary_grade' => 17,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'dentist_ii' => [
        'position_name' => 'Dentist II',
        'position_group' => 'B',
        'salary_grade' => 17,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'info_sys_researcher_iii' => [
        'position_name' => 'Information Systems Researcher III',
        'position_group' => 'B',
        'salary_grade' => 17,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'accountant_ii' => [
        'position_name' => 'Accountant II',
        'position_group' => 'B',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'architect_ii' => [
        'position_name' => 'Architect II',
        'position_group' => 'B',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'attorney_i' => [
        'position_name' => 'Attorney I',
        'position_group' => 'B',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'engineer_ii' => [
        'position_name' => 'Engineer II',
        'position_group' => 'B',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'guidance_services_specialist_i' => [
        'position_name' => 'Guidance Services Specialist I',
        'position_group' => 'B',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'info_sys_analyst_ii' => [
        'position_name' => 'Information Systems Analyst II',
        'position_group' => 'B',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_officer_iv' => [
        'position_name' => 'Administrative Officer IV',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'agriculturist_ii' => [
        'position_name' => 'Agriculturist II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'college_librarian_ii' => [
        'position_name' => 'College Librarian II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'computer_programming_ii' => [
        'position_name' => 'Computer Programming II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'creative_arts_specialist_ii' => [
        'position_name' => 'Creative Arts Specialist II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'hrm_officer_ii' => [
        'position_name' => 'Human Resource Management Officer II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'internal_auditor_ii' => [
        'position_name' => 'Internal Auditor II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'librarian_ii' => [
        'position_name' => 'Librarian II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'nurse_ii' => [
        'position_name' => 'Nurse II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'nutritionist_dietitian_ii' => [
        'position_name' => 'Nutritionist-Dietitian II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'planning_officer_ii' => [
        'position_name' => 'Planning Officer II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'project_dev_officer_ii' => [
        'position_name' => 'Project Development Officer II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'publication_production_supervisor' => [
        'position_name' => 'Publication Production Supervisor',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'registrar_ii' => [
        'position_name' => 'Registrar II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'security_officer_ii' => [
        'position_name' => 'Security Officer II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'senior_admin_assistant_iii' => [
        'position_name' => 'Senior Administrative Assistant III',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'special_investigator_ii' => [
        'position_name' => 'Special Investigator II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'statistician_ii' => [
        'position_name' => 'Statistician II',
        'position_group' => 'B',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_officer_iii' => [
        'position_name' => 'Administrative Officer III',
        'position_group' => 'A',
        'salary_grade' => 14,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'cashier_ii' => [
        'position_name' => 'Cashier II',
        'position_group' => 'A',
        'salary_grade' => 14,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'dentist_i' => [
        'position_name' => 'Dentist I',
        'position_group' => 'A',
        'salary_grade' => 14,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'health_education_officer_ii' => [
        'position_name' => 'Health Education and Promotion Officer II',
        'position_group' => 'A',
        'salary_grade' => 14,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'records_officer_ii' => [
        'position_name' => 'Records Officer II',
        'position_group' => 'A',
        'salary_grade' => 14,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'senior_admin_assistant_ii' => [
        'position_name' => 'Senior Administrative Assistant II',
        'position_group' => 'A',
        'salary_grade' => 14,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'supply_officer_ii' => [
        'position_name' => 'Supply Officer II',
        'position_group' => 'A',
        'salary_grade' => 14,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'college_librarian_i' => [
        'position_name' => 'College Librarian I',
        'position_group' => 'A',
        'salary_grade' => 13,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'school_librarian_iii' => [
        'position_name' => 'School Librarian III',
        'position_group' => 'A',
        'salary_grade' => 13,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'senior_admin_assistant_i' => [
        'position_name' => 'Senior Administrative Assistant I',
        'position_group' => 'A',
        'salary_grade' => 13,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'voc_placement_coordinator_i' => [
        'position_name' => 'Vocational Placement Coordinator I',
        'position_group' => 'A',
        'salary_grade' => 13,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'accountant_i' => [
        'position_name' => 'Accountant I',
        'position_group' => 'A',
        'salary_grade' => 12,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_assistant_vi' => [
        'position_name' => 'Administrative Assistant VI',
        'position_group' => 'A',
        'salary_grade' => 12,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'legal_assistant_ii' => [
        'position_name' => 'Legal Assistant II',
        'position_group' => 'A',
        'salary_grade' => 12,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'school_librarian_ii' => [
        'position_name' => 'School Librarian II',
        'position_group' => 'A',
        'salary_grade' => 12,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'accounting_analyst' => [
        'position_name' => 'Accounting Analyst',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_assistant_v' => [
        'position_name' => 'Administrative Assistant V',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_officer_ii' => [
        'position_name' => 'Administrative Officer II',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'agriculturist_i' => [
        'position_name' => 'Agriculturist I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'aquaculturist_i' => [
        'position_name' => 'Aquaculturist I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'budget_officer_i' => [
        'position_name' => 'Budget Officer I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'comm_equipment_operator_iv' => [
        'position_name' => 'Communication Equipment Operator IV',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'computer_maint_technologist_i' => [
        'position_name' => 'Computer Maintenance Technologist I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'creative_arts_specialist_i' => [
        'position_name' => 'Creative Arts Specialist I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'dormitory_manager_ii' => [
        'position_name' => 'Dormitory Manager II',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'fiscal_examiner_i' => [
        'position_name' => 'Fiscal Examiner I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'hrm_officer_i' => [
        'position_name' => 'Human Resource Management Officer I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'internal_auditor_i' => [
        'position_name' => 'Internal Auditor I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'librarian_i' => [
        'position_name' => 'Librarian I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'nurse_i' => [
        'position_name' => 'Nurse I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'nutritionist_dietitian_i' => [
        'position_name' => 'Nutritionist-Dietitian I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'planning_officer_i' => [
        'position_name' => 'Planning Officer I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'project_dev_officer_i' => [
        'position_name' => 'Project Development Officer I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'psychologist_i' => [
        'position_name' => 'Psychologist I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'registrar_i' => [
        'position_name' => 'Registrar I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'school_librarian' => [
        'position_name' => 'School Librarian',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'social_welfare_officer_i' => [
        'position_name' => 'Social Welfare Officer I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'statistician_i' => [
        'position_name' => 'Statistician I',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'warehouseman_iii' => [
        'position_name' => 'Warehouseman III',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_officer_i' => [
        'position_name' => 'Administrative Officer I',
        'position_group' => 'A',
        'salary_grade' => 10,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'cashier_i' => [
        'position_name' => 'Cashier I',
        'position_group' => 'A',
        'salary_grade' => 10,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'cinematographer_i' => [
        'position_name' => 'Cinematographer I',
        'position_group' => 'A',
        'salary_grade' => 10,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'computer_file_librarian_ii' => [
        'position_name' => 'Computer File Librarian II',
        'position_group' => 'A',
        'salary_grade' => 10,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'legal_assistant_i' => [
        'position_name' => 'Legal Assistant I',
        'position_group' => 'A',
        'salary_grade' => 10,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'supply_officer_i' => [
        'position_name' => 'Supply Officer I',
        'position_group' => 'A',
        'salary_grade' => 10,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_assistant_iii' => [
        'position_name' => 'Administrative Assistant III',
        'position_group' => 'A',
        'salary_grade' => 9,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'comm_equipment_operator_iii' => [
        'position_name' => 'Communication Equipment Operator III',
        'position_group' => 'A',
        'salary_grade' => 9,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'dormitory_manager_i' => [
        'position_name' => 'Dormitory Manager I',
        'position_group' => 'A',
        'salary_grade' => 9,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'printing_foreman' => [
        'position_name' => 'Printing Foreman',
        'position_group' => 'A',
        'salary_grade' => 9,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'science_research_assistant' => [
        'position_name' => 'Science Research Assistant',
        'position_group' => 'A',
        'salary_grade' => 9,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'science_technician_i' => [
        'position_name' => 'Science Research Technician I',
        'position_group' => 'A',
        'salary_grade' => 9,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'senior_bookkeeper' => [
        'position_name' => 'Senior Bookkeeper',
        'position_group' => 'A',
        'salary_grade' => 9,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_assistant_ii' => [
        'position_name' => 'Administrative Assistant II',
        'position_group' => 'A',
        'salary_grade' => 8,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'aquaculural_technician_ii' => [
        'position_name' => 'Aquacultural Technician II',
        'position_group' => 'A',
        'salary_grade' => 8,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'artist_illustrator_ii' => [
        'position_name' => 'Artist-Illustrator II',
        'position_group' => 'A',
        'salary_grade' => 8,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'bookkeeper' => [
        'position_name' => 'Bookkeeper',
        'position_group' => 'A',
        'salary_grade' => 8,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'computer_file_librarian_i' => [
        'position_name' => 'Computer File Librarian I',
        'position_group' => 'A',
        'salary_grade' => 8,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'disbursing_officer_ii' => [
        'position_name' => 'Disbursing Officer II',
        'position_group' => 'A',
        'salary_grade' => 8,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'draftsman_ii' => [
        'position_name' => 'Draftsman II',
        'position_group' => 'A',
        'salary_grade' => 8,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'internal_auditing_assistant' => [
        'position_name' => 'Internal Auditing Assistant',
        'position_group' => 'A',
        'salary_grade' => 8,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'project_dev_assistant' => [
        'position_name' => 'Project Development Assistant',
        'position_group' => 'A',
        'salary_grade' => 8,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'security_guard_iii' => [
        'position_name' => 'Security Guard III',
        'position_group' => 'A',
        'salary_grade' => 8,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_assistant_i' => [
        'position_name' => 'Administrative Assistant I',
        'position_group' => 'A',
        'salary_grade' => 7,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'copy_reader' => [
        'position_name' => 'Copy Reader',
        'position_group' => 'A',
        'salary_grade' => 7,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'accounting_clerk_ii' => [
        'position_name' => 'Accounting Clerk II',
        'position_group' => 'A',
        'salary_grade' => 6,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_aide_vi' => [
        'position_name' => 'Administrative Aide VI',
        'position_group' => 'A',
        'salary_grade' => 6,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'clerk_iii' => [
        'position_name' => 'Clerk III',
        'position_group' => 'A',
        'salary_grade' => 6,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'comm_equipment_operator_ii' => [
        'position_name' => 'Communication Equipment Operator II',
        'position_group' => 'A',
        'salary_grade' => 6,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'disbursing_officer_i' => [
        'position_name' => 'Disbursing Officer I',
        'position_group' => 'A',
        'salary_grade' => 6,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'draftsman_i' => [
        'position_name' => 'Draftsman I',
        'position_group' => 'A',
        'salary_grade' => 6,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'electronics_comm_equipment_tech_i' => [
        'position_name' => 'Electronics And Communication Equipment Technician I',
        'position_group' => 'A',
        'salary_grade' => 6,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'laboratory_technician_i' => [
        'position_name' => 'Laboratory Technician I',
        'position_group' => 'A',
        'salary_grade' => 6,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'mechanic_ii' => [
        'position_name' => 'Mechanic II',
        'position_group' => 'A',
        'salary_grade' => 6,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'mechanical_plant_operator_ii' => [
        'position_name' => 'Mechanical Plant Operator II',
        'position_group' => 'A',
        'salary_grade' => 6,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'photoengraver_ii' => [
        'position_name' => 'Photoengraver II',
        'position_group' => 'A',
        'salary_grade' => 6,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'proofreader_ii' => [
        'position_name' => 'Proofreader II',
        'position_group' => 'A',
        'salary_grade' => 6,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'typesetter_ii' => [
        'position_name' => 'Typesetter II',
        'position_group' => 'A',
        'salary_grade' => 6,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'utility_foreman' => [
        'position_name' => 'Utility Foreman',
        'position_group' => 'A',
        'salary_grade' => 6,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_aide_v' => [
        'position_name' => 'Administrative Aide V',
        'position_group' => 'A',
        'salary_grade' => 5,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'handicraft_worker_ii' => [
        'position_name' => 'Handicraft Worker II',
        'position_group' => 'A',
        'salary_grade' => 5,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'legal_aide' => [
        'position_name' => 'Legal Aide',
        'position_group' => 'A',
        'salary_grade' => 5,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'master_fisherman_i' => [
        'position_name' => 'Master Fisherman I',
        'position_group' => 'A',
        'salary_grade' => 5,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'security_guard_ii' => [
        'position_name' => 'Security Guard II',
        'position_group' => 'A',
        'salary_grade' => 5,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_aide_iv' => [
        'position_name' => 'Administrative Aide IV',
        'position_group' => 'A',
        'salary_grade' => 4,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'clerk_ii' => [
        'position_name' => 'Clerk II',
        'position_group' => 'A',
        'salary_grade' => 4,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'comm_equipment_operator_i' => [
        'position_name' => 'Communication Equipment Operator I',
        'position_group' => 'A',
        'salary_grade' => 4,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'dental_aide' => [
        'position_name' => 'Dental Aide',
        'position_group' => 'A',
        'salary_grade' => 4,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'fiscal_clerk_i' => [
        'position_name' => 'Fiscal Clerk I',
        'position_group' => 'A',
        'salary_grade' => 4,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'heavy_equipment_operator_i' => [
        'position_name' => 'Heavy Equipment Operator I',
        'position_group' => 'A',
        'salary_grade' => 4,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'houseparent_i' => [
        'position_name' => 'Houseparent I',
        'position_group' => 'A',
        'salary_grade' => 4,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'marine_engineman_i' => [
        'position_name' => 'Marine Engineman I',
        'position_group' => 'A',
        'salary_grade' => 4,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'mechanic_i' => [
        'position_name' => 'Mechanic I',
        'position_group' => 'A',
        'salary_grade' => 4,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'mechanical_plant_operator_i' => [
        'position_name' => 'Mechanical Plant Operator I',
        'position_group' => 'A',
        'salary_grade' => 4,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'metal_worker_i' => [
        'position_name' => 'Metal Worker I',
        'position_group' => 'A',
        'salary_grade' => 4,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'nursing_attendant_i' => [
        'position_name' => 'Nursing Attendant I',
        'position_group' => 'A',
        'salary_grade' => 4,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'statistician_aide' => [
        'position_name' => 'Statistician Aide',
        'position_group' => 'A',
        'salary_grade' => 4,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'telegram_carrier' => [
        'position_name' => 'Telegram Carrier',
        'position_group' => 'A',
        'salary_grade' => 4,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'watchman_ii' => [
        'position_name' => 'Watchman II',
        'position_group' => 'A',
        'salary_grade' => 4,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_aide_iii' => [
        'position_name' => 'Administrative Aide III',
        'position_group' => 'A',
        'salary_grade' => 3,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'clerk_i' => [
        'position_name' => 'Clerk I',
        'position_group' => 'A',
        'salary_grade' => 3,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'cook_i' => [
        'position_name' => 'Cook I',
        'position_group' => 'A',
        'salary_grade' => 3,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'coxswain' => [
        'position_name' => 'Coxswain',
        'position_group' => 'A',
        'salary_grade' => 3,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'driver_i' => [
        'position_name' => 'Driver I',
        'position_group' => 'A',
        'salary_grade' => 3,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'fisherman' => [
        'position_name' => 'Fisherman',
        'position_group' => 'A',
        'salary_grade' => 3,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'handicraft_worker_i' => [
        'position_name' => 'Handicraft Worker I',
        'position_group' => 'A',
        'salary_grade' => 3,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'lineman_i' => [
        'position_name' => 'Lineman I',
        'position_group' => 'A',
        'salary_grade' => 3,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'security_guard_i' => [
        'position_name' => 'Security Guard I',
        'position_group' => 'A',
        'salary_grade' => 3,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_aide_ii' => [
        'position_name' => 'Administrative Aide II',
        'position_group' => 'A',
        'salary_grade' => 2,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'construction_maintenance_man' => [
        'position_name' => 'Construction And Maintenance Man',
        'position_group' => 'A',
        'salary_grade' => 2,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'farm_worker_i' => [
        'position_name' => 'Farm Worker I',
        'position_group' => 'A',
        'salary_grade' => 2,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'guesthouse_caretaker' => [
        'position_name' => 'Guesthouse Caretaker',
        'position_group' => 'A',
        'salary_grade' => 2,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'light_equipment_operator' => [
        'position_name' => 'Light Equipment Operator',
        'position_group' => 'A',
        'salary_grade' => 2,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'nurse_maid_i' => [
        'position_name' => 'Nurse Maid I',
        'position_group' => 'A',
        'salary_grade' => 2,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'reproduction_machine_operator_i' => [
        'position_name' => 'Reproduction Machine Operator I',
        'position_group' => 'A',
        'salary_grade' => 2,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'watchman_i' => [
        'position_name' => 'Watchman I',
        'position_group' => 'A',
        'salary_grade' => 2,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_aide_i' => [
        'position_name' => 'Administrative Aide I',
        'position_group' => 'A',
        'salary_grade' => 1,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'utility_worker_i' => [
        'position_name' => 'Utility Worker I',
        'position_group' => 'A',
        'salary_grade' => 1,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    
    // ========== ADDITIONAL RELATED TEACHING POSITIONS ==========
    'school_farming_coord_ii' => [
        'position_name' => 'School Farming Coordinator II',
        'position_group' => 'B',
        'salary_grade' => 14,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'school_farming_coord_i' => [
        'position_name' => 'School Farming Coordinator I',
        'position_group' => 'B',
        'salary_grade' => 13,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'science_technician_iii' => [
        'position_name' => 'Science Research Technician III',
        'position_group' => 'A',
        'salary_grade' => 13,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'teacher_credentials_evaluator_i' => [
        'position_name' => 'Teacher Credentials Evaluator I',
        'position_group' => 'A',
        'salary_grade' => 13,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'crafts_education_demonstrator_ii' => [
        'position_name' => 'Crafts Education Demonstrator II',
        'position_group' => 'A',
        'salary_grade' => 12,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'edu_program_specialist_i' => [
        'position_name' => 'Education Program Specialist I',
        'position_group' => 'A',
        'salary_grade' => 12,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'guidance_counselor_ii' => [
        'position_name' => 'Guidance Counselor II',
        'position_group' => 'A',
        'salary_grade' => 12,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'science_technician_ii' => [
        'position_name' => 'Science Research Technician II',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'teaching_aids_specialist' => [
        'position_name' => 'Teaching-Aids Specialist',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'crafts_education_demonstrator_i' => [
        'position_name' => 'Crafts Education Demonstrator I',
        'position_group' => 'A',
        'salary_grade' => 10,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'edu_research_assistant_ii' => [
        'position_name' => 'Education Research Assistant II',
        'position_group' => 'A',
        'salary_grade' => 10,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'school_farm_demonstrator' => [
        'position_name' => 'School Farm Demonstrator',
        'position_group' => 'A',
        'salary_grade' => 10,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    
    // ========== DEFAULT/CUSTOM POSITION ==========
    'custom' => [
        'position_name' => 'Custom Position',
        'position_group' => 'A',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ]
];

/**
 * Get baseline for a position
 */
function getBaselineForPosition($positionKey) {
    global $baselineLibrary;
    return $baselineLibrary[$positionKey] ?? $baselineLibrary['custom'];
}

/**
 * Get all available positions
 */
function getAllPositions() {
    global $baselineLibrary;
    return $baselineLibrary;
}

// Return library if required directly
if (!function_exists('getBaselineForPosition')) {
    return $baselineLibrary;
}
