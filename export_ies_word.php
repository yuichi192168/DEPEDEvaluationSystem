<?php
/**
 * Export IES to Word Document
 * Converts HTML IES content to .docx format
 */

if (!isset($_POST['html_content']) || !isset($_POST['applicant_name'])) {
    die('Missing required data');
}

$htmlContent = $_POST['html_content'];
$applicantName = $_POST['applicant_name'];

// Create Word document content (DOCX is a ZIP file with XML inside)
// For simplicity, we'll create an HTML file that Word can open

$wordContent = <<<EOD
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>IES - $applicantName</title>
    <style>
        body {
            font-family: Calibri, Arial, sans-serif;
            margin: 1in;
            line-height: 1.15;
        }
        
        .page {
            page-break-after: always;
        }
        
        h1 {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin: 12pt 0;
        }
        
        h2 {
            font-size: 12pt;
            font-weight: bold;
            margin: 12pt 0 6pt 0;
        }
        
        .subtitle {
            text-align: center;
            font-size: 11pt;
            margin: 6pt 0 12pt 0;
        }
        
        .info-section {
            margin-bottom: 12pt;
        }
        
        .info-line {
            margin-bottom: 6pt;
            display: flex;
        }
        
        .info-label {
            font-weight: bold;
            min-width: 180pt;
        }
        
        .info-value {
            border-bottom: 1px solid #000;
            flex: 1;
            padding-bottom: 2pt;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 12pt 0;
        }
        
        th {
            background: #e8e8e8;
            border: 1px solid #000;
            padding: 6pt;
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
        }
        
        td {
            border: 1px solid #000;
            padding: 6pt;
            font-size: 10pt;
        }
        
        .criteria-col {
            width: 20%;
            text-align: left;
        }
        
        .weight-col {
            width: 10%;
            text-align: center;
        }
        
        .details-col {
            width: 30%;
            text-align: left;
        }
        
        .computation-col {
            width: 15%;
            text-align: center;
        }
        
        .score-col {
            width: 12%;
            text-align: center;
        }
        
        .total-row {
            background: #f5f5f5;
            font-weight: bold;
        }
        
        .attestation {
            margin-top: 12pt;
            font-size: 10pt;
            line-height: 1.3;
            text-align: justify;
        }
        
        .signature-section {
            margin-top: 20pt;
            font-size: 10pt;
        }
        
        .sig-block {
            margin-bottom: 12pt;
        }
        
        .sig-line {
            border-bottom: 1px solid #000;
            height: 36pt;
            margin-bottom: 3pt;
        }
        
        .annex {
            position: absolute;
            top: 12pt;
            right: 36pt;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="annex">Annex G</div>
    
    <div class="page">
        <h1>INDIVIDUAL EVALUATION SHEET (IES)</h1>
        <div class="subtitle">DepEd Human Resource Management and Professional Selection Board</div>
        
        <div class="info-section">
            <div class="info-line">
                <span class="info-label">Name of Applicant:</span>
                <span class="info-value">$applicantName</span>
                <span class="info-label" style="margin-left: 24pt;">Application Code:</span>
                <span class="info-value">_______________________</span>
            </div>
            
            <div class="info-line">
                <span class="info-label">Position Applied for:</span>
                <span class="info-value">_______________________</span>
            </div>
            
            <div class="info-line">
                <span class="info-label">Schools Division Office:</span>
                <span class="info-value">City Schools Division of Cabuyao</span>
            </div>
            
            <div class="info-line">
                <span class="info-label">Contact Number:</span>
                <span class="info-value">_______________________</span>
                <span class="info-label" style="margin-left: 24pt;">Job Group/SG-Level:</span>
                <span class="info-value">_______________________</span>
            </div>
        </div>
        
        <h2>Applicant's Actual Qualifications</h2>
        
        <table>
            <thead>
                <tr>
                    <th class="criteria-col">Criteria</th>
                    <th class="weight-col">Weight Allocation</th>
                    <th class="details-col">Details of Applicant's Qualifications (Relevant documents submitted; additional requirements, notes or HRMPSB comments)</th>
                    <th class="computation-col">Computation</th>
                    <th class="score-col">Actual Score</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="criteria-col">Education</td>
                    <td class="weight-col">5</td>
                    <td class="details-col"></td>
                    <td class="computation-col"></td>
                    <td class="score-col"></td>
                </tr>
                <tr>
                    <td class="criteria-col">Training</td>
                    <td class="weight-col">5</td>
                    <td class="details-col"></td>
                    <td class="computation-col"></td>
                    <td class="score-col"></td>
                </tr>
                <tr>
                    <td class="criteria-col">Experience</td>
                    <td class="weight-col">20</td>
                    <td class="details-col"></td>
                    <td class="computation-col"></td>
                    <td class="score-col"></td>
                </tr>
                <tr>
                    <td class="criteria-col">Performance</td>
                    <td class="weight-col">20</td>
                    <td class="details-col"></td>
                    <td class="computation-col"></td>
                    <td class="score-col"></td>
                </tr>
                <tr>
                    <td class="criteria-col">Outstanding Accomplishments</td>
                    <td class="weight-col">10</td>
                    <td class="details-col"></td>
                    <td class="computation-col"></td>
                    <td class="score-col"></td>
                </tr>
                <tr>
                    <td class="criteria-col">Application of Education</td>
                    <td class="weight-col">10</td>
                    <td class="details-col"></td>
                    <td class="computation-col"></td>
                    <td class="score-col"></td>
                </tr>
                <tr>
                    <td class="criteria-col">Application of Learning and Development</td>
                    <td class="weight-col">10</td>
                    <td class="details-col"></td>
                    <td class="computation-col"></td>
                    <td class="score-col"></td>
                </tr>
                <tr>
                    <td class="criteria-col">Potential</td>
                    <td class="weight-col">20</td>
                    <td class="details-col"></td>
                    <td class="computation-col"></td>
                    <td class="score-col"></td>
                </tr>
                <tr class="total-row">
                    <td colspan="2" class="criteria-col">TOTAL</td>
                    <td class="details-col"></td>
                    <td class="computation-col">100</td>
                    <td class="score-col"></td>
                </tr>
            </tbody>
        </table>
        
        <div class="attestation">
            <p>I hereby attest to the conduct of the application code and assessment process in accordance with the applicable guidelines, and knowledge, upon discussion with the Human Resource Merit Promotion and Selection Board (HRMPSB), the results of the comparative assessment and the points given to me based on my qualifications and submitted documentary requirements for the Information and Communications Technology under Contract of Service.</p>
            
            <p>Furthermore, I hereby affix my signature in this Form to attest to the objectives and judicious conduct of the HRMPSB evaluation through Open Ranking System.</p>
        </div>
        
        <div class="signature-section">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div><strong>Name and Signature of Applicant</strong></div>
                <div>Date: _______________________</div>
            </div>
            
            <div style="margin-top: 24pt;">
                <div><strong>Attested:</strong></div>
            </div>
            
            <div class="sig-block">
                <div class="sig-line"></div>
                <div><strong>RANDY D. PUNZALAN, CESO VI</strong></div>
                <div>HRMPSB Chair</div>
            </div>
        </div>
    </div>
</body>
</html>
EOD;

// Output as Word-compatible HTML
header('Content-Type: application/msword');
header('Content-Disposition: attachment; filename="IES-' . str_replace([' ', '\'', '"'], ['_', '', ''], $applicantName) . '.doc"');
header('Cache-Control: max-age=0');

echo $wordContent;
exit;
?>
