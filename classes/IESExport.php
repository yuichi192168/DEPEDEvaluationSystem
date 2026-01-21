<?php
/**
 * IES Export Handler
 * Handles export to Word, PDF, Excel formats
 */

require_once __DIR__ . '/IESReportGenerator.php';

class IESExport {
    /**
     * Clear any output buffering to avoid corrupting binary downloads (DOCX/PDF/XLSX).
     */
    private function clearOutputBuffers(): void {
        // If anything was already sent, binary downloads can be corrupted.
        // We can't reliably recover, but we can at least stop adding more output.
        while (ob_get_level() > 0) {
            @ob_end_clean();
        }
    }

    /**
     * Send common download headers (safe for binary downloads).
     */
    private function sendDownloadHeaders(string $contentType, string $filename): void {
        $this->clearOutputBuffers();

        header_remove();
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
    }
    
    /**
     * Export to Word Document (.docx)
     */
    public function exportToWord($evaluation, $additionalData = []) {
        $autoloadPath = __DIR__ . '/../vendor/autoload.php';
        if (!file_exists($autoloadPath)) {
            die('PHP libraries not installed. Please run: composer install<br><br>See INSTALLATION.md for details.');
        }
        
        require_once $autoloadPath;
        
        if (!class_exists('PhpOffice\\PhpWord\\PhpWord')) {
            die('PHPWord library not found. Please run: composer install');
        }
        
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        // Set document properties
        $properties = $phpWord->getDocInfo();
        $properties->setCreator('DepEd HRMPSB Evaluation System');
        $properties->setTitle('Individual Evaluation Sheet (IES)');
        $properties->setDescription('Annex G - DepEd Order No. 007, s. 2023');
        
        // Add section
        $section = $phpWord->addSection([
            'marginTop' => 1440,
            'marginBottom' => 1440,
            'marginLeft' => 1440,
            'marginRight' => 1440
        ]);
        
        // Header with Annex G
        $header = $section->addHeader();
        $header->addText('Annex G', ['bold' => true, 'size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);
        
        // Title
        $section->addText('INDIVIDUAL EVALUATION SHEET (IES)', [
            'bold' => true,
            'size' => 14,
            'allCaps' => true
        ], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTextBreak(1);
        
        // Applicant Information
        $applicantInfo = [
            'Name of Applicant' => $evaluation['applicant_name'],
            'Application Code' => $additionalData['application_code'] ?? '',
            'Position Applied for' => $evaluation['position_applied'],
            'Schools Division Office' => $additionalData['schools_division_office'] ?? '',
            'Contact Number' => $additionalData['contact_number'] ?? '',
            'Job Group/SG-Level' => $additionalData['job_group_sg_level'] ?? ''
        ];
        
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
        foreach ($applicantInfo as $label => $value) {
            $table->addRow();
            $table->addCell(3000)->addText($label . ':', ['bold' => true]);
            $table->addCell(6000)->addText($value);
        }
        $section->addTextBreak(1);
        
        // Evaluation Table
        $evalTable = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80
        ]);
        
        // Header row
        $evalTable->addRow(400);
        $evalTable->addCell(1500)->addText('Criteria', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $evalTable->addCell(800)->addText('Weight Allocation', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $evalTable->addCell(3500)->addText('Details of Applicant\'s Qualifications', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $evalTable->addCell(1500)->addText('Computation', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $evalTable->addCell(800)->addText('Actual Score', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        // Subtitle row
        $evalTable->addRow(300);
        $evalTable->addCell(1500)->addText('');
        $evalTable->addCell(800)->addText('');
        $evalTable->addCell(3500)->addText('(Relevant documents submitted, additional requirements, notes of HRMPSB Members)', [
            'italic' => true,
            'size' => 8
        ], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $evalTable->addCell(1500)->addText('');
        $evalTable->addCell(800)->addText('');
        
        // Data rows
        $criteriaOrder = [
            'education', 'training', 'experience', 'performance',
            'outstanding_accomplishments', 'application_of_education',
            'application_of_ld', 'potential'
        ];
        
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
        
        foreach ($criteriaOrder as $criterion) {
            if (isset($evaluation['criteria'][$criterion])) {
                $criteria = $evaluation['criteria'][$criterion];
                // Format computation - handle null increment for weighted criteria
                if ($criteria['increment'] === null) {
                    // Check if Outstanding Accomplishments (direct points, not weighted rating)
                    if ($criterion === 'outstanding_accomplishments') {
                        $computation = 'min(' . $criteria['applicant_level'] . ', ' . $criteria['weight'] . ')';
                    } else {
                        // Weighted computation for Performance, Application, Potential
                        $computation = '(' . $criteria['applicant_level'] . '/5) × ' . $criteria['weight'];
                    }
                } else {
                    // Increment-based computation for Education, Training, Experience
                    $computation = $criteria['applicant_level'] . '-' . $criteria['baseline_level'] . '=' . $criteria['increment'];
                }
                $score = $criteria['final_score'] == intval($criteria['final_score']) 
                    ? intval($criteria['final_score']) 
                    : number_format($criteria['final_score'], 1);
                
                $evalTable->addRow();
                $evalTable->addCell(1500)->addText($criterionNames[$criterion], ['bold' => true]);
                $evalTable->addCell(800)->addText($criteria['weight'], [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                $evalTable->addCell(3500)->addText($criteria['applicant_qualification']);
                $evalTable->addCell(1500)->addText($computation, ['name' => 'Courier New'], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                $evalTable->addCell(800)->addText($score, [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            }
        }
        
        // Total row
        $totalScore = $evaluation['total_score'] == intval($evaluation['total_score']) 
            ? intval($evaluation['total_score']) 
            : number_format($evaluation['total_score'], 1);
        
        $evalTable->addRow(400);
        $evalTable->addCell(1500)->addText('TOTAL', ['bold' => true]);
        $evalTable->addCell(800)->addText('100', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $evalTable->addCell(3500)->addText('');
        $evalTable->addCell(1500)->addText('');
        $evalTable->addCell(800)->addText($totalScore, ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        $section->addTextBreak(2);
        
        // Attestation
        $positionApplied = $evaluation['position_applied'];
        $jobGroup = $additionalData['job_group_sg_level'] ?: 'the position';
        
        $section->addText('I hereby attest to the conduct of the application code and assessment process in accordance with the applicable guidelines; and acknowledge, upon discussion with the Human Resource Merit Promotion and Selection Board (HRMPSB), the results of the comparative assessment and the points given to me based on my qualifications and submitted documentary requirements for the ' . $positionApplied . ' under ' . $jobGroup . '.', [
            'size' => 10
        ], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::JUSTIFY]);
        
        $section->addTextBreak(1);
        
        $section->addText('Furthermore, I hereby affix my signature in this Form to attest to the objective and judicious conduct of the HRMPSB evaluation through Open Ranking System.', [
            'size' => 10
        ], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::JUSTIFY]);
        
        $section->addTextBreak(3);
        
        // Signature section
        $signatureTable = $section->addTable(['borderSize' => 0]);
        $signatureTable->addRow();
        
        // Applicant signature
        $cell1 = $signatureTable->addCell(4000);
        $cell1->addText('Name and Signature of Applicant', ['bold' => true]);
        $cell1->addTextBreak(8);
        $cell1->addText('Date: _________________');
        
        // HRMPSB Chair signature
        $cell2 = $signatureTable->addCell(4000);
        $cell2->addText('Attested:', ['bold' => true]);
        $cell2->addTextBreak(8);
        if (!empty($additionalData['hrmpsb_chair'])) {
            $cell2->addText($additionalData['hrmpsb_chair'], ['bold' => true]);
            $cell2->addText('HRMPSB Chair', ['size' => 9]);
        } else {
            $cell2->addText('_________________________', ['bold' => true]);
            $cell2->addText('HRMPSB Chair', ['size' => 9]);
        }
        
        // Save file
        $filename = 'IES_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $evaluation['applicant_name']) . '_' . date('YmdHis') . '.docx';

        $this->sendDownloadHeaders(
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            $filename
        );

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
        exit;
    }
    
    /**
     * Export to PDF
     */
    public function exportToPDF($evaluation, $additionalData = []) {
        $autoloadPath = __DIR__ . '/../vendor/autoload.php';
        if (!file_exists($autoloadPath)) {
            die('PHP libraries not installed. Please run: composer install<br><br>See INSTALLATION.md for details.');
        }
        
        require_once $autoloadPath;
        
        if (!class_exists('TCPDF')) {
            die('TCPDF library not found. Please run: composer install');
        }
        
        // Create PDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('DepEd HRMPSB Evaluation System');
        $pdf->SetAuthor('DepEd HRMPSB');
        $pdf->SetTitle('Individual Evaluation Sheet (IES)');
        $pdf->SetSubject('Annex G - DepEd Order No. 007, s. 2023');
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Set margins
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetAutoPageBreak(TRUE, 20);
        
        // Add a page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('times', '', 11);
        
        // Annex G header
        $pdf->SetFont('times', 'B', 12);
        $pdf->Cell(0, 10, 'Annex G', 0, 1, 'R');
        
        // Title
        $pdf->SetFont('times', 'B', 14);
        $pdf->Cell(0, 10, 'INDIVIDUAL EVALUATION SHEET (IES)', 0, 1, 'C');
        $pdf->Ln(5);
        
        // Applicant Information
        $pdf->SetFont('times', '', 11);
        $applicantInfo = [
            'Name of Applicant' => $evaluation['applicant_name'],
            'Application Code' => $additionalData['application_code'] ?? '',
            'Position Applied for' => $evaluation['position_applied'],
            'Schools Division Office' => $additionalData['schools_division_office'] ?? '',
            'Contact Number' => $additionalData['contact_number'] ?? '',
            'Job Group/SG-Level' => $additionalData['job_group_sg_level'] ?? ''
        ];
        
        $pdf->SetLineWidth(0.5);
        $pdf->Rect(15, $pdf->GetY(), 180, 60);
        
        foreach ($applicantInfo as $label => $value) {
            $pdf->SetFont('times', 'B', 10);
            $pdf->Cell(50, 8, $label . ':', 0, 0, 'L');
            $pdf->SetFont('times', '', 10);
            $pdf->Cell(0, 8, $value, 'B', 1, 'L');
            $pdf->Ln(2);
        }
        
        $pdf->Ln(5);
        
        // Evaluation Table
        $pdf->SetFont('times', 'B', 9);
        
        // Table header
        $pdf->SetFillColor(240, 240, 240);
        $pdf->Cell(27, 12, 'Criteria', 1, 0, 'C', true);
        $pdf->Cell(15, 12, 'Weight', 1, 0, 'C', true);
        $pdf->Cell(65, 6, 'Details of Applicant\'s', 1, 0, 'C', true);
        $pdf->Cell(27, 12, 'Computation', 1, 0, 'C', true);
        $pdf->Cell(15, 12, 'Actual', 1, 0, 'C', true);
        $pdf->Ln();
        $pdf->SetX(57);
        $pdf->SetFont('times', 'I', 7);
        $pdf->Cell(65, 6, 'Qualifications (Relevant documents submitted, additional requirements, notes of HRMPSB Members)', 1, 0, 'C', true);
        $pdf->SetX(142);
        $pdf->SetFont('times', 'B', 9);
        $pdf->Cell(15, 6, 'Score', 1, 1, 'C', true);
        
        // Table data
        $criteriaOrder = [
            'education', 'training', 'experience', 'performance',
            'outstanding_accomplishments', 'application_of_education',
            'application_of_ld', 'potential'
        ];
        
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
        
        $pdf->SetFont('times', '', 9);
        foreach ($criteriaOrder as $criterion) {
            if (isset($evaluation['criteria'][$criterion])) {
                $criteria = $evaluation['criteria'][$criterion];
                // Format computation - handle null increment for weighted criteria
                if ($criteria['increment'] === null) {
                    // Check if Outstanding Accomplishments (direct points, not weighted rating)
                    if ($criterion === 'outstanding_accomplishments') {
                        $computation = 'min(' . $criteria['applicant_level'] . ', ' . $criteria['weight'] . ')';
                    } else {
                        // Weighted computation for Performance, Application, Potential
                        $computation = '(' . $criteria['applicant_level'] . '/5) × ' . $criteria['weight'];
                    }
                } else {
                    // Increment-based computation for Education, Training, Experience
                    $computation = $criteria['applicant_level'] . '-' . $criteria['baseline_level'] . '=' . $criteria['increment'];
                }
                $score = $criteria['final_score'] == intval($criteria['final_score']) 
                    ? intval($criteria['final_score']) 
                    : number_format($criteria['final_score'], 1);
                
                $pdf->SetFont('times', 'B', 9);
                $pdf->Cell(27, 8, $criterionNames[$criterion], 1, 0, 'L');
                $pdf->SetFont('times', '', 9);
                $pdf->Cell(15, 8, $criteria['weight'], 1, 0, 'C');
                $pdf->Cell(65, 8, $criteria['applicant_qualification'], 1, 0, 'L');
                $pdf->SetFont('courier', '', 9);
                $pdf->Cell(27, 8, $computation, 1, 0, 'C');
                $pdf->SetFont('times', '', 9);
                $pdf->Cell(15, 8, $score, 1, 1, 'C');
            }
        }
        
        // Total row
        $totalScore = $evaluation['total_score'] == intval($evaluation['total_score']) 
            ? intval($evaluation['total_score']) 
            : number_format($evaluation['total_score'], 1);
        
        $pdf->SetFillColor(232, 232, 232);
        $pdf->SetFont('times', 'B', 9);
        $pdf->Cell(27, 8, 'TOTAL', 1, 0, 'L', true);
        $pdf->Cell(15, 8, '100', 1, 0, 'C', true);
        $pdf->Cell(65, 8, '', 1, 0, 'L', true);
        $pdf->Cell(27, 8, '', 1, 0, 'C', true);
        $pdf->Cell(15, 8, $totalScore, 1, 1, 'C', true);
        
        $pdf->Ln(10);
        
        // Attestation
        $positionApplied = $evaluation['position_applied'];
        $jobGroup = $additionalData['job_group_sg_level'] ?: 'the position';
        
        $pdf->SetFont('times', '', 10);
        $attestation1 = 'I hereby attest to the conduct of the application code and assessment process in accordance with the applicable guidelines; and acknowledge, upon discussion with the Human Resource Merit Promotion and Selection Board (HRMPSB), the results of the comparative assessment and the points given to me based on my qualifications and submitted documentary requirements for the ' . $positionApplied . ' under ' . $jobGroup . '.';
        $pdf->MultiCell(0, 5, $attestation1, 0, 'J');
        
        $pdf->Ln(5);
        
        $attestation2 = 'Furthermore, I hereby affix my signature in this Form to attest to the objective and judicious conduct of the HRMPSB evaluation through Open Ranking System.';
        $pdf->MultiCell(0, 5, $attestation2, 0, 'J');
        
        $pdf->Ln(15);
        
        // Signature section
        $pdf->SetFont('times', 'B', 10);
        $pdf->Cell(90, 5, 'Name and Signature of Applicant', 0, 0, 'L');
        $pdf->Cell(0, 5, 'Attested:', 0, 1, 'L');
        
        $pdf->Ln(30);
        
        $pdf->SetFont('times', '', 10);
        $pdf->Cell(90, 5, 'Date: _________________', 0, 0, 'L');
        
        if (!empty($additionalData['hrmpsb_chair'])) {
            $pdf->SetFont('times', 'B', 10);
            $pdf->Cell(0, 5, $additionalData['hrmpsb_chair'], 0, 1, 'L');
            $pdf->SetFont('times', '', 9);
            $pdf->SetX(105);
            $pdf->Cell(0, 5, 'HRMPSB Chair', 0, 1, 'L');
        } else {
            $pdf->SetFont('times', 'B', 10);
            $pdf->Cell(0, 5, '_________________________', 0, 1, 'L');
            $pdf->SetFont('times', '', 9);
            $pdf->SetX(105);
            $pdf->Cell(0, 5, 'HRMPSB Chair', 0, 1, 'L');
        }
        
        // Output PDF
        $filename = 'IES_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $evaluation['applicant_name']) . '_' . date('YmdHis') . '.pdf';
        $this->sendDownloadHeaders('application/pdf', $filename);
        // TCPDF will output directly
        $pdf->Output($filename, 'D');
        exit;
    }
    
    /**
     * Export to Excel (.xlsx)
     */
    public function exportToExcel($evaluation, $additionalData = []) {
        $autoloadPath = __DIR__ . '/../vendor/autoload.php';
        if (!file_exists($autoloadPath)) {
            die('PHP libraries not installed. Please run: composer install<br><br>See INSTALLATION.md for details.');
        }
        
        require_once $autoloadPath;
        
        if (!class_exists('PhpOffice\\PhpSpreadsheet\\Spreadsheet')) {
            die('PhpSpreadsheet library not found. Please run: composer install');
        }
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('DepEd HRMPSB Evaluation System')
            ->setTitle('Individual Evaluation Sheet (IES)')
            ->setDescription('Annex G - DepEd Order No. 007, s. 2023');
        
        $row = 1;
        
        // Annex G header
        $sheet->setCellValue('E1', 'Annex G');
        $sheet->getStyle('E1')->getFont()->setBold(true);
        
        // Title
        $sheet->setCellValue('A3', 'INDIVIDUAL EVALUATION SHEET (IES)');
        $sheet->mergeCells('A3:E3');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $row = 5;
        
        // Applicant Information
        $applicantInfo = [
            'Name of Applicant' => $evaluation['applicant_name'],
            'Application Code' => $additionalData['application_code'] ?? '',
            'Position Applied for' => $evaluation['position_applied'],
            'Schools Division Office' => $additionalData['schools_division_office'] ?? '',
            'Contact Number' => $additionalData['contact_number'] ?? '',
            'Job Group/SG-Level' => $additionalData['job_group_sg_level'] ?? ''
        ];
        
        foreach ($applicantInfo as $label => $value) {
            $sheet->setCellValue('A' . $row, $label . ':');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $sheet->setCellValue('B' . $row, $value);
            $sheet->mergeCells('B' . $row . ':E' . $row);
            $row++;
        }
        
        $row += 2;
        
        // Table Header
        $headers = ['Criteria', 'Weight Allocation', 'Details of Applicant\'s Qualifications', 'Computation', 'Actual Score'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $sheet->getStyle($col . $row)->getFont()->setBold(true);
            $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . $row)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E0E0E0');
            $col++;
        }
        
        // Subtitle
        $row++;
        $sheet->setCellValue('C' . $row, '(Relevant documents submitted, additional requirements, notes of HRMPSB Members)');
        $sheet->getStyle('C' . $row)->getFont()->setItalic(true)->setSize(8);
        $sheet->mergeCells('C' . $row . ':E' . $row);
        
        $row++;
        
        // Table Data
        $criteriaOrder = [
            'education', 'training', 'experience', 'performance',
            'outstanding_accomplishments', 'application_of_education',
            'application_of_ld', 'potential'
        ];
        
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
        
        foreach ($criteriaOrder as $criterion) {
            if (isset($evaluation['criteria'][$criterion])) {
                $criteria = $evaluation['criteria'][$criterion];
                // Format computation - handle null increment for weighted criteria
                if ($criteria['increment'] === null) {
                    // Check if Outstanding Accomplishments (direct points, not weighted rating)
                    if ($criterion === 'outstanding_accomplishments') {
                        $computation = 'min(' . $criteria['applicant_level'] . ', ' . $criteria['weight'] . ')';
                    } else {
                        // Weighted computation for Performance, Application, Potential
                        $computation = '(' . $criteria['applicant_level'] . '/5) × ' . $criteria['weight'];
                    }
                } else {
                    // Increment-based computation for Education, Training, Experience
                    $computation = $criteria['applicant_level'] . '-' . $criteria['baseline_level'] . '=' . $criteria['increment'];
                }
                $score = $criteria['final_score'] == intval($criteria['final_score']) 
                    ? intval($criteria['final_score']) 
                    : number_format($criteria['final_score'], 1);
                
                $sheet->setCellValue('A' . $row, $criterionNames[$criterion]);
                $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                $sheet->setCellValue('B' . $row, $criteria['weight']);
                $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('C' . $row, $criteria['applicant_qualification']);
                $sheet->setCellValue('D' . $row, $computation);
                $sheet->getStyle('D' . $row)->getFont()->setName('Courier New');
                $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('E' . $row, $score);
                $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                
                // Add borders
                foreach (['A', 'B', 'C', 'D', 'E'] as $col) {
                    $sheet->getStyle($col . $row)->getBorders()->getAllBorders()
                        ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }
                
                $row++;
            }
        }
        
        // Total row
        $totalScore = $evaluation['total_score'] == intval($evaluation['total_score']) 
            ? intval($evaluation['total_score']) 
            : number_format($evaluation['total_score'], 1);
        
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('B' . $row, '100');
        $sheet->getStyle('B' . $row)->getFont()->setBold(true);
        $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('E' . $row, $totalScore);
        $sheet->getStyle('E' . $row)->getFont()->setBold(true);
        $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Fill total row
        $sheet->getStyle('A' . $row . ':E' . $row)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E8E8E8');
        
        // Add borders to total row
        foreach (['A', 'B', 'C', 'D', 'E'] as $col) {
            $sheet->getStyle($col . $row)->getBorders()->getAllBorders()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }
        
        $row += 3;
        
        // Attestation
        $positionApplied = $evaluation['position_applied'];
        $jobGroup = $additionalData['job_group_sg_level'] ?: 'the position';
        
        $attestation1 = 'I hereby attest to the conduct of the application code and assessment process in accordance with the applicable guidelines; and acknowledge, upon discussion with the Human Resource Merit Promotion and Selection Board (HRMPSB), the results of the comparative assessment and the points given to me based on my qualifications and submitted documentary requirements for the ' . $positionApplied . ' under ' . $jobGroup . '.';
        $sheet->setCellValue('A' . $row, $attestation1);
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
        $sheet->getRowDimension($row)->setRowHeight(-1);
        
        $row += 3;
        
        $attestation2 = 'Furthermore, I hereby affix my signature in this Form to attest to the objective and judicious conduct of the HRMPSB evaluation through Open Ranking System.';
        $sheet->setCellValue('A' . $row, $attestation2);
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
        $sheet->getRowDimension($row)->setRowHeight(-1);
        
        $row += 5;
        
        // Signature section
        $sheet->setCellValue('A' . $row, 'Name and Signature of Applicant');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('C' . $row, 'Attested:');
        $sheet->getStyle('C' . $row)->getFont()->setBold(true);
        
        $row += 8;
        
        $sheet->setCellValue('A' . $row, 'Date: _________________');
        if (!empty($additionalData['hrmpsb_chair'])) {
            $sheet->setCellValue('C' . $row, $additionalData['hrmpsb_chair']);
            $sheet->getStyle('C' . $row)->getFont()->setBold(true);
            $row++;
            $sheet->setCellValue('C' . $row, 'HRMPSB Chair');
        } else {
            $sheet->setCellValue('C' . $row, '_________________________');
            $sheet->getStyle('C' . $row)->getFont()->setBold(true);
            $row++;
            $sheet->setCellValue('C' . $row, 'HRMPSB Chair');
        }
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(50);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(15);
        
        // Add borders to header
        foreach (['A', 'B', 'C', 'D', 'E'] as $col) {
            $sheet->getStyle($col . '6')->getBorders()->getAllBorders()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }
        
        // Output Excel
        $filename = 'IES_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $evaluation['applicant_name']) . '_' . date('YmdHis') . '.xlsx';

        $this->sendDownloadHeaders(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $filename
        );

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}

