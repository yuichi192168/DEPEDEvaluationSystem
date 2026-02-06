<?php
/**
 * Evaluation Storage Helper
 * Stores and retrieves evaluations from database for Annex G-1 consolidation
 */

require_once __DIR__ . '/DBConnection.php';

// Only define class once
if (!class_exists('EvaluationStorage', false)) {

class EvaluationStorage {
    private $conn;
    
    public function __construct() {
        // Use singleton connection
        $this->conn = DBConnection::getConnection();
    }
    
    /**
     * Save evaluation to database
     */
    public function saveEvaluation($evaluation, $additionalData = []) {
        // Start transaction
        $this->conn->begin_transaction();
        
        try {
            // Insert or get applicant
            $applicantId = $this->getOrCreateApplicant(
                $evaluation['applicant_name'],
                $evaluation['position_applied'],
                $evaluation['position_group'],
                $additionalData
            );
            
            // Insert or get position
            $positionId = $this->getOrCreatePosition(
                $evaluation['position_applied'],
                $evaluation['position_group']
            );
            
            // Insert evaluation
            $stmt = $this->conn->prepare("
                INSERT INTO evaluations (
                    applicant_id, position_id, position_group, total_score, 
                    evaluation_date, evaluator_name, status, notes
                ) VALUES (?, ?, ?, ?, CURDATE(), ?, 'pending', ?)
            ");
            
            $evaluatorName = $additionalData['hrmpsb_chair'] ?? '';
            $notes = $additionalData['notes'] ?? '';
            
            $stmt->bind_param(
                "iissss",
                $applicantId,
                $positionId,
                $evaluation['position_group'],
                $evaluation['total_score'],
                $evaluatorName,
                $notes
            );
            
            $stmt->execute();
            $evaluationId = $this->conn->insert_id;
            
            // Insert evaluation details
            foreach ($evaluation['criteria'] as $criterion => $details) {
                $detailStmt = $this->conn->prepare("
                    INSERT INTO evaluation_details (
                        evaluation_id, criterion, applicant_qualification, applicant_level,
                        baseline_qualification, baseline_level, increment, weight, points, final_score
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                // Prepare all variables for binding (cannot use ?? directly in bind_param)
                $increment = $details['increment'] ?? 0;
                $weight = $details['weight'] ?? 0;
                $points = $details['points'] ?? 0;
                $applicantQual = $details['applicant_qualification'] ?? '';
                $applicantLvl = $details['applicant_level'] ?? 0;
                $baselineQual = $details['baseline_qualification'] ?? '';
                $baselineLvl = $details['baseline_level'] ?? 0;
                $finalScore = $details['final_score'] ?? 0;
                
                $detailStmt->bind_param(
                    "issisiiidd",
                    $evaluationId,
                    $criterion,
                    $applicantQual,
                    $applicantLvl,
                    $baselineQual,
                    $baselineLvl,
                    $increment,
                    $weight,
                    $points,
                    $finalScore
                );
                
                $detailStmt->execute();
            }
            
            // Commit transaction
            $this->conn->commit();
            return $evaluationId;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }
    
    /**
     * Get all evaluations for a specific position
     */
    public function getEvaluationsByPosition($positionName) {
        $stmt = $this->conn->prepare("
            SELECT 
                e.id,
                e.total_score,
                e.evaluation_date,
                e.notes as remarks,
                a.name as applicant_name,
                p.position_name,
                p.position_group,
                GROUP_CONCAT(
                    CONCAT(ed.criterion, ':', ed.final_score)
                    ORDER BY ed.criterion SEPARATOR '|'
                ) as criteria_scores
            FROM evaluations e
            INNER JOIN applicants a ON e.applicant_id = a.id
            LEFT JOIN positions p ON e.position_id = p.id
            LEFT JOIN evaluation_details ed ON e.id = ed.evaluation_id
            WHERE (p.position_name = ? OR e.notes LIKE ?) AND a.archive_status = 'active'
            GROUP BY e.id
            ORDER BY e.total_score DESC
        ");
        
        $positionLike = '%' . $positionName . '%';
        $stmt->bind_param("ss", $positionName, $positionLike);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $evaluations = [];
        
        while ($row = $result->fetch_assoc()) {
            // Reconstruct evaluation structure
            $evaluation = [
                'id' => $row['id'],
                'applicant_name' => $row['applicant_name'],
                'position_applied' => $row['position_name'],
                'position_group' => $row['position_group'],
                'total_score' => floatval($row['total_score']),
                'remarks' => $row['remarks'],
                'criteria' => []
            ];
            
            // Fetch full details for each criterion
            $detailStmt = $this->conn->prepare("
                SELECT * FROM evaluation_details 
                WHERE evaluation_id = ? 
                ORDER BY criterion
            ");
            $detailStmt->bind_param("i", $row['id']);
            $detailStmt->execute();
            $detailResult = $detailStmt->get_result();
            
            while ($detail = $detailResult->fetch_assoc()) {
                $evaluation['criteria'][$detail['criterion']] = [
                    'applicant_qualification' => $detail['applicant_qualification'] ?? '',
                    'applicant_level' => intval($detail['applicant_level'] ?? 0),
                    'baseline_qualification' => $detail['baseline_qualification'] ?? '',
                    'baseline_level' => intval($detail['baseline_level'] ?? 0),
                    'increment' => intval($detail['increment'] ?? 0),
                    'weight' => intval($detail['weight'] ?? 0),
                    'points' => floatval($detail['points'] ?? 0),
                    'final_score' => floatval($detail['final_score'] ?? 0)
                ];
            }
            
            $evaluations[] = $evaluation;
        }
        
        return $evaluations;
    }
    
    /**
     * Get applicant or create if not exists
     */
    private function getOrCreateApplicant($name, $positionName, $positionGroup, $additionalData) {
        // Try to find existing applicant
        $stmt = $this->conn->prepare("SELECT id FROM applicants WHERE name = ? LIMIT 1");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row['id'];
        }
        
        // Create new applicant
        $positionId = $this->getOrCreatePosition($positionName, $positionGroup);
        
        $stmt = $this->conn->prepare("
            INSERT INTO applicants (name, position_applied_id, position_group) 
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("sis", $name, $positionId, $positionGroup);
        $stmt->execute();
        
        return $this->conn->insert_id;
    }
    
    /**
     * Get position or create if not exists
     */
    private function getOrCreatePosition($positionName, $positionGroup) {
        // Try to find existing position
        $stmt = $this->conn->prepare("SELECT id FROM positions WHERE position_name = ? LIMIT 1");
        $stmt->bind_param("s", $positionName);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row['id'];
        }
        
        // Create new position
        $stmt = $this->conn->prepare("
            INSERT INTO positions (position_name, position_group, description) 
            VALUES (?, ?, ?)
        ");
        $description = "Position Group " . $positionGroup;
        $stmt->bind_param("sss", $positionName, $positionGroup, $description);
        $stmt->execute();
        
        return $this->conn->insert_id;
    }
    
    /**
     * Get comparative assessment results for a position with ranking
     */
    public function getComparativeAssessmentResults($positionId) {
        // Retrieve draft data to recalculate scores with current formulas
        $query = "
            SELECT 
                car.id,
                car.position_id,
                car.applicant_id,
                car.application_code,
                car.total_score,
                car.remarks,
                a.name as applicant_name,
                p.position_name,
                p.position_group,
                d.data as draft_data
            FROM comparative_assessment_results car
            INNER JOIN applicants a ON car.applicant_id = a.id
            INNER JOIN positions p ON car.position_id = p.id
            LEFT JOIN drafts d ON car.application_code = d.application_code COLLATE utf8mb4_unicode_ci
            WHERE car.position_id = ? AND a.archive_status = 'active'
            ORDER BY car.total_score DESC
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $positionId);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $results = [];
        $rank = 1;
        $previousScore = null;
        
        require_once __DIR__ . '/../config/baseline_library.php';
        require_once __DIR__ . '/../classes/HRMPSBEvaluator.php';
        
        while ($row = $result->fetch_assoc()) {
            $draftData = [];
            $totalScore = floatval($row['total_score']);
            $scores = [
                'education_score' => 0,
                'training_score' => 0,
                'experience_score' => 0,
                'performance_score' => 0,
                'outstanding_accomplishments_score' => 0,
                'application_of_education_score' => 0,
                'application_of_ld_score' => 0,
                'potential_score' => 0
            ];
            
            // Parse draft data if available
            if (!empty($row['draft_data'])) {
                $draftData = json_decode($row['draft_data'], true) ?: [];
            }
            
            // Recalculate scores from applicant data if draft data available
            if (!empty($draftData)) {
                try {
                    // Initialize evaluator
                    $evaluator = new HRMPSBEvaluator('NON-TEACHING LEVEL II'); // Default, will be overridden
                    
                    // Get position group if available
                    $posGroup = $row['position_group'] ?? 'NON-TEACHING LEVEL II';
                    $evaluator = new HRMPSBEvaluator($posGroup);
                    
                    // Get baseline using position_key from draft
                    $allPos = getAllPositions();
                    $positionKey = $draftData['position_key'] ?? 'custom';
                    $baseline = getBaselineForPosition($positionKey);
                    
                    // Build applicant data from draft
                    $appData = [
                        'name' => $row['applicant_name'],
                        'position' => $row['position_name'],
                        'education' => [
                            'degree' => $draftData['applicant_education_degree'] ?? 'None',
                            'masters_units' => intval($draftData['applicant_education_masters_units'] ?? 0),
                            'doctoral_units' => intval($draftData['applicant_education_doctoral_units'] ?? 0)
                        ],
                        'training' => floatval($draftData['applicant_training'] ?? 0),
                        'experience' => floatval($draftData['applicant_experience'] ?? 0),
                        'performance' => floatval($draftData['applicant_performance'] ?? 0),
                        'outstanding_accomplishments' => floatval($draftData['applicant_outstanding_accomplishments'] ?? 0),
                        'application_of_education' => floatval($draftData['applicant_application_of_education'] ?? 0),
                        'application_of_ld' => floatval($draftData['applicant_application_of_ld'] ?? 0),
                        'potential' => floatval($draftData['applicant_potential'] ?? 0)
                    ];
                    
                    // Build baseline from database
                    $baseData = [
                        'name' => 'Baseline',
                        'position' => $row['position_name'],
                        'education' => [
                            'degree' => $baseline['education']['degree'] ?? 'None',
                            'masters_units' => intval($baseline['education']['masters_units'] ?? 0),
                            'doctoral_units' => intval($baseline['education']['doctoral_units'] ?? 0)
                        ],
                        'training' => floatval($baseline['training'] ?? 0),
                        'experience' => floatval($baseline['experience'] ?? 0),
                        'performance' => floatval($baseline['performance'] ?? 0),
                        'outstanding_accomplishments' => floatval($baseline['outstanding_accomplishments'] ?? 0),
                        'application_of_education' => floatval($baseline['application_of_education'] ?? 0),
                        'application_of_ld' => floatval($baseline['application_of_ld'] ?? 0),
                        'potential' => floatval($baseline['potential'] ?? 0)
                    ];
                    
                    // Perform evaluation with current formulas
                    $evaluation = $evaluator->evaluateApplicant($appData, $baseData);
                    
                    // Extract recalculated scores
                    if (isset($evaluation['criteria'])) {
                        $scores['education_score'] = floatval($evaluation['criteria']['education']['final_score'] ?? 0);
                        $scores['training_score'] = floatval($evaluation['criteria']['training']['final_score'] ?? 0);
                        $scores['experience_score'] = floatval($evaluation['criteria']['experience']['final_score'] ?? 0);
                        $scores['performance_score'] = floatval($evaluation['criteria']['performance']['final_score'] ?? 0);
                        $scores['outstanding_accomplishments_score'] = floatval($evaluation['criteria']['outstanding_accomplishments']['final_score'] ?? 0);
                        $scores['application_of_education_score'] = floatval($evaluation['criteria']['application_of_education']['final_score'] ?? 0);
                        $scores['application_of_ld_score'] = floatval($evaluation['criteria']['application_of_ld']['final_score'] ?? 0);
                        $scores['potential_score'] = floatval($evaluation['criteria']['potential']['final_score'] ?? 0);
                        
                        // Recalculate total score with current formulas
                        $totalScore = array_sum($scores);
                    }
                } catch (Exception $e) {
                    // If recalculation fails, fall back to database values
                    error_log("Score recalculation failed for applicant {$row['applicant_name']}: {$e->getMessage()}");
                }
            }
            
            // Apply ranking logic
            if ($previousScore === null || $previousScore != $totalScore) {
                $rank = count($results) + 1;
                $previousScore = $totalScore;
            }
            
            $results[] = [
                'id' => $row['id'],
                'applicant_name' => $row['applicant_name'],
                'application_code' => $row['application_code'],
                'position_applied' => $row['position_name'],
                'position_group' => $row['position_group'],
                'education_score' => $scores['education_score'],
                'training_score' => $scores['training_score'],
                'experience_score' => $scores['experience_score'],
                'performance_score' => $scores['performance_score'],
                'outstanding_accomplishments_score' => $scores['outstanding_accomplishments_score'],
                'application_of_education_score' => $scores['application_of_education_score'],
                'application_of_ld_score' => $scores['application_of_ld_score'],
                'potential_score' => $scores['potential_score'],
                'total_score' => $totalScore,
                'rank' => $rank,
                'remarks' => $row['remarks']
            ];
        }
        
        return $results;
    }
}

} // end if !class_exists
?>
