<?php
/**
 * Lightweight assessment processor for DepEd-style hiring rules.
 * Provides: QS lookup, completeness validation, ETE calculation,
 * specialized scoring modules (Teacher I implemented), and IES generation.
 */

class AssessmentProcessor
{
    // Minimal Qualification Standards (example entries). Extend as needed.
    public static $QUAL_STANDARDS = [
        'Teacher I' => [
            'position_group' => 'TEACHING POSITIONS',
            'education' => "Bachelor's Degree in relevant field",
            'min_experience_years' => 0,
            'eligibility' => ['LET','PBET','LEPT'],
            'qs_text' => "Bachelor's degree; relevant licensure (LET/PBET/LEPT)",
        ],
        'School Administration' => [
            'position_group' => 'SCHOOL ADMINISTRATION POSITION',
            'education' => "Bachelor's or higher; preferably master's for some posts",
            'min_experience_years' => 3,
            'eligibility' => ['CS-eligible','LET'],
            'qs_text' => "Relevant education; supervisory experience; eligibility",
        ],
        // Add more positions as needed
    ];

    // Checklist items (Items 20.a-j) - keys expected in applicant data
    public static $CHECKLIST = [
        'personal_information', 'education_documents', 'eligibility_documents',
        'work_experience_documents', 'training_documents', 'performance_ratings',
        'birth_certificate', 'transcript_of_records', 'id_picture', 'other_required_docs'
    ];

    // Position group -> positions mapping for dropdown behavior (ordered)
    public static $POSITION_GROUPS = null; // Will be built dynamically from baseline_library

    /**
     * Build position groups dynamically from baseline_library
     */
    public static function buildPositionGroups() {
        if (self::$POSITION_GROUPS !== null) {
            return self::$POSITION_GROUPS;
        }
        
        require_once __DIR__ . '/../config/baseline_library.php';
        $groups = [];
        
        foreach ($baselineLibrary as $key => $position) {
            $group = $position['position_group'] ?? 'NON-TEACHING LEVEL I';
            $posName = $position['position_name'] ?? '';
            
            if (!isset($groups[$group])) {
                $groups[$group] = [];
            }
            
            if ($posName && !in_array($posName, $groups[$group])) {
                $groups[$group][] = $posName;
            }
        }
        
        // Sort each group's positions alphabetically
        foreach ($groups as &$positions) {
            sort($positions);
        }
        
        // Sort groups in defined order
        $ordered = [];
        $order = [
            'TEACHING POSITIONS',
            'HIGHER TEACHING POSITIONS',
            'SCHOOL ADMINISTRATION POSITION',
            'RELATED TEACHING POSITION',
            'NON-TEACHING LEVEL I',
            'NON-TEACHING LEVEL II'
        ];
        
        foreach ($order as $grp) {
            if (isset($groups[$grp])) {
                $ordered[$grp] = $groups[$grp];
            }
        }
        
        // Add any remaining groups not in the defined order
        foreach ($groups as $grp => $pos) {
            if (!isset($ordered[$grp])) {
                $ordered[$grp] = $pos;
            }
        }
        
        self::$POSITION_GROUPS = $ordered;
        return self::$POSITION_GROUPS;
    }

    // Scoring weight presets per position type (totals map to 100)
    public static $WEIGHTS = [
        'Teacher I' => [
            'education'=>10,'training'=>10,'experience'=>10,'pb_et'=>10,'ppst_cois'=>35,'ppst_ncois'=>25
        ],
        'School Administration' => [
            'education'=>10,'training'=>10,'experience'=>10,'performance'=>25,'outstanding'=>10,'application_of_education'=>10,'application_of_ld'=>10,'potential'=>15
        ],
        // Defaults for related-teaching and non-teaching use dynamic selection elsewhere
    ];

    // Entry point: process one applicant for a given position title
    public static function processApplication(array $applicant, string $positionTitle)
    {
        $result = [
            'position' => $positionTitle,
            'qualified' => false,
            'missing_items' => [],
            'qs_checks' => [],
            'scores' => [],
            'ies' => null,
            'notes' => [],
        ];

        // 1. Completeness
        $missing = self::validateCompleteness($applicant);
        $result['missing_items'] = $missing;
        if (!empty($missing)) {
            $result['notes'][] = 'Incomplete application';
        }

        // 2. QS check
        $qs = self::getQS($positionTitle);
        $qsCheck = self::checkQS($applicant, $qs);
        $result['qs_checks'] = $qsCheck;
        if (!$qsCheck['meets']) {
            $result['notes'][] = 'Did not meet minimum QS';
        }

        // 3. Route to scoring module based on position category
        $salaryGrade = isset($applicant['salary_grade']) ? intval($applicant['salary_grade']) : null;
        $isGeneralServices = !empty($applicant['is_general_services']);

        if (stripos($positionTitle, 'Teacher I') !== false) {
            $scores = self::scoreTeacherI($applicant);
            $result['scores'] = $scores;
            $total = array_sum($scores['breakdown']);
            $result['total_score'] = $total;
            // Teacher I cut-off rule: 50
            $result['qualified'] = ($total >= 50) && $qsCheck['meets'] && empty($missing);
            if (!$result['qualified']) $result['notes'][] = 'Below Teacher I 50-point cutoff or QS/Docs not satisfied.';
            $result['ies'] = self::generateIES($applicant, 'Teacher I', $scores);

        } elseif (stripos($positionTitle, 'School Administration') !== false || stripos($positionTitle, 'Administration') !== false) {
            $scores = self::scoreSchoolAdministration($applicant);
            $result['scores'] = $scores;
            $result['total_score'] = array_sum($scores['breakdown']);
            $result['qualified'] = $qsCheck['meets'] && empty($missing);
            $result['ies'] = self::generateIES($applicant, $positionTitle, $scores);

        } elseif (stripos($positionTitle, 'Related') !== false || stripos($positionTitle, 'Related Teaching') !== false) {
            $scores = self::scoreRelatedTeaching($applicant, $salaryGrade ?? 11);
            $result['scores'] = $scores;
            $result['total_score'] = array_sum($scores['breakdown']);
            $result['qualified'] = $qsCheck['meets'] && empty($missing);
            $result['ies'] = self::generateIES($applicant, $positionTitle, $scores);

        } elseif (stripos($positionTitle, 'Clerk') !== false || stripos($positionTitle, 'Utility') !== false || stripos($positionTitle, 'Non-Teaching') !== false || stripos($positionTitle, 'General Services') !== false) {
            $scores = self::scoreNonTeaching($applicant, $salaryGrade, $isGeneralServices);
            $result['scores'] = $scores;
            $result['total_score'] = array_sum($scores['breakdown']);
            $result['qualified'] = $qsCheck['meets'] && empty($missing);
            $result['ies'] = self::generateIES($applicant, $positionTitle, $scores);

        } else {
            // Generic fallback
            $ete = self::calculateETE($applicant, $positionTitle);
            $scores = ['ete'=>$ete, 'breakdown'=>['education'=>$ete['education'],'training'=>$ete['training'],'experience'=>$ete['experience']]];
            $total = array_sum($scores['breakdown']);
            $result['scores'] = $scores;
            $result['total_score'] = $total;
            $result['qualified'] = $qsCheck['meets'] && empty($missing);
            $result['ies'] = self::generateIES($applicant, $positionTitle, $scores);
        }

        return $result;
    }

    // Validate presence of checklist keys in applicant data
    public static function validateCompleteness(array $applicant)
    {
        $missing = [];
        foreach (self::$CHECKLIST as $k) {
            if (empty($applicant[$k])) $missing[] = $k;
        }
        return $missing;
    }

    // Return QS array for position (fallback to generic)
    public static function getQS(string $positionTitle)
    {
        return self::$QUAL_STANDARDS[$positionTitle] ?? [
            'education' => 'See position spec', 'min_experience_years' => 0, 'eligibility' => [], 'qs_text' => ''
        ];
    }

    // Check applicant against QS (education, experience years, eligibility)
    public static function checkQS(array $applicant, array $qs)
    {
        $meets = true;
        $reasons = [];
        // Education check (simple string presence match)
        if (!empty($qs['education'])) {
            $found = false;
            if (!empty($applicant['education_level'])) {
                // naive text match: degree name contains keyword from QS
                if (stripos($applicant['education_level'], strtok($qs['education'], ' ')) !== false) $found = true;
            }
            if (!$found) { $meets = false; $reasons[] = 'education'; }
        }
        // Experience
        $minExp = $qs['min_experience_years'] ?? 0;
        $years = isset($applicant['experience_years']) ? floatval($applicant['experience_years']) : 0;
        if ($years < $minExp) { $meets = false; $reasons[] = 'experience'; }
        // Eligibility
        if (!empty($qs['eligibility'])) {
            $hasElig = false;
            if (!empty($applicant['eligibilities']) && is_array($applicant['eligibilities'])) {
                foreach ($applicant['eligibilities'] as $e) {
                    if (in_array($e, $qs['eligibility'])) { $hasElig = true; break; }
                }
            }
            if (!$hasElig) { $meets = false; $reasons[] = 'eligibility'; }
        }
        return ['meets' => $meets, 'reasons' => $reasons];
    }

    // Simple ETE calculation returning each subscore (education, training, experience) max 10 each
    public static function calculateETE(array $applicant, string $positionTitle)
    {
        // Education: map degree to 0-10
        $education = 0;
        $deg = $applicant['education_level'] ?? '';
        if ($deg) {
            $d = strtolower($deg);
            if (strpos($d, 'doctor') !== false || strpos($d, 'phd') !== false) $education = 10;
            elseif (strpos($d, 'master') !== false) $education = 8;
            elseif (strpos($d, 'bachelor') !== false) $education = 7;
            else $education = 4;
        }

        // Training: based on training_hours (0-10 mapping for up to 80 hours)
        $training_hours = floatval($applicant['training_hours'] ?? 0);
        $training = min(10, round(($training_hours / 80) * 10));

        // Experience: full years, convert part-time proportionally
        $years = floatval($applicant['experience_years'] ?? 0);
        // Cap experience mapping so that >=10 years -> 10 points
        $experience = min(10, round(($years / 10) * 10));

        return ['education'=>$education,'training'=>$training,'experience'=>$experience];
    }

    // Teacher I specialized scoring
    // Expects applicant fields: 'education_level','training_hours','experience_years','pb_et_rating', 'cot_rating','trf_rating'
    public static function scoreTeacherI(array $applicant)
    {
        $ete = self::calculateETE($applicant,'Teacher I');

        // d. PBET/LET/LEPT Rating: (Rating/100)*10
        $pb = floatval($applicant['pb_et_rating'] ?? 0);
        $d = round(($pb / 100) * self::$WEIGHTS['Teacher I']['pb_et'], 2);

        // e. PPST COIs: (COT_Rating/30)*35
        $cot = floatval($applicant['cot_rating'] ?? 0);
        $e = round(($cot / 30) * self::$WEIGHTS['Teacher I']['ppst_cois'], 2);

        // f. PPST NCOIs: (TRF_Rating/20)*25
        $trf = floatval($applicant['trf_rating'] ?? 0);
        $f = round(($trf / 20) * self::$WEIGHTS['Teacher I']['ppst_ncois'], 2);

        $breakdown = [
            'education' => $ete['education'],
            'training' => $ete['training'],
            'experience' => $ete['experience'],
            'pb_et' => $d,
            'ppst_cois' => $e,
            'ppst_ncois' => $f,
        ];

        return ['ete'=>$ete, 'breakdown'=>$breakdown];
    }

    // Generate a simple Individual Evaluation Sheet (IES) array
    public static function generateIES(array $applicant, string $positionTitle, array $scores)
    {
        $ies = [
            'applicant' => $applicant['personal_information'] ?? ['name'=>$applicant['name'] ?? 'Unknown'],
            'position' => $positionTitle,
            'scores' => $scores['breakdown'] ?? $scores,
            'generated_at' => date('c'),
        ];
        return $ies;
    }

    // Utility: return position groups mapping for UI
    public static function getPositionGroups()
    {
        if (self::$POSITION_GROUPS === null) {
            self::buildPositionGroups();
        }
        return self::$POSITION_GROUPS;
    }

    // Return detailed QS text for UI autopopulation
    public static function getQSText(string $positionTitle)
    {
        $qs = self::getQS($positionTitle);
        return $qs['qs_text'] ?? '';
    }

    // Weight selection helper based on position and optional salary grade
    public static function getWeightsForPosition(string $positionTitle, $salaryGrade = null, $isGeneralServices = false)
    {
        if ($positionTitle === 'Teacher I') return self::$WEIGHTS['Teacher I'];
        if ($positionTitle === 'School Administration') return self::$WEIGHTS['School Administration'];

        // Related-Teaching weights by SG buckets
        if (stripos($positionTitle, 'Related') !== false || stripos($positionTitle, 'Related Teaching') !== false) {
            // default SG handling
            if ($salaryGrade === null) $salaryGrade = 11;
            if ($salaryGrade >= 11 && $salaryGrade <= 15) {
                return ['education'=>10,'training'=>10,'experience'=>10,'performance'=>20,'outstanding'=>10,'application_of_education'=>10,'application_of_ld'=>10,'potential'=>20];
            } elseif (($salaryGrade >= 16 && $salaryGrade <= 23) || $salaryGrade == 27) {
                return ['education'=>10,'training'=>10,'experience'=>10,'performance'=>20,'outstanding'=>5,'application_of_education'=>15,'application_of_ld'=>10,'potential'=>20];
            } elseif ($salaryGrade == 24) {
                return ['education'=>10,'training'=>10,'experience'=>10,'performance'=>25,'outstanding'=>10,'application_of_education'=>10,'application_of_ld'=>10,'potential'=>15];
            }
        }

        // Non-Teaching
        if ($isGeneralServices) {
            return ['education'=>5,'training'=>5,'experience'=>20,'performance'=>10,'outstanding'=>5,'application_of_education'=>0,'application_of_ld'=>0,'potential'=>55];
        }
        // SG buckets for other non-teaching
        if ($salaryGrade !== null) {
            if ($salaryGrade >=1 && $salaryGrade <=9) {
                return ['education'=>5,'training'=>5,'experience'=>20,'performance'=>20,'outstanding'=>10,'application_of_education'=>10,'application_of_ld'=>10,'potential'=>20];
            }
            if (($salaryGrade >=10 && $salaryGrade <=22) || $salaryGrade == 27) {
                return ['education'=>5,'training'=>10,'experience'=>15,'performance'=>20,'outstanding'=>10,'application_of_education'=>10,'application_of_ld'=>10,'potential'=>20];
            }
            if ($salaryGrade == 24) {
                return ['education'=>10,'training'=>5,'experience'=>15,'performance'=>20,'outstanding'=>10,'application_of_education'=>10,'application_of_ld'=>10,'potential'=>20];
            }
        }

        // fallback: simple ETE weights
        return ['education'=>10,'training'=>10,'experience'=>10,'performance'=>30,'outstanding'=>10,'application_of_education'=>10,'application_of_ld'=>10,'potential'=>10];
    }

    // School Administration scoring
    public static function scoreSchoolAdministration(array $applicant)
    {
        $weights = self::getWeightsForPosition('School Administration');
        $ete = self::calculateETE($applicant,'School Administration');

        // performance: expect 0-5 scale
        $pr = floatval($applicant['performance_rating'] ?? 0);
        $performance = round(($pr / 5) * $weights['performance'], 2);

        // Outstanding accomplishments: sum subcomponents with caps
        $awards = min(4, floatval($applicant['awards_points'] ?? 0));
        $research = min(4, floatval($applicant['research_points'] ?? 0));
        $sme = min(3, floatval($applicant['sme_points'] ?? 0));
        $speak = min(2, floatval($applicant['speak_points'] ?? 0));
        $neap = min(2, floatval($applicant['neap_points'] ?? 0));
        $outstanding_total = $awards + $research + $sme + $speak + $neap;
        // scale outstanding_total (max 15) into weight cap
        $outstanding = round(($outstanding_total / 15) * $weights['outstanding'], 2);

        // application of education: raw points capped by criterion max
        $app_edu = min(max(0, floatval($applicant['application_of_education'] ?? 0)), $weights['application_of_education']);
        $app_ld = round((floatval($applicant['application_of_ld'] ?? 0) / 10) * $weights['application_of_ld'], 2);

        // potential: combine written exam (0-100) and BEI (0-5) normalized to weight
        $written = floatval($applicant['written_exam'] ?? 0);
        $bei = floatval($applicant['bei_rating'] ?? 0);
        $potential = round((($written/100)*0.7 + ($bei/5)*0.3) * $weights['potential'], 2);

        $breakdown = [
            'education'=>$ete['education'],'training'=>$ete['training'],'experience'=>$ete['experience'],
            'performance'=>$performance,'outstanding'=>$outstanding,'application_of_education'=>$app_edu,'application_of_ld'=>$app_ld,'potential'=>$potential
        ];

        return ['ete'=>$ete,'breakdown'=>$breakdown];
    }

    // Related-Teaching scoring (takes salary grade into account)
    public static function scoreRelatedTeaching(array $applicant, $salaryGrade = 11)
    {
        $weights = self::getWeightsForPosition('Related Teaching', $salaryGrade);
        $ete = self::calculateETE($applicant,'Related Teaching');

        // performance: try RPMS (0-5) or board_rating (0-100)
        if (!empty($applicant['rpms_rating'])) {
            $pr = floatval($applicant['rpms_rating']);
            $performance = round(($pr / 5) * $weights['performance'], 2);
        } else {
            $br = floatval($applicant['board_rating'] ?? 0);
            $performance = round(($br / 100) * $weights['performance'], 2);
        }

        $outstanding = round((min(10, floatval($applicant['outstanding_points'] ?? 0))/10) * $weights['outstanding'],2);
        $app_edu = min(max(0, floatval($applicant['application_of_education'] ?? 0)), $weights['application_of_education']);
        $app_ld = round((floatval($applicant['application_of_ld'] ?? 0)/10) * $weights['application_of_ld'],2);
        $potential = round((floatval($applicant['written_exam'] ?? 0)/100) * $weights['potential'],2);

        $breakdown = ['education'=>$ete['education'],'training'=>$ete['training'],'experience'=>$ete['experience'],'performance'=>$performance,'outstanding'=>$outstanding,'application_of_education'=>$app_edu,'application_of_ld'=>$app_ld,'potential'=>$potential];
        return ['ete'=>$ete,'breakdown'=>$breakdown];
    }

    // Non-Teaching scoring
    public static function scoreNonTeaching(array $applicant, $salaryGrade = null, $isGeneralServices = false)
    {
        $weights = self::getWeightsForPosition('Non-Teaching', $salaryGrade, $isGeneralServices);
        $ete = self::calculateETE($applicant,'Non-Teaching');

        $performance = round((floatval($applicant['performance_rating'] ?? 0)/5) * $weights['performance'],2);
        $outstanding = round((min(10,floatval($applicant['outstanding_points'] ?? 0))/10) * $weights['outstanding'],2);
        $app_edu = min(max(0, floatval($applicant['application_of_education'] ?? 0)), $weights['application_of_education']);
        $app_ld = round((floatval($applicant['application_of_ld'] ?? 0)/10) * $weights['application_of_ld'],2);
        $potential = round((floatval($applicant['potential_score'] ?? 0)/100) * $weights['potential'],2);

        $breakdown = ['education'=>$ete['education'],'training'=>$ete['training'],'experience'=>$ete['experience'],'performance'=>$performance,'outstanding'=>$outstanding,'application_of_education'=>$app_edu,'application_of_ld'=>$app_ld,'potential'=>$potential];
        return ['ete'=>$ete,'breakdown'=>$breakdown];
    }

}


?>
