<?php
/**
 * DepEd HRMPSB Comparative Assessment System
 * Based on DepEd Order No. 007, s. 2023
 * 
 * Implements Increment Method for Comparative Assessment
 */

require_once __DIR__ . '/../config/evaluation_criteria.php';

// Only define class once
if (!class_exists('HRMPSBEvaluator', false)) {

class HRMPSBEvaluator {
    
    private $positionGroup;
    private $salaryGrade;
    private $category;
    private $weights;
    
    public function __construct($positionGroup = 'TEACHING POSITIONS', $salaryGrade = null, $category = null) {
        $this->positionGroup = $positionGroup;
        $this->salaryGrade = $salaryGrade;
        $this->category = $category;
        $this->setWeights();
    }
    
    /**
     * Set position group
     */
    public function setPositionGroup($positionGroup) {
        $this->positionGroup = $positionGroup;
        $this->setWeights();
    }
    
    /**
     * Set salary grade
     */
    public function setSalaryGrade($salaryGrade) {
        $this->salaryGrade = $salaryGrade;
        $this->setWeights();
    }
    
    /**
     * Set category for non-teaching positions
     */
    public function setCategory($category) {
        $this->category = $category;
        $this->setWeights();
    }
    
    /**
     * Set weights based on position group using evaluation_criteria.php
     */
    private function setWeights() {
        // Get criteria from evaluation_criteria.php
        $criteria = getEvaluationCriteria($this->positionGroup, $this->salaryGrade, $this->category);
        
        if ($criteria && isset($criteria['criteria'])) {
            // Build weights array from criteria
            $this->weights = [
                'education' => $criteria['criteria']['a']['max_points'] ?? 10,
                'training' => $criteria['criteria']['b']['max_points'] ?? 10,
                'experience' => $criteria['criteria']['c']['max_points'] ?? 10,
                'performance' => $criteria['criteria']['d']['max_points'] ?? 10,
                'outstanding_accomplishments' => $criteria['criteria']['e']['max_points'] ?? 10,
                'application_of_education' => $criteria['criteria']['f']['max_points'] ?? 10,
                'application_of_ld' => $criteria['criteria']['g']['max_points'] ?? 10,
                'potential' => $criteria['criteria']['h']['max_points'] ?? 10,
            ];
        } else {
            // Fallback to default weights if criteria not found
            $this->weights = [
                'education' => 10,
                'training' => 10,
                'experience' => 10,
                'performance' => 10,
                'outstanding_accomplishments' => 10,
                'application_of_education' => 10,
                'application_of_ld' => 10,
                'potential' => 10,
            ];
        }
    }
    
    /**
     * Convert Education qualification to Level
     * 
     * Level 6: Bachelor's Degree
     * Levels 7-20: Bachelor's with Master's units (increase by 1 per 3 units)
     * Level 21: Master's Degree
     * Levels 22-30: Master's with Doctoral units (increase by 1 per 3 units)
     * Level 31: Doctorate Degree
     */
    public function convertEducationToLevel($education, $mastersUnits = 0, $doctoralUnits = 0) {
        $level = 0;
        
        if (stripos($education, 'doctorate') !== false || stripos($education, 'phd') !== false || stripos($education, 'ph.d') !== false) {
            $level = 31;
        } elseif (stripos($education, 'master') !== false) {
            $level = 21;
            if ($doctoralUnits > 0) {
                $additionalLevels = floor($doctoralUnits / 3);
                $level += min($additionalLevels, 9); // Max level 30 for Master's with Doctoral units
            }
        } elseif (stripos($education, 'bachelor') !== false || stripos($education, 'college') !== false) {
            $level = 6;
            if ($mastersUnits > 0) {
                $additionalLevels = floor($mastersUnits / 3);
                $level += min($additionalLevels, 14); // Max level 20 for Bachelor's with Master's units
            }
        }
        
        return $level;
    }
    
    /**
     * Convert Training hours to Level
     * 
     * Level 1: 0 to less than 8 hours
     * Level 2: 8 to less than 16 hours
     * Level 3: 16 to less than 24 hours
     * Level 4: 24 to less than 32 hours
     * Increase by 1 for every additional 8 hours
     */
    public function convertTrainingToLevel($hours) {
        if ($hours < 8) {
            return 1;
        }
        
        return floor($hours / 8) + 1;
    }
    
    /**
     * Convert Experience to Level
     * 
     * Level 1: 0 to less than 6 months
     * Level 2: 6 months to less than 1 year
     * Level 3: 1 year to less than 1.5 years
     * Level 4: 1.5 years to less than 2 years
     * Level 5: 2 years to less than 2.5 years
     * Increase by 1 for every additional 6 months
     */
    public function convertExperienceToLevel($months) {
        if ($months < 6) {
            return 1;
        }
        
        return floor($months / 6) + 1;
    }
    
    /**
     * Calculate Increment
     * Formula: Applicant Level - Baseline Level = Increment
     * Rule: If Applicant Level < Baseline Level, increment = 0
     */
    public function calculateIncrement($applicantLevel, $baselineLevel) {
        $increment = $applicantLevel - $baselineLevel;
        return max(0, $increment); // No negative increments
    }
    
    /**
     * Convert Increment to Points using Range Rubric
     * 
     * For weight = 10 points:
     * 10+ increments = 10 points
     * 8-9 increments = 8 points
     * 6-7 increments = 6 points
     * 4-5 increments = 4 points
     * 2-3 increments = 2 points
     * 0-1 increments = 0 points
     * 
     * Scaling:
     * - If weight is 20 points, multiply by 2
     * - If weight is 5 points, divide by 2
     */
    public function convertIncrementToPoints($increment, $weight) {
        // Base points for 10-point weight
        $basePoints = 0;
        
        if ($increment >= 10) {
            $basePoints = 10;
        } elseif ($increment >= 8) {
            $basePoints = 8;
        } elseif ($increment >= 6) {
            $basePoints = 6;
        } elseif ($increment >= 4) {
            $basePoints = 4;
        } elseif ($increment >= 2) {
            $basePoints = 2;
        } else {
            $basePoints = 0;
        }
        
        // Scale based on weight
        if ($weight == 20) {
            return $basePoints * 2;
        } elseif ($weight == 5) {
            return $basePoints / 2;
        } elseif ($weight == 15) {
            return $basePoints * 1.5;
        } elseif ($weight == 25) {
            return $basePoints * 2.5;
        } else {
            return $basePoints;
        }
    }

    /**
     * Convert a 1..5 rating into weighted points.
     * Formula: (rating / maxRating) * weight
     */
    public function convertRatingToWeightedPoints($rating, $weight, $maxRating = 5) {
        $rating = floatval($rating);
        $weight = floatval($weight);
        $maxRating = floatval($maxRating);

        if ($maxRating <= 0) {
            return 0;
        }

        // Clamp rating into [0, maxRating]
        $rating = max(0, min($rating, $maxRating));

        return ($rating / $maxRating) * $weight;
    }
    
    /**
     * Evaluate applicant and generate complete assessment
     */
    public function evaluateApplicant($applicantData, $baselineData) {
        $evaluation = [
            'applicant_name' => $applicantData['name'] ?? '',
            'position_applied' => $applicantData['position'] ?? '',
            'position_group' => $this->positionGroup,
            'criteria' => [],
            'total_score' => 0,
            'missing_documents' => [],
            'baseline_requirements' => []
        ];
        
        // 1. Education
        $eduResult = $this->evaluateEducation(
            $applicantData['education'] ?? [],
            $baselineData['education'] ?? []
        );
        $evaluation['criteria']['education'] = $eduResult;
        $evaluation['total_score'] += $eduResult['final_score'];
        
        // 2. Training
        $trainingResult = $this->evaluateTraining(
            $applicantData['training'] ?? 0,
            $baselineData['training'] ?? 0
        );
        $evaluation['criteria']['training'] = $trainingResult;
        $evaluation['total_score'] += $trainingResult['final_score'];
        
        // 3. Experience
        $expResult = $this->evaluateExperience(
            $applicantData['experience'] ?? 0,
            $baselineData['experience'] ?? 0
        );
        $evaluation['criteria']['experience'] = $expResult;
        $evaluation['total_score'] += $expResult['final_score'];
        
        // 4. Performance (placeholder - requires actual performance rating)
        $perfResult = $this->evaluatePerformance(
            $applicantData['performance'] ?? 0,
            $baselineData['performance'] ?? 0
        );
        $evaluation['criteria']['performance'] = $perfResult;
        $evaluation['total_score'] += $perfResult['final_score'];
        
        // 5. Outstanding Accomplishments
        $oaResult = $this->evaluateOutstandingAccomplishments(
            $applicantData['outstanding_accomplishments'] ?? 0,
            $baselineData['outstanding_accomplishments'] ?? 0
        );
        $evaluation['criteria']['outstanding_accomplishments'] = $oaResult;
        $evaluation['total_score'] += $oaResult['final_score'];
        
        // 6. Application of Education
        $aoeResult = $this->evaluateApplicationOfEducation(
            $applicantData['application_of_education'] ?? 0,
            $baselineData['application_of_education'] ?? 0
        );
        $evaluation['criteria']['application_of_education'] = $aoeResult;
        $evaluation['total_score'] += $aoeResult['final_score'];
        
        // 7. Application of L&D
        $aoldResult = $this->evaluateApplicationOfLD(
            $applicantData['application_of_ld'] ?? 0,
            $baselineData['application_of_ld'] ?? 0
        );
        $evaluation['criteria']['application_of_ld'] = $aoldResult;
        $evaluation['total_score'] += $aoldResult['final_score'];
        
        // 8. Potential
        $potResult = $this->evaluatePotential(
            $applicantData['potential'] ?? 0,
            $baselineData['potential'] ?? 0
        );
        $evaluation['criteria']['potential'] = $potResult;
        $evaluation['total_score'] += $potResult['final_score'];
        
        // Check baseline requirements
        $evaluation['baseline_requirements'] = $this->checkBaselineRequirements($applicantData, $baselineData);
        
        return $evaluation;
    }
    
    /**
     * Evaluate Education criterion
     */
    private function evaluateEducation($applicantEdu, $baselineEdu) {
        $applicantLevel = $this->convertEducationToLevel(
            $applicantEdu['degree'] ?? '',
            $applicantEdu['masters_units'] ?? 0,
            $applicantEdu['doctoral_units'] ?? 0
        );
        
        $baselineLevel = $this->convertEducationToLevel(
            $baselineEdu['degree'] ?? '',
            $baselineEdu['masters_units'] ?? 0,
            $baselineEdu['doctoral_units'] ?? 0
        );
        
        $increment = $this->calculateIncrement($applicantLevel, $baselineLevel);
        $weight = $this->weights['education'];
        $points = $this->convertIncrementToPoints($increment, $weight);
        
        return [
            'criterion' => 'Education',
            'applicant_qualification' => $this->formatEducationQualification($applicantEdu),
            'applicant_level' => $applicantLevel,
            'baseline_qualification' => $this->formatEducationQualification($baselineEdu),
            'baseline_level' => $baselineLevel,
            'increment' => $increment,
            'weight' => $weight,
            'points' => $points,
            'final_score' => $points
        ];
    }
    
    /**
     * Evaluate Training criterion
     */
    private function evaluateTraining($applicantHours, $baselineHours) {
        $applicantLevel = $this->convertTrainingToLevel($applicantHours);
        $baselineLevel = $this->convertTrainingToLevel($baselineHours);
        
        $increment = $this->calculateIncrement($applicantLevel, $baselineLevel);
        $weight = $this->weights['training'];
        $points = $this->convertIncrementToPoints($increment, $weight);
        
        return [
            'criterion' => 'Training',
            'applicant_qualification' => $applicantHours . ' hours',
            'applicant_level' => $applicantLevel,
            'baseline_qualification' => $baselineHours . ' hours',
            'baseline_level' => $baselineLevel,
            'increment' => $increment,
            'weight' => $weight,
            'points' => $points,
            'final_score' => $points
        ];
    }
    
    /**
     * Evaluate Experience criterion
     */
    private function evaluateExperience($applicantMonths, $baselineMonths) {
        $applicantLevel = $this->convertExperienceToLevel($applicantMonths);
        $baselineLevel = $this->convertExperienceToLevel($baselineMonths);
        
        $increment = $this->calculateIncrement($applicantLevel, $baselineLevel);
        $weight = $this->weights['experience'];
        $points = $this->convertIncrementToPoints($increment, $weight);
        
        return [
            'criterion' => 'Experience',
            'applicant_qualification' => $this->formatMonths($applicantMonths),
            'applicant_level' => $applicantLevel,
            'baseline_qualification' => $this->formatMonths($baselineMonths),
            'baseline_level' => $baselineLevel,
            'increment' => $increment,
            'weight' => $weight,
            'points' => $points,
            'final_score' => $points
        ];
    }
    
    /**
     * Evaluate Performance criterion
     */
    private function evaluatePerformance($applicantRating, $baselineRating) {
        // Weighted computation (non-increment criteria):
        // Score = (rating / 5) * weight
        $weight = $this->weights['performance'];
        $points = $this->convertRatingToWeightedPoints($applicantRating, $weight, 5);

        return [
            'criterion' => 'Performance',
            'applicant_qualification' => 'Rating ' . $applicantRating . ' / 5',
            'applicant_level' => floatval($applicantRating),
            'baseline_qualification' => $baselineRating,
            'baseline_level' => floatval($baselineRating),
            'increment' => null,
            'weight' => $weight,
            'points' => $points,
            'final_score' => $points
        ];
    }
    
    /**
     * Evaluate Outstanding Accomplishments
     */
    private function evaluateOutstandingAccomplishments($applicantCount, $baselineCount) {
        // Treat as direct points (from Enclosure 3 computation), capped by weight.
        $weight = $this->weights['outstanding_accomplishments'];
        $points = min(max(0, floatval($applicantCount)), $weight);

        return [
            'criterion' => 'Outstanding Accomplishments',
            'applicant_qualification' => $applicantCount . ' pt(s)',
            'applicant_level' => floatval($applicantCount),
            'baseline_qualification' => $baselineCount,
            'baseline_level' => floatval($baselineCount),
            'increment' => null,
            'weight' => $weight,
            'points' => $points,
            'final_score' => $points
        ];
    }
    
    /**
     * Evaluate Application of Education
     */
    private function evaluateApplicationOfEducation($applicantLevel, $baselineLevel) {
        $weight = $this->weights['application_of_education'];
        $points = $this->convertRatingToWeightedPoints($applicantLevel, $weight, 5);

        return [
            'criterion' => 'Application of Education',
            'applicant_qualification' => 'Rating ' . $applicantLevel . ' / 5',
            'applicant_level' => floatval($applicantLevel),
            'baseline_qualification' => $baselineLevel,
            'baseline_level' => floatval($baselineLevel),
            'increment' => null,
            'weight' => $weight,
            'points' => $points,
            'final_score' => $points
        ];
    }
    
    /**
     * Evaluate Application of L&D
     */
    private function evaluateApplicationOfLD($applicantLevel, $baselineLevel) {
        $weight = $this->weights['application_of_ld'];
        $points = $this->convertRatingToWeightedPoints($applicantLevel, $weight, 5);

        return [
            'criterion' => 'Application of L&D',
            'applicant_qualification' => 'Rating ' . $applicantLevel . ' / 5',
            'applicant_level' => floatval($applicantLevel),
            'baseline_qualification' => $baselineLevel,
            'baseline_level' => floatval($baselineLevel),
            'increment' => null,
            'weight' => $weight,
            'points' => $points,
            'final_score' => $points
        ];
    }
    
    /**
     * Evaluate Potential
     */
    private function evaluatePotential($applicantLevel, $baselineLevel) {
        $weight = $this->weights['potential'];
        $points = $this->convertRatingToWeightedPoints($applicantLevel, $weight, 5);

        return [
            'criterion' => 'Potential',
            'applicant_qualification' => 'Rating ' . $applicantLevel . ' / 5',
            'applicant_level' => floatval($applicantLevel),
            'baseline_qualification' => $baselineLevel,
            'baseline_level' => floatval($baselineLevel),
            'increment' => null,
            'weight' => $weight,
            'points' => $points,
            'final_score' => $points
        ];
    }
    
    /**
     * Check if applicant meets baseline requirements
     */
    private function checkBaselineRequirements($applicantData, $baselineData) {
        $requirements = [];
        
        // Check education baseline
        $applicantEduLevel = $this->convertEducationToLevel(
            $applicantData['education']['degree'] ?? '',
            $applicantData['education']['masters_units'] ?? 0,
            $applicantData['education']['doctoral_units'] ?? 0
        );
        $baselineEduLevel = $this->convertEducationToLevel(
            $baselineData['education']['degree'] ?? '',
            $baselineData['education']['masters_units'] ?? 0,
            $baselineData['education']['doctoral_units'] ?? 0
        );
        
        if ($applicantEduLevel < $baselineEduLevel) {
            $requirements[] = [
                'criterion' => 'Education',
                'status' => 'FAILED',
                'message' => 'Applicant does not meet minimum education requirement'
            ];
        }
        
        return $requirements;
    }
    
    /**
     * Format education qualification for display
     */
    private function formatEducationQualification($edu) {
        $degree = $edu['degree'] ?? '';
        $mastersUnits = $edu['masters_units'] ?? 0;
        $doctoralUnits = $edu['doctoral_units'] ?? 0;
        
        $qualification = $degree;
        
        if ($mastersUnits > 0) {
            $qualification .= " with {$mastersUnits} Master's units";
        }
        
        if ($doctoralUnits > 0) {
            $qualification .= " with {$doctoralUnits} Doctoral units";
        }
        
        return $qualification;
    }
    
    /**
     * Format months to readable format
     */
    private function formatMonths($months) {
        if ($months < 12) {
            return $months . ' month(s)';
        }
        
        $years = floor($months / 12);
        $remainingMonths = $months % 12;
        
        $result = $years . ' year(s)';
        if ($remainingMonths > 0) {
            $result .= ' and ' . $remainingMonths . ' month(s)';
        }
        
        return $result;
    }
    
    /**
     * Get weights for current position group
     */
    public function getWeights() {
        return $this->weights;
    }
}

} // end if !class_exists
?>

