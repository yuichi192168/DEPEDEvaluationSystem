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
