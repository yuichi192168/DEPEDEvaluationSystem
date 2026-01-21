<?php
/**
 * Comparative Assessment Results (CAR) Report Generator
 * Annex G-1 Format - DepEd Order No. 007, s. 2023
 * 
 * Generates the consolidated ranking table showing all applicants for a position
 */

require_once 'HRMPSBEvaluator.php';

class CARReportGenerator {
    
    /**
     * Generate Annex G-1 Comparative Assessment Results
     * 
     * @param array $evaluations Array of evaluation results (each from evaluateApplicant)
     * @param array $additionalData Position info, salary grade, item number, etc.
     * @return string HTML content
     */
    public function generateCAR($evaluations, $additionalData = []) {
        // Sort evaluations by total score (descending)
        usort($evaluations, function($a, $b) {
            return $b['total_score'] <=> $a['total_score'];
        });
        
        $html = $this->generateHTML($evaluations, $additionalData);
        return $html;
    }
    
    /**
     * Generate HTML format of Annex G-1 matching official format
     */
    private function generateHTML($evaluations, $additionalData) {
        $positionName = $additionalData['position_name'] ?? 'Position';
        $positionGroup = $additionalData['position_group'] ?? 'A';
        $salaryGrade = $additionalData['salary_grade'] ?? '';
        $itemNumber = $additionalData['item_number'] ?? '';
        $schoolsDivisionOffice = $additionalData['schools_division_office'] ?? '';
        $hrMPSBMembers = $additionalData['hrmpsb_members'] ?? [];
        
        // Get weights for position group
        $evaluator = new HRMPSBEvaluator($positionGroup);
        $weights = $evaluator->getWeights();
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comparative Assessment Results (CAR) - Annex G-1</title>
    <style>
        @media print {
            @page {
                size: letter;
                margin: 1cm;
            }
        }
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 20px;
            font-size: 11pt;
            line-height: 1.3;
        }
        .header {
            position: relative;
            margin-bottom: 20px;
        }
        .annex-g1 {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 12pt;
            font-weight: bold;
        }
        .header-title {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .header-title h1 {
            font-size: 14pt;
            margin: 5px 0;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .position-info {
            margin-bottom: 20px;
            border: 1px solid #000;
            padding: 10px;
        }
        .position-info-row {
            display: flex;
            margin-bottom: 8px;
            align-items: flex-start;
        }
        .position-info-row:last-child {
            margin-bottom: 0;
        }
        .info-label {
            font-weight: bold;
            width: 200px;
            flex-shrink: 0;
        }
        .info-value {
            flex: 1;
            border-bottom: 1px solid #000;
            min-height: 20px;
            padding-left: 5px;
        }
        .info-value-filled {
            border-bottom: none;
            padding-left: 0;
        }
        .car-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 9pt;
        }
        .car-table th,
        .car-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }
        .car-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 9pt;
        }
        .car-table .col-rank {
            width: 4%;
        }
        .car-table .col-name {
            width: 15%;
            text-align: left;
        }
        .car-table .col-score {
            width: 7%;
        }
        .car-table .col-total {
            width: 8%;
            font-weight: bold;
            background-color: #e8e8e8;
        }
        .car-table .col-remarks {
            width: 15%;
            text-align: left;
            font-size: 8pt;
        }
        .below-threshold {
            background-color: #ffebee;
        }
        .tie-row {
            background-color: #fff9c4;
        }
        .certification {
            margin-top: 30px;
            text-align: justify;
            font-size: 10pt;
            line-height: 1.6;
        }
        .certification p {
            margin-bottom: 15px;
            text-indent: 30px;
        }
        .signature-section {
            margin-top: 50px;
        }
        .signature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 60px;
        }
        .signature-box {
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 10px;
            padding-top: 5px;
            min-height: 50px;
        }
        .signature-name {
            margin-top: 5px;
            font-weight: bold;
        }
        .signature-title {
            font-size: 9pt;
            margin-top: 3px;
        }
        .date-field {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>';
        
        // Header with Annex G-1
        $html .= '<div class="header">
            <div class="annex-g1">Annex G-1</div>
            <div class="header-title">
                <h1>COMPARATIVE ASSESSMENT RESULTS (CAR)</h1>
            </div>
        </div>';
        
        // Position Information Section
        $html .= '<div class="position-info">
            <div class="position-info-row">
                <div class="info-label">Position Applied for:</div>
                <div class="info-value-filled">' . htmlspecialchars($positionName) . '</div>
            </div>
            <div class="position-info-row">
                <div class="info-label">Salary Grade:</div>
                <div class="info-value">' . htmlspecialchars($salaryGrade) . '</div>
            </div>
            <div class="position-info-row">
                <div class="info-label">Item Number:</div>
                <div class="info-value">' . htmlspecialchars($itemNumber) . '</div>
            </div>
            <div class="position-info-row">
                <div class="info-label">Schools Division Office:</div>
                <div class="info-value-filled">' . htmlspecialchars($schoolsDivisionOffice) . '</div>
            </div>
        </div>';
        
        // Main CAR Table
        $html .= '<table class="car-table">
            <thead>
                <tr>
                    <th class="col-rank">Rank</th>
                    <th class="col-name">Name of Applicant</th>
                    <th class="col-score">Education<br>(' . $weights['education'] . ' pts)</th>
                    <th class="col-score">Training<br>(' . $weights['training'] . ' pts)</th>
                    <th class="col-score">Experience<br>(' . $weights['experience'] . ' pts)</th>
                    <th class="col-score">Performance<br>(' . $weights['performance'] . ' pts)</th>
                    <th class="col-score">Accomplishments<br>(' . $weights['outstanding_accomplishments'] . ' pts)</th>
                    <th class="col-score">Application of Ed/L&D<br>(' . ($weights['application_of_education'] + $weights['application_of_ld']) . ' pts)</th>
                    <th class="col-score">Potential<br>(' . $weights['potential'] . ' pts)</th>
                    <th class="col-total">Total<br>Score</th>
                    <th class="col-remarks">Remarks<br>(Tie-breaking, etc.)</th>
                </tr>
            </thead>
            <tbody>';
        
        $rank = 1;
        $previousScore = null;
        $tieStartRank = 1;
        $thresholdScore = 50.0; // 50% rule - can be configured
        
        foreach ($evaluations as $index => $evaluation) {
            $currentScore = $evaluation['total_score'];
            $isBelowThreshold = $currentScore < $thresholdScore;
            $isTie = ($previousScore !== null && abs($currentScore - $previousScore) < 0.01);
            
            // Determine display rank (show same rank for ties)
            $displayRank = $rank;
            if ($isTie) {
                // Use the rank where the tie started
                $displayRank = $tieStartRank;
            } else {
                // New score, update tie start rank
                $tieStartRank = $rank;
            }
            
            // Extract scores for each criterion
            $educationScore = $evaluation['criteria']['education']['final_score'] ?? 0;
            $trainingScore = $evaluation['criteria']['training']['final_score'] ?? 0;
            $experienceScore = $evaluation['criteria']['experience']['final_score'] ?? 0;
            $performanceScore = $evaluation['criteria']['performance']['final_score'] ?? 0;
            $accomplishmentsScore = $evaluation['criteria']['outstanding_accomplishments']['final_score'] ?? 0;
            $applicationOfEdScore = $evaluation['criteria']['application_of_education']['final_score'] ?? 0;
            $applicationOfLDScore = $evaluation['criteria']['application_of_ld']['final_score'] ?? 0;
            $applicationCombinedScore = $applicationOfEdScore + $applicationOfLDScore;
            $potentialScore = $evaluation['criteria']['potential']['final_score'] ?? 0;
            
            // Format scores (remove decimals if whole number)
            $formatScore = function($score) {
                return $score == intval($score) ? intval($score) : number_format($score, 1);
            };
            
            $rowClass = '';
            if ($isBelowThreshold) {
                $rowClass .= ' below-threshold';
            }
            if ($isTie) {
                $rowClass .= ' tie-row';
            }
            
            // Get remarks from evaluation data if available
            $remarks = $evaluation['remarks'] ?? '';
            if ($isTie && empty($remarks)) {
                $remarks = 'TIE';
            }
            if ($isBelowThreshold && empty($remarks)) {
                $remarks = 'Below 50% threshold';
            }
            
            $html .= '<tr class="' . trim($rowClass) . '">
                <td class="col-rank">' . $displayRank . '</td>
                <td class="col-name">' . htmlspecialchars($evaluation['applicant_name']) . '</td>
                <td class="col-score">' . $formatScore($educationScore) . '</td>
                <td class="col-score">' . $formatScore($trainingScore) . '</td>
                <td class="col-score">' . $formatScore($experienceScore) . '</td>
                <td class="col-score">' . $formatScore($performanceScore) . '</td>
                <td class="col-score">' . $formatScore($accomplishmentsScore) . '</td>
                <td class="col-score">' . $formatScore($applicationCombinedScore) . '</td>
                <td class="col-score">' . $formatScore($potentialScore) . '</td>
                <td class="col-total">' . $formatScore($currentScore) . '</td>
                <td class="col-remarks">' . htmlspecialchars($remarks) . '</td>
            </tr>';
            
            // Update rank for next iteration
            if (!$isTie) {
                // Score changed, next rank is current + 1
                $rank = $index + 2; // +2 because index is 0-based and we're moving to next
            } else {
                // Still in a tie, keep incrementing but display rank stays same
                $rank++;
            }
            
            $previousScore = $currentScore;
        }
        
        $html .= '</tbody></table>';
        
        // Certification Section
        $html .= '<div class="certification">
            <p><strong>We, the members of the Human Resource Merit Promotion and Selection Board (HRMPSB)</strong>, hereby certify that the abovementioned applicants were assessed based on the existing guidelines and that the comparative assessment was conducted objectively and judiciously.</p>
            
            <p>This Comparative Assessment Results (CAR) is prepared in accordance with DepEd Order No. 007, s. 2023, and reflects the actual scores obtained by each applicant based on their qualifications and submitted documentary requirements.</p>
        </div>';
        
        // Signature Section
        $html .= '<div class="signature-section">
            <div class="signature-grid">';
        
        // Generate signature boxes for HRMPSB members
        $memberCount = max(3, count($hrMPSBMembers)); // At least 3 members
        for ($i = 0; $i < $memberCount; $i++) {
            $memberName = $hrMPSBMembers[$i]['name'] ?? '';
            $memberTitle = $hrMPSBMembers[$i]['title'] ?? 'HRMPSB Member';
            
            $html .= '<div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">' . htmlspecialchars($memberName) . '</div>
                <div class="signature-title">' . htmlspecialchars($memberTitle) . '</div>
            </div>';
        }
        
        $html .= '</div>
            <div class="date-field">
                Date: _________________
            </div>
        </div>';
        
        $html .= '</body></html>';
        
        return $html;
    }
    
    /**
     * Generate text-based CAR (for console/plain text output)
     */
    public function generateTextCAR($evaluations, $additionalData = []) {
        // Sort evaluations by total score (descending)
        usort($evaluations, function($a, $b) {
            return $b['total_score'] <=> $a['total_score'];
        });
        
        $text = "\n";
        $text .= str_repeat("=", 120) . "\n";
        $text .= "COMPARATIVE ASSESSMENT RESULTS (CAR) - Annex G-1\n";
        $text .= str_repeat("=", 120) . "\n\n";
        
        $positionName = $additionalData['position_name'] ?? 'Position';
        $text .= "Position Applied for: " . $positionName . "\n";
        $text .= "Salary Grade: " . ($additionalData['salary_grade'] ?? '') . "\n";
        $text .= "Item Number: " . ($additionalData['item_number'] ?? '') . "\n\n";
        
        $text .= str_repeat("-", 120) . "\n";
        $text .= sprintf("%-4s %-25s %8s %8s %8s %8s %8s %8s %8s %8s %-15s\n",
            "Rank", "Name", "Edu", "Train", "Exp", "Perf", "Accom", "App/L&D", "Pot", "Total", "Remarks");
        $text .= str_repeat("-", 120) . "\n";
        
        $rank = 1;
        foreach ($evaluations as $evaluation) {
            $educationScore = $evaluation['criteria']['education']['final_score'] ?? 0;
            $trainingScore = $evaluation['criteria']['training']['final_score'] ?? 0;
            $experienceScore = $evaluation['criteria']['experience']['final_score'] ?? 0;
            $performanceScore = $evaluation['criteria']['performance']['final_score'] ?? 0;
            $accomplishmentsScore = $evaluation['criteria']['outstanding_accomplishments']['final_score'] ?? 0;
            $applicationOfEdScore = $evaluation['criteria']['application_of_education']['final_score'] ?? 0;
            $applicationOfLDScore = $evaluation['criteria']['application_of_ld']['final_score'] ?? 0;
            $applicationCombinedScore = $applicationOfEdScore + $applicationOfLDScore;
            $potentialScore = $evaluation['criteria']['potential']['final_score'] ?? 0;
            $remarks = $evaluation['remarks'] ?? '';
            
            $text .= sprintf("%-4d %-25s %8.1f %8.1f %8.1f %8.1f %8.1f %8.1f %8.1f %8.1f %-15s\n",
                $rank++,
                substr($evaluation['applicant_name'], 0, 25),
                $educationScore,
                $trainingScore,
                $experienceScore,
                $performanceScore,
                $accomplishmentsScore,
                $applicationCombinedScore,
                $potentialScore,
                $evaluation['total_score'],
                substr($remarks, 0, 15)
            );
        }
        
        $text .= str_repeat("=", 120) . "\n";
        
        return $text;
    }
}

