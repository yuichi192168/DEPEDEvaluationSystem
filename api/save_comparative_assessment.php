<?php
/**
 * Save Comparative Assessment Result API
 * Endpoint to save individual or batch assessment results
 */

require_once '../classes/ComparativeAssessmentReport.php';

header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            throw new Exception('Invalid JSON input');
        }
        
        $car = new ComparativeAssessmentReport();
        
        // Check if batch operation
        if (isset($input['batch']) && $input['batch'] === true && isset($input['results'])) {
            $positionId = $input['position_id'] ?? null;
            if (!$positionId) {
                throw new Exception('position_id is required for batch operations');
            }
            
            $savedCount = 0;
            foreach ($input['results'] as $result) {
                $scores = $result['scores'] ?? [];
                $remarks = $result['remarks'] ?? '';
                $assessmentDate = $result['assessment_date'] ?? null;
                
                if ($car->saveResult($positionId, $result['applicant_id'], $scores, $remarks, $assessmentDate)) {
                    $savedCount++;
                }
            }
            
            // Generate rankings after batch save
            $car->generateRankings($positionId);
            
            echo json_encode([
                'success' => true,
                'message' => "Saved {$savedCount} results successfully",
                'saved_count' => $savedCount
            ]);
        } else {
            // Single record save
            $positionId = $input['position_id'] ?? null;
            $applicantId = $input['applicant_id'] ?? null;
            $scores = $input['scores'] ?? [];
            $remarks = $input['remarks'] ?? '';
            $assessmentDate = $input['assessment_date'] ?? null;
            
            if (!$positionId || !$applicantId) {
                throw new Exception('position_id and applicant_id are required');
            }
            
            if ($car->saveResult($positionId, $applicantId, $scores, $remarks, $assessmentDate)) {
                // Generate rankings
                $car->generateRankings($positionId);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Result saved successfully'
                ]);
            } else {
                throw new Exception('Failed to save result');
            }
        }
    } elseif ($method === 'GET') {
        $positionId = $_GET['position_id'] ?? null;
        
        if (!$positionId) {
            throw new Exception('position_id is required');
        }
        
        $car = new ComparativeAssessmentReport();
        $results = $car->exportResults($positionId);
        
        echo json_encode([
            'success' => true,
            'results' => $results
        ]);
    } else {
        throw new Exception('Method not allowed');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
