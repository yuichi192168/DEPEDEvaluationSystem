<?php
/**
 * Daily Time Record (DTR) Generator
 * 
 * Automati

cally reads attendance logs from raw OSDS file and populates
 * a pre-formatted Excel DTR template for each employee.
 * 
 * Requirements:
 * - PHP 8.x
 * - PhpSpreadsheet library
 * - OSDS-January-2026.xls (or .xlsx) with columns: Name, Date, Timetable, Clock In, Clock Out, Department
 * - dtr-jan-2026.xlsx template
 */

require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DTRGenerator
{
    private $sourceFile;
    private $templateFile;
    private $outputDir;
    private $logData = [];
    private $monthYear;
    
    /**
     * Constructor
     */
    public function __construct(
        $sourceFile = 'OSDS-January-2026.xls',
        $templateFile = 'dtr-jan-2026.xlsx',
        $outputDir = 'output'
    ) {
        $this->sourceFile = $sourceFile;
        $this->templateFile = $templateFile;
        $this->outputDir = $outputDir;
        $this->monthYear = 'January 2026';
        
        // Create output directory if it doesn't exist
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0755, true);
        }
    }
    
    /**
     * Load and parse the raw attendance log file
     */
    public function loadSourceData()
    {
        // Check for both .xls and .xlsx versions
        $sourceFile = $this->sourceFile;
        if (!file_exists($sourceFile)) {
            // Try .xlsx variant
            $sourceFileXlsx = preg_replace('/\.xls$/', '.xlsx', $sourceFile);
            if (file_exists($sourceFileXlsx)) {
                $sourceFile = $sourceFileXlsx;
            } else {
                throw new Exception("Source file not found: {$this->sourceFile} or {$sourceFileXlsx}");
            }
        }
        
        echo "Loading source data from: {$sourceFile}\n";
        
        $reader = IOFactory::createReader('Xlsx');
        // Try Xlsx format, fallback to Xls if needed
        try {
            $spreadsheet = $reader->load($sourceFile);
        } catch (Exception $e) {
            echo "Could not read as XLSX, attempting Xls format...\n";
            $reader = IOFactory::createReader('Xls');
            $spreadsheet = $reader->load($sourceFile);
        }
        
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        
        // Parse the data
        // Expected columns: A=Name, B=Date, C=Timetable, D=Clock In, E=Clock Out, F=Department
        $headers = array_shift($rows); // Skip header row
        
        foreach ($rows as $rowIndex => $row) {
            if (empty($row[0])) continue; // Skip empty rows
            
            $name = trim($row[0] ?? '');
            $date = $row[1] ?? '';
            $timetable = trim($row[2] ?? ''); // Morning or Afternoon
            $clockIn = trim($row[3] ?? '');
            $clockOut = trim($row[4] ?? '');
            $department = trim($row[5] ?? '');
            
            if (empty($name) || empty($date)) continue;
            
            // Parse date to get day of month
            $dateObj = $this->parseDate($date);
            if (!$dateObj) continue;
            $dayOfMonth = $dateObj->format('j'); // 1-31
            
            // Initialize employee array if needed
            if (!isset($this->logData[$name])) {
                $this->logData[$name] = [
                    'department' => $department,
                    'dates' => []
                ];
            }
            
            // Initialize day array if needed
            if (!isset($this->logData[$name]['dates'][$dayOfMonth])) {
                $this->logData[$name]['dates'][$dayOfMonth] = [
                    'morning_arrival' => '',
                    'morning_departure' => '',
                    'afternoon_arrival' => '',
                    'afternoon_departure' => '',
                    'remarks' => ''
                ];
            }
            
            // Map clock in/out based on timetable type
            if (stripos($timetable, 'Morning') !== false) {
                $this->logData[$name]['dates'][$dayOfMonth]['morning_arrival'] = $this->formatTime($clockIn);
                $this->logData[$name]['dates'][$dayOfMonth]['morning_departure'] = $this->formatTime($clockOut);
            } elseif (stripos($timetable, 'Afternoon') !== false) {
                $this->logData[$name]['dates'][$dayOfMonth]['afternoon_arrival'] = $this->formatTime($clockIn);
                $this->logData[$name]['dates'][$dayOfMonth]['afternoon_departure'] = $this->formatTime($clockOut);
            }
        }
        
        echo "Loaded data for " . count($this->logData) . " employees\n";
        return true;
    }
    
    /**
     * Parse various date formats to DateTime object
     */
    private function parseDate($dateStr)
    {
        if (empty($dateStr)) return null;
        
        // Handle numeric Excel dates
        if (is_numeric($dateStr)) {
            // Excel date serial number (days since 1/1/1900)
            $excelDate = intval($dateStr);
            $excelBase = new \DateTime('1899-12-30'); // Excel epoch
            $dateObj = clone $excelBase;
            $dateObj->modify("+{$excelDate} days");
            return $dateObj;
        }
        
        // Handle standard date formats
        $formats = ['m/d/Y', 'd/m/Y', 'Y-m-d', 'j/n/Y'];
        
        foreach ($formats as $format) {
            $parsed = \DateTime::createFromFormat($format, $dateStr);
            if ($parsed !== false) {
                return $parsed;
            }
        }
        
        return null;
    }
    
    /**
     * Format time string to HH:mm format (24-hour)
     */
    private function formatTime($timeStr)
    {
        if (empty($timeStr)) return '';
        
        // Handle numeric Excel time (decimal fraction of day)
        if (is_numeric($timeStr) && $timeStr < 1) {
            $hours = floor($timeStr * 24);
            $minutes = round(($timeStr * 24 - $hours) * 60);
            return sprintf('%02d:%02d', $hours, $minutes);
        }
        
        // Regular string parsing
        $timeStr = trim((string)$timeStr);
        
        // Try to parse common formats
        $time = \DateTime::createFromFormat('H:i:s', $timeStr);
        if (!$time) {
            $time = \DateTime::createFromFormat('H:i', $timeStr);
        }
        if (!$time) {
            $time = \DateTime::createFromFormat('h:i A', $timeStr);
        }
        if (!$time) {
            $time = \DateTime::createFromFormat('h:i:s A', $timeStr);
        }
        
        if (!$time) {
            // Fallback: return as-is if it looks like a time
            if (preg_match('/^\d{1,2}:\d{2}/', $timeStr)) {
                return $timeStr;
            }
            return '';
        }
        
        return $time->format('H:i');
    }
    
    /**
     * Load the DTR template
     */
    private function loadTemplate()
    {
        if (!file_exists($this->templateFile)) {
            throw new Exception("Template file not found: {$this->templateFile}");
        }
        
        $reader = IOFactory::createReader('Xlsx');
        return $reader->load($this->templateFile);
    }
    
    /**
     * Generate DTR for each employee
     */
    public function generateDTRs()
    {
        if (empty($this->logData)) {
            echo "No data to process. Load source data first.\n";
            return false;
        }
        
        echo "\nGenerating DTR files...\n";
        $count = 0;
        
        foreach ($this->logData as $employeeName => $data) {
            try {
                $this->generateSingleDTR($employeeName, $data);
                $count++;
                echo "✓ Generated DTR for: $employeeName\n";
            } catch (Exception $e) {
                echo "✗ Error generating DTR for $employeeName: " . $e->getMessage() . "\n";
            }
        }
        
        echo "\nCompleted! Generated $count DTR files in {$this->outputDir}/\n";
        return true;
    }

    /**
     * Get loaded employee count
     */
    public function getEmployeeCount()
    {
        return count($this->logData);
    }
    
    /**
     * Generate DTR for a single employee
     */
    private function generateSingleDTR($employeeName, $employeeData)
    {
        // Load template
        $template = $this->loadTemplate();
        $sheet = $template->getActiveSheet();
        
        // CLEAR ALL existing data rows completely (columns A-F)
        // Remove all template/default data - nothing should remain
        for ($day = 1; $day <= 31; $day++) {
            $row = 18 + $day; // Days start at row 19 (day 1)
            for ($col = 1; $col <= 6; $col++) { // Clear columns A through F
                $sheet->setCellValueByColumnAndRow($col, $row, '');
            }
        }
        
        // Set employee name in cell A13
        $sheet->setCellValue('A13', strtoupper($employeeName));
        
        // Populate ONLY with uploaded data - replace everything
        for ($day = 1; $day <= 31; $day++) {
            $row = 18 + $day; // Days start at row 19 (day 1)
            
            $dayData = $employeeData['dates'][$day] ?? null;
            
            if ($dayData) {
                // Set the day number in Column A (only if there's data for this day)
                $sheet->setCellValueByColumnAndRow(1, $row, $day);
                
                // Set morning arrival (Column B)
                if (!empty($dayData['morning_arrival'])) {
                    $sheet->setCellValueByColumnAndRow(2, $row, $dayData['morning_arrival']);
                }
                
                // Set morning departure (Column C)
                if (!empty($dayData['morning_departure'])) {
                    $sheet->setCellValueByColumnAndRow(3, $row, $dayData['morning_departure']);
                }
                
                // Set afternoon arrival (Column D)
                if (!empty($dayData['afternoon_arrival'])) {
                    $sheet->setCellValueByColumnAndRow(4, $row, $dayData['afternoon_arrival']);
                }
                
                // Set afternoon departure (Column E)
                if (!empty($dayData['afternoon_departure'])) {
                    $sheet->setCellValueByColumnAndRow(5, $row, $dayData['afternoon_departure']);
                }
                
                // Set remarks if any
                if (!empty($dayData['remarks'])) {
                    $sheet->setCellValueByColumnAndRow(6, $row, $dayData['remarks']);
                }
            }
        }
        
        // Save the file
        $filename = $this->sanitizeFilename("DTR_Generated_{$employeeName}.xlsx");
        $filepath = "{$this->outputDir}/{$filename}";
        
        $writer = IOFactory::createWriter($template, 'Xlsx');
        $writer->save($filepath);
        
        // Free memory
        $template->disconnectWorksheets();
        unset($template);
    }
    
    /**
     * Sanitize filename
     */
    private function sanitizeFilename($filename)
    {
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        return trim($filename, '_');
    }
    
    /**
     * Generate sample data for testing
     */
    public function generateSampleData()
    {
        echo "Generating sample OSDS data for testing...\n";
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Add headers
        $headers = ['Name', 'Date', 'Timetable', 'Clock In', 'Clock Out', 'Department'];
        $col = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col++, 1, $header);
        }
        
        // Add sample data
        $employees = [
            'BUENA G. VILLAN',
            'JOSE M. SANTOS',
            'MARIA CRUZ',
            'JUAN DELA CRUZ',
            'ANA MARIA GARCIA'
        ];
        
        $row = 2;
        $department = 'City Schools Division';
        
        foreach ($employees as $employee) {
            // Multiple entries per employee for different days and times
            for ($day = 1; $day <= 15; $day++) {
                // Morning entry
                $sheet->setCellValueByColumnAndRow(1, $row, $employee);
                $sheet->setCellValueByColumnAndRow(2, $row, "1/$day/2026");
                $sheet->setCellValueByColumnAndRow(3, $row, 'Morning');
                $sheet->setCellValueByColumnAndRow(4, $row, '08:00');
                $sheet->setCellValueByColumnAndRow(5, $row, '12:00');
                $sheet->setCellValueByColumnAndRow(6, $row, $department);
                $row++;
                
                // Afternoon entry
                $sheet->setCellValueByColumnAndRow(1, $row, $employee);
                $sheet->setCellValueByColumnAndRow(2, $row, "1/$day/2026");
                $sheet->setCellValueByColumnAndRow(3, $row, 'Afternoon');
                $sheet->setCellValueByColumnAndRow(4, $row, '13:00');
                $sheet->setCellValueByColumnAndRow(5, $row, '17:00');
                $sheet->setCellValueByColumnAndRow(6, $row, $department);
                $row++;
            }
        }
        
        // Save sample data
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $sampleFile = 'OSDS-January-2026-SAMPLE.xlsx';
        $writer->save($sampleFile);
        $spreadsheet->disconnectWorksheets();
        
        echo "Sample data saved to: $sampleFile\n";
        return true;
    }
}

/**
 * Main execution
 */
if (php_sapi_name() === 'cli') {
    try {
        $generator = new DTRGenerator();
        
        // Check if --sample flag is provided
        if (in_array('--sample', $argv)) {
            $generator->generateSampleData();
            echo "\nYou can now edit the sample file and use it as your OSDS-January-2026.xls\n\n";
        }
        
        // Load source data and generate DTRs
        if (file_exists('OSDS-January-2026.xls') || file_exists('OSDS-January-2026.xlsx')) {
            $generator->loadSourceData();
            $generator->generateDTRs();
        } else {
            echo "Source file (OSDS-January-2026.xls or .xlsx) not found.\n";
            echo "Options:\n";
            echo "1. Run with --sample flag to generate sample data:\n";
            echo "   php generate_dtr.php --sample\n";
            echo "2. Ensure OSDS-January-2026.xls exists with the following columns:\n";
            echo "   A: Name\n";
            echo "   B: Date (mm/dd/yyyy or numeric Excel date)\n";
            echo "   C: Timetable (Morning or Afternoon)\n";
            echo "   D: Clock In (HH:mm or numeric Excel time)\n";
            echo "   E: Clock Out (HH:mm or numeric Excel time)\n";
            echo "   F: Department\n";
        }
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
} else {
    // Can be used as a library
    // require_once 'generate_dtr.php';
    // $generator = new DTRGenerator();
    // $generator->loadSourceData();
    // $generator->generateDTRs();
}
?>
