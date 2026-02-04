<?php
/**
 * Individual Evaluation Sheet (IES) Report Generator
 * Annex G Format - DepEd Order No. 007, s. 2023
 * 
 * Matches the official Annex G format as shown in DepEd Order No. 007, s. 2023
 */

require_once 'HRMPSBEvaluator.php';
require_once __DIR__ . '/../config/evaluation_criteria.php';

class IESReportGenerator {
    
    private $positionGroup = null;
    private $salaryGrade = null;
    private $category = null;
    
    /**
     * Set position group for position-specific criteria
     */
    public function setPositionGroup($positionGroup) {
        $this->positionGroup = $positionGroup;
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
     * Get criteria mappings for position-specific names
     * Only includes criteria with max_points > 0
     */
    private function getCriteriaMappings() {
        if (!$this->positionGroup) {
            return [];
        }
        
        $criteria = getEvaluationCriteria($this->positionGroup, $this->salaryGrade, $this->category);
        if (!$criteria || empty($criteria['criteria'])) {
            return [];
        }
        
        $mappings = [];
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
        
        foreach ($dbToKey as $dbKey => $key) {
            if (isset($criteria['criteria'][$key])) {
                $maxPoints = $criteria['criteria'][$key]['max_points'];
                // Only include criteria with max_points > 0
                if ($maxPoints > 0) {
                    $mappings[$dbKey] = [
                        'name' => $criteria['criteria'][$key]['name'],
                        'max_points' => $maxPoints
                    ];
                }
            }
        }
        
        return $mappings;
    }
    
    /**
     * Get the ordered list of valid criteria for the position
     */
    private function getValidCriteriaOrder() {
        $criteriaMapping = $this->getCriteriaMappings();
        // Return keys in the order they appear in the mapping
        return array_keys($criteriaMapping);
    }
    
    /**
     * Generate Annex G Individual Evaluation Sheet
     */
    public function generateIES($evaluation, $additionalData = []) {
        // Set position group from additional data if available
        if (isset($additionalData['position_group'])) {
            $this->positionGroup = $additionalData['position_group'];
        }
        if (isset($additionalData['salary_grade'])) {
            $this->salaryGrade = $additionalData['salary_grade'];
        }
        if (isset($additionalData['category'])) {
            $this->category = $additionalData['category'];
        }
        
        $html = $this->generateHTML($evaluation, $additionalData);
        return $html;
    }
    
    /**
     * Generate HTML format of IES matching the official Annex G format
     */
    private function generateHTML($evaluation, $additionalData = []) {
        // Extract additional data
        $applicationCode = $additionalData['application_code'] ?? '';
        $schoolsDivisionOffice = $additionalData['schools_division_office'] ?? '';
        $contactNumber = $additionalData['contact_number'] ?? '';
        $jobGroupSGLevel = $additionalData['job_group_sg_level'] ?? '';
        $hrMPSBChair = $additionalData['hrmpsb_chair'] ?? '';
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Individual Evaluation Sheet (IES) - Annex G</title>
    <link rel="icon" type="image/x-icon" href="/DEPEDEvaluationSystem/images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/DEPEDEvaluationSystem/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/DEPEDEvaluationSystem/images/favicon-16x16.png">
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
        .annex-g {
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
        .applicant-info {
            margin-bottom: 25px;
            border: 1px solid #000;
            padding: 10px;
        }
        .applicant-info-row {
            display: flex;
            margin-bottom: 8px;
            align-items: flex-start;
        }
        .applicant-info-row:last-child {
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
        .evaluation-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 10pt;
        }
        .evaluation-table th,
        .evaluation-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        .evaluation-table th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
        }
        .evaluation-table .col-criteria {
            width: 15%;
            font-weight: bold;
        }
        .evaluation-table .col-weight {
            width: 8%;
            text-align: center;
        }
        .evaluation-table .col-details {
            width: 35%;
        }
        .evaluation-table .col-computation {
            width: 15%;
            text-align: center;
            font-family: "Courier New", monospace;
        }
        .evaluation-table .col-score {
            width: 8%;
            text-align: center;
        }
        .details-subtitle {
            font-size: 8pt;
            font-style: italic;
            color: #666;
            margin-top: 3px;
        }
        .total-row {
            font-weight: bold;
            background-color: #e8e8e8;
        }
        .total-row td {
            text-align: center;
        }
        .attestation {
            margin-top: 30px;
            text-align: justify;
            font-size: 10pt;
            line-height: 1.6;
        }
        .attestation p {
            margin-bottom: 15px;
            text-indent: 30px;
        }
        .attestation strong {
            font-weight: bold;
        }
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 60px;
            padding-top: 5px;
            min-height: 80px;
        }
        .signature-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .signature-name {
            margin-top: 10px;
            font-weight: bold;
        }
        .signature-title {
            font-size: 9pt;
            margin-top: 3px;
        }
        .date-field {
            margin-top: 10px;
        }
        /* Banner styles */
        :root {
            --banner-success-bg: #d4edda;
            --banner-success-border: #c3e6cb;
            --banner-success-text: #155724;
            --banner-success-icon: #28a745;
        }
        .banner {
            margin: 20px 0;
            border-left: 5px solid #28a745;
            border-radius: 4px;
            padding: 12px 16px;
            background-color: var(--banner-success-bg);
            color: var(--banner-success-text);
            animation: slideIn 0.3s ease-in-out, fadeOut 0.3s ease-in-out 4.7s forwards;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-20px); }
        }
        .banner-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .banner-icon {
            font-size: 18px;
            font-weight: bold;
            color: var(--banner-success-icon);
            flex-shrink: 0;
        }
        .banner-text {
            flex: 1;
            line-height: 1.5;
        }
        .banner-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            padding: 0 4px;
            opacity: 0.6;
            transition: opacity 0.2s;
        }
        .banner-close:hover {
            opacity: 1;
        }
    </style>
</head>
<body>';
        
        // Add success banner
        $html .= '<div class="banner" role="status" aria-live="polite">
            <div class="banner-content">
                <span class="banner-icon">✓</span>
                <span class="banner-text"><strong>Evaluation completed successfully!</strong> Individual Evaluation Sheet generated.</span>
                <button class="banner-close" onclick="this.parentElement.parentElement.style.display=\'none\';" aria-label="Close message">&times;</button>
            </div>
        </div>';
        
        // Header with Annex G
        $html .= '<div class="header">
            <div class="annex-g">Annex G</div>
            <div class="header-title">
                <h1>INDIVIDUAL EVALUATION SHEET (IES)</h1>
            </div>
        </div>';
        
        // Applicant Information Section
        $html .= '<div class="applicant-info">
            <div class="applicant-info-row">
                <div class="info-label">Name of Applicant:</div>
                <div class="info-value">' . htmlspecialchars($evaluation['applicant_name']) . '</div>
            </div>
            <div class="applicant-info-row">
                <div class="info-label">Application Code:</div>
                <div class="info-value">' . htmlspecialchars($applicationCode) . '</div>
            </div>
            <div class="applicant-info-row">
                <div class="info-label">Position Applied for:</div>
                <div class="info-value-filled">' . htmlspecialchars($evaluation['position_applied']) . '</div>
            </div>
            <div class="applicant-info-row">
                <div class="info-label">Schools Division Office:</div>
                <div class="info-value">' . htmlspecialchars($schoolsDivisionOffice) . '</div>
            </div>
            <div class="applicant-info-row">
                <div class="info-label">Contact Number:</div>
                <div class="info-value">' . htmlspecialchars($contactNumber) . '</div>
            </div>
            <div class="applicant-info-row">
                <div class="info-label">Job Group/SG-Level:</div>
                <div class="info-value-filled">' . htmlspecialchars($jobGroupSGLevel) . '</div>
            </div>
        </div>';
        
        // Main Evaluation Table
        $html .= '<table class="evaluation-table">
            <thead>
                <tr>
                    <th class="col-criteria">Criteria</th>
                    <th class="col-weight">Weight<br>Allocation</th>
                    <th class="col-details">Details of Applicant\'s Qualifications<br>
                        <span class="details-subtitle">(Relevant documents submitted, additional requirements, notes of HRMPSB Members)</span>
                    </th>
                    <th class="col-computation">Computation</th>
                    <th class="col-score">Actual<br>Score</th>
                </tr>
            </thead>
            <tbody>';
        
        $totalScore = 0;
        
        // Get position-specific criterion mappings (only includes criteria with max_points > 0)
        $criteriaMapping = $this->getCriteriaMappings();
        $validCriteriaOrder = $this->getValidCriteriaOrder();
        
        // DEBUG: Always include debug info as HTML comment for troubleshooting
        $debugInfo = "positionGroup=" . ($this->positionGroup ?? 'NULL') . "; ";
        $debugInfo .= "salaryGrade=" . ($this->salaryGrade ?? 'NULL') . "; ";
        $debugInfo .= "criteriaMapping_count=" . count($criteriaMapping) . "; ";
        $debugInfo .= "validCriteriaOrder_count=" . count($validCriteriaOrder);
        $html .= "<!-- IES_DEBUG: $debugInfo -->";
        
        // Fallback to generic names if position group not set
        $criterionNames = [
            'education' => 'Education',
            'training' => 'Training',
            'experience' => 'Experience',
            'performance' => 'Performance',
            'outstanding_accomplishments' => 'Outstanding Accomplishments',
            'application_of_education' => 'Application of Education',
            'application_of_ld' => 'Application of Learning and Development',
            'potential' => 'Potential (Written Text, BEI, Work Sample Test)'
        ];
        
        // Override with position-specific names if available
        if (!empty($criteriaMapping)) {
            foreach ($criteriaMapping as $dbKey => $mapping) {
                $criterionNames[$dbKey] = $mapping['name'];
            }
        }
        
        // Iterate only through valid criteria for this position
        foreach ($validCriteriaOrder as $criterion) {
            // Check if criterion exists in evaluation data
            if (!isset($evaluation['criteria'][$criterion])) {
                // Criterion exists in config but not in evaluation data
                // This could happen if evaluation wasn't fully processed
                // Try to use the criterion name from mapping and show as N/A or skip
                continue;
            }
            
            $criteria = $evaluation['criteria'][$criterion];
            $totalScore += $criteria['final_score'];
            
            // Get position-specific weight (max_points) - guaranteed to be > 0
            $displayWeight = $criteriaMapping[$criterion]['max_points'] ?? $criteria['weight'];
            
            // Format computation
            // For increment-based criteria: "6-6=0"
            // For weighted criteria (increment is null): "(rating/5) × weight"
            // For Outstanding Accomplishments: "min(points, weight)"
            if ($criteria['increment'] === null) {
                // Check if Outstanding Accomplishments (direct points, not weighted rating)
                if ($criterion === 'outstanding_accomplishments') {
                    $computation = 'min(' . $criteria['applicant_level'] . ', ' . $displayWeight . ')';
                } else {
                    // Weighted computation for Performance, Application, Potential
                    $computation = '(' . $criteria['applicant_level'] . '/5) × ' . $displayWeight;
                }
            } else {
                // Increment-based computation for Education, Training, Experience
                $computation = $criteria['applicant_level'] . '-' . $criteria['baseline_level'] . '=' . $criteria['increment'];
            }
            
            // Format score (remove decimals if whole number)
            $score = $criteria['final_score'] == intval($criteria['final_score']) 
                ? intval($criteria['final_score']) 
                : number_format($criteria['final_score'], 1);
            
            $html .= '<tr>
                <td class="col-criteria">' . htmlspecialchars($criterionNames[$criterion]) . '</td>
                <td class="col-weight">' . $displayWeight . '</td>
                <td class="col-details">' . htmlspecialchars($criteria['applicant_qualification']) . '</td>
                <td class="col-computation">' . $computation . '</td>
                <td class="col-score">' . $score . '</td>
            </tr>';
        }
        
        // Total Row
        $totalScoreFormatted = $evaluation['total_score'] == intval($evaluation['total_score']) 
            ? intval($evaluation['total_score']) 
            : number_format($evaluation['total_score'], 1);
        
        $html .= '<tr class="total-row">
            <td class="col-criteria">TOTAL</td>
            <td class="col-weight">100</td>
            <td class="col-details"></td>
            <td class="col-computation"></td>
            <td class="col-score">' . $totalScoreFormatted . '</td>
        </tr>';
        
        $html .= '</tbody></table>';
        
        // Attestation Section
        $positionApplied = htmlspecialchars($evaluation['position_applied']);
        $jobGroup = htmlspecialchars($jobGroupSGLevel ?: 'the position');
        
        $html .= '<div class="attestation">
            <p>I hereby attest to the conduct of the application code and assessment process in accordance with the applicable guidelines; and acknowledge, upon discussion with the Human Resource Merit Promotion and Selection Board (HRMPSB), the results of the comparative assessment and the points given to me based on my qualifications and submitted documentary requirements for the <strong>' . $positionApplied . '</strong> under <strong>' . $jobGroup . '</strong>.</p>
            
            <p>Furthermore, I hereby affix my signature in this Form to attest to the objective and judicious conduct of the HRMPSB evaluation through Open Ranking System.</p>
        </div>';
        
        // Signature Section
        $html .= '<div class="signature-section">
            <div class="signature-box">
                <div class="signature-label">Name and Signature of Applicant</div>
                <div class="signature-line"></div>
                <div class="date-field">Date: _________________</div>
            </div>
            <div class="signature-box">
                <div class="signature-label">Attested:</div>
                <div class="signature-line"></div>';
        
        if ($hrMPSBChair) {
            $html .= '<div class="signature-name">' . htmlspecialchars($hrMPSBChair) . '</div>
                <div class="signature-title">HRMPSB Chair</div>';
        } else {
            $html .= '<div class="signature-name">_________________________</div>
                <div class="signature-title">HRMPSB Chair</div>';
        }
        
        $html .= '</div>
        </div>';
        
        // Add navigation buttons
        $html .= '<div style="margin-top: 40px; padding: 20px; border-top: 2px solid #ddd; display: flex; gap: 10px; justify-content: center;">
            <button onclick="window.history.back()" style="padding: 10px 30px; background-color: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">Back</button>
            <button onclick="window.location.href=\'index.php\'" style="padding: 10px 30px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">Home</button>
        </div>';
        
        $html .= '</body></html>';
        
        return $html;
    }
    
    /**
     * Generate text-based IES (for console/plain text output)
     */
    public function generateTextIES($evaluation, $additionalData = []) {
        $text = "\n";
        $text .= str_repeat("=", 80) . "\n";
        $text .= "INDIVIDUAL EVALUATION SHEET (IES) - Annex G\n";
        $text .= str_repeat("=", 80) . "\n\n";
        
        $text .= "Name of Applicant: " . $evaluation['applicant_name'] . "\n";
        $text .= "Application Code: " . ($additionalData['application_code'] ?? '') . "\n";
        $text .= "Position Applied For: " . $evaluation['position_applied'] . "\n";
        $text .= "Schools Division Office: " . ($additionalData['schools_division_office'] ?? '') . "\n";
        $text .= "Contact Number: " . ($additionalData['contact_number'] ?? '') . "\n";
        $text .= "Job Group/SG-Level: " . ($additionalData['job_group_sg_level'] ?? '') . "\n\n";
        
        $text .= str_repeat("-", 80) . "\n";
        $text .= sprintf("%-25s %-8s %-20s %-15s %-8s\n", 
            "Criteria", "Weight", "Details", "Computation", "Score");
        $text .= str_repeat("-", 80) . "\n";
        
        // Get position-specific mappings and valid criteria
        $criteriaMapping = $this->getCriteriaMappings();
        $validCriteriaOrder = $this->getValidCriteriaOrder();
        
        foreach ($validCriteriaOrder as $criterion) {
            if (isset($evaluation['criteria'][$criterion])) {
                $criteria = $evaluation['criteria'][$criterion];
                
                // Get position-specific weight
                $displayWeight = $criteriaMapping[$criterion]['max_points'] ?? $criteria['weight'];
                
                // Format computation
                if ($criteria['increment'] === null) {
                    // Check if Outstanding Accomplishments (direct points, not weighted rating)
                    if ($criterion === 'outstanding_accomplishments') {
                        $computation = "min({$criteria['applicant_level']}, {$displayWeight})";
                    } else {
                        // Weighted computation for Performance, Application, Potential
                        $computation = "({$criteria['applicant_level']}/5) × {$displayWeight}";
                    }
                } else {
                    // Increment-based computation for Education, Training, Experience
                    $computation = "{$criteria['applicant_level']}-{$criteria['baseline_level']}={$criteria['increment']}";
                }
                $text .= sprintf("%-25s %-8s %-20s %-15s %-8.1f\n",
                    $criteria['criterion'],
                    $displayWeight,
                    substr($criteria['applicant_qualification'], 0, 20),
                    $computation,
                    $criteria['final_score']
                );
            }
        }
        
        $text .= str_repeat("-", 80) . "\n";
        $text .= sprintf("%-68s %-8.1f\n", "TOTAL:", $evaluation['total_score']);
        $text .= str_repeat("=", 80) . "\n";
        
        return $text;
    }
}
