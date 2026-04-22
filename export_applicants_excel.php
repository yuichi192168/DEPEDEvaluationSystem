<?php
/**
 * Export filtered CAR/IES report data to Excel.
 */

session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/classes/DBConnection.php';
require_once __DIR__ . '/includes/report_filters.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conn = DBConnection::getConnection();
if (!$conn) {
    http_response_code(500);
    exit('Database connection failed.');
}

$viewMode = isset($_GET['view']) ? trim((string)$_GET['view']) : 'all';
if (!in_array($viewMode, ['all', 'position', 'ies'], true)) {
    $viewMode = 'all';
}

$positionId = isset($_GET['position_id']) && ctype_digit((string)$_GET['position_id'])
    ? (int)$_GET['position_id']
    : null;

$filters = normalizeReportFilters($_GET);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$rowIndex = 1;

$filterLabel = buildActiveFilterLabel($filters);
$sheet->setCellValue('A' . $rowIndex, 'DepEd HRMPSB Evaluation System Export');
$rowIndex++;
$sheet->setCellValue('A' . $rowIndex, $filterLabel);
$rowIndex += 2;

if ($viewMode === 'ies') {
    $headers = [
        'Applicant Name',
        'Position',
        'Evaluation Date',
        'Total Score',
        'Criterion',
        'Applicant Qualification',
        'Applicant Level',
        'Baseline Qualification',
        'Baseline Level',
        'Weight',
        'Increment',
        'Final Score'
    ];

    foreach ($headers as $col => $header) {
        $sheet->setCellValueByColumnAndRow($col + 1, $rowIndex, $header);
    }
    $rowIndex++;

    $rows = fetchFilteredIESRows($conn, $filters);
    foreach ($rows as $row) {
        $sheet->setCellValueByColumnAndRow(1, $rowIndex, $row['name'] ?? '');
        $sheet->setCellValueByColumnAndRow(2, $rowIndex, $row['position_name'] ?? '');
        $sheet->setCellValueByColumnAndRow(3, $rowIndex, $row['evaluation_date'] ?? '');
        $sheet->setCellValueByColumnAndRow(4, $rowIndex, isset($row['total_score']) ? (float)$row['total_score'] : 0);
        $sheet->setCellValueByColumnAndRow(5, $rowIndex, $row['criterion'] ?? '');
        $sheet->setCellValueByColumnAndRow(6, $rowIndex, $row['applicant_qualification'] ?? '');
        $sheet->setCellValueByColumnAndRow(7, $rowIndex, $row['applicant_level'] ?? '');
        $sheet->setCellValueByColumnAndRow(8, $rowIndex, $row['baseline_qualification'] ?? '');
        $sheet->setCellValueByColumnAndRow(9, $rowIndex, $row['baseline_level'] ?? '');
        $sheet->setCellValueByColumnAndRow(10, $rowIndex, isset($row['weight']) ? (float)$row['weight'] : 0);
        $sheet->setCellValueByColumnAndRow(11, $rowIndex, isset($row['increment']) ? (float)$row['increment'] : 0);
        $sheet->setCellValueByColumnAndRow(12, $rowIndex, isset($row['final_score']) ? (float)$row['final_score'] : 0);
        $rowIndex++;
    }
} else {
    $headers = [
        'Position',
        'Rank',
        'Applicant Name',
        'Application Code',
        'Education',
        'Training',
        'Experience',
        'Performance',
        'Outstanding Accomplishments',
        'Application of Education',
        'Application of L&D',
        'Potential',
        'Total Score',
        'Assessment Date',
        'Remarks'
    ];

    foreach ($headers as $col => $header) {
        $sheet->setCellValueByColumnAndRow($col + 1, $rowIndex, $header);
    }
    $rowIndex++;

    if ($viewMode === 'position' && $positionId !== null) {
        $rows = fetchFilteredResultsByPosition($conn, $positionId, $filters);
    } else {
        $rows = fetchFilteredAllResults($conn, $filters);
    }

    foreach ($rows as $row) {
        $sheet->setCellValueByColumnAndRow(1, $rowIndex, $row['position_name'] ?? '');
        $sheet->setCellValueByColumnAndRow(2, $rowIndex, isset($row['rank']) ? (int)$row['rank'] : '');
        $sheet->setCellValueByColumnAndRow(3, $rowIndex, $row['name'] ?? '');
        $sheet->setCellValueByColumnAndRow(4, $rowIndex, $row['application_code'] ?? '');
        $sheet->setCellValueByColumnAndRow(5, $rowIndex, isset($row['education_score']) ? (float)$row['education_score'] : 0);
        $sheet->setCellValueByColumnAndRow(6, $rowIndex, isset($row['training_score']) ? (float)$row['training_score'] : 0);
        $sheet->setCellValueByColumnAndRow(7, $rowIndex, isset($row['experience_score']) ? (float)$row['experience_score'] : 0);
        $sheet->setCellValueByColumnAndRow(8, $rowIndex, isset($row['performance_score']) ? (float)$row['performance_score'] : 0);
        $sheet->setCellValueByColumnAndRow(9, $rowIndex, isset($row['outstanding_accomplishments_score']) ? (float)$row['outstanding_accomplishments_score'] : 0);
        $sheet->setCellValueByColumnAndRow(10, $rowIndex, isset($row['application_of_education_score']) ? (float)$row['application_of_education_score'] : 0);
        $sheet->setCellValueByColumnAndRow(11, $rowIndex, isset($row['application_of_ld_score']) ? (float)$row['application_of_ld_score'] : 0);
        $sheet->setCellValueByColumnAndRow(12, $rowIndex, isset($row['potential_score']) ? (float)$row['potential_score'] : 0);
        $sheet->setCellValueByColumnAndRow(13, $rowIndex, isset($row['total_score']) ? (float)$row['total_score'] : 0);
        $sheet->setCellValueByColumnAndRow(14, $rowIndex, $row['assessment_date'] ?? '');
        $sheet->setCellValueByColumnAndRow(15, $rowIndex, $row['remarks'] ?? '');
        $rowIndex++;
    }
}

foreach (range(1, 15) as $colIndex) {
    $sheet->getColumnDimensionByColumn($colIndex)->setAutoSize(true);
}

$filterQuery = buildFilterQueryString($filters);
$suffix = $filterQuery !== '' ? preg_replace('/[^A-Za-z0-9_-]+/', '_', $filterQuery) : 'all_records';
$filename = 'Applicants_Report_' . $viewMode . '_' . date('Ymd_His') . '_' . $suffix . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
