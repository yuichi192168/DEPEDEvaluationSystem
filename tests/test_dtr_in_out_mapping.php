<?php
/**
 * Regression test: split IN/OUT rows should map OUT to Departure.
 *
 * Run:
 *   php tests/test_dtr_in_out_mapping.php
 */

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../generate_dtr.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

function fail($message)
{
    fwrite(STDERR, "FAIL: {$message}\n");
    exit(1);
}

function ok($message)
{
    fwrite(STDOUT, "OK: {$message}\n");
}

$rawFile = __DIR__ . '/../excel-files/_test_in_out_case.xlsx';
$outDir = __DIR__ . '/../output/_test_in_out_output';
$templateFile = __DIR__ . '/../DTR-Monthly-Template.xlsx';

if (!file_exists($templateFile)) {
    fail('Template file is missing: DTR-Monthly-Template.xlsx');
}

if (!is_dir(dirname($rawFile))) {
    mkdir(dirname($rawFile), 0755, true);
}

if (!is_dir($outDir)) {
    mkdir($outDir, 0755, true);
}

// Build test source workbook with split IN/OUT rows on same day.
$raw = new Spreadsheet();
$sheet = $raw->getActiveSheet();
$headers = ['Name', 'Date', 'Timetable', 'IN', 'OUT', 'Remarks'];
foreach ($headers as $index => $header) {
    $sheet->setCellValueByColumnAndRow($index + 1, 1, $header);
}

$sheet->setCellValue('A2', 'TEST EMP');
$sheet->setCellValue('B2', '3/5/2026');
$sheet->setCellValue('C2', 'IN');
$sheet->setCellValue('D2', '7:01 AM');
$sheet->setCellValue('E2', '');
$sheet->setCellValue('F2', '7:00 AM - 4:00 PM');

$sheet->setCellValue('A3', 'TEST EMP');
$sheet->setCellValue('B3', '3/5/2026');
$sheet->setCellValue('C3', 'OUT');
$sheet->setCellValue('D3', '');
$sheet->setCellValue('E3', '4:02 PM');
$sheet->setCellValue('F3', '7:00 AM - 4:00 PM');

IOFactory::createWriter($raw, 'Xlsx')->save($rawFile);
$raw->disconnectWorksheets();
unset($raw, $sheet);

$generator = new DTRGenerator($rawFile, $templateFile, $outDir);
$generator->loadSourceData();
$data = $generator->getEmployeeData();

if (!isset($data['TEST EMP']['dates'][5])) {
    fail('Parsed data for TEST EMP day 5 was not found.');
}

$dayData = $data['TEST EMP']['dates'][5];
if (($dayData['morning_arrival'] ?? '') !== '07:01') {
    fail('Expected morning_arrival 07:01, got: ' . ($dayData['morning_arrival'] ?? '[empty]'));
}

if (($dayData['morning_departure'] ?? '') !== '16:02') {
    fail('Expected morning_departure 16:02 from OUT value, got: ' . ($dayData['morning_departure'] ?? '[empty]'));
}

ok('Parsed mapping preserves OUT as Departure (morning_departure).');

$generator->generateDTRs();
$files = glob($outDir . '/*.xlsx');
if (empty($files)) {
    fail('No output file was generated.');
}

sort($files);
$outputFile = $files[0];
$outSheet = IOFactory::load($outputFile)->getActiveSheet();

// Day 5 row in monthly template: starts at day 1 row 19, so day 5 is row 23.
$arrival = (string)$outSheet->getCell('B23')->getCalculatedValue();
$departure = (string)$outSheet->getCell('C23')->getCalculatedValue();

if ($arrival !== '07:01') {
    fail("Template Arrival mismatch at B23. Expected 07:01, got: {$arrival}");
}

if ($departure !== '16:02') {
    fail("Template Departure mismatch at C23. Expected 16:02, got: {$departure}");
}

ok('Template mapping is correct: IN -> Arrival, OUT -> Departure.');

// Cleanup generated test artifacts.
@unlink($rawFile);
foreach ($files as $file) {
    @unlink($file);
}

ok('Regression test completed successfully.');
