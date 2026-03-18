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
    private $currentMonth;
    private $currentYear;
    private $holidays = [];
    
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
        
        // Extract month and year from filename or use defaults
        $this->extractMonthYear();
        
        // Initialize holidays for the current month/year
        $this->initializeHolidays();
        
        // Create output directory if it doesn't exist
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0755, true);
        }
    }
    
    /**
     * Extract month and year from source filename or monthYear property
     */
    private function extractMonthYear()
    {
        // Try to extract from source filename (e.g., "OSDS-January-2026.xls")
        if (preg_match('/(January|February|March|April|May|June|July|August|September|October|November|December)[-_\s]?(\d{4})/i', $this->sourceFile, $matches)) {
            $monthName = ucfirst(strtolower($matches[1]));
            $this->currentYear = (int)$matches[2];
            $this->currentMonth = date('n', strtotime($monthName));
            $this->monthYear = "$monthName {$this->currentYear}";
        } else {
            // Default to current month/year
            $this->currentMonth = (int)date('n');
            $this->currentYear = (int)date('Y');
        }
    }
    
    /**
     * Initialize holiday calendar for Philippines (DepEd)
     * Can be customized per year
     */
    private function initializeHolidays()
    {
        $year = $this->currentYear;
        
        // Philippine Public Holidays 2026 (update as needed)
        $allHolidays = [
            // Regular Holidays
            "$year-01-01" => "New Year's Day",
            "$year-04-09" => "Araw ng Kagitingan (Day of Valor)",
            "$year-05-01" => "Labor Day",
            "$year-06-12" => "Independence Day",
            "$year-08-31" => "National Heroes Day",
            "$year-11-30" => "Bonifacio Day",
            "$year-12-25" => "Christmas Day",
            "$year-12-30" => "Rizal Day",
            
            // 2026 Specific (Moveable dates)
            "$year-04-17" => "Maundy Thursday",
            "$year-04-18" => "Good Friday",
            "$year-11-01" => "All Saints' Day",
            "$year-12-24" => "Christmas Eve (Special Non-Working)",
            "$year-12-31" => "New Year's Eve (Special Non-Working)",
        ];
        
        // Filter holidays for current month
        $this->holidays = [];
        foreach ($allHolidays as $date => $name) {
            $holidayDate = strtotime($date);
            if ($holidayDate && (int)date('n', $holidayDate) === $this->currentMonth) {
                $day = (int)date('j', $holidayDate);
                $this->holidays[$day] = $name;
            }
        }
    }
    
    /**
     * Check if a given day in the current month is a weekend
     */
    private function isWeekend($dayOfMonth)
    {
        $date = mktime(0, 0, 0, $this->currentMonth, $dayOfMonth, $this->currentYear);
        $dayOfWeek = (int)date('N', $date); // 1 (Mon) to 7 (Sun)
        return $dayOfWeek >= 6; // Saturday (6) or Sunday (7)
    }
    
    /**
     * Check if a given day is a holiday
     */
    private function isHoliday($dayOfMonth)
    {
        return isset($this->holidays[$dayOfMonth]);
    }
    
    /**
     * Get holiday name for a given day
     */
    private function getHolidayName($dayOfMonth)
    {
        return $this->holidays[$dayOfMonth] ?? null;
    }
    
    /**
     * Get day name (Monday, Tuesday, etc.)
     */
    private function getDayName($dayOfMonth)
    {
        $date = mktime(0, 0, 0, $this->currentMonth, $dayOfMonth, $this->currentYear);
        return date('l', $date); // Full day name
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
        
        // Determine file extension and use appropriate reader
        $extension = strtolower(pathinfo($sourceFile, PATHINFO_EXTENSION));
        
        try {
            if ($extension === 'xls') {
                // Try Xls reader first for old Excel format
                try {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                    $spreadsheet = $reader->load($sourceFile);
                } catch (Exception $e) {
                    // If Xls reader fails, throw helpful error
                    throw new Exception(".xls format not supported or file is corrupted. Please convert to .xlsx format: " . $e->getMessage());
                }
            } else if ($extension === 'xlsx') {
                // Use Xlsx reader for new Excel format
                $reader = IOFactory::createReader('Xlsx');
                $spreadsheet = $reader->load($sourceFile);
            } else {
                // Try auto-detection for other formats
                $reader = IOFactory::createReaderForFile($sourceFile);
                $spreadsheet = $reader->load($sourceFile);
            }
        } catch (Exception $e) {
            throw new Exception("Error loading source file: " . $e->getMessage());
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
            $clockIn = $row[3] ?? ''; // Don't trim yet - preserve original for better parsing
            $clockOut = $row[4] ?? ''; // Don't trim yet - preserve original for better parsing
            $department = trim($row[5] ?? '');
            
            if (empty($name) || empty($date)) {
                echo "Warning: Skipping row " . ($rowIndex + 2) . " - missing name or date\n";
                continue;
            }
            
            // Parse date to get day of month
            $dateObj = $this->parseDate($date);
            if (!$dateObj) {
                echo "Warning: Could not parse date '" . substr((string)$date, 0, 20) . "' for $name at row " . ($rowIndex + 2) . "\n";
                continue;
            }
            $dayOfMonth = $dateObj->format('j'); // 1-31
            
            // Initialize employee array if needed
            if (!isset($this->logData[$name])) {
                $this->logData[$name] = [
                    'department' => $department,
                    'dates' => [],
                    'schedule' => null, // Will be detected later
                    'arrival_times' => [], // Fallback for schedule detection when timetable labels are missing
                    'timetable_values' => [] // Track raw timetable labels for deterministic schedule detection
                ];
            }

            // Track timetable values for schedule detection (e.g., "7-4pm Morning", "8-5pm Afternoon")
            if ($timetable !== '') {
                $this->logData[$name]['timetable_values'][] = $timetable;
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
                $morningArrival = $this->formatTime($clockIn, $name, 'Morning Arrival', $dayOfMonth);
                $morningDeparture = $this->formatTime($clockOut, $name, 'Morning Departure', $dayOfMonth);
                
                $this->logData[$name]['dates'][$dayOfMonth]['morning_arrival'] = $morningArrival;
                $this->logData[$name]['dates'][$dayOfMonth]['morning_departure'] = $morningDeparture;
                
                // Track arrival time for schedule detection
                if ($morningArrival) {
                    $this->logData[$name]['arrival_times'][] = $morningArrival;
                }
            } elseif (stripos($timetable, 'Afternoon') !== false) {
                $afternoonArrival = $this->formatTime($clockIn, $name, 'Afternoon Arrival', $dayOfMonth);
                $afternoonDeparture = $this->formatTime($clockOut, $name, 'Afternoon Departure', $dayOfMonth);
                
                $this->logData[$name]['dates'][$dayOfMonth]['afternoon_arrival'] = $afternoonArrival;
                $this->logData[$name]['dates'][$dayOfMonth]['afternoon_departure'] = $afternoonDeparture;
            } else {
                // If timetable doesn't match Morning/Afternoon, log it
                if ($timetable !== '') {
                    echo "Notice: Unknown timetable type '" . substr($timetable, 0, 30) . "' for $name on day $dayOfMonth\n";
                }
            }
        }
        
        // Detect schedules for all employees
        $this->detectSchedules();
        
        echo "Loaded data for " . count($this->logData) . " employees\n";
        return true;
    }
    
    /**
     * Detect employee schedule (7-4 or 8-5)
     * Priority: timetable labels (7-4pm/8-5pm) -> fallback to arrival times
     */
    private function detectSchedules()
    {
        foreach ($this->logData as $name => &$data) {
            $sevenFourLabelCount = 0;
            $eightFiveLabelCount = 0;

            foreach (($data['timetable_values'] ?? []) as $timetableValue) {
                $detected = $this->detectScheduleFromTimetable($timetableValue);
                if ($detected === '7-4') {
                    $sevenFourLabelCount++;
                } elseif ($detected === '8-5') {
                    $eightFiveLabelCount++;
                }
            }

            // Primary logic: timetable labels decide schedule
            if ($sevenFourLabelCount > 0 || $eightFiveLabelCount > 0) {
                $data['schedule'] = ($sevenFourLabelCount > $eightFiveLabelCount) ? '7-4' : '8-5';
                continue;
            }

            if (empty($data['arrival_times'])) {
                $data['schedule'] = '8-5'; // Default when no usable timetable/arrival data
                continue;
            }
            
            $sevenAMCount = 0;
            $eightAMCount = 0;
            
            foreach ($data['arrival_times'] as $time) {
                $hour = (int)substr($time, 0, 2);
                
                // Consider 6:00-7:59 as 7-4 schedule
                if ($hour >= 6 && $hour < 8) {
                    $sevenAMCount++;
                } 
                // Consider 8:00-9:00 as 8-5 schedule
                elseif ($hour >= 8 && $hour < 9) {
                    $eightAMCount++;
                }
            }
            
            // Determine schedule based on majority of arrivals
            if ($sevenAMCount > $eightAMCount) {
                $data['schedule'] = '7-4';
            } else {
                $data['schedule'] = '8-5';
            }
        }
        unset($data); // Break reference
    }

    /**
     * Detect schedule from timetable text (e.g., "7-4pm Morning", "8-5pm Afternoon")
     */
    private function detectScheduleFromTimetable($timetableValue)
    {
        $timetable = strtolower(trim((string)$timetableValue));

        if ($timetable === '') {
            return null;
        }

        if (strpos($timetable, '7-4pm') !== false || (strpos($timetable, '7') !== false && strpos($timetable, '4pm') !== false)) {
            return '7-4';
        }

        if (strpos($timetable, '8-5pm') !== false || (strpos($timetable, '8') !== false && strpos($timetable, '5pm') !== false)) {
            return '8-5';
        }

        return null;
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
     * Now includes better fallback handling and diagnostic logging
     */
    private function formatTime($timeStr, $employeeName = '', $fieldName = '', $day = 0)
    {
        if (empty($timeStr) || $timeStr === '' || $timeStr === null) {
            return '';
        }
        
        $originalValue = $timeStr;
        
        // Handle numeric Excel time (decimal fraction of day)
        if (is_numeric($timeStr)) {
            if ($timeStr < 1 && $timeStr >= 0) {
                $hours = floor($timeStr * 24);
                $minutes = round(($timeStr * 24 - $hours) * 60);
                return sprintf('%02d:%02d', $hours, $minutes);
            } elseif ($timeStr >= 1) {
                // Might be a full datetime serial - try to extract time portion
                $timePortion = $timeStr - floor($timeStr);
                if ($timePortion > 0) {
                    $hours = floor($timePortion * 24);
                    $minutes = round(($timePortion * 24 - $hours) * 60);
                    return sprintf('%02d:%02d', $hours, $minutes);
                }
            }
        }
        
        // Regular string parsing
        $timeStr = trim((string)$timeStr);
        
        if ($timeStr === '') {
            return '';
        }
        
        // Try to parse common formats
        $formats = [
            'H:i:s',      // 14:30:00
            'H:i',        // 14:30
            'h:i:s A',    // 02:30:00 PM
            'h:i A',      // 02:30 PM
            'h:i:s a',    // 02:30:00 pm
            'h:i a',      // 02:30 pm
            'g:i A',      // 2:30 PM
            'g:i a',      // 2:30 pm
        ];
        
        foreach ($formats as $format) {
            $time = \DateTime::createFromFormat($format, $timeStr);
            if ($time !== false) {
                return $time->format('H:i');
            }
        }
        
        // Fallback: return as-is if it looks like a time pattern
        if (preg_match('/^\d{1,2}:\d{2}(:\d{2})?/', $timeStr)) {
            // Extract just the time part (HH:MM or HH:MM:SS)
            if (preg_match('/^(\d{1,2}):(\d{2})/', $timeStr, $matches)) {
                return sprintf('%02d:%02d', (int)$matches[1], (int)$matches[2]);
            }
            return $timeStr;
        }
        
        // Last resort: if the value is non-empty and we couldn't parse it, log and preserve it
        if ($timeStr !== '') {
            $context = $employeeName ? "for $employeeName" : '';
            $context .= $fieldName ? " ($fieldName)" : '';
            $context .= $day ? " on day $day" : '';
            echo "Warning: Could not parse time '" . substr($timeStr, 0, 20) . "' $context - preserving raw value\n";
            return $timeStr; // Preserve the original value rather than losing it
        }
        
        return '';
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
        // Increase execution time limit for large batches
        set_time_limit(300); // 5 minutes
        ini_set('max_execution_time', '300');
        
        if (empty($this->logData)) {
            echo "No data to process. Load source data first.\n";
            return 0;
        }
        
        echo "\nGenerating DTR files...\n";
        
        // Load template ONCE (major performance improvement)
        try {
            $templateSpreadsheet = $this->loadTemplate();
            // Disable auto-calculation for better performance
            $templateSpreadsheet->getActiveSheet()->setAutoFilter(null);
        } catch (Exception $e) {
            echo "Error loading template: " . $e->getMessage() . "\n";
            return 0;
        }
        
        $count = 0;
        $generationErrors = [];
        foreach ($this->logData as $employeeName => $data) {
            try {
                // Pass the template to avoid reloading from disk
                $this->generateSingleDTR($employeeName, $data, $templateSpreadsheet);
                $count++;
                echo "✓ Generated DTR for: $employeeName\n";
                
                // Free memory every 5 employees
                if ($count % 5 === 0) {
                    gc_collect_cycles();
                }
            } catch (Exception $e) {
                $errorMsg = "Error generating DTR for $employeeName: " . $e->getMessage();
                $generationErrors[] = $errorMsg;
                echo "✗ {$errorMsg}\n";
            }
        }
        
        // Clean up template
        $templateSpreadsheet->disconnectWorksheets();
        unset($templateSpreadsheet);

        if ($count === 0 && !empty($generationErrors)) {
            $previewErrors = array_slice($generationErrors, 0, 3);
            throw new Exception(
                "Failed to generate DTR files for all employees. " .
                implode(' | ', $previewErrors)
            );
        }
        
        echo "\nCompleted! Generated $count DTR files in {$this->outputDir}/\n";
        return $count;
    }

    /**
     * Get employee data with schedules
     */
    public function getEmployeeData()
    {
        return $this->logData;
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
     * @param Spreadsheet $templateSpreadsheet The pre-loaded template to copy
     */
    private function generateSingleDTR($employeeName, $employeeData, $templateSpreadsheet)
    {
        // PhpSpreadsheet no longer supports cloning Spreadsheet directly.
        $template = $templateSpreadsheet->copy();
        $sheet = $template->getActiveSheet();
        
        // Detect and update official hours based on schedule
        $schedule = $employeeData['schedule'] ?? '8-5';
        $officialHoursText = '';
        
        if ($schedule === '7-4') {
            $officialHoursText = 'Official hours for arrival and departure: 7:00 a.m. to 4:00 p.m.';
        } else {
            $officialHoursText = 'Official hours for arrival and departure: 8:00 a.m. to 5:00 p.m.';
        }
        
        // Update cells A14 and I14 with official hours
        $sheet->setCellValue('A14', $officialHoursText);
        $sheet->setCellValue('I14', $officialHoursText);

        // Ensure no mixed duplicate "Official hours" line remains in nearby cells
        foreach (['A15', 'I15'] as $cellRef) {
            $currentValue = strtolower(trim((string)$sheet->getCell($cellRef)->getValue()));
            if (strpos($currentValue, 'official hours for arrival and departure:') !== false) {
                $sheet->setCellValue($cellRef, $officialHoursText);
            }
        }
        
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
        
        // Populate ALL days of the month (1-31), marking weekends and holidays
        $daysInMonth = (int)date('t', mktime(0, 0, 0, $this->currentMonth, 1, $this->currentYear));
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $row = 18 + $day; // Days start at row 19 (day 1)
            
            // Always write the day number
            $sheet->setCellValueByColumnAndRow(1, $row, $day);
            
            $dayData = $employeeData['dates'][$day] ?? null;
            $isWeekend = $this->isWeekend($day);
            $isHoliday = $this->isHoliday($day);
            
            // Determine remarks for this day
            // Only write remarks for holidays or employee-specific data
            // Skip weekends since the template already displays them
            $remarks = '';
            if ($isHoliday) {
                $remarks = $this->getHolidayName($day);
            } elseif ($dayData && !empty($dayData['remarks'])) {
                $remarks = $dayData['remarks'];
            }
            
            // For weekends and holidays, skip writing time data (leave blank)
            if ($isWeekend || $isHoliday) {
                // Leave time columns blank
                $sheet->setCellValueByColumnAndRow(2, $row, '');
                $sheet->setCellValueByColumnAndRow(3, $row, '');
                $sheet->setCellValueByColumnAndRow(4, $row, '');
                $sheet->setCellValueByColumnAndRow(5, $row, '');
                // Only write remarks for holidays, skip weekends to avoid duplication
                if ($isHoliday) {
                    $sheet->setCellValueByColumnAndRow(6, $row, $remarks);
                } else {
                    // Leave remarks blank for weekends - template already shows them
                    $sheet->setCellValueByColumnAndRow(6, $row, '');
                }
            } elseif ($dayData) {
                // Regular workday with data
                $sheet->setCellValueByColumnAndRow(2, $row, $dayData['morning_arrival'] ?? '');
                $sheet->setCellValueByColumnAndRow(3, $row, $dayData['morning_departure'] ?? '');
                $sheet->setCellValueByColumnAndRow(4, $row, $dayData['afternoon_arrival'] ?? '');
                $sheet->setCellValueByColumnAndRow(5, $row, $dayData['afternoon_departure'] ?? '');
                $sheet->setCellValueByColumnAndRow(6, $row, $remarks);
            } else {
                // Workday with no data (employee might be absent)
                $sheet->setCellValueByColumnAndRow(2, $row, '');
                $sheet->setCellValueByColumnAndRow(3, $row, '');
                $sheet->setCellValueByColumnAndRow(4, $row, '');
                $sheet->setCellValueByColumnAndRow(5, $row, '');
                $sheet->setCellValueByColumnAndRow(6, $row, $remarks);
            }
        }
        
        // Clear any remaining rows beyond the actual days in the month
        for ($day = $daysInMonth + 1; $day <= 31; $day++) {
            $row = 18 + $day;
            for ($col = 1; $col <= 6; $col++) {
                $sheet->setCellValueByColumnAndRow($col, $row, '');
            }
        }
        
        // Save the file with schedule-specific filename
        $schedule = $employeeData['schedule'] ?? '8-5';
        $filename = $this->sanitizeFilename("DTR_{$schedule}_{$employeeName}.xlsx");
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
