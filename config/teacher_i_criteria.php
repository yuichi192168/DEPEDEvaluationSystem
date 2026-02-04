<?php
/**
 * Teacher I Hiring Criteria and Point System
 * Based on DepEd Order No. 007, s. 2023
 * Criteria and Point System for Hiring to Teacher I Positions
 * 
 * This configuration contains the specific criteria for evaluating Teacher I position applicants
 * including Education, Training, Experience (ETE), Professional examinations, and teaching demonstrations.
 */

/**
 * TEACHER I POSITION CRITERIA AND WEIGHTS
 * Total Points: 100
 */
$teacherICriteria = [
    'education' => [
        'max_points' => 10,
        'weight' => 10,
        'description' => 'Education units and/or degree relevant to the position'
    ],
    'training' => [
        'max_points' => 10,
        'weight' => 10,
        'description' => 'Training hours in Curriculum and Instruction and other specialized training'
    ],
    'experience' => [
        'max_points' => 10,
        'weight' => 10,
        'description' => 'Experience in teaching exceeding the minimum requirements'
    ],
    'pbet_let_lept' => [
        'max_points' => 10,
        'weight' => 10,
        'description' => 'Professional Board Examination for Teachers (PBET), Licensure Examination for Teachers (LET), or Licensure Examination for Professional Teachers (LEPT) Rating'
    ],
    'ppst_cois' => [
        'max_points' => 35,
        'weight' => 35,
        'description' => 'PPST Classroom Observable Indicators (COI) measured through Classroom Observation/Demonstration Teaching'
    ],
    'ppst_ncois' => [
        'max_points' => 25,
        'weight' => 25,
        'description' => 'PPST Non-Classroom Observable Indicators (NCOI) measured through the Teacher Reflection Form (TRF)'
    ]
];

/**
 * EDUCATION, TRAINING, EXPERIENCE (ETE) INCREMENT RUBRIC
 * Based on Tables 2.a, 2.b, 2.c from DepEd Order
 * 
 * Points are calculated based on number of increments:
 * - 10 or more increments = 10 points
 * - 8-9 increments = 8 points
 * - 6-7 increments = 6 points
 * - 4-5 increments = 4 points
 * - 2-3 increments = 2 points
 * - Less than 2 or baseline = 0 points
 */
$eteIncrementRubric = [
    10 => 10,   // 10 or more increments
    8 => 8,     // 8-9 increments
    6 => 6,     // 6-7 increments
    4 => 4,     // 4-5 increments
    2 => 2,     // 2-3 increments
    0 => 0      // Less than 2 or meets minimum
];

/**
 * EDUCATION LEVELS (Table 2.a)
 * Levels 1-31 based on educational qualifications
 * Format: Level => ['range_description' => 'From-To']
 */
$educationLevels = [
    1 => 'Can Read and Write to Elementary Level Education',
    2 => 'Elementary Graduate to Junior High School Level Education (K to 12)',
    3 => 'Completed Junior High School (K to 12) to Senior High School Level Education',
    4 => 'Less than 2 years of College',
    5 => 'Completed 2 years in College to Less than Bachelor\'s Degree',
    6 => 'Bachelor\'s Degree',
    7 => '6 Units earned towards Master\'s Degree to Less than 9 Units',
    8 => '9 Units earned towards Master\'s Degree to Less than 12 Units',
    9 => '12 Units earned towards Master\'s Degree to Less than 15 Units',
    10 => '15 Units earned towards Master\'s Degree to Less than 18 Units',
    11 => '18 Units earned towards Master\'s Degree to Less than 21 Units',
    12 => '21 Units earned towards Master\'s Degree to Master\'s Degree',
    13 => '3 Units earned towards Doctorate to Less than 6 Units towards Doctorate',
    14 => '6 Units earned towards Doctorate to Less than 9 Units towards Doctorate',
    15 => '9 Units earned towards Doctorate to Less than 12 Units towards Doctorate',
    16 => '12 Units earned towards Doctorate to Less than 15 Units towards Doctorate',
    17 => '15 Units earned towards Doctorate to Less than 18 Units towards Doctorate',
    18 => '18 Units earned towards Doctorate to Less than 21 Units towards Doctorate',
    19 => '21 Units earned towards Doctorate to Complete Academic Requirements',
    20 => 'Complete Academic Requirements towards Doctorate',
    21 => 'Doctorate',
    // Levels 22-31 are for specializations and additional Doctorates
];

/**
 * TRAINING LEVELS (Table 2.b)
 * Based on cumulative hours of training
 */
$trainingLevels = [
    1 => '0 hours to Less than 8 hours',
    2 => '8 hours to Less than 16 hours',
    3 => '16 hours to Less than 24 hours',
    4 => '24 hours to Less than 32 hours',
    5 => '32 hours to Less than 40 hours',
    6 => '40 hours to Less than 48 hours',
    7 => '48 hours to Less than 56 hours',
    8 => '56 hours to Less than 64 hours',
    9 => '64 hours to Less than 72 hours',
    10 => '72 hours to Less than 80 hours',
    // ... continues to Level 31
];

/**
 * EXPERIENCE LEVELS (Table 2.c)
 * Based on months/years of teaching experience
 */
$experienceLevels = [
    1 => 'None to Less than 6 months',
    2 => '6 months to Less than 1 year',
    3 => '1 year to Less than 1 year 6 months',
    4 => '1 year 6 months to Less than 2 years',
    5 => '2 years to Less than 2 years 6 months',
    6 => '2 years 6 months to Less than 3 years',
    7 => '3 years to Less than 3 years 6 months',
    8 => '3 years 6 months to Less than 4 years',
    9 => '4 years to Less than 4 years 6 months',
    10 => '4 years 6 months to Less than 5 years',
    // ... continues to Level 31
];

/**
 * PBET/LET/LEPT RATING CALCULATION
 * Formula: Points = (PBET/LET/LEPT rating / 100) × WA(PBET/LET/LEPT)
 * Where WA = Weight Allocation (10 points for Teacher I)
 * 
 * Example:
 * PBET rating = 82.75
 * WA = 10
 * Points = (82.75 / 100) × 10 = 8.275 points
 */

/**
 * PPST CLASSROOM OBSERVABLE INDICATORS (COIs) CALCULATION
 * Formula: Points = (COT rating / 30) × WA(COI)
 * Where WA = Weight Allocation (35 points for Teacher I)
 * COT = Classroom Observation Tool rating (max 30)
 * 
 * Example:
 * COT rating = 20
 * WA = 35
 * Points = (20 / 30) × 35 = 23.333 points
 * 
 * COIs for Teacher I include:
 * - 1.1.2: Apply knowledge of content within and across curriculum teaching areas
 * - 1.4.2: Use a range of teaching strategies that enhance learner achievement
 * - 1.5.2: Apply teaching strategies to develop critical and creative thinking
 * - 4.1.2: Plan, manage and implement developmentally sequenced teaching and learning processes
 * - 5.1.2: Design and use diagnostic, formative and summative assessment strategies
 */

/**
 * PPST NON-CLASSROOM OBSERVABLE INDICATORS (NCOIs) CALCULATION
 * Formula: Points = (TRF rating / 20) × WA(NCOI)
 * Where WA = Weight Allocation (25 points for Teacher I)
 * TRF = Teacher Reflection Form rating (max 20)
 * 
 * Example:
 * TRF rating = 14
 * WA = 25
 * Points = (14 / 20) × 25 = 17.5 points
 * 
 * NCOIs for Teacher I include:
 * - 6.1.2: Maintain learning environments that are responsive to community contexts
 * - 6.3.2: Review regularly personal teaching practice using existing laws and regulations
 * - 7.2.2: Adopt practices that uphold the dignity of teaching as a profession
 * - 7.3.2: Participate in professional networks to share knowledge and enhance experience
 */

/**
 * BASELINE QUALIFICATION STANDARDS FOR TEACHER I
 * Per CSC-approved QS for Teacher I
 * 
 * Minimum Requirements:
 * - Education: Bachelor of Secondary Education (BSEd) or Bachelor's degree plus 18 professional units in Education
 * - Training: None required
 * - Experience: None required
 * 
 * Corresponding Baseline Levels:
 * - Education: Level 6 (Bachelor's Degree) or Level 12 (if with 18 units Master's)
 * - Training: Level 1 (Based on Table 2.b)
 * - Experience: Level 1 (Based on Table 2.c)
 */
$teacherIBaseline = [
    'education_level' => 6,  // Bachelor's Degree
    'training_level' => 1,
    'experience_level' => 1
];

/**
 * GENERAL GUIDELINES FOR ETE QUALIFICATIONS
 * 
 * 1. Education units and/or degrees in multiple or different majors may be given corresponding 
 *    points on a cumulative basis, provided that the units or degrees earned are relevant to 
 *    the position applied for
 * 
 * 2. Relevant training hours earned from digital/virtual/online learning may be considered, 
 *    subject to the conditions prescribed in CSC Memorandum Circular (MC) No. 3, s. 2021
 * 
 * 3. TESDA National Certification (NC) II and Trainers Methodology (TM) Certificate may be 
 *    considered, provided that the skills acquired from the training are relevant to the work
 * 
 * 4. Relevant experience gained from part-time work of at least four (4) hours per day may be 
 *    considered, provided that the Certificate of Employment is submitted with details
 * 
 * 5. Relevant experience gained from abroad or outside the Philippines may be considered, 
 *    provided that the applicant submits a Certificate of Employment with proper translation
 * 
 * 6. Applicable provisions under Rule VIII Part I to IV of the CSC RAOHRA shall apply in the 
 *    appreciation of relevant Education, Training, and Experience qualifications
 */

// Return configuration if required directly
return [
    'teacherICriteria' => $teacherICriteria,
    'eteIncrementRubric' => $eteIncrementRubric,
    'educationLevels' => $educationLevels,
    'trainingLevels' => $trainingLevels,
    'experienceLevels' => $experienceLevels,
    'teacherIBaseline' => $teacherIBaseline
];
?>
