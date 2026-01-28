<?php
/**
 * Comparative Assessment Results (CAR) Report Generator - Annex G-2
 * Consolidated Format for Administrative Officers and Non-Teaching Roles
 * DepEd Order No. 007, s. 2023
 * 
 * Generates landscape-oriented consolidated ranking table with Top 5 highlighting
 * and tie-breaking alerts
 */

require_once 'HRMPSBEvaluator.php';

class CARReportGeneratorG2 {
    
    /**
     * Generate Annex G-2 Comparative Assessment Results (Consolidated)
     * 
     * @param array $evaluations Array of evaluation results (each from evaluateApplicant)
     * @param array $additionalData Position info, salary grade, item number, signatories, etc.
     * @return string HTML content
     */
    public function generateCAR($evaluations, $additionalData = []) {
        // Sort evaluations by total score (descending)
        usort($evaluations, function($a, $b) {
            $scoreA = $a['total_score'];
            $scoreB = $b['total_score'];
            
            // Primary sort: total score
            if (abs($scoreA - $scoreB) > 0.01) {
                return $scoreB <=> $scoreA;
            }
            
            // Tie-breaking: Performance first, then Potential
            $perfA = $a['criteria']['performance']['final_score'] ?? 0;
            $perfB = $b['criteria']['performance']['final_score'] ?? 0;
            if (abs($perfA - $perfB) > 0.01) {
                return $perfB <=> $perfA;
            }
            
            $potA = $a['criteria']['potential']['final_score'] ?? 0;
            $potB = $b['criteria']['potential']['final_score'] ?? 0;
            return $potB <=> $potA;
        });
        
        $html = $this->generateHTML($evaluations, $additionalData);
        return $html;
    }
    
    /**
     * Generate HTML format of Annex G-2 matching official format
     */
    private function generateHTML($evaluations, $additionalData) {
        $positionName = $additionalData['position_name'] ?? 'Position';
        $positionGroup = $additionalData['position_group'] ?? 'A';
        $salaryGrade = $additionalData['salary_grade'] ?? '';
        $itemNumber = $additionalData['item_number'] ?? '';
        $schoolsDivisionOffice = $additionalData['schools_division_office'] ?? '';
        $hrMPSBMembers = $additionalData['hrmpsb_members'] ?? [];
        $secretariat = $additionalData['secretariat'] ?? [];
        $appointingAuthority = $additionalData['appointing_authority'] ?? [];
        
        // Get weights for position group
        $evaluator = new HRMPSBEvaluator($positionGroup);
        $weights = $evaluator->getWeights();
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comparative Assessment Results (CAR) - Annex G-2</title>
    <style>
        @media print {
            @page {
                size: letter landscape;
                margin: 1cm;
            }
        }
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 20px;
            font-size: 10pt;
            line-height: 1.3;
        }
        .header {
            position: relative;
            margin-bottom: 20px;
        }
        .annex-g2 {
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
            margin-bottom: 15px;
            border: 1px solid #000;
            padding: 8px;
            font-size: 10pt;
        }
        .position-info-row {
            display: flex;
            margin-bottom: 5px;
            align-items: flex-start;
        }
        .position-info-row:last-child {
            margin-bottom: 0;
        }
        .info-label {
            font-weight: bold;
            width: 180px;
            flex-shrink: 0;
        }
        .info-value {
            flex: 1;
            border-bottom: 1px solid #000;
            min-height: 18px;
            padding-left: 5px;
        }
        .info-value-filled {
            border-bottom: none;
            padding-left: 0;
        }
        .car-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 8pt;
        }
        .car-table th,
        .car-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }
        .car-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 8pt;
        }
        .car-table .col-rank {
            width: 3%;
        }
        .car-table .col-name {
            width: 12%;
            text-align: left;
            padding-left: 5px;
        }
        .car-table .col-score {
            width: 6%;
        }
        .car-table .col-total {
            width: 6%;
            font-weight: bold;
            background-color: #e8e8e8;
        }
        .car-table .col-remarks {
            width: 10%;
            text-align: left;
            font-size: 7pt;
            padding-left: 3px;
        }
        .top-five {
            background-color: #c8e6c9 !important;
            font-weight: bold;
        }
        .below-threshold {
            background-color: #ffebee;
        }
        .tie-row {
            background-color: #fff9c4;
        }
        .tie-alert {
            background-color: #ffcdd2;
            border: 2px solid #d32f2f;
            font-weight: bold;
        }
        .certification {
            margin-top: 25px;
            text-align: justify;
            font-size: 9pt;
            line-height: 1.6;
        }
        .certification p {
            margin-bottom: 12px;
            text-indent: 30px;
        }
        .signature-section {
            margin-top: 40px;
        }
        .signature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 50px;
        }
        .signature-box {
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 8px;
            padding-top: 5px;
            min-height: 40px;
        }
        .signature-name {
            margin-top: 5px;
            font-weight: bold;
            font-size: 9pt;
        }
        .signature-title {
            font-size: 8pt;
            margin-top: 3px;
        }
        .date-field {
            margin-top: 15px;
            text-align: center;
            font-size: 9pt;
        }
        .secretariat-section {
            margin-top: 30px;
            border-top: 2px solid #000;
            padding-top: 20px;
        }
        .appointing-authority-section {
            margin-top: 40px;
            border-top: 2px solid #000;
            padding-top: 20px;
            text-align: center;
        }
        .tie-notice {
            margin-top: 15px;
            padding: 10px;
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            font-size: 9pt;
        }
        .tie-notice strong {
            color: #856404;
        }
    </style>
</head>
<body>';
        
        // Header with Annex G-2
        $html .= '<div class="header">
            <div class="annex-g2">Annex G-2</div>
            <div class="header-title">
                <h1>COMPARATIVE ASSESSMENT RESULTS (CONSOLIDATED)</h1>
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
                    <th class="col-score">Application of Ed<br>(' . $weights['application_of_education'] . ' pts)</th>
                    <th class="col-score">Application of L&D<br>(' . $weights['application_of_ld'] . ' pts)</th>
                    <th class="col-score">Potential<br>(' . $weights['potential'] . ' pts)</th>
                    <th class="col-total">Total<br>Score</th>
                    <th class="col-remarks">Remarks</th>
                </tr>
            </thead>
            <tbody>';
        
        $rank = 1;
        $previousScore = null;
        $tieStartRank = 1;
        $thresholdScore = 50.0; // 50% rule
        $topFiveCount = 0;
        $tieGroups = [];
        $currentTieGroup = [];
        
        foreach ($evaluations as $index => $evaluation) {
            $currentScore = $evaluation['total_score'];
            $isBelowThreshold = $currentScore < $thresholdScore;
            $isTie = ($previousScore !== null && abs($currentScore - $previousScore) < 0.01);
            $isTopFive = ($rank <= 5);
            
            // Track ties
            if ($isTie) {
                if (empty($currentTieGroup) || $currentTieGroup[0]['score'] != $currentScore) {
                    // New tie group
                    if (!empty($currentTieGroup) && count($currentTieGroup) > 1) {
                        $tieGroups[] = $currentTieGroup;
                    }
                    $currentTieGroup = [['index' => $index, 'rank' => $rank, 'score' => $currentScore]];
                }
                $currentTieGroup[] = ['index' => $index, 'rank' => $rank, 'score' => $currentScore];
            } else {
                if (!empty($currentTieGroup) && count($currentTieGroup) > 1) {
                    $tieGroups[] = $currentTieGroup;
                }
                $currentTieGroup = [];
            }
            
            // Determine display rank (show same rank for ties)
            $displayRank = $rank;
            if ($isTie && $previousScore !== null) {
                $displayRank = $tieStartRank;
            } else {
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
            $potentialScore = $evaluation['criteria']['potential']['final_score'] ?? 0;
            
            // Format scores (remove decimals if whole number)
            $formatScore = function($score) {
                return $score == intval($score) ? intval($score) : number_format($score, 2);
            };
            
            $rowClass = '';
            if ($isTopFive) {
                $rowClass .= ' top-five';
                $topFiveCount++;
            }
            if ($isBelowThreshold) {
                $rowClass .= ' below-threshold';
            }
            if ($isTie) {
                $rowClass .= ' tie-row';
            }
            
            // Get remarks from evaluation data if available
            $remarks = $evaluation['remarks'] ?? '';
            if ($isTie && empty($remarks)) {
                $remarks = 'TIE - See tie-breaking protocol';
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
                <td class="col-score">' . $formatScore($applicationOfEdScore) . '</td>
                <td class="col-score">' . $formatScore($applicationOfLDScore) . '</td>
                <td class="col-score">' . $formatScore($potentialScore) . '</td>
                <td class="col-total">' . $formatScore($currentScore) . '</td>
                <td class="col-remarks">' . htmlspecialchars($remarks) . '</td>
            </tr>';
            
            // Update rank for next iteration
            if (!$isTie) {
                $rank = $index + 2;
            } else {
                $rank++;
            }
            
            $previousScore = $currentScore;
        }
        
        // Handle remaining tie group
        if (!empty($currentTieGroup) && count($currentTieGroup) > 1) {
            $tieGroups[] = $currentTieGroup;
        }
        
        $html .= '</tbody></table>';
        
        // Tie-breaking Notice
        if (!empty($tieGroups)) {
            $html .= '<div class="tie-notice">
                <strong>TIE-BREAKING ALERT:</strong> The following candidates have identical total scores. Per DepEd Order No. 007, s. 2023, the tie-breaking protocol should be applied, considering Performance rating first, then Potential rating.';
            foreach ($tieGroups as $tieGroup) {
                if (count($tieGroup) > 1) {
                    $names = [];
                    foreach ($tieGroup as $tie) {
                        $names[] = htmlspecialchars($evaluations[$tie['index']]['applicant_name']);
                    }
                    $html .= '<br>- <strong>Rank ' . $tieGroup[0]['rank'] . ':</strong> ' . implode(', ', $names) . ' (Score: ' . number_format($tieGroup[0]['score'], 2) . ')';
                }
            }
            $html .= '</div>';
        }
        
        // Top 5 Endorsement Notice
        if ($topFiveCount > 0) {
            $html .= '<div style="margin-top: 15px; padding: 10px; background-color: #e8f5e9; border-left: 4px solid #4caf50; font-size: 9pt;">
                <strong>TOP 5 CANDIDATES (Highlighted in Green):</strong> These candidates are recommended for endorsement to the Appointing Authority as per Merit Selection Plan guidelines.
            </div>';
        }
        
        // Certification Section
        $html .= '<div class="certification">
            <p><strong>We, the members of the Human Resource Merit Promotion and Selection Board (HRMPSB)</strong>, hereby certify that the abovementioned applicants were assessed based on the existing guidelines and that the comparative assessment was conducted objectively and judiciously in accordance with the Merit Selection Plan and DepEd Order No. 007, s. 2023.</p>
            
            <p>This Comparative Assessment Results (CAR) is prepared in accordance with DepEd Order No. 007, s. 2023, and reflects the actual weighted points obtained by each applicant based on their qualifications and submitted documentary requirements.</p>
        </div>';
        
        // HRMPSB Signature Section
        $html .= '<div class="signature-section">
            <strong style="font-size: 10pt;">HRMPSB MEMBERS:</strong>
            <div class="signature-grid">';
        
        // Generate signature boxes for HRMPSB members
        $memberCount = max(3, count($hrMPSBMembers));
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
        
        // Secretariat Section (Annex G-2 specific)
        if (!empty($secretariat)) {
            $html .= '<div class="secretariat-section">
                <strong style="font-size: 10pt;">SECRETARIAT:</strong>
                <div class="signature-grid">';
            
            foreach ($secretariat as $secretary) {
                $html .= '<div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-name">' . htmlspecialchars($secretary['name'] ?? '') . '</div>
                    <div class="signature-title">' . htmlspecialchars($secretary['title'] ?? 'Secretariat Member') . '</div>
                </div>';
            }
            
            $html .= '</div></div>';
        }
        
        // Appointing Authority Section (Annex G-2 specific)
        if (!empty($appointingAuthority)) {
            $html .= '<div class="appointing-authority-section">
                <div style="margin-bottom: 30px;">
                    <div class="signature-line" style="width: 50%; margin: 0 auto;"></div>
                    <div class="signature-name" style="margin-top: 10px;">' . htmlspecialchars($appointingAuthority['name'] ?? '') . '</div>
                    <div class="signature-title">' . htmlspecialchars($appointingAuthority['title'] ?? 'Schools Division Superintendent') . '</div>
                    <div style="margin-top: 10px; font-weight: bold;">Appointing Authority</div>
                </div>
                <div class="date-field">
                    Date: _________________
                </div>
            </div>';
        }
        
        // Add navigation buttons
        $html .= '<div style="margin-top: 40px; padding: 20px; border-top: 2px solid #ddd; display: flex; gap: 10px; justify-content: center;">
            <button onclick="window.history.back()" style="padding: 10px 30px; background-color: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">← Back</button>
            <button onclick="window.location.href=\'index.php\'" style="padding: 10px 30px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">Home</button>
        </div>';
        
        $html .= '</body></html>';
        
        return $html;
    }
    
    /**
     * Generate text-based CAR (for console/plain text output)
     */
    public function generateTextCAR($evaluations, $additionalData = []) {
        // Sort same as HTML version
        usort($evaluations, function($a, $b) {
            $scoreA = $a['total_score'];
            $scoreB = $b['total_score'];
            
            if (abs($scoreA - $scoreB) > 0.01) {
                return $scoreB <=> $scoreA;
            }
            
            $perfA = $a['criteria']['performance']['final_score'] ?? 0;
            $perfB = $b['criteria']['performance']['final_score'] ?? 0;
            if (abs($perfA - $perfB) > 0.01) {
                return $perfB <=> $perfA;
            }
            
            $potA = $a['criteria']['potential']['final_score'] ?? 0;
            $potB = $b['criteria']['potential']['final_score'] ?? 0;
            return $potB <=> $potA;
        });
        
        $text = "\n";
        $text .= str_repeat("=", 150) . "\n";
        $text .= "COMPARATIVE ASSESSMENT RESULTS (CONSOLIDATED) - Annex G-2\n";
        $text .= str_repeat("=", 150) . "\n\n";
        
        $positionName = $additionalData['position_name'] ?? 'Position';
        $text .= "Position Applied for: " . $positionName . "\n";
        $text .= "Salary Grade: " . ($additionalData['salary_grade'] ?? '') . "\n";
        $text .= "Item Number: " . ($additionalData['item_number'] ?? '') . "\n\n";
        
        $text .= str_repeat("-", 150) . "\n";
        $text .= sprintf("%-4s %-25s %7s %7s %7s %7s %7s %7s %7s %7s %7s %-20s\n",
            "Rank", "Name", "Edu", "Train", "Exp", "Perf", "Accom", "App/Ed", "App/L&D", "Pot", "Total", "Remarks");
        $text .= str_repeat("-", 150) . "\n";
        
        $rank = 1;
        foreach ($evaluations as $evaluation) {
            $educationScore = $evaluation['criteria']['education']['final_score'] ?? 0;
            $trainingScore = $evaluation['criteria']['training']['final_score'] ?? 0;
            $experienceScore = $evaluation['criteria']['experience']['final_score'] ?? 0;
            $performanceScore = $evaluation['criteria']['performance']['final_score'] ?? 0;
            $accomplishmentsScore = $evaluation['criteria']['outstanding_accomplishments']['final_score'] ?? 0;
            $applicationOfEdScore = $evaluation['criteria']['application_of_education']['final_score'] ?? 0;
            $applicationOfLDScore = $evaluation['criteria']['application_of_ld']['final_score'] ?? 0;
            $potentialScore = $evaluation['criteria']['potential']['final_score'] ?? 0;
            $remarks = $evaluation['remarks'] ?? '';
            
            $marker = ($rank <= 5) ? "[TOP 5]" : "";
            
            $text .= sprintf("%-4d %-25s %7.2f %7.2f %7.2f %7.2f %7.2f %7.2f %7.2f %7.2f %7.2f %-20s %s\n",
                $rank++,
                substr($evaluation['applicant_name'], 0, 25),
                $educationScore,
                $trainingScore,
                $experienceScore,
                $performanceScore,
                $accomplishmentsScore,
                $applicationOfEdScore,
                $applicationOfLDScore,
                $potentialScore,
                $evaluation['total_score'],
                substr($remarks, 0, 20),
                $marker
            );
        }
        
        $text .= str_repeat("=", 150) . "\n";
        
        return $text;
    }
}

