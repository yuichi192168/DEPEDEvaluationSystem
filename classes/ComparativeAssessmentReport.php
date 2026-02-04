<?php
/**
 * Comparative Assessment Report (CAR) Manager
 * Handles storage, retrieval, and ranking of assessment results
 */

require_once 'DBConnection.php';

// Only define class once
if (!class_exists('ComparativeAssessmentReport', false)) {

class ComparativeAssessmentReport {
    
    private $conn;
    private $db;
    private $table = 'comparative_assessment_results';
    
    public function __construct() {
        $this->conn = DBConnection::getConnection();
    }
    
    /**
     * Get connection (ensures it's still alive)
     */
    private function getConnection() {
        if (!$this->conn || !$this->conn->ping()) {
            $this->conn = DBConnection::getConnection();
        }
        return $this->conn;
    }
    
    /**
     * Save comparative assessment result for an applicant
     * If evaluationId is provided, fetch actual scores from evaluation_details
     */
    public function saveResult($positionId, $applicantId, $scores, $remarks = '', $assessmentDate = null, $evaluationId = null) {
        if (!$assessmentDate) {
            $assessmentDate = date('Y-m-d');
        }
        
        try {
            $conn = $this->getConnection();
            
            // If evaluationId provided, fetch actual scores from evaluation_details
            if ($evaluationId) {
                $scores = $this->getScoresFromEvaluation($conn, $evaluationId, $scores);
            }
            
            // Check if record exists
            $checkQuery = "SELECT id FROM {$this->table} WHERE position_id = ? AND applicant_id = ?";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param('ii', $positionId, $applicantId);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            $exists = $result->num_rows > 0;
            $checkStmt->close();
            
            if ($exists) {
                return $this->updateResult($positionId, $applicantId, $scores, $remarks, $assessmentDate);
            } else {
                return $this->insertResult($positionId, $applicantId, $scores, $remarks, $assessmentDate);
            }
        } catch (Exception $e) {
            error_log("Error in saveResult: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Fetch actual scores from evaluation_details table
     */
    private function getScoresFromEvaluation($conn, $evaluationId, $baseScores) {
        $query = "
            SELECT 
                criterion,
                SUM(final_score) as total_score
            FROM evaluation_details
            WHERE evaluation_id = ?
            GROUP BY criterion
        ";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $evaluationId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $totalScore = 0;
        $criterionScores = [];
        
        while ($row = $result->fetch_assoc()) {
            $criterion = strtolower($row['criterion']);
            $score = floatval($row['total_score']);
            $criterionScores[$criterion] = $score;
            $totalScore += $score;
        }
        
        // Update scores array with actual values from database
        $scores = [
            'application_code' => $baseScores['application_code'] ?? '',
            'education' => $criterionScores['education'] ?? 0,
            'training' => $criterionScores['training'] ?? 0,
            'experience' => $criterionScores['experience'] ?? 0,
            'performance' => $criterionScores['performance'] ?? 0,
            'outstanding_accomplishments' => $criterionScores['outstanding_accomplishments'] ?? 0,
            'application_of_education' => $criterionScores['application_of_education'] ?? 0,
            'application_of_ld' => $criterionScores['application_of_ld'] ?? 0,
            'potential' => $criterionScores['potential'] ?? 0,
            'total_score' => $totalScore,
            'background_yes' => $baseScores['background_yes'] ?? false,
            'background_no' => $baseScores['background_no'] ?? false,
            'for_appointment' => $baseScores['for_appointment'] ?? false,
            'for_probation' => $baseScores['for_probation'] ?? false
        ];
        
        return $scores;
    }
    
    /**
     * Insert new assessment result
     */
    private function insertResult($positionId, $applicantId, $scores, $remarks, $assessmentDate) {
        $conn = $this->getConnection();
        $query = "INSERT INTO {$this->table} 
                  (position_id, applicant_id, application_code, education_score, training_score, 
                   experience_score, performance_score, outstanding_accomplishments_score, 
                   application_of_education_score, application_of_ld_score, potential_score, 
                   total_score, background_yes, background_no, for_appointment, 
                   for_probation, assessment_date)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return false;
        }
        
        $backgroundYes = isset($scores['background_yes']) ? $scores['background_yes'] : 0;
        $backgroundNo = isset($scores['background_no']) ? $scores['background_no'] : 0;
        $forAppointment = isset($scores['for_appointment']) ? $scores['for_appointment'] : 0;
        $forProbation = isset($scores['for_probation']) ? $scores['for_probation'] : 0;
        
        // Pre-assign all expressions to variables (required for bind_param)
        $appCode = $scores['application_code'] ?? '';
        $education = $scores['education'] ?? 0;
        $training = $scores['training'] ?? 0;
        $experience = $scores['experience'] ?? 0;
        $performance = $scores['performance'] ?? 0;
        $outstandingAccomplishments = $scores['outstanding_accomplishments'] ?? 0;
        $appOfEducation = $scores['application_of_education'] ?? 0;
        $appOfLd = $scores['application_of_ld'] ?? 0;
        $potential = $scores['potential'] ?? 0;
        $totalScore = $scores['total_score'] ?? 0;
        
        $stmt->bind_param(
            'iisddddddddsbbbbs',
            $positionId,
            $applicantId,
            $appCode,
            $education,
            $training,
            $experience,
            $performance,
            $outstandingAccomplishments,
            $appOfEducation,
            $appOfLd,
            $potential,
            $totalScore,
            $backgroundYes,
            $backgroundNo,
            $forAppointment,
            $forProbation,
            $assessmentDate
        );
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            error_log("Execute failed: " . $stmt->error);
            $stmt->close();
            return false;
        }
    }
    
    /**
     * Update existing assessment result
     */
    private function updateResult($positionId, $applicantId, $scores, $remarks, $assessmentDate) {
        $conn = $this->getConnection();
        $query = "UPDATE {$this->table} 
                  SET application_code = ?, education_score = ?, training_score = ?, 
                      experience_score = ?, performance_score = ?, outstanding_accomplishments_score = ?, 
                      application_of_education_score = ?, application_of_ld_score = ?, potential_score = ?, 
                      total_score = ?, remarks = ?, background_yes = ?, background_no = ?, 
                      for_appointment = ?, for_probation = ?, assessment_date = ?
                  WHERE position_id = ? AND applicant_id = ?";
        
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return false;
        }
        
        $backgroundYes = isset($scores['background_yes']) ? $scores['background_yes'] : 0;
        $backgroundNo = isset($scores['background_no']) ? $scores['background_no'] : 0;
        $forAppointment = isset($scores['for_appointment']) ? $scores['for_appointment'] : 0;
        $forProbation = isset($scores['for_probation']) ? $scores['for_probation'] : 0;
        
        // Pre-assign all expressions to variables (required for bind_param)
        $appCode = $scores['application_code'] ?? '';
        $education = $scores['education'] ?? 0;
        $training = $scores['training'] ?? 0;
        $experience = $scores['experience'] ?? 0;
        $performance = $scores['performance'] ?? 0;
        $outstandingAccomplishments = $scores['outstanding_accomplishments'] ?? 0;
        $appOfEducation = $scores['application_of_education'] ?? 0;
        $appOfLd = $scores['application_of_ld'] ?? 0;
        $potential = $scores['potential'] ?? 0;
        $totalScore = $scores['total_score'] ?? 0;
        
        $stmt->bind_param(
            'sdddddddddsbbbbsii',
            $appCode,
            $education,
            $training,
            $experience,
            $performance,
            $outstandingAccomplishments,
            $appOfEducation,
            $appOfLd,
            $potential,
            $totalScore,
            $remarks,
            $backgroundYes,
            $backgroundNo,
            $forAppointment,
            $forProbation,
            $assessmentDate,
            $positionId,
            $applicantId
        );
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            error_log("Execute failed: " . $stmt->error);
            $stmt->close();
            return false;
        }
    }
    
    /**
     * Generate rankings for a position
     * Ranks applicants based on multiple criteria:
     * 1. Primary: Total Score (highest first)
     * 2. Secondary: Education Score
     * 3. Tertiary: Training Score
     * 4. Quaternary: Experience Score
     * 5. Quinary: Performance Score
     * 6. Senary: Outstanding Accomplishments Score
     * 7. Septenary: Application of Education Score
     * 8. Octonary: Application of L&D Score
     * 9. Final: Application Code (alphabetical - tiebreaker)
     */
    public function generateRankings($positionId) {
        try {
            $conn = $this->getConnection();
            // Get all results for this position with all scoring criteria
            $query = "SELECT 
                        id, 
                        total_score,
                        education_score,
                        training_score,
                        experience_score,
                        performance_score,
                        outstanding_accomplishments_score,
                        application_of_education_score,
                        application_of_ld_score,
                        application_code
                      FROM {$this->table} 
                      WHERE position_id = ? 
                      ORDER BY 
                        total_score DESC,
                        education_score DESC,
                        training_score DESC,
                        experience_score DESC,
                        performance_score DESC,
                        outstanding_accomplishments_score DESC,
                        application_of_education_score DESC,
                        application_of_ld_score DESC,
                        application_code ASC";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $positionId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $rank = 1;
            $tieRank = 1;
            $lastScores = [
                'total_score' => null,
                'education_score' => null,
                'training_score' => null,
                'experience_score' => null,
                'performance_score' => null,
                'outstanding_accomplishments_score' => null,
                'application_of_education_score' => null,
                'application_of_ld_score' => null,
                'application_code' => null
            ];
            
            // Update rank for each result based on multi-criteria sorting
            while ($row = $result->fetch_assoc()) {
                // Check if this is a different rank than the previous applicant
                $isSameRank = true;
                
                // Compare all scoring criteria and application code
                if ($lastScores['total_score'] !== null) {
                    foreach ($lastScores as $key => $value) {
                        if ($row[$key] != $value) {
                            $isSameRank = false;
                            break;
                        }
                    }
                }
                
                // Assign rank (handle ties where all criteria match)
                if (!$isSameRank && $lastScores['total_score'] !== null) {
                    $rank = $tieRank;
                }
                
                // Update the rank in database
                $updateQuery = "UPDATE {$this->table} SET rank = ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param('ii', $rank, $row['id']);
                $updateStmt->execute();
                $updateStmt->close();
                
                // Update last scores for next iteration
                foreach ($lastScores as $key => &$value) {
                    $value = $row[$key];
                }
                
                $tieRank++;
            }
            
            $stmt->close();
            return true;
        } catch (Exception $e) {
            error_log("Error in generateRankings: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all results for a position with applicant details
     */
    public function getResultsByPosition($positionId) {
        try {
            $conn = $this->getConnection();
            $query = "SELECT 
                        car.id,
                        car.applicant_id,
                        car.application_code,
                        car.education_score,
                        car.training_score,
                        car.experience_score,
                        car.performance_score,
                        car.outstanding_accomplishments_score,
                        car.application_of_education_score,
                        car.application_of_ld_score,
                        car.potential_score,
                        car.total_score,
                        car.rank,
                        car.remarks,
                        car.background_yes,
                        car.background_no,
                        car.for_appointment,
                        car.for_probation,
                        car.assessment_date,
                        a.name,
                        p.position_name,
                        p.salary_grade,
                        p.item_number
                      FROM {$this->table} car
                      JOIN applicants a ON car.applicant_id = a.id
                      JOIN positions p ON car.position_id = p.id
                      WHERE car.position_id = ? AND a.archive_status = 'active'
                      ORDER BY 
                        car.total_score DESC,
                        car.education_score DESC,
                        car.training_score DESC,
                        car.experience_score DESC,
                        car.performance_score DESC,
                        car.outstanding_accomplishments_score DESC,
                        car.application_of_education_score DESC,
                        car.application_of_ld_score DESC,
                        car.application_code ASC";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $positionId);
            $stmt->execute();
            return $stmt->get_result();
        } catch (Exception $e) {
            error_log("Error in getResultsByPosition: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get single result by ID
     */
    public function getResultById($resultId) {
        try {
            $conn = $this->getConnection();
            $query = "SELECT 
                        car.*,
                        a.name,
                        p.position_name,
                        p.salary_grade,
                        p.item_number,
                        p.position_group
                      FROM {$this->table} car
                      JOIN applicants a ON car.applicant_id = a.id
                      JOIN positions p ON car.position_id = p.id
                      WHERE car.id = ? AND a.archive_status = 'active'";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $resultId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error in getResultById: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Delete result
     */
    public function deleteResult($resultId) {
        try {
            $conn = $this->getConnection();
            $query = "DELETE FROM {$this->table} WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $resultId);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in deleteResult: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all positions with their results count (ONLY positions with applicants)
     */
    public function getPositionsWithResults() {
        try {
            $conn = $this->getConnection();
            // Modified to ONLY show positions that have ACTIVE applicants (result_count > 0)
            $query = "SELECT DISTINCT 
                        p.id,
                        p.position_name,
                        p.position_group,
                        p.salary_grade,
                        p.item_number,
                        COUNT(car.id) as result_count
                      FROM positions p
                      INNER JOIN {$this->table} car ON p.id = car.position_id
                      INNER JOIN applicants a ON car.applicant_id = a.id
                      WHERE a.archive_status = 'active'
                      GROUP BY p.id
                      HAVING result_count > 0
                      ORDER BY p.position_name";
            
            $result = $conn->query($query);
            return $result;
        } catch (Exception $e) {
            error_log("Error in getPositionsWithResults: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get all CAR results (all applicants across all positions)
     */
    public function getAllResults() {
        try {
            $conn = $this->getConnection();
            $query = "SELECT 
                        car.id,
                        car.position_id,
                        car.applicant_id,
                        car.application_code,
                        car.education_score,
                        car.training_score,
                        car.experience_score,
                        car.performance_score,
                        car.outstanding_accomplishments_score,
                        car.application_of_education_score,
                        car.application_of_ld_score,
                        car.potential_score,
                        car.total_score,
                        car.rank,
                        car.remarks,
                        car.background_yes,
                        car.background_no,
                        car.for_appointment,
                        car.for_probation,
                        car.assessment_date,
                        a.name,
                        p.position_name,
                        p.salary_grade,
                        p.item_number
                      FROM {$this->table} car
                      JOIN applicants a ON car.applicant_id = a.id
                      JOIN positions p ON car.position_id = p.id
                      WHERE a.archive_status = 'active'
                      ORDER BY 
                        p.position_name,
                        car.total_score DESC,
                        car.education_score DESC,
                        car.training_score DESC,
                        car.experience_score DESC,
                        car.performance_score DESC,
                        car.outstanding_accomplishments_score DESC,
                        car.application_of_education_score DESC,
                        car.application_of_ld_score DESC,
                        car.application_code ASC";
            
            return $conn->query($query);
        } catch (Exception $e) {
            error_log("Error in getAllResults: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Export results as array
     */
    public function exportResults($positionId) {
        $result = $this->getResultsByPosition($positionId);
        if (!$result) {
            return [];
        }
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}

} // end if !class_exists
?>

?>
