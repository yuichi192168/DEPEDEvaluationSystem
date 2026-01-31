<?php
/**
 * DepEd Evaluation Criteria Database
 * Dynamic criteria and point system based on position type and salary grade
 */

$evaluationCriteria = [
    // TEACHER I POSITIONS - Fixed criteria
    'TEACHING POSITIONS' => [
        'default' => [
            'criteria' => [
                'a' => ['name' => 'Education', 'max_points' => 10, 'description' => 'Educational attainment and qualifications'],
                'b' => ['name' => 'Training', 'max_points' => 10, 'description' => 'Professional development training hours'],
                'c' => ['name' => 'Experience', 'max_points' => 10, 'description' => 'Years of teaching experience'],
                'd' => ['name' => 'PBET, LET, or LEPT Rating', 'max_points' => 10, 'description' => 'Professional Board Exam or Licensure rating'],
                'e' => ['name' => 'PPST COIs (Classroom Observation or Demonstration Teaching)', 'max_points' => 35, 'description' => 'Classroom Observation or Demonstration Teaching score'],
                'f' => ['name' => 'PPST NCOIs (Teacher Reflection)', 'max_points' => 25, 'description' => 'Teacher Reflection and non-classroom observation'],
                'g' => ['name' => 'Application of L&D', 'max_points' => 0, 'description' => 'Not applicable for teaching positions'],
                'h' => ['name' => 'Potential', 'max_points' => 0, 'description' => 'Not applicable for teaching positions'],
            ],
            'total_points' => 100,
            'description' => 'Teacher I Evaluation Criteria'
        ]
    ],

    // HIGHER TEACHING POSITIONS - Same as Teacher I for consistency
    'HIGHER TEACHING POSITIONS' => [
        'default' => [
            'criteria' => [
                'a' => ['name' => 'Education', 'max_points' => 10, 'description' => 'Educational attainment and qualifications'],
                'b' => ['name' => 'Training', 'max_points' => 10, 'description' => 'Professional development training hours'],
                'c' => ['name' => 'Experience', 'max_points' => 10, 'description' => 'Years of teaching experience'],
                'd' => ['name' => 'PBET, LET, or LEPT Rating', 'max_points' => 10, 'description' => 'Professional Board Exam or Licensure rating'],
                'e' => ['name' => 'PPST COIs (Classroom Observation or Demonstration Teaching)', 'max_points' => 35, 'description' => 'Classroom Observation or Demonstration Teaching score'],
                'f' => ['name' => 'PPST NCOIs (Teacher Reflection)', 'max_points' => 25, 'description' => 'Teacher Reflection and non-classroom observation'],
                'g' => ['name' => 'Application of L&D', 'max_points' => 0, 'description' => 'Not applicable for teaching positions'],
                'h' => ['name' => 'Potential', 'max_points' => 0, 'description' => 'Not applicable for teaching positions'],
            ],
            'total_points' => 100,
            'description' => 'Higher Teaching Positions Evaluation Criteria'
        ]
    ],

    // SCHOOL ADMINISTRATION POSITIONS
    'SCHOOL ADMINISTRATION POSITION' => [
        'default' => [
            'criteria' => [
                'a' => ['name' => 'Education', 'max_points' => 10, 'description' => 'Educational attainment and qualifications'],
                'b' => ['name' => 'Training', 'max_points' => 10, 'description' => 'Professional development training hours'],
                'c' => ['name' => 'Experience', 'max_points' => 10, 'description' => 'Administrative/supervisory experience'],
                'd' => ['name' => 'Performance', 'max_points' => 25, 'description' => 'Performance rating/appraisal'],
                'e' => ['name' => 'Outstanding Accomplishments', 'max_points' => 10, 'description' => 'Notable achievements and contributions'],
                'f' => ['name' => 'Application of Education', 'max_points' => 10, 'description' => 'Applied knowledge in administrative functions'],
                'g' => ['name' => 'Application of L&D', 'max_points' => 10, 'description' => 'Application of learning and development'],
                'h' => ['name' => 'Potential', 'max_points' => 15, 'description' => 'Written exam, BEI, management capability'],
            ],
            'total_points' => 100,
            'description' => 'School Administration Positions Evaluation Criteria'
        ]
    ],

    // RELATED TEACHING POSITIONS - Variable based on salary grade
    'RELATED TEACHING POSITION' => [
        'sg_11_15' => [
            'criteria' => [
                'a' => ['name' => 'Education', 'max_points' => 10, 'description' => 'Educational attainment and qualifications'],
                'b' => ['name' => 'Training', 'max_points' => 10, 'description' => 'Professional development training hours'],
                'c' => ['name' => 'Experience', 'max_points' => 10, 'description' => 'Years of relevant experience'],
                'd' => ['name' => 'Performance', 'max_points' => 20, 'description' => 'Performance rating/appraisal'],
                'e' => ['name' => 'Outstanding Accomplishments', 'max_points' => 10, 'description' => 'Notable achievements and contributions'],
                'f' => ['name' => 'Application of Education', 'max_points' => 10, 'description' => 'Applied knowledge in role'],
                'g' => ['name' => 'Application of L&D', 'max_points' => 10, 'description' => 'Application of learning and development'],
                'h' => ['name' => 'Potential', 'max_points' => 20, 'description' => 'Written test, BEI, work sample test'],
            ],
            'total_points' => 100,
            'salary_grades' => [11, 12, 13, 14, 15],
            'description' => 'Related Teaching (SG 11-15) Evaluation Criteria'
        ],
        'sg_16_23_27' => [
            'criteria' => [
                'a' => ['name' => 'Education', 'max_points' => 10, 'description' => 'Educational attainment and qualifications'],
                'b' => ['name' => 'Training', 'max_points' => 10, 'description' => 'Professional development training hours'],
                'c' => ['name' => 'Experience', 'max_points' => 10, 'description' => 'Years of relevant experience'],
                'd' => ['name' => 'Performance', 'max_points' => 20, 'description' => 'Performance rating/appraisal'],
                'e' => ['name' => 'Outstanding Accomplishments', 'max_points' => 5, 'description' => 'Notable achievements and contributions'],
                'f' => ['name' => 'Application of Education', 'max_points' => 15, 'description' => 'Applied knowledge in role'],
                'g' => ['name' => 'Application of L&D', 'max_points' => 10, 'description' => 'Application of learning and development'],
                'h' => ['name' => 'Potential', 'max_points' => 20, 'description' => 'Written test, BEI, work sample test'],
            ],
            'total_points' => 100,
            'salary_grades' => [16, 17, 18, 19, 20, 21, 22, 23, 27],
            'description' => 'Related Teaching (SG 16-23, 27) Evaluation Criteria'
        ],
        'sg_24_chief' => [
            'criteria' => [
                'a' => ['name' => 'Education', 'max_points' => 10, 'description' => 'Educational attainment and qualifications'],
                'b' => ['name' => 'Training', 'max_points' => 10, 'description' => 'Professional development training hours'],
                'c' => ['name' => 'Experience', 'max_points' => 10, 'description' => 'Years of relevant experience'],
                'd' => ['name' => 'Performance', 'max_points' => 25, 'description' => 'Performance rating/appraisal'],
                'e' => ['name' => 'Outstanding Accomplishments', 'max_points' => 10, 'description' => 'Notable achievements and contributions'],
                'f' => ['name' => 'Application of Education', 'max_points' => 10, 'description' => 'Applied knowledge in role'],
                'g' => ['name' => 'Application of L&D', 'max_points' => 10, 'description' => 'Application of learning and development'],
                'h' => ['name' => 'Potential', 'max_points' => 15, 'description' => 'Written test, BEI, work sample test'],
            ],
            'total_points' => 100,
            'salary_grades' => [24],
            'description' => 'Related Teaching (SG 24 - Chief) Evaluation Criteria'
        ]
    ],

    // NON-TEACHING POSITIONS - Variable based on salary grade and category
    'NON-TEACHING LEVEL I' => [
        'general_services' => [
            'criteria' => [
                'a' => ['name' => 'Education', 'max_points' => 5, 'description' => 'Educational attainment'],
                'b' => ['name' => 'Training', 'max_points' => 5, 'description' => 'Professional development training'],
                'c' => ['name' => 'Experience', 'max_points' => 20, 'description' => 'Years of relevant experience'],
                'd' => ['name' => 'Performance', 'max_points' => 10, 'description' => 'Performance rating/appraisal'],
                'e' => ['name' => 'Outstanding Accomplishments', 'max_points' => 5, 'description' => 'Notable achievements'],
                'f' => ['name' => 'Application of Education', 'max_points' => 0, 'description' => 'N/A for general services'],
                'g' => ['name' => 'Application of L&D', 'max_points' => 0, 'description' => 'N/A for general services'],
                'h' => ['name' => 'Potential', 'max_points' => 55, 'description' => 'Written test, BEI, work sample test'],
            ],
            'total_points' => 100,
            'category' => 'general_services',
            'description' => 'Non-Teaching General Services Evaluation Criteria'
        ],
        'non_general_services' => [
            'criteria' => [
                'a' => ['name' => 'Education', 'max_points' => 5, 'description' => 'Educational attainment'],
                'b' => ['name' => 'Training', 'max_points' => 5, 'description' => 'Professional development training'],
                'c' => ['name' => 'Experience', 'max_points' => 20, 'description' => 'Years of relevant experience'],
                'd' => ['name' => 'Performance', 'max_points' => 20, 'description' => 'Performance rating/appraisal'],
                'e' => ['name' => 'Outstanding Accomplishments', 'max_points' => 10, 'description' => 'Notable achievements'],
                'f' => ['name' => 'Application of Education', 'max_points' => 10, 'description' => 'Applied knowledge in role'],
                'g' => ['name' => 'Application of L&D', 'max_points' => 10, 'description' => 'Application of learning and development'],
                'h' => ['name' => 'Potential', 'max_points' => 20, 'description' => 'Written test, BEI, work sample test'],
            ],
            'total_points' => 100,
            'salary_grades' => [1, 2, 3, 4, 5, 6, 7, 8, 9],
            'description' => 'Non-Teaching (SG 1-9) Evaluation Criteria'
        ]
    ],

    'NON-TEACHING LEVEL II' => [
        'sg_10_22_27' => [
            'criteria' => [
                'a' => ['name' => 'Education', 'max_points' => 5, 'description' => 'Educational attainment'],
                'b' => ['name' => 'Training', 'max_points' => 10, 'description' => 'Professional development training'],
                'c' => ['name' => 'Experience', 'max_points' => 15, 'description' => 'Years of relevant experience'],
                'd' => ['name' => 'Performance', 'max_points' => 20, 'description' => 'Performance rating/appraisal'],
                'e' => ['name' => 'Outstanding Accomplishments', 'max_points' => 10, 'description' => 'Notable achievements'],
                'f' => ['name' => 'Application of Education', 'max_points' => 10, 'description' => 'Applied knowledge in role'],
                'g' => ['name' => 'Application of L&D', 'max_points' => 10, 'description' => 'Application of learning and development'],
                'h' => ['name' => 'Potential', 'max_points' => 20, 'description' => 'Written test, BEI, work sample test'],
            ],
            'total_points' => 100,
            'salary_grades' => [10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 27],
            'description' => 'Non-Teaching (SG 10-22, 27) Evaluation Criteria'
        ],
        'sg_24_chief' => [
            'criteria' => [
                'a' => ['name' => 'Education', 'max_points' => 10, 'description' => 'Educational attainment'],
                'b' => ['name' => 'Training', 'max_points' => 5, 'description' => 'Professional development training'],
                'c' => ['name' => 'Experience', 'max_points' => 15, 'description' => 'Years of relevant experience'],
                'd' => ['name' => 'Performance', 'max_points' => 20, 'description' => 'Performance rating/appraisal'],
                'e' => ['name' => 'Outstanding Accomplishments', 'max_points' => 10, 'description' => 'Notable achievements'],
                'f' => ['name' => 'Application of Education', 'max_points' => 10, 'description' => 'Applied knowledge in role'],
                'g' => ['name' => 'Application of L&D', 'max_points' => 10, 'description' => 'Application of learning and development'],
                'h' => ['name' => 'Potential', 'max_points' => 20, 'description' => 'Written test, BEI, work sample test'],
            ],
            'total_points' => 100,
            'salary_grades' => [24],
            'description' => 'Non-Teaching (SG 24 - Chief) Evaluation Criteria'
        ]
    ]
];

/**
 * Get evaluation criteria for a position
 * @param string $positionGroup - Position group name (e.g., 'TEACHING POSITIONS')
 * @param int $salaryGrade - Salary grade of the position
 * @param string|null $category - Optional category for non-teaching positions
 * @return array - Evaluation criteria with max points
 */
function getEvaluationCriteria($positionGroup, $salaryGrade = null, $category = null) {
    global $evaluationCriteria;
    
    // Get position group criteria
    if (!isset($evaluationCriteria[$positionGroup])) {
        return null;
    }
    
    $groupCriteria = $evaluationCriteria[$positionGroup];
    
    // For positions with default criteria (no salary grade breakdown)
    if (isset($groupCriteria['default'])) {
        return $groupCriteria['default'];
    }
    
    // For positions with salary grade breakdown
    if ($salaryGrade === null || $salaryGrade === '') {
        return reset($groupCriteria); // Return first available criteria
    }
    
    // Handle RELATED TEACHING POSITION
    if ($positionGroup === 'RELATED TEACHING POSITION') {
        if ($salaryGrade >= 16 && $salaryGrade <= 23 || $salaryGrade == 27) {
            return $groupCriteria['sg_16_23_27'];
        } elseif ($salaryGrade == 24) {
            return $groupCriteria['sg_24_chief'];
        } else {
            return $groupCriteria['sg_11_15'];
        }
    }
    
    // Handle NON-TEACHING LEVEL I
    if ($positionGroup === 'NON-TEACHING LEVEL I') {
        if ($category === 'general_services') {
            return $groupCriteria['general_services'];
        } else {
            return $groupCriteria['non_general_services'];
        }
    }
    
    // Handle NON-TEACHING LEVEL II
    if ($positionGroup === 'NON-TEACHING LEVEL II') {
        if ($salaryGrade == 24) {
            return $groupCriteria['sg_24_chief'];
        } else {
            return $groupCriteria['sg_10_22_27'];
        }
    }
    
    // Fallback
    return reset($groupCriteria);
}

/**
 * Get all evaluation criteria grouped by position group
 * @return array - All evaluation criteria
 */
function getAllEvaluationCriteria() {
    global $evaluationCriteria;
    return $evaluationCriteria;
}

/**
 * Format criteria for JSON output
 * @param array $criteria - Criteria array
 * @return array - Formatted criteria
 */
function formatCriteriaForJSON($criteria) {
    if (!$criteria) return null;
    
    return [
        'criteria' => $criteria['criteria'],
        'total_points' => $criteria['total_points'],
        'description' => $criteria['description'] ?? ''
    ];
}
