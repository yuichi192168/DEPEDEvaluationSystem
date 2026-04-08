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
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

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
    private $monthYearLocked = false;
    private $templateLayout = null;
    
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
            $this->monthYearLocked = true;
        } else {
            // Default to current month/year
            $this->currentMonth = (int)date('n');
            $this->currentYear = (int)date('Y');
            $this->monthYearLocked = false;
        }
    }

    /**
     * Use the first valid attendance date to infer month/year when filename is ambiguous.
     */
    private function alignMonthYearFromDate(\DateTime $dateObj)
    {
        if ($this->monthYearLocked) {
            return;
        }

        $newMonth = (int)$dateObj->format('n');
        $newYear = (int)$dateObj->format('Y');

        if ($newMonth !== $this->currentMonth || $newYear !== $this->currentYear) {
            $this->currentMonth = $newMonth;
            $this->currentYear = $newYear;
            $this->monthYear = $dateObj->format('F Y');
            $this->initializeHolidays();
        }
    }

    /**
     * Parse numeric/string time values into minutes from midnight for ordering.
     */
    private function toMinutes($timeValue)
    {
        if ($timeValue === '' || $timeValue === null) {
            return null;
        }

        if (is_numeric($timeValue)) {
            $decimal = (float)$timeValue;
            if ($decimal >= 0 && $decimal < 1) {
                return (int)round($decimal * 24 * 60);
            }
            $fraction = $decimal - floor($decimal);
            if ($fraction > 0) {
                return (int)round($fraction * 24 * 60);
            }
        }

        $formatted = $this->formatTime($timeValue);
        if ($formatted === '') {
            return null;
        }

        if (preg_match('/^(\d{2}):(\d{2})$/', $formatted, $matches)) {
            return ((int)$matches[1] * 60) + (int)$matches[2];
        }

        return null;
    }

    /**
     * Build morning/afternoon fields when source has compact daily rows without timetable labels.
     */
    private function mapCompactDayTimes($name, $dayOfMonth, $rawTimes)
    {
        $timePoints = [];

        foreach ($rawTimes as $rawTime) {
            $formatted = $this->formatTime($rawTime, $name, 'Time', $dayOfMonth);
            $minutes = $this->toMinutes($rawTime);
            if ($formatted === '' || $minutes === null) {
                continue;
            }
            $timePoints[] = ['minutes' => $minutes, 'time' => $formatted];
        }

        if (empty($timePoints)) {
            return [
                'morning_arrival' => '',
                'morning_departure' => '',
                'afternoon_arrival' => '',
                'afternoon_departure' => ''
            ];
        }

        usort($timePoints, function ($a, $b) {
            return $a['minutes'] <=> $b['minutes'];
        });

        $orderedTimes = [];
        foreach ($timePoints as $point) {
            $orderedTimes[] = $point['time'];
        }
        $orderedTimes = array_values(array_unique($orderedTimes));

        return [
            'morning_arrival' => $orderedTimes[0] ?? '',
            'morning_departure' => $orderedTimes[1] ?? '',
            'afternoon_arrival' => $orderedTimes[2] ?? '',
            'afternoon_departure' => $orderedTimes[3] ?? ''
        ];
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
        // Large attendance workbooks can take longer than the default 120s PHP limit.
        set_time_limit(0);
        ini_set('max_execution_time', '0');
        // Raise memory ceiling for big source files while still staying bounded.
        ini_set('memory_limit', '1024M');

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
                    $reader->setReadDataOnly(true);
                    $spreadsheet = $reader->load($sourceFile);
                } catch (Exception $e) {
                    // If Xls reader fails, throw helpful error
                    throw new Exception(".xls format not supported or file is corrupted. Please convert to .xlsx format: " . $e->getMessage());
                }
            } else if ($extension === 'xlsx') {
                // Use Xlsx reader for new Excel format
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                if (method_exists($reader, 'setReadEmptyCells')) {
                    $reader->setReadEmptyCells(false);
                }
                $spreadsheet = $reader->load($sourceFile);
            } else {
                // Try auto-detection for other formats
                $reader = IOFactory::createReaderForFile($sourceFile);
                if (method_exists($reader, 'setReadDataOnly')) {
                    $reader->setReadDataOnly(true);
                }
                if (method_exists($reader, 'setReadEmptyCells')) {
                    $reader->setReadEmptyCells(false);
                }
                $spreadsheet = $reader->load($sourceFile);
            }
        } catch (Exception $e) {
            throw new Exception("Error loading source file: " . $e->getMessage());
        }
        
        $sheet = $spreadsheet->getActiveSheet();
        $isHeaderRow = true;

        // Parse row-by-row to avoid materializing the entire sheet into memory.
        // Expected columns: A=Name, B=Date, C=Timetable, D=Clock In, E=Clock Out, F=Department
        foreach ($sheet->getRowIterator() as $row) {
            if ($isHeaderRow) {
                $isHeaderRow = false;
                continue;
            }

            $rowIndex = $row->getRowIndex();
            $rowValues = array_fill(0, 6, '');
            $cellIterator = $row->getCellIterator('A', 'F');
            $cellIterator->setIterateOnlyExistingCells(true);

            foreach ($cellIterator as $cell) {
                $columnIndex = Coordinate::columnIndexFromString($cell->getColumn()) - 1;
                if ($columnIndex >= 0 && $columnIndex < 6) {
                    $rowValues[$columnIndex] = $cell->getValue();
                }
            }

            if (empty($rowValues[0]) && empty($rowValues[1]) && empty($rowValues[2]) && empty($rowValues[3]) && empty($rowValues[4]) && empty($rowValues[5])) {
                continue;
            }
            
            $name = trim((string)($rowValues[0] ?? ''));
            $date = $rowValues[1] ?? '';
            $timetable = trim((string)($rowValues[2] ?? '')); // Morning or Afternoon
            $clockIn = $rowValues[3] ?? ''; // Don't trim yet - preserve original for better parsing
            $clockOut = $rowValues[4] ?? ''; // Don't trim yet - preserve original for better parsing
            $department = trim((string)($rowValues[5] ?? ''));
            
            if ($this->isHeaderLikeRow($name, $date, $timetable)) {
                continue;
            }

            if (empty($name) || empty($date)) {
                echo "Warning: Skipping row " . $rowIndex . " - missing name or date\n";
                continue;
            }
            
            // Parse date to get day of month
            $dateObj = $this->parseDate($date);
            if (!$dateObj) {
                echo "Warning: Could not parse date '" . substr((string)$date, 0, 20) . "' for $name at row " . $rowIndex . "\n";
                continue;
            }
            $this->alignMonthYearFromDate($dateObj);
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
                // Support compact daily rows with no timetable labels (common in March raw data).
                $mapped = $this->mapCompactDayTimes($name, $dayOfMonth, [$timetable, $clockIn, $clockOut]);
                foreach (['morning_arrival', 'morning_departure', 'afternoon_arrival', 'afternoon_departure'] as $field) {
                    if (!empty($mapped[$field])) {
                        $this->logData[$name]['dates'][$dayOfMonth][$field] = $mapped[$field];
                    }
                }

                if (!empty($mapped['morning_arrival'])) {
                    $this->logData[$name]['arrival_times'][] = $mapped['morning_arrival'];
                }
            }
        }

        $spreadsheet->disconnectWorksheets();
        unset($sheet, $spreadsheet);
        gc_collect_cycles();
        
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

        if ($dateStr instanceof \DateTimeInterface) {
            return \DateTime::createFromInterface($dateStr);
        }

        if (is_object($dateStr) && method_exists($dateStr, 'format')) {
            try {
                return new \DateTime($dateStr->format('Y-m-d H:i:s'));
            } catch (Exception $e) {
                // Fall through to string parsing.
            }
        }
        
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
        $cleanDateStr = $this->normalizeDateString((string)$dateStr);

        $formats = ['m/d/Y', 'd/m/Y', 'Y-m-d', 'j/n/Y', 'n/j/Y', 'd-m-Y', 'm-d-Y'];
        
        foreach ($formats as $format) {
            $parsed = \DateTime::createFromFormat($format, $cleanDateStr);
            if ($parsed !== false) {
                return $parsed;
            }
        }

        // Handle localized dates and messy strings like "2026年3月2日" or strings with extra prefixes/suffixes.
        if (preg_match('/((?:19|20)\d{2})\D{0,8}(\d{1,2})\D{0,8}(\d{1,2})/u', $cleanDateStr, $matches)) {
            try {
                return new \DateTime(sprintf('%04d-%02d-%02d', (int)$matches[1], (int)$matches[2], (int)$matches[3]));
            } catch (Exception $e) {
                // Continue to fallback parsing.
            }
        }

        if (preg_match('/(\d{1,2})\D{0,8}(\d{1,2})\D{0,8}((?:19|20)?\d{2})?/u', $cleanDateStr, $matches)) {
            $year = !empty($matches[3]) ? (int)$matches[3] : (int)$this->currentYear;
            if ($year < 100) {
                $year += 2000;
            }
            try {
                return new \DateTime(sprintf('%04d-%02d-%02d', $year, (int)$matches[1], (int)$matches[2]));
            } catch (Exception $e) {
                // Continue to null.
            }
        }
        
        return null;
    }

    /**
     * Normalize noisy date strings before parsing.
     */
    private function normalizeDateString($value)
    {
        $value = (string)$value;
        $value = preg_replace('/[\x{00A0}\x{200B}\x{200C}\x{200D}\x{FEFF}]/u', ' ', $value);
        $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value);
        $value = trim($value);
        return $value;
    }

    /**
     * Skip obvious header/title rows that leak into the data range.
     */
    private function isHeaderLikeRow($name, $date, $timetable)
    {
        $name = strtolower(trim((string)$name));
        $date = strtolower(trim((string)$date));
        $timetable = strtolower(trim((string)$timetable));

        if ($name === '' || $date === '') {
            return false;
        }

        $headerNames = ['name', 'employee name', 'employee', 'full name'];
        $headerDates = ['date', 'd/m/y', 'month/date', 'day'];
        $headerTimetables = ['timetable', 'schedule', 'time table'];

        return in_array($name, $headerNames, true) || in_array($date, $headerDates, true) || in_array($timetable, $headerTimetables, true);
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
        $reader->setReadDataOnly(false);
        if (method_exists($reader, 'setReadEmptyCells')) {
            $reader->setReadEmptyCells(false);
        }
        if (method_exists($reader, 'setIncludeCharts')) {
            $reader->setIncludeCharts(false);
        }
        return $reader->load($this->templateFile);
    }

    /**
     * Build and cache template mapping so custom monthly templates can be used
     * without changing code.
     */
    private function getTemplateLayout(Worksheet $sheet)
    {
        if (is_array($this->templateLayout) && !empty($this->templateLayout)) {
            return $this->templateLayout;
        }

        $this->templateLayout = $this->analyzeTemplateLayout($sheet);
        return $this->templateLayout;
    }

    /**
     * Analyze the uploaded template to discover key anchors and data columns.
     */
    private function analyzeTemplateLayout(Worksheet $sheet)
    {
        $layout = [
            'nameCell' => 'A13',
            'periodCell' => 'A14',
            'officialHoursCells' => ['A15'],
            'dayStartRow' => 19,
            'dayEndRow' => 49,
            'dayColumn' => 'A',
            'morningArrivalColumn' => 'B',
            'morningDepartureColumn' => 'C',
            'afternoonArrivalColumn' => 'D',
            'afternoonDepartureColumn' => 'E',
            'remarksColumn' => 'H',
            'namePlaceholderCells' => []
        ];

        $dayBlock = $this->detectTemplateDayBlock($sheet);
        if ($dayBlock) {
            $layout['dayColumn'] = $dayBlock['column'];
            $layout['dayStartRow'] = $dayBlock['startRow'];
            $layout['dayEndRow'] = $dayBlock['endRow'];

            $dayColumnIndex = Coordinate::columnIndexFromString($layout['dayColumn']);
            $layout['morningArrivalColumn'] = Coordinate::stringFromColumnIndex($dayColumnIndex + 1);
            $layout['morningDepartureColumn'] = Coordinate::stringFromColumnIndex($dayColumnIndex + 2);
            $layout['afternoonArrivalColumn'] = Coordinate::stringFromColumnIndex($dayColumnIndex + 3);
            $layout['afternoonDepartureColumn'] = Coordinate::stringFromColumnIndex($dayColumnIndex + 4);
        }

        $remarksColumn = $this->detectRemarksColumn($sheet, $layout['dayStartRow']);
        if ($remarksColumn) {
            $layout['remarksColumn'] = $remarksColumn;
        }

        $officialHoursCells = $this->detectOfficialHoursCells($sheet);
        if (!empty($officialHoursCells)) {
            $layout['officialHoursCells'] = $officialHoursCells;

            if (preg_match('/^([A-Z]+)(\d+)$/', $officialHoursCells[0], $matches)) {
                $baseColumn = $matches[1];
                $baseRow = (int)$matches[2];
                if ($baseRow > 2) {
                    $layout['periodCell'] = $baseColumn . ($baseRow - 1);
                    $layout['nameCell'] = $baseColumn . ($baseRow - 2);
                }
            }
        }

        $layout['namePlaceholderCells'] = $this->detectNamePlaceholders($sheet, $layout['dayEndRow']);

        return $layout;
    }

    /**
     * Find the 1..31 day block in the template.
     */
    private function detectTemplateDayBlock(Worksheet $sheet)
    {
        $bestBlock = null;
        $bestLength = 0;
        $maxScanRow = min(160, (int)$sheet->getHighestRow());

        for ($colIndex = 1; $colIndex <= 14; $colIndex++) {
            $column = Coordinate::stringFromColumnIndex($colIndex);

            for ($row = 1; $row <= $maxScanRow; $row++) {
                $value = $sheet->getCell($column . $row)->getCalculatedValue();
                if ($this->extractDayNumber($value) !== 1) {
                    continue;
                }

                $expected = 1;
                $cursor = $row;
                while ($cursor <= $maxScanRow) {
                    $current = $this->extractDayNumber($sheet->getCell($column . $cursor)->getCalculatedValue());
                    if ($current !== $expected) {
                        break;
                    }
                    $expected++;
                    $cursor++;
                    if ($expected > 31) {
                        break;
                    }
                }

                $length = $expected - 1;
                if ($length > $bestLength) {
                    $bestLength = $length;
                    $bestBlock = [
                        'column' => $column,
                        'startRow' => $row,
                        'endRow' => $row + $length - 1
                    ];
                }
            }
        }

        return ($bestLength >= 28) ? $bestBlock : null;
    }

    /**
     * Convert day cell values to day numbers when possible.
     */
    private function extractDayNumber($value)
    {
        if (is_numeric($value)) {
            $day = (int)$value;
            if ($day >= 1 && $day <= 31 && (float)$value == (float)$day) {
                return $day;
            }
            return null;
        }

        $stringValue = trim((string)$value);
        if (preg_match('/^(\d{1,2})$/', $stringValue, $matches)) {
            $day = (int)$matches[1];
            return ($day >= 1 && $day <= 31) ? $day : null;
        }

        return null;
    }

    /**
     * Detect the remarks column from header labels near the day header rows.
     */
    private function detectRemarksColumn(Worksheet $sheet, $dayStartRow)
    {
        $headerStart = max(1, $dayStartRow - 2);
        $headerEnd = max($headerStart, $dayStartRow);

        for ($row = $headerStart; $row <= $headerEnd; $row++) {
            for ($colIndex = 1; $colIndex <= 20; $colIndex++) {
                $column = Coordinate::stringFromColumnIndex($colIndex);
                $value = strtolower(trim((string)$sheet->getCell($column . $row)->getCalculatedValue()));
                if ($value !== '' && strpos($value, 'remark') !== false) {
                    return $column;
                }
            }
        }

        return null;
    }

    /**
     * Detect one or more official-hours cells for schedule text updates.
     */
    private function detectOfficialHoursCells(Worksheet $sheet)
    {
        $cells = [];

        for ($row = 1; $row <= 40; $row++) {
            for ($colIndex = 1; $colIndex <= 20; $colIndex++) {
                $column = Coordinate::stringFromColumnIndex($colIndex);
                $value = strtolower(trim((string)$sheet->getCell($column . $row)->getCalculatedValue()));
                if ($value !== '' && strpos($value, 'official hours for arrival and departure') !== false) {
                    $cells[] = $column . $row;
                }
            }
        }

        return $cells;
    }

    /**
     * Detect bottom signature name placeholders like "(NAME)".
     */
    private function detectNamePlaceholders(Worksheet $sheet, $dayEndRow)
    {
        $placeholders = [];
        $highestRow = min((int)$sheet->getHighestRow(), $dayEndRow + 40);

        for ($row = $dayEndRow + 1; $row <= $highestRow; $row++) {
            for ($colIndex = 1; $colIndex <= 20; $colIndex++) {
                $column = Coordinate::stringFromColumnIndex($colIndex);
                $value = strtolower(trim((string)$sheet->getCell($column . $row)->getCalculatedValue()));
                if ($value === '(name)' || $value === 'name') {
                    $placeholders[] = $column . $row;
                }
            }
        }

        return $placeholders;
    }
    
    /**
     * Generate DTR for each employee
     */
    public function generateDTRs()
    {
        // Remove the execution time limit for large batches.
        set_time_limit(0);
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '1024M');
        
        if (empty($this->logData)) {
            echo "No data to process. Load source data first.\n";
            return 0;
        }
        
        echo "\nGenerating DTR files...\n";
        
        $count = 0;
        $generationErrors = [];
        foreach ($this->logData as $employeeName => $data) {
            try {
                // Load a fresh template per employee to avoid in-memory workbook cloning spikes.
                $templateSpreadsheet = $this->loadTemplate();
                $this->generateSingleDTR($employeeName, $data, $templateSpreadsheet);
                $count++;
                echo "✓ Generated DTR for: $employeeName\n";
                
                gc_collect_cycles();
            } catch (Exception $e) {
                $errorMsg = "Error generating DTR for $employeeName: " . $e->getMessage();
                $generationErrors[] = $errorMsg;
                echo "✗ {$errorMsg}\n";
            }
        }

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
     * @param Spreadsheet $templateSpreadsheet The loaded template workbook
     */
    private function generateSingleDTR($employeeName, $employeeData, $templateSpreadsheet)
    {
        $template = $templateSpreadsheet;
        try {
            $sheet = $template->getActiveSheet();
            $layout = $this->getTemplateLayout($sheet);

            // Detect and update official hours based on schedule
            $schedule = $employeeData['schedule'] ?? '8-5';
            $officialHoursText = '';

            if ($schedule === '7-4') {
                $officialHoursText = 'Official hours for arrival and departure: 7:00 a.m. to 4:00 p.m.';
            } else {
                $officialHoursText = 'Official hours for arrival and departure: 8:00 a.m. to 5:00 p.m.';
            }

            // Update all detected official-hours anchors in the active template.
            foreach ($layout['officialHoursCells'] as $cellAddress) {
                $sheet->setCellValue($cellAddress, $officialHoursText);
            }

            // Keep the template period synced to the source month and year.
            if (!empty($layout['periodCell'])) {
                $sheet->setCellValue($layout['periodCell'], 'For ' . $this->monthYear);
            }

            // Set employee name in primary and signature placeholders.
            if (!empty($layout['nameCell'])) {
                $sheet->setCellValue($layout['nameCell'], strtoupper($employeeName));
            }
            foreach ($layout['namePlaceholderCells'] as $nameCell) {
                $sheet->setCellValue($nameCell, strtoupper($employeeName));
            }

            // Clear 31 template day rows while preserving all template styles/formatting.
            for ($offset = 0; $offset < 31; $offset++) {
                $row = $layout['dayStartRow'] + $offset;
                $sheet->setCellValue($layout['dayColumn'] . $row, '');
                $sheet->setCellValue($layout['morningArrivalColumn'] . $row, '');
                $sheet->setCellValue($layout['morningDepartureColumn'] . $row, '');
                $sheet->setCellValue($layout['afternoonArrivalColumn'] . $row, '');
                $sheet->setCellValue($layout['afternoonDepartureColumn'] . $row, '');
                $sheet->setCellValue($layout['remarksColumn'] . $row, '');
            }

            // Populate ALL days of the month (1-31), marking weekends and holidays
            $daysInMonth = (int)date('t', mktime(0, 0, 0, $this->currentMonth, 1, $this->currentYear));

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $row = $layout['dayStartRow'] + ($day - 1);

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

                $morningArrival = '';
                $morningDeparture = '';
                $afternoonArrival = '';
                $afternoonDeparture = '';

                // For weekends and holidays, leave time data blank.
                if ($isWeekend || $isHoliday) {
                    // Only write remarks for holidays, skip weekends to avoid duplication.
                    if ($isHoliday) {
                        // Keep holiday remarks from the calendar.
                    } else {
                        $remarks = '';
                    }
                } elseif ($dayData) {
                    $morningArrival = $dayData['morning_arrival'] ?? '';
                    $morningDeparture = $dayData['morning_departure'] ?? '';
                    $afternoonArrival = $dayData['afternoon_arrival'] ?? '';
                    $afternoonDeparture = $dayData['afternoon_departure'] ?? '';
                }

                $sheet->setCellValue($layout['dayColumn'] . $row, $day);
                $sheet->setCellValue($layout['morningArrivalColumn'] . $row, $morningArrival);
                $sheet->setCellValue($layout['morningDepartureColumn'] . $row, $morningDeparture);
                $sheet->setCellValue($layout['afternoonArrivalColumn'] . $row, $afternoonArrival);
                $sheet->setCellValue($layout['afternoonDepartureColumn'] . $row, $afternoonDeparture);
                $sheet->setCellValue($layout['remarksColumn'] . $row, $remarks);
            }

            // Save the file with schedule-specific filename
            $schedule = $employeeData['schedule'] ?? '8-5';
            $filename = $this->sanitizeFilename("DTR_{$schedule}_{$employeeName}.xlsx");
            $filepath = "{$this->outputDir}/{$filename}";

            $writer = IOFactory::createWriter($template, 'Xlsx');
            $writer->setPreCalculateFormulas(false);
            $writer->save($filepath);
        } finally {
            // Always release workbook memory, including when generation fails.
            $template->disconnectWorksheets();
            unset($sheet, $template);
        }
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
$isDirectCliExecution = (
    php_sapi_name() === 'cli' &&
    isset($_SERVER['SCRIPT_FILENAME']) &&
    realpath((string)$_SERVER['SCRIPT_FILENAME']) === __FILE__
);

if ($isDirectCliExecution) {
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
