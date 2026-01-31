<?php
/**
 * Comparative Assessment Results (CAR) Report Generator - Annex G-1
 * Consolidated Format for All Positions
 * DepEd Order No. 007, s. 2023
 * 
 * Generates consolidated ranking table with all candidates sorted by total score,
 * including tie-breaking logic and remarks column
 */

require_once 'HRMPSBEvaluator.php';
require_once __DIR__ . '/../config/evaluation_criteria.php';

class CARReportGeneratorG1 {
    
    private $positionGroup = 'A';
    private $evaluator;
    private $salaryGrade = null;
    private $category = null;
    
    /**
     * Constructor
     */
    public function __construct($positionGroup = 'A') {
        $this->positionGroup = $positionGroup;
        $this->evaluator = new HRMPSBEvaluator($positionGroup);
    }
    
    /**
     * Set salary grade for position-specific criteria
     */
    public function setSalaryGrade($salaryGrade) {
        $this->salaryGrade = $salaryGrade;
    }
    
    /**
     * Set category for non-teaching positions
     */
    public function setCategory($category) {
        $this->category = $category;
    }
    
    /**
     * Get criteria mappings for table headers
     */
    private function getCriteriaMappings() {
        $dbToKey = [
            'education' => 'a',
            'training' => 'b',
            'experience' => 'c',
            'performance' => 'd',
            'outstanding_accomplishments' => 'e',
            'application_of_education' => 'f',
            'application_of_ld' => 'g',
            'potential' => 'h'
        ];
        
        $criteria = getEvaluationCriteria($this->positionGroup, $this->salaryGrade, $this->category);
        if (!$criteria || empty($criteria['criteria'])) {
            return [];
        }
        
        $mappings = [];
        foreach ($dbToKey as $dbKey => $key) {
            if (isset($criteria['criteria'][$key])) {
                $mappings[] = [
                    'db_key' => $dbKey,
                    'key' => $key,
                    'criteria_name' => $criteria['criteria'][$key]['name'],
                    'max_points' => $criteria['criteria'][$key]['max_points']
                ];
            }
        }
        
        return $mappings;
    }
    
    /**
     * Generate Annex G-1 Comparative Assessment Results (Consolidated Master List)
     * 
     * @param array $evaluations Array of evaluation results
     * @param array $additionalData Position info, salary grade, item number, signatories, etc.
     * @return string HTML content
     */
    public function generateCAR($evaluations, $additionalData = []) {
        // Sort evaluations by total score (descending) with tie-breaking
        $sortedEvaluations = $this->sortByTieBreakingRules($evaluations);
        
        // Assign ranks
        $rankedEvaluations = $this->assignRanks($sortedEvaluations);
        
        // Identify ties
        $ties = $this->identifyTies($rankedEvaluations);
        
        $html = $this->generateHTML($rankedEvaluations, $ties, $additionalData);
        return $html;
    }
    
    /**
     * Sort evaluations by DepEd Order No. 007 tie-breaking rules:
     * 1. Total Score (highest first)
     * 2. Performance Rating (highest first)
     * 3. Potential Rating (highest first)
     */
    private function sortByTieBreakingRules($evaluations) {
        usort($evaluations, function($a, $b) {
            // Primary sort: total score (descending)
            $scoreA = $a['total_score'] ?? 0;
            $scoreB = $b['total_score'] ?? 0;
            
            if (abs($scoreA - $scoreB) > 0.001) {
                return $scoreB <=> $scoreA;
            }
            
            // Tie-breaker 1: Performance Rating (descending)
            $perfA = $a['criteria']['performance']['final_score'] ?? 0;
            $perfB = $b['criteria']['performance']['final_score'] ?? 0;
            
            if (abs($perfA - $perfB) > 0.001) {
                return $perfB <=> $perfA;
            }
            
            // Tie-breaker 2: Potential Rating (descending)
            $potA = $a['criteria']['potential']['final_score'] ?? 0;
            $potB = $b['criteria']['potential']['final_score'] ?? 0;
            
            return $potB <=> $potA;
        });
        
        return $evaluations;
    }
    
    /**
     * Assign ranks to sorted evaluations
     * Handling ties with same rank numbers
     */
    private function assignRanks($sortedEvaluations) {
        $rank = 1;
        $previousScore = null;
        $tieGroup = 0;
        
        foreach ($sortedEvaluations as &$eval) {
            $currentScore = $eval['total_score'] ?? 0;
            
            // Check if same score as previous (indicating a tie)
            if ($previousScore !== null && abs($currentScore - $previousScore) < 0.001) {
                // Same rank for tied scores
                $eval['rank'] = $rank;
                $eval['tie_group'] = $tieGroup;
            } else {
                // New rank
                $rank = count(array_filter($sortedEvaluations, function($e) use ($currentScore) {
                    return ($e['total_score'] ?? 0) > $currentScore + 0.001;
                })) + 1;
                $eval['rank'] = $rank;
                $eval['tie_group'] = null;
                $tieGroup = $rank;
                $previousScore = $currentScore;
            }
        }
        
        return $sortedEvaluations;
    }
    
    /**
     * Identify candidates in tie situations
     */
    private function identifyTies($evaluations) {
        $ties = [];
        $scoreGroups = [];
        
        foreach ($evaluations as $eval) {
            $score = $eval['total_score'] ?? 0;
            $key = round($score, 2);
            
            if (!isset($scoreGroups[$key])) {
                $scoreGroups[$key] = [];
            }
            $scoreGroups[$key][] = $eval;
        }
        
        foreach ($scoreGroups as $score => $group) {
            if (count($group) > 1) {
                // This is a tie
                $ties[$score] = $group;
            }
        }
        
        return $ties;
    }
    
    /**
     * Generate HTML format of Annex G-1
     */
    private function generateHTML($evaluations, $ties, $additionalData) {
        $positionName = $additionalData['position_name'] ?? 'Position';
        $positionGroup = $additionalData['position_group'] ?? 'A';
        $salaryGrade = $additionalData['salary_grade'] ?? '';
        $itemNumber = $additionalData['item_number'] ?? '';
        $schoolsDivisionOffice = $additionalData['schools_division_office'] ?? '';
        $hrMPSBMembers = $additionalData['hrmpsb_members'] ?? [];
        $certificateText = $additionalData['certificate_text'] ?? '';
        
        // Get weights for position group
        $weights = $this->evaluator->getWeights();
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comparative Assessment Results (CAR) - Annex G-1</title>
    <style>
        @media print {
            @page {
                size: letter;
                margin: 0.75cm;
            }
        }
        body {
            font-family: "Arial", sans-serif;
            margin: 15px;
            font-size: 10pt;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 5px 0;
            font-size: 14pt;
            font-weight: bold;
        }
        .header p {
            margin: 3px 0;
            font-size: 9pt;
        }
        .position-info {
            margin-bottom: 15px;
            font-size: 10pt;
        }
        .position-info p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9pt;
        }
        table th {
            background-color: #e0e0e0;
            border: 1px solid #999;
            padding: 5px;
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
        }
        table td {
            border: 1px solid #999;
            padding: 4px;
            text-align: center;
        }
        table td.text-left {
            text-align: left;
            padding-left: 8px;
        }
        table tr.top-5 {
            background-color: #fffacd;
        }
        table tr.tie-row {
            background-color: #ffcccc;
        }
        .tie-alert {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 10px;
            margin: 15px 0;
            border-radius: 4px;
            font-size: 9pt;
        }
        .tie-alert strong {
            color: #856404;
        }
        .certification {
            margin-top: 30px;
            font-size: 10pt;
            line-height: 1.5;
        }
        .certification-text {
            text-align: justify;
            margin-bottom: 20px;
            font-size: 9pt;
        }
        .signatures {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }
        .signature-box {
            text-align: center;
            width: 23%;
        }
        .signature-box p {
            border-top: 1px solid #333;
            margin: 40px 0 0 0;
            padding-top: 5px;
            font-size: 9pt;
        }
        .remarks {
            margin-top: 20px;
            font-size: 9pt;
            background-color: #f5f5f5;
            padding: 10px;
            border-left: 3px solid #d32f2f;
        }
        .page-break {
            page-break-before: always;
        }
        .annex-label {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 11pt;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="annex-label">Annex G-1</div>
    
    <div class="header">
        <p><strong>Republic of the Philippines</strong></p>
        <p><strong>Department of Education</strong></p>
        <p><strong>HUMAN RESOURCE MERIT PROMOTION AND SELECTION BOARD (HRMPSB)</strong></p>
        <h1>COMPARATIVE ASSESSMENT RESULTS</h1>
        <p><strong>Annex G-1</strong></p>
    </div>
    
    <div class="position-info">
        <p><strong>Position:</strong> ' . htmlspecialchars($positionName) . '</p>
        <p><strong>Salary Grade:</strong> ' . htmlspecialchars($salaryGrade) . ' | <strong>Item Number:</strong> ' . htmlspecialchars($itemNumber) . '</p>
        <p><strong>Schools Division Office:</strong> ' . htmlspecialchars($schoolsDivisionOffice) . '</p>
        <p><strong>Position Group:</strong> ' . htmlspecialchars($positionGroup) . '</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 4%">Rank</th>
                <th style="width: 16%">Name of Applicant</th>';
        
        // Add dynamic criteria headers
        $criteriaMap = $this->getCriteriaMappings();
        foreach ($criteriaMap as $mapping) {
            // Truncate long names for display
            $displayName = $mapping['criteria_name'];
            if (strlen($displayName) > 15) {
                $displayName = substr($displayName, 0, 12) . '...';
            }
            $html .= '<th style="width: 7%">' . htmlspecialchars($displayName) . '</th>';
        }
        
        $html .= '<th style="width: 8%">TOTAL SCORE</th>
                <th style="width: 12%">Remarks</th>
            </tr>
        </thead>
        <tbody>';
        
        foreach ($evaluations as $index => $eval) {
            $isTop5 = ($eval['rank'] <= 5);
            $isTied = isset($ties[round($eval['total_score'], 2)]);
            $rowClass = '';
            
            if ($isTied) {
                $rowClass = 'tie-row';
            } elseif ($isTop5) {
                $rowClass = 'top-5';
            }
            
            $applicantName = htmlspecialchars($eval['applicant_name'] ?? 'N/A');
            $rank = $eval['rank'] ?? ($index + 1);
            
            // Get criteria mappings for this row
            $rowCriteria = $this->getCriteriaMappings();
            
            // Start row with rank and name
            $html .= '<tr class="' . $rowClass . '">
                <td>' . $rank . '</td>
                <td class="text-left">' . $applicantName . '</td>';
            
            // Add scores for each criterion
            foreach ($rowCriteria as $mapping) {
                $dbKey = $mapping['db_key'];
                $score = $eval['criteria'][$dbKey]['final_score'] ?? 0;
                $html .= '<td>' . number_format($score, 2) . '</td>';
            }
            
            $totalScore = $eval['total_score'] ?? 0;
            
            // Determine remarks
            $remarks = '';
            if ($isTied) {
                $remarks = 'TIED - See tie-breaking protocol';
            }
            
            $html .= '<td><strong>' . number_format($totalScore, 2) . '</strong></td>
                <td class="text-left" style="font-size: 8pt;">' . htmlspecialchars($remarks) . '</td>
            </tr>';
        }
        
        $html .= '
        </tbody>
    </table>';
        
        // Add tie-breaking alert if ties exist
        if (!empty($ties)) {
            $html .= $this->generateTieBreakingAlert($ties);
        }
        
        // Add certification and signature lines
        $html .= $this->generateCertification($hrMPSBMembers, $certificateText);
        
        $html .= '
</body>
</html>';
        
        return $html;
    }
    
    /**
     * Generate tie-breaking alert section
     */
    private function generateTieBreakingAlert($ties) {
        if (empty($ties)) {
            return '';
        }
        
        $html = '<div class="tie-alert">
            <strong>⚠ TIE-BREAKING ALERT:</strong> The following candidates have identical total scores. 
            Per DepEd Order No. 007, s. 2023, the tie-breaking protocol should be applied, considering 
            Performance rating first, then Potential rating. Board members should refer to the detailed 
            evaluation sheets (Annex G) for further assessment if needed.
        </div>';
        
        return $html;
    }
    
    /**
     * Generate certification and signature section
     */
    private function generateCertification($members = [], $customText = '') {
        $defaultCertification = 'We, the members of the HRMPSB, hereby certify that the abovementioned applicants were assessed based on the existing guidelines in accordance with DepEd Order No. 007, s. 2023. All qualifications have been carefully reviewed and verified against the Commission on Civil Service (CSC) approved Qualification Standards for the position. The comparative assessment results presented herein represent the collective judgment of this Board based on the evaluation criteria and weights established for this particular position.';
        
        $certText = !empty($customText) ? $customText : $defaultCertification;
        
        $html = '
        <div class="certification">
            <div class="certification-text">
                ' . nl2br(htmlspecialchars($certText)) . '
            </div>
            
            <div class="signatures">';
        
        if (count($members) > 0) {
            $memberCount = min(count($members), 4);
            for ($i = 0; $i < $memberCount; $i++) {
                $member = $members[$i];
                $memberName = htmlspecialchars($member['name'] ?? 'HRMPSB Member ' . ($i + 1));
                $memberTitle = htmlspecialchars($member['title'] ?? '');
                
                $html .= '
                <div class="signature-box">
                    <div></div>
                    <p>' . $memberName . '</p>
                    <p style="font-size: 8pt;">' . $memberTitle . '</p>
                </div>';
            }
        } else {
            // Default 4 signature lines
            for ($i = 0; $i < 4; $i++) {
                $html .= '
                <div class="signature-box">
                    <div></div>
                    <p>HRMPSB Member ' . ($i + 1) . '</p>
                    <p style="font-size: 8pt;">Position/Title</p>
                </div>';
            }
        }
        
        $html .= '
            </div>
        </div>';
        
        return $html;
    }
    
    /**
     * Get weights for the position group
     */
    public function getWeights() {
        return $this->evaluator->getWeights();
    }
}
?>
