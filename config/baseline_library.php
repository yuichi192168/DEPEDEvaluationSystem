<?php
/**
 * Baseline Qualification Standards Library - Complete
 * Based on DepEd Order No. 007, s. 2023
 * 
 * Organized by Position Groups:
 * - teaching positions
 * - higher teaching positions
 * - non-teaching level I
 * - non-teaching level II
 * - related teaching position
 * - school administration position
 */

$baselineLibrary = [
    // ========== TEACHING POSITIONS ==========
    'teacher_i' => [
        'position_name' => 'Teacher I',
        'position_group' => 'TEACHING POSITIONS',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],

    // ========== HIGHER TEACHING POSITIONS ==========
    'teacher_ii' => [
        'position_name' => 'Teacher II',
        'position_group' => 'HIGHER TEACHING POSITIONS',
        'salary_grade' => 12,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'master_teacher_i' => [
        'position_name' => 'Master Teacher I',
        'position_group' => 'HIGHER TEACHING POSITIONS',
        'salary_grade' => 14,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 18, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'master_teacher_ii' => [
        'position_name' => 'Master Teacher II',
        'position_group' => 'HIGHER TEACHING POSITIONS',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 18, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'master_teacher_iii' => [
        'position_name' => 'Master Teacher III',
        'position_group' => 'HIGHER TEACHING POSITIONS',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 18, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'master_teacher_iv' => [
        'position_name' => 'Master Teacher IV',
        'position_group' => 'HIGHER TEACHING POSITIONS',
        'salary_grade' => 17,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 18, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'master_teacher_v' => [
        'position_name' => 'Master Teacher V',
        'position_group' => 'HIGHER TEACHING POSITIONS',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 18, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'head_teacher_i' => [
        'position_name' => 'Head Teacher I',
        'position_group' => 'HIGHER TEACHING POSITIONS',
        'salary_grade' => 14,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'head_teacher_ii' => [
        'position_name' => 'Head Teacher II',
        'position_group' => 'HIGHER TEACHING POSITIONS',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'head_teacher_iii' => [
        'position_name' => 'Head Teacher III',
        'position_group' => 'HIGHER TEACHING POSITIONS',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 18, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'head_teacher_iv' => [
        'position_name' => 'Head Teacher IV',
        'position_group' => 'HIGHER TEACHING POSITIONS',
        'salary_grade' => 17,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 18, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'head_teacher_v' => [
        'position_name' => 'Head Teacher V',
        'position_group' => 'HIGHER TEACHING POSITIONS',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 18, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'head_teacher_vi' => [
        'position_name' => 'Head Teacher VI',
        'position_group' => 'HIGHER TEACHING POSITIONS',
        'salary_grade' => 19,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],

    // ========== SCHOOL ADMINISTRATION POSITIONS ==========
    'principal_iv' => [
        'position_name' => 'School Principal IV',
        'position_group' => 'SCHOOL ADMINISTRATION POSITION',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'principal_iii' => [
        'position_name' => 'School Principal III',
        'position_group' => 'SCHOOL ADMINISTRATION POSITION',
        'salary_grade' => 21,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'ass_principal_iii' => [
        'position_name' => 'Assistant School Principal III',
        'position_group' => 'SCHOOL ADMINISTRATION POSITION',
        'salary_grade' => 20,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'principal_ii' => [
        'position_name' => 'School Principal II',
        'position_group' => 'SCHOOL ADMINISTRATION POSITION',
        'salary_grade' => 20,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'special_principal_ii' => [
        'position_name' => 'Special School Principal II',
        'position_group' => 'SCHOOL ADMINISTRATION POSITION',
        'salary_grade' => 20,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'ass_principal_ii' => [
        'position_name' => 'Assistant School Principal II',
        'position_group' => 'SCHOOL ADMINISTRATION POSITION',
        'salary_grade' => 19,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'principal_i' => [
        'position_name' => 'School Principal I',
        'position_group' => 'SCHOOL ADMINISTRATION POSITION',
        'salary_grade' => 19,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'special_principal_i' => [
        'position_name' => 'Special School Principal I',
        'position_group' => 'SCHOOL ADMINISTRATION POSITION',
        'salary_grade' => 19,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'ass_principal_i' => [
        'position_name' => 'Assistant School Principal I',
        'position_group' => 'SCHOOL ADMINISTRATION POSITION',
        'salary_grade' => 18,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'ass_special_principal' => [
        'position_name' => 'Assistant Special School Principal',
        'position_group' => 'SCHOOL ADMINISTRATION POSITION',
        'salary_grade' => 18,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],

    // ========== RELATED TEACHING POSITIONS ==========
    'chief_eps' => [
        'position_name' => 'Chief Education Program Specialist',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'edu_program_supervisor' => [
        'position_name' => 'Education Program Supervisor',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'public_schools_dist_supervisor' => [
        'position_name' => 'Public Schools District Supervisor',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'supervising_eps' => [
        'position_name' => 'Supervising Education Program Specialist',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 32, 'experience' => 48,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'senior_eps' => [
        'position_name' => 'Senior Education Program Specialist',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 19,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'senior_science_specialist' => [
        'position_name' => 'Senior Science Research Specialist',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 19,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 18, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'voc_inst_supervisor_iii' => [
        'position_name' => 'Vocational Instruction Supervisor III',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'voc_inst_supervisor_ii' => [
        'position_name' => 'Vocational Instruction Supervisor II',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 17,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'edu_program_specialist_ii' => [
        'position_name' => 'Education Program Specialist II',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'guidance_coordinator_iii' => [
        'position_name' => 'Guidance Coordinator III',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'science_specialist_ii' => [
        'position_name' => 'Science Research Specialist II',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 18, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'science_technician_iv' => [
        'position_name' => 'Science Research Technician IV',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'voc_inst_supervisor_i' => [
        'position_name' => 'Vocational Instruction Supervisor I',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'guidance_coordinator_ii' => [
        'position_name' => 'Guidance Coordinator II',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'school_farming_coord_iii' => [
        'position_name' => 'School Farming Coordinator III',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'teacher_credentials_eval_ii' => [
        'position_name' => 'Teacher Credentials Evaluator II',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'guidance_counselor_iii' => [
        'position_name' => 'Guidance Counselor III',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 13,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'guidance_counselor_i' => [
        'position_name' => 'Guidance Counselor I',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'school_farming_coord_ii' => [
        'position_name' => 'School Farming Coordinator II',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 14,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'school_farming_coord_i' => [
        'position_name' => 'School Farming Coordinator I',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 13,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'science_technician_iii' => [
        'position_name' => 'Science Research Technician III',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 13,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'teacher_credentials_evaluator_i' => [
        'position_name' => 'Teacher Credentials Evaluator I',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 13,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'crafts_education_demonstrator_ii' => [
        'position_name' => 'Crafts Education Demonstrator II',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 12,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'edu_program_specialist_i' => [
        'position_name' => 'Education Program Specialist I',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 12,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'guidance_counselor_ii' => [
        'position_name' => 'Guidance Counselor II',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 12,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 8, 'experience' => 12,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'science_technician_ii' => [
        'position_name' => 'Science Research Technician II',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'teaching_aids_specialist' => [
        'position_name' => 'Teaching-Aids Specialist',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'crafts_education_demonstrator_i' => [
        'position_name' => 'Crafts Education Demonstrator I',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 10,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'edu_research_assistant_ii' => [
        'position_name' => 'Education Research Assistant II',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 10,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'school_farm_demonstrator' => [
        'position_name' => 'School Farm Demonstrator',
        'position_group' => 'RELATED TEACHING POSITION',
        'salary_grade' => 10,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],

    // ========== NON-TEACHING LEVEL I ==========
    'attorney_v' => [
        'position_name' => 'Attorney V',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 25,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'chief_accountant' => [
        'position_name' => 'Chief Accountant',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'chief_admin_officer' => [
        'position_name' => 'Chief Administrative Officer',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'chief_edu_supervisor' => [
        'position_name' => 'Chief Education Supervisor',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'chief_health_program_officer' => [
        'position_name' => 'Chief Health Program Officer',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'engineer_v' => [
        'position_name' => 'Engineer V',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'ict_officer_iii' => [
        'position_name' => 'Information Technology Officer III',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'internal_auditor_v' => [
        'position_name' => 'Internal Auditor V',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'planning_officer_v' => [
        'position_name' => 'Planning Officer V',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'project_dev_officer_v' => [
        'position_name' => 'Project Development Officer V',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'teachers_camp_superintendent' => [
        'position_name' => 'Teachers\' Camp Superintendent',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 24,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'attorney_iv' => [
        'position_name' => 'Attorney IV',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 23,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'medical_officer_iv' => [
        'position_name' => 'Medical Officer IV',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 23,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'voc_admin_ii' => [
        'position_name' => 'Vocational School Administrator II',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 23,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_aide' => [
        'position_name' => 'Administrative Aide',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 10,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'ict' => [
        'position_name' => 'Information and Communications Technology',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 11,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'accountant_iv' => [
        'position_name' => 'Accountant IV',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 22,
        'education' => ['degree' => 'Master', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 40, 'experience' => 60,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'clerk_i' => [
        'position_name' => 'Clerk I',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 3,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'utility_worker_i' => [
        'position_name' => 'Utility Worker I',
        'position_group' => 'NON-TEACHING LEVEL I',
        'salary_grade' => 1,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],

    // ========== NON-TEACHING LEVEL II ==========
    'attorney_iii' => [
        'position_name' => 'Attorney III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 21,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'medical_officer_iii' => [
        'position_name' => 'Medical Officer III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 21,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'dentist_iii' => [
        'position_name' => 'Dentist III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 20,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'accountant_iii' => [
        'position_name' => 'Accountant III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 19,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'architect_iii' => [
        'position_name' => 'Architect III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 19,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'engineer_iii' => [
        'position_name' => 'Engineer III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 19,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'info_sys_analyst_iii' => [
        'position_name' => 'Information Systems Analyst III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 19,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 24, 'experience' => 36,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'ict_officer_i' => [
        'position_name' => 'Information Technology Officer I',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 19,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_officer_v' => [
        'position_name' => 'Administrative Officer V',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'attorney_ii' => [
        'position_name' => 'Attorney II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'computer_programmer_iii' => [
        'position_name' => 'Computer Programmer III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'guidance_services_specialist_ii' => [
        'position_name' => 'Guidance Services Specialist II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'health_education_officer_iii' => [
        'position_name' => 'Health Education And Promotion Officer III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'internal_auditor_iii' => [
        'position_name' => 'Internal Auditor III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'librarian_iii' => [
        'position_name' => 'Librarian III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'medical_officer_ii' => [
        'position_name' => 'Medical Officer II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'nutritionist_dietitian_iii' => [
        'position_name' => 'Nutritionist-Dietitian III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'planning_officer_iii' => [
        'position_name' => 'Planning Officer III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'project_dev_officer_iii' => [
        'position_name' => 'Project Development Officer III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'senior_admin_assistant_v' => [
        'position_name' => 'Senior Administrative Assistant V',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'special_investigator_iii' => [
        'position_name' => 'Special Investigator III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'statistician_iii' => [
        'position_name' => 'Statistician III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 18,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'computer_maint_technologist_iii' => [
        'position_name' => 'Computer Maintenance Technologist III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 17,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'dentist_ii' => [
        'position_name' => 'Dentist II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 17,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'info_sys_researcher_iii' => [
        'position_name' => 'Information Systems Researcher III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 17,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'accountant_ii' => [
        'position_name' => 'Accountant II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'architect_ii' => [
        'position_name' => 'Architect II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'attorney_i' => [
        'position_name' => 'Attorney I',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'engineer_ii' => [
        'position_name' => 'Engineer II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'guidance_services_specialist_i' => [
        'position_name' => 'Guidance Services Specialist I',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'info_sys_analyst_ii' => [
        'position_name' => 'Information Systems Analyst II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 16,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_officer_iv' => [
        'position_name' => 'Administrative Officer IV',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'agriculturist_ii' => [
        'position_name' => 'Agriculturist II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'college_librarian_ii' => [
        'position_name' => 'College Librarian II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'computer_programming_ii' => [
        'position_name' => 'Computer Programming II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'creative_arts_specialist_ii' => [
        'position_name' => 'Creative Arts Specialist II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'hrm_officer_ii' => [
        'position_name' => 'Human Resource Management Officer II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'internal_auditor_ii' => [
        'position_name' => 'Internal Auditor II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'librarian_ii' => [
        'position_name' => 'Librarian II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'nurse_ii' => [
        'position_name' => 'Nurse II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'nutritionist_dietitian_ii' => [
        'position_name' => 'Nutritionist-Dietitian II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'planning_officer_ii' => [
        'position_name' => 'Planning Officer II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'project_dev_officer_ii' => [
        'position_name' => 'Project Development Officer II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'publication_production_supervisor' => [
        'position_name' => 'Publication Production Supervisor',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'registrar_ii' => [
        'position_name' => 'Registrar II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'security_officer_ii' => [
        'position_name' => 'Security Officer II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'senior_admin_assistant_iii' => [
        'position_name' => 'Senior Administrative Assistant III',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'special_investigator_ii' => [
        'position_name' => 'Special Investigator II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'statistician_ii' => [
        'position_name' => 'Statistician II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 15,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 16, 'experience' => 24,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'clerk_ii' => [
        'position_name' => 'Clerk II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 4,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    'admin_aide_ii' => [
        'position_name' => 'Administrative Aide II',
        'position_group' => 'NON-TEACHING LEVEL II',
        'salary_grade' => 2,
        'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
        'training' => 0, 'experience' => 0,
        'performance' => 0, 'outstanding_accomplishments' => 0,
        'application_of_education' => 0, 'application_of_ld' => 0, 'potential' => 0
    ],
    
    // ========== DEFAULT/CUSTOM POSITION ==========
    'custom' => [
        'position_name' => 'Custom Position',
        'position_group' => 'NON-TEACHING LEVEL I',
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
