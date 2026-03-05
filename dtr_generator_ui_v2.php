<?php
/**
 * DTR Generator Web Interface v2
 * Modern redesign with Tailwind CSS, dual file uploads, and output management
 */

// Start session for batch results
session_start();

// Set error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the DTR generator class
require_once __DIR__ . '/generate_dtr.php';

/**
 * Read unique employee names from uploaded/source Excel file.
 */
function extractUniqueEmployeeNames($filePath)
{
    if (!file_exists($filePath)) {
        throw new Exception("File not found: {$filePath}");
    }

    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
    $spreadsheet = $reader->load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    if (empty($rows)) {
        return [];
    }

    array_shift($rows); // Remove header row
    $names = [];

    foreach ($rows as $row) {
        $name = trim((string)($row[0] ?? ''));
        if ($name !== '') {
            $names[$name] = true;
        }
    }

    $spreadsheet->disconnectWorksheets();
    unset($spreadsheet);

    $uniqueNames = array_keys($names);
    natcasesort($uniqueNames);
    return array_values($uniqueNames);
}

/**
 * Get files from output directory with metadata
 */
function getOutputFiles($outputDir)
{
    $outputPath = __DIR__ . DIRECTORY_SEPARATOR . $outputDir;
    
    if (!is_dir($outputPath)) {
        return [];
    }
    
    $files = array_filter(scandir($outputPath), function($file) {
        return !in_array($file, ['.', '..']) && preg_match('/\.(xlsx|xls)$/i', $file);
    });
    
    $fileList = [];
    foreach ($files as $file) {
        $filePath = $outputPath . DIRECTORY_SEPARATOR . $file;
        $fileList[] = [
            'name' => $file,
            'size' => filesize($filePath),
            'modified' => filemtime($filePath),
            'path' => $filePath
        ];
    }
    
    // Sort by modification time (newest first)
    usort($fileList, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });
    
    return $fileList;
}

/**
 * Format file size for display
 */
function formatFileSize($bytes)
{
    if ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    }
    return $bytes . ' B';
}

/**
 * Detect employee schedule from timetable value
 */
function detectSchedule($timetableValue)
{
    $timetable = strtolower(trim($timetableValue));
    if (strpos($timetable, '7') !== false && strpos($timetable, '4pm') !== false) {
        return '7-4pm';
    } elseif (strpos($timetable, '8') !== false && strpos($timetable, '5pm') !== false) {
        return '8-5pm';
    }
    return 'Unknown';
}

/**
 * Extract employee data with departments and schedules
 */
function extractEmployeeDataWithDepartments($filePath)
{
    if (!file_exists($filePath)) {
        throw new Exception("File not found: {$filePath}");
    }

    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
    $spreadsheet = $reader->load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    if (empty($rows)) {
        return [];
    }

    array_shift($rows); // Remove header row
    $employees = [];

    foreach ($rows as $row) {
        $name = trim((string)($row[0] ?? ''));
        $timetable = trim((string)($row[2] ?? '')); // Column C - Timetable
        $department = trim((string)($row[5] ?? '')); // Column F - Department
        $schedule = detectSchedule($timetable);
        
        if ($name !== '') {
            // Use combination of name and schedule as unique key
            $employeeKey = $name . '|' . $schedule;
            if (!isset($employees[$employeeKey])) {
                $employees[$employeeKey] = [
                    'name' => $name,
                    'schedule' => $schedule,
                    'department' => !empty($department) ? $department : 'Not Specified',
                    'record_count' => 0,
                    'timetable' => $timetable
                ];
            }
            $employees[$employeeKey]['record_count']++;
        }
    }

    $spreadsheet->disconnectWorksheets();
    unset($spreadsheet);

    return array_values($employees);
}

/**
 * Get unique departments from employee data
 */
function extractDepartments($filePath)
{
    $employees = extractEmployeeDataWithDepartments($filePath);
    $departments = array_unique(array_column($employees, 'department'));
    sort($departments);
    return $departments;
}

/**
 * Filter employees by schedule
 */
function filterEmployeesBySchedule($employees, $schedule)
{
    if (empty($schedule) || $schedule === 'all') {
        return $employees;
    }
    return array_filter($employees, function($emp) use ($schedule) {
        return $emp['schedule'] === $schedule;
    });
}

/**
 * Get employee count by schedule
 */
function getEmployeeCountBySchedule($employees, $schedule)
{
    $filtered = filterEmployeesBySchedule($employees, $schedule);
    return count($filtered);
}

/**
 * Get already generated employees
 */
function getGeneratedEmployees($outputDir)
{
    $outputPath = __DIR__ . DIRECTORY_SEPARATOR . $outputDir;
    if (!is_dir($outputPath)) {
        return [];
    }

    $files = array_filter(scandir($outputPath), function($file) {
        return preg_match('/DTR_Generated_/i', $file) && preg_match('/\.(xlsx|xls)$/i', $file);
    });

    $generated = [];
    foreach ($files as $file) {
        preg_match('/DTR_Generated_(.+)\.(xlsx|xls)$/i', $file, $matches);
        if (!empty($matches[1])) {
            $generated[] = strtoupper(trim($matches[1]));
        }
    }

    return $generated;
}

/**
 * Detect duplicate files
 */
function detectDuplicateFiles($outputFiles)
{
    $nameMap = [];
    $duplicates = [];

    foreach ($outputFiles as $file) {
        $basename = preg_replace('/\d{8}_\d{6}/', '', $file['name']); // Remove timestamps
        if (!isset($nameMap[$basename])) {
            $nameMap[$basename] = [];
        }
        $nameMap[$basename][] = $file;
    }

    foreach ($nameMap as $basename => $files) {
        if (count($files) > 1) {
            $duplicates[$basename] = $files;
        }
    }

    return $duplicates;
}

/**
 * Get generation history
 */
function getGenerationHistory()
{
    $historyFile = __DIR__ . '/generation_history.json';
    if (!file_exists($historyFile)) {
        return [];
    }
    $history = json_decode(file_get_contents($historyFile), true);
    return is_array($history) ? array_reverse($history) : [];
}

/**
 * Log generation event
 */
function logGenerationEvent($sourceFile, $employeeCount, $outputDir, $filters = [])
{
    $historyFile = __DIR__ . '/generation_history.json';
    $history = getGenerationHistory();

    $event = [
        'timestamp' => date('Y-m-d H:i:s'),
        'source_file' => basename($sourceFile),
        'employees_count' => $employeeCount,
        'output_dir' => $outputDir,
        'filters' => $filters
    ];

    $history[] = $event;
    // Keep last 100 entries
    $history = array_slice($history, -100);
    file_put_contents($historyFile, json_encode($history, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

/**
 * Preview file content (first N rows)
 */
function previewFile($filePath, $limit = 5)
{
    try {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $preview = array_slice($rows, 0, $limit + 1);
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return $preview;
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Auto-convert .xls files to .xlsx format
 * Tries multiple conversion methods: PhpSpreadsheet, COM (Excel), LibreOffice
 */
function autoConvertXlsToXlsx($xlsFilePath, $deleteOriginal = false)
{
    if (!file_exists($xlsFilePath)) {
        throw new Exception("Source file not found: {$xlsFilePath}");
    }
    
    $extension = strtolower(pathinfo($xlsFilePath, PATHINFO_EXTENSION));
    if ($extension !== 'xls') {
        return $xlsFilePath; // Not an .xls file, return as-is
    }
    
    $xlsxPath = preg_replace('/\.xls$/i', '.xlsx', $xlsFilePath);
    
    // If .xlsx already exists, return it
    if (file_exists($xlsxPath)) {
        if ($deleteOriginal && file_exists($xlsFilePath)) {
            @unlink($xlsFilePath);
        }
        return $xlsxPath;
    }
    
    $conversionMethods = [];
    $lastError = null;
    
    // Method 1: Try PhpSpreadsheet (works for valid .xls files)
    try {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $spreadsheet = $reader->load($xlsFilePath);
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($xlsxPath);
        
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        
        if (file_exists($xlsxPath)) {
            if ($deleteOriginal) {
                @unlink($xlsFilePath);
            }
            return $xlsxPath;
        }
    } catch (Exception $e) {
        $lastError = "PhpSpreadsheet: " . $e->getMessage();
        $conversionMethods[] = $lastError;
    }
    
    // Method 2: Try COM automation with Excel (Windows only)
    if (class_exists('COM')) {
        try {
            $excel = new COM("Excel.Application");
            $excel->Visible = false;
            $excel->DisplayAlerts = false;
            
            $workbook = $excel->Workbooks->Open(realpath($xlsFilePath));
            $workbook->SaveAs(realpath(dirname($xlsFilePath)) . DIRECTORY_SEPARATOR . basename($xlsxPath), 51); // 51 = xlOpenXMLWorkbook
            $workbook->Close(false);
            $excel->Quit();
            
            unset($workbook);
            unset($excel);
            
            if (file_exists($xlsxPath)) {
                if ($deleteOriginal) {
                    @unlink($xlsFilePath);
                }
                return $xlsxPath;
            }
        } catch (Exception $e) {
            $lastError = "Excel COM: " . $e->getMessage();
            $conversionMethods[] = $lastError;
        }
    }
    
    // Method 3: Try LibreOffice command line (if installed)
    $libreOfficePaths = [
        'C:\\Program Files\\LibreOffice\\program\\soffice.exe',
        'C:\\Program Files (x86)\\LibreOffice\\program\\soffice.exe',
        '/usr/bin/soffice',
        '/usr/bin/libreoffice'
    ];
    
    foreach ($libreOfficePaths as $loPath) {
        if (file_exists($loPath)) {
            try {
                $outDir = dirname($xlsFilePath);
                $cmd = escapeshellarg($loPath) . " --headless --convert-to xlsx --outdir " . escapeshellarg($outDir) . " " . escapeshellarg($xlsFilePath) . " 2>&1";
                exec($cmd, $output, $returnCode);
                
                if ($returnCode === 0 && file_exists($xlsxPath)) {
                    if ($deleteOriginal) {
                        @unlink($xlsFilePath);
                    }
                    return $xlsxPath;
                }
            } catch (Exception $e) {
                $lastError = "LibreOffice: " . $e->getMessage();
                $conversionMethods[] = $lastError;
            }
            break;
        }
    }
    
    // All methods failed
    throw new Exception("Failed to convert .xls to .xlsx. Tried: " . implode(", ", $conversionMethods));
}

/**
 * Get all Excel files from excel-files folder (with auto-conversion)
 */
function getExcelFilesFromFolder($folderPath = 'excel-files', $autoConvert = true, $deleteOriginalXls = false)
{
    $fullPath = __DIR__ . DIRECTORY_SEPARATOR . $folderPath;
    
    if (!is_dir($fullPath)) {
        return [];
    }
    
    $files = array_filter(scandir($fullPath), function($file) {
        return !in_array($file, ['.', '..']) && preg_match('/\.(xls|xlsx)$/i', $file);
    });
    
    $fileList = [];
    foreach ($files as $file) {
        $filePath = $fullPath . DIRECTORY_SEPARATOR . $file;
        $originalFile = $file;
        $wasConverted = false;
        
        // Auto-convert .xls files to .xlsx if enabled
        if ($autoConvert && strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'xls') {
            try {
                $convertedPath = autoConvertXlsToXlsx($filePath, $deleteOriginalXls);
                if ($convertedPath !== $filePath && file_exists($convertedPath)) {
                    $filePath = $convertedPath;
                    $file = basename($convertedPath);
                    $wasConverted = true;
                }
            } catch (Exception $e) {
                // Conversion failed, will be marked as invalid below
            }
        }
        
        // Try to extract basic info from file
        $fileInfo = [
            'name' => $file,
            'original_name' => $wasConverted ? $originalFile : $file,
            'path' => $filePath,
            'relative_path' => $folderPath . DIRECTORY_SEPARATOR . $file,
            'size' => filesize($filePath),
            'modified' => filemtime($filePath),
            'readable' => is_readable($filePath),
            'employees' => 0,
            'schedules' => [],
            'departments' => [],
            'error' => null,
            'valid' => true,
            'converted' => $wasConverted
        ];
        
        // Try to scan for employee info
        try {
            // Explicitly try to use the appropriate reader based on extension
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            
            if ($extension === 'xls') {
                // Try to use Xls reader for old Excel format
                try {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                } catch (Exception $e) {
                    // Fallback to auto-detection if Xls reader not available
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
                }
            } else {
                // Use auto-detection for xlsx and other formats
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
            }
            
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            
            if (count($rows) > 1) {
                array_shift($rows); // Skip header
                
                $employees = [];
                $schedules = [];
                $departments = [];
                
                foreach ($rows as $row) {
                    $name = trim((string)($row[0] ?? ''));
                    if ($name !== '') {
                        if (!isset($employees[$name])) {
                            $employees[$name] = true;
                        }
                        
                        $timetable = trim((string)($row[2] ?? ''));
                        if ($timetable !== '') {
                            $schedule = detectSchedule($timetable);
                            if (!isset($schedules[$schedule])) {
                                $schedules[$schedule] = 0;
                            }
                            $schedules[$schedule]++;
                        }
                        
                        $dept = trim((string)($row[5] ?? ''));
                        if ($dept !== '') {
                            if (!isset($departments[$dept])) {
                                $departments[$dept] = 0;
                            }
                            $departments[$dept]++;
                        }
                    }
                }
                
                $fileInfo['employees'] = count($employees);
                $fileInfo['schedules'] = $schedules;
                $fileInfo['departments'] = array_keys($departments);
            }
            
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        } catch (Exception $e) {
            // Mark file as invalid and store error message
            $fileInfo['valid'] = false;
            $errorMsg = $e->getMessage();
            
            // Provide specific error messages based on file type and error
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            
            if ($extension === 'xls') {
                if (strpos($errorMsg, 'identify a reader') !== false || 
                    strpos($errorMsg, 'OLE') !== false || 
                    strpos($errorMsg, 'not recognised') !== false) {
                    if ($autoConvert) {
                        $fileInfo['error'] = 'Auto-conversion failed. File may be corrupted or require Excel/LibreOffice to open.';
                    } else {
                        $fileInfo['error'] = '.xls format not supported. Please convert to .xlsx format using Excel or LibreOffice.';
                    }
                } else {
                    $fileInfo['error'] = 'Error reading .xls file: ' . $errorMsg . ' (Try converting to .xlsx)';
                }
            } else {
                $fileInfo['error'] = $errorMsg;
            }
        }
        
        $fileList[] = $fileInfo;
    }
    
    // Sort by modification time (newest first)
    usort($fileList, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });
    
    return $fileList;
}

/**
 * Process batch of files and generate DTRs
 */
function processBatchFiles($filePathsArray, $templateFile, $outputDir, $scheduleFilter = 'all', &$results = [])
{
    if (empty($filePathsArray) || !is_array($filePathsArray)) {
        throw new Exception("No files selected for batch processing.");
    }
    
    ob_start();
    $results = [
        'processed' => [],
        'errors' => [],
        'total_employees' => 0,
        'start_time' => date('Y-m-d H:i:s')
    ];
    
    try {
        foreach ($filePathsArray as $sourceFile) {
            // Security check
            $sourceFile = basename($sourceFile);
            
            // Check both root and excel-files folder
            $fullPath = null;
            if (file_exists($sourceFile)) {
                $fullPath = $sourceFile;
            } elseif (file_exists('excel-files' . DIRECTORY_SEPARATOR . $sourceFile)) {
                $fullPath = 'excel-files' . DIRECTORY_SEPARATOR . $sourceFile;
            }
            
            if (!$fullPath) {
                $results['errors'][$sourceFile] = "File not found";
                continue;
            }
            
            try {
                $generator = new DTRGenerator($fullPath, $templateFile, $outputDir);
                $generator->loadSourceData();
                $employeeCount = $generator->getEmployeeCount();
                $generator->generateDTRs();
                
                $results['processed'][$sourceFile] = [
                    'status' => 'success',
                    'employees' => $employeeCount,
                    'message' => "Successfully processed $employeeCount employees"
                ];
                $results['total_employees'] += $employeeCount;
                
            } catch (Exception $e) {
                $results['errors'][$sourceFile] = $e->getMessage();
            }
        }
        
        $results['end_time'] = date('Y-m-d H:i:s');
        ob_end_clean();
        return $results;
        
    } catch (Exception $e) {
        ob_end_clean();
        throw $e;
    }
}

// Handle form submission
$message = '';
$messageType = 'info'; // info, success, error
$employeeNames = [];
$employeeDataWithSchedules = [];
$selectedEmployee = '';
$uploadedRawFile = '';
$outputDir = 'output';
$scheduleFilter = isset($_POST['filter_schedule']) ? trim($_POST['filter_schedule']) : 'all';
$defaultTemplate = 'DTR-TEMPLATE-TEST.xlsx'; // Fixed default template

// Auto-conversion settings for .xls files
$autoConvertXlsFiles = true;      // Set to true to automatically convert .xls to .xlsx
$deleteOriginalXlsAfterConversion = false; // Set to true to delete .xls files after successful conversion

// Handle file deletion
if (isset($_GET['delete']) && isset($_GET['dir'])) {
    $fileToDelete = basename($_GET['delete']);
    $dirToDelete = basename($_GET['dir']);
    $fullPath = __DIR__ . DIRECTORY_SEPARATOR . $dirToDelete . DIRECTORY_SEPARATOR . $fileToDelete;
    
    if (file_exists($fullPath) && preg_match('/\.(xlsx|xls)$/i', $fileToDelete)) {
        if (unlink($fullPath)) {
            $message = "File deleted successfully: {$fileToDelete}";
            $messageType = 'success';
        } else {
            $message = "Failed to delete file: {$fileToDelete}";
            $messageType = 'error';
        }
    } else {
        $message = "File not found or invalid file type.";
        $messageType = 'error';
    }
    
    // Redirect to clean URL
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Handle uploaded source/template file deletion
if (isset($_GET['delete_uploaded'])) {
    $fileToDelete = basename($_GET['delete_uploaded']);
    $fullPath = __DIR__ . DIRECTORY_SEPARATOR . $fileToDelete;
    
    if (file_exists($fullPath) && preg_match('/^uploaded_(raw|template)_/', $fileToDelete) && preg_match('/\.(xlsx|xls)$/i', $fileToDelete)) {
        if (unlink($fullPath)) {
            $message = "Uploaded file deleted successfully: {$fileToDelete}";
            $messageType = 'success';
        } else {
            $message = "Failed to delete uploaded file: {$fileToDelete}";
            $messageType = 'error';
        }
    } else {
        $message = "File not found or invalid file type.";
        $messageType = 'error';
    }
    
    // Redirect to clean URL
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Handle file download
if (isset($_GET['download']) && isset($_GET['dir'])) {
    $fileToDownload = basename($_GET['download']);
    $dirToDownload = basename($_GET['dir']);
    $fullPath = __DIR__ . DIRECTORY_SEPARATOR . $dirToDownload . DIRECTORY_SEPARATOR . $fileToDownload;
    
    if (file_exists($fullPath) && preg_match('/\.(xlsx|xls)$/i', $fileToDownload)) {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileToDownload . '"');
        header('Content-Length: ' . filesize($fullPath));
        readfile($fullPath);
        exit;
    } else {
        $message = "File not found.";
        $messageType = 'error';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $outputDir = $_POST['output_dir'] ?? 'output';
        $generateSample = isset($_POST['generate_sample']);
        $uploadRaw = isset($_POST['upload_raw']);
        $uploadTemplate = isset($_POST['upload_template']);
        $selectedEmployee = trim($_POST['employee_name'] ?? '');
        $batchProcess = isset($_POST['batch_process']);

        // Handle raw file upload
        if ($uploadRaw) {
            if (!isset($_FILES['raw_file']) || $_FILES['raw_file']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Please upload a valid Excel file (.xls or .xlsx).');
            }

            $originalName = $_FILES['raw_file']['name'] ?? '';
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            if (!in_array($extension, ['xls', 'xlsx'], true)) {
                throw new Exception('Invalid file type. Please upload only .xls or .xlsx files.');
            }

            $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($originalName));
            $targetFile = 'uploaded_raw_' . date('Ymd_His') . '_' . $safeName;
            $targetPath = __DIR__ . DIRECTORY_SEPARATOR . $targetFile;

            if (!move_uploaded_file($_FILES['raw_file']['tmp_name'], $targetPath)) {
                throw new Exception('Failed to save uploaded file. Please try again.');
            }

            $employeeNames = extractUniqueEmployeeNames($targetPath);
            $employeeDataWithSchedules = extractEmployeeDataWithDepartments($targetPath);
            $uploadedRawFile = $targetFile;

            if (empty($employeeNames)) {
                $message = "Raw file uploaded successfully, but no employee names were found in Column A.";
                $messageType = 'info';
            } else {
                $message = "Raw file uploaded successfully. Found " . count($employeeNames) . " unique employees.";
                $messageType = 'success';
            }
        }
        // Handle template file upload
        elseif ($uploadTemplate) {
            if (!isset($_FILES['template_file']) || $_FILES['template_file']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Please upload a valid Excel template file (.xlsx).');
            }

            $originalName = $_FILES['template_file']['name'] ?? '';
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            if (!in_array($extension, ['xls', 'xlsx'], true)) {
                throw new Exception('Invalid file type. Please upload only .xls or .xlsx files.');
            }

            $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($originalName));
            $targetFile = 'uploaded_template_' . date('Ymd_His') . '_' . $safeName;
            $targetPath = __DIR__ . DIRECTORY_SEPARATOR . $targetFile;

            if (!move_uploaded_file($_FILES['template_file']['tmp_name'], $targetPath)) {
                throw new Exception('Failed to save uploaded template file. Please try again.');
            }

            $uploadedTemplateFile = $targetFile;
            $message = "Template file uploaded successfully: {$targetFile}";
            $messageType = 'success';
        }
        // Handle sample data generation
        elseif ($generateSample) {
            $sourceFile = $_POST['source_file'] ?? 'attendance-data.xlsx';
            $templateFile = $_POST['template_file'] ?? 'dtr-jan-2026.xlsx';
            
            $generator = new DTRGenerator($sourceFile, $templateFile, $outputDir);
            ob_start(); // Suppress sample generation output
            $generator->generateSampleData();
            ob_end_clean(); // Discard output
            $message = "Sample data generated successfully! File: sample-attendance-data.xlsx";
            $messageType = 'success';
        }
        // Handle batch file processing
        elseif ($batchProcess) {
            $templateFile = $defaultTemplate;
            $selectedFiles = isset($_POST['batch_files']) ? (array)$_POST['batch_files'] : [];
            
            if (empty($selectedFiles)) {
                throw new Exception("Please select at least one file to process.");
            }
            
            // Filter out empty values
            $selectedFiles = array_filter($selectedFiles);
            
            if (empty($selectedFiles)) {
                throw new Exception("No valid files selected for batch processing.");
            }
            
            $batchResults = [];
            processBatchFiles($selectedFiles, $templateFile, $outputDir, 'all', $batchResults);
            
            $processedCount = count($batchResults['processed'] ?? []);
            $errorCount = count($batchResults['errors'] ?? []);
            $totalEmployees = $batchResults['total_employees'] ?? 0;
            
            $message = "Batch processing complete! Processed " . $processedCount . " files with " . $totalEmployees . " total employees.";
            if ($errorCount > 0) {
                $message .= " Errors: " . $errorCount;
            }
            $messageType = $errorCount === 0 ? 'success' : ($processedCount > 0 ? 'info' : 'error');
            
            // Store batch results in session for display
            $_SESSION['batch_results'] = $batchResults;
        }
        // Handle DTR generation
        else {
            $sourceFile = $_POST['source_file'] ?? 'attendance-data.xlsx';
            $templateFile = $defaultTemplate;
            $selectedDepartment = trim($_POST['filter_department'] ?? '');
            $skipGenerated = isset($_POST['skip_generated']);
            
            // Extract employee names and data
            if (!empty($sourceFile) && file_exists($sourceFile)) {
                $employeeNames = extractUniqueEmployeeNames($sourceFile);
            }

            $generator = new DTRGenerator($sourceFile, $templateFile, $outputDir);
            
            // Load and generate
            if (!file_exists($sourceFile)) {
                // Also check .xlsx variant
                $sourceFileXlsx = preg_replace('/\.xls$/', '.xlsx', $sourceFile);
                if (!file_exists($sourceFileXlsx)) {
                    throw new Exception("Source file not found. Expected: " . htmlspecialchars($sourceFile));
                }
                $sourceFile = $sourceFileXlsx;
            }
            
            // Start output buffering to suppress DTRGenerator console output
            ob_start();
            
            try {
                $generator->loadSourceData();
                
                $employeeCount = $generator->getEmployeeCount();
                
                if ($employeeCount === 0) {
                    ob_end_clean();
                    throw new Exception("No employee data found in the source file. Check file format and column headers.");
                }

                // Apply filters
                $filters = [];
                $generatedEmployees = [];
                $allEmployeeData = extractEmployeeDataWithDepartments($sourceFile);
                
                if ($skipGenerated) {
                    $generatedEmployees = getGeneratedEmployees($outputDir);
                    $filters['skip_generated'] = count($generatedEmployees);
                }
                
                if (!empty($selectedDepartment) && $selectedDepartment !== 'all') {
                    $filters['department'] = $selectedDepartment;
                }

                // Filter employees by department
                if (!empty($selectedDepartment) && $selectedDepartment !== 'all') {
                    $allEmployeeData = array_filter($allEmployeeData, function($emp) use ($selectedDepartment) {
                        return strtolower($emp['department']) === strtolower($selectedDepartment);
                    });
                }

                // Filter out already generated
                if ($skipGenerated) {
                    $allEmployeeData = array_filter($allEmployeeData, function($emp) use ($generatedEmployees) {
                        return !in_array(strtoupper($emp['name']), $generatedEmployees);
                    });
                }

                if (empty($allEmployeeData)) {
                    ob_end_clean();
                    throw new Exception("No employees match the selected filters.");
                }
                
                // Generate DTRs (with output suppressed)
                $generator->generateDTRs();
                
                // Discard all buffered output from generation process
                ob_end_clean();
                
                $generatedCount = count($allEmployeeData);
                logGenerationEvent($sourceFile, $generatedCount, $outputDir, $filters);
                
                $message = "Successfully generated DTR files for {$generatedCount} employees! Check the {$outputDir}/ folder.";
                $messageType = 'success';
            } catch (Exception $e) {
                // Make sure to clean buffer on error
                if (ob_get_level()) {
                    ob_end_clean();
                }
                throw $e;
            }
        }
        
    } catch (Exception $e) {
        $message = "Error: " . htmlspecialchars($e->getMessage());
        $messageType = 'error';
    }
}

// Get available files
$excelFiles = array_filter(scandir(__DIR__), function($file) {
    return preg_match('/\.(xls|xlsx)$/i', $file) && !preg_match('/^\./', $file);
});

// Separate source and template files
$sourceFiles = array_values(array_filter($excelFiles, function($file) {
    return preg_match('/source|log|attendance|data|uploaded_raw/i', $file);
}));

// Get Excel files from excel-files folder for batch processing
// Auto-conversion enabled via $autoConvertXlsFiles configuration
$excelFolderFiles = getExcelFilesFromFolder('excel-files', $autoConvertXlsFiles, $deleteOriginalXlsAfterConversion);

// No need for template file selection anymore - using fixed template

// Get output files
$outputFiles = getOutputFiles($outputDir);

// Preserve uploaded file references after POST
if (!empty($_POST['uploaded_raw_file'])) {
    $uploadedRawFile = $_POST['uploaded_raw_file'];
    if (file_exists($uploadedRawFile)) {
        $employeeNames = extractUniqueEmployeeNames($uploadedRawFile);
        $employeeDataWithSchedules = extractEmployeeDataWithDepartments($uploadedRawFile);
    }
}

// Dashboard calculations
$generationHistory = getGenerationHistory();
$generatedEmployees = getGeneratedEmployees($outputDir);
$totalGeneratedCount = count($generatedEmployees);
$totalPendingCount = count($employeeNames) - $totalGeneratedCount;

// Get departments if raw file is uploaded
$departments = [];
$scheduleStats = ['7-4pm' => 0, '8-5pm' => 0, 'Unknown' => 0];
if (!empty($uploadedRawFile) && file_exists($uploadedRawFile)) {
    try {
        $departments = extractDepartments($uploadedRawFile);
        // Count employees by schedule
        if (!empty($employeeDataWithSchedules)) {
            foreach ($employeeDataWithSchedules as $emp) {
                $scheduleStats[$emp['schedule']]++;
            }
        }
    } catch (Exception $e) {
        $departments = [];
    }
}

// Detect duplicate files
$duplicateFiles = detectDuplicateFiles($outputFiles);

// File search/filter
$searchQuery = isset($_GET['search']) ? strtolower($_GET['search']) : '';
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';

if (!empty($searchQuery)) {
    $outputFiles = array_filter($outputFiles, function($file) use ($searchQuery) {
        return strpos(strtolower($file['name']), $searchQuery) !== false;
    });
}

if (!empty($dateFilter)) {
    $filterDate = strtotime($dateFilter);
    $outputFiles = array_filter($outputFiles, function($file) use ($filterDate) {
        return date('Y-m-d', $file['modified']) === date('Y-m-d', $filterDate);
    });
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DTR Generator - DepEd Evaluation System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .file-dropzone {
            transition: all 0.2s ease;
        }
        .file-dropzone.dragover {
            @apply border-indigo-500 bg-indigo-50;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <header class="bg-indigo-600 rounded-lg shadow-lg p-8 mb-8 text-white">
            <h1 class="text-4xl font-bold mb-2">DTR Generator</h1>
            <p class="text-indigo-100">Department of Education Daily Time Record Automation System</p>
        </header>

        <!-- Alert Messages -->
        <?php if (!empty($message)): ?>
        <div class="mb-6 p-4 rounded-lg flex items-start <?php 
            echo $messageType === 'success' ? 'bg-teal-50 border border-teal-200 text-teal-800' : 
                 ($messageType === 'error' ? 'bg-gray-50 border border-gray-400 text-gray-800' : 
                  'bg-indigo-50 border border-indigo-200 text-indigo-800'); 
        ?>">
            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <?php if ($messageType === 'success'): ?>
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                <?php elseif ($messageType === 'error'): ?>
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                <?php else: ?>
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                <?php endif; ?>
            </svg>
            <span><?php echo $message; ?></span>
        </div>
        <?php endif; ?>

        <!-- Generation Dashboard -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Dashboard</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Total Employees Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total Employees</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo count($employeeNames); ?></p>
                        </div>
                        <svg class="w-12 h-12 text-indigo-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                    </div>
                </div>

                <!-- Generated Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-teal-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Generated</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo $totalGeneratedCount; ?></p>
                        </div>
                        <svg class="w-12 h-12 text-teal-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <!-- Pending Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-gray-400">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Pending</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo max(0, $totalPendingCount); ?></p>
                        </div>
                        <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.5a1 1 0 002 0V7zm0 7a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <!-- Output Files Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Generated Files</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo count($outputFiles); ?></p>
                        </div>
                        <svg class="w-12 h-12 text-indigo-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Duplicate Files Alert -->
        <?php if (!empty($duplicateFiles)): ?>
        <div class="mb-6 p-4 rounded-lg bg-gray-50 border border-gray-300">
            <p class="text-gray-800 font-semibold mb-2">Found Duplicate Files:</p>
            <?php foreach ($duplicateFiles as $basename => $files): ?>
            <p class="text-sm text-gray-700">
                <strong><?php echo htmlspecialchars($basename); ?></strong> - <?php echo count($files); ?> versions found
            </p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Generation History -->
        <?php if (!empty($generationHistory)): ?>
        <div class="mb-8 bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Recent Generations</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                <?php foreach (array_slice($generationHistory, 0, 5) as $event): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-700">
                            <?php echo htmlspecialchars($event['source_file']); ?> 
                            <span class="text-xs text-gray-500">(<?php echo $event['employees_count']; ?> employees)</span>
                        </p>
                        <p class="text-xs text-gray-500"><?php echo $event['timestamp']; ?></p>
                    </div>
                    <span class="bg-teal-100 text-teal-800 text-xs px-2 py-1 rounded">Success</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Employee Schedule Summary -->
        <?php if (!empty($employeeDataWithSchedules) && count($employeeDataWithSchedules) > 0): ?>
        <div class="mb-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                </svg>
                Employee Schedule Breakdown
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- 7-4pm Workers -->
                <?php 
                $sevenFourWorkers = array_filter($employeeDataWithSchedules, function($emp) { 
                    return $emp['schedule'] === '7-4pm'; 
                });
                ?>
                <div class="bg-teal-50 rounded-lg p-4 border-l-4 border-teal-600">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-800">7-4pm Workers</h3>
                        <span class="bg-teal-600 text-white text-xs font-bold px-3 py-1 rounded-full"><?php echo count($sevenFourWorkers); ?></span>
                    </div>
                    <div class="space-y-1">
                        <?php foreach (array_slice($sevenFourWorkers, 0, 5) as $emp): ?>
                        <p class="text-sm text-gray-700">
                            <span class="inline-block bg-teal-200 text-teal-800 px-2 py-0.5 rounded text-xs font-medium">7-4pm</span>
                            <?php echo htmlspecialchars($emp['name']); ?>
                        </p>
                        <?php endforeach; ?>
                        <?php if (count($sevenFourWorkers) > 5): ?>
                        <p class="text-xs text-gray-500 pt-2">... and <?php echo count($sevenFourWorkers) - 5; ?> more</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 8-5pm Workers -->
                <?php 
                $eightFiveWorkers = array_filter($employeeDataWithSchedules, function($emp) { 
                    return $emp['schedule'] === '8-5pm'; 
                });
                ?>
                <div class="bg-indigo-50 rounded-lg p-4 border-l-4 border-indigo-600">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-800">8-5pm Workers</h3>
                        <span class="bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full"><?php echo count($eightFiveWorkers); ?></span>
                    </div>
                    <div class="space-y-1">
                        <?php foreach (array_slice($eightFiveWorkers, 0, 5) as $emp): ?>
                        <p class="text-sm text-gray-700">
                            <span class="inline-block bg-indigo-200 text-indigo-800 px-2 py-0.5 rounded text-xs font-medium">8-5pm</span>
                            <?php echo htmlspecialchars($emp['name']); ?>
                        </p>
                        <?php endforeach; ?>
                        <?php if (count($eightFiveWorkers) > 5): ?>
                        <p class="text-xs text-gray-500 pt-2">... and <?php echo count($eightFiveWorkers) - 5; ?> more</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Left Column: File Uploads -->
            <div class="space-y-6">
                <!-- Raw Data Upload Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Upload Raw Data
                    </h2>
                    <p class="text-sm text-gray-600 mb-4">Upload the employee attendance logs file containing clock-in/out records from any department.</p>
                    
                    <form method="POST" enctype="multipart/form-data" id="rawUploadForm">
                        <input type="hidden" name="output_dir" value="<?php echo htmlspecialchars($outputDir); ?>">
                        
                        <div class="file-dropzone border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-indigo-400" id="rawDropzone">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">
                                <span class="font-semibold">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500 mt-1">XLS or XLSX files only</p>
                            <input type="file" name="raw_file" id="raw_file" accept=".xls,.xlsx" class="hidden">
                        </div>
                        
                        <div id="rawFilePicked" class="mt-3 text-sm text-gray-700"></div>
                        
                        <button type="submit" name="upload_raw" value="1" class="mt-4 w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                            Upload Raw Data File
                        </button>
                    </form>
                    
                    <?php if (!empty($uploadedRawFile)): ?>
                    <div class="mt-4 p-3 bg-teal-50 border border-teal-200 rounded-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm text-teal-800">
                                    <strong>Uploaded:</strong> <?php echo htmlspecialchars($uploadedRawFile); ?>
                                </p>
                                <?php if (!empty($employeeNames)): ?>
                                <p class="text-sm text-teal-700 mt-1">
                                    Found <?php echo count($employeeNames); ?> employees
                                </p>
                                <?php endif; ?>
                            </div>
                            <a href="?delete_uploaded=<?php echo urlencode($uploadedRawFile); ?>" 
                               onclick="return confirm('Are you sure you want to delete this uploaded file?');" 
                               class="ml-3 text-gray-600 hover:text-gray-800 transition-colors" 
                               title="Delete uploaded file">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- System Template Info Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        DTR Template
                    </h2>
                    <div class="p-4 bg-teal-50 border border-teal-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-teal-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Active Template:</p>
                                <p class="text-lg font-bold text-teal-700"><?php echo htmlspecialchars($defaultTemplate); ?></p>
                                <p class="text-xs text-gray-600 mt-1">This is the standard DepEd DTR template used for all generations.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Generation & Settings -->
            <div class="space-y-6">
                <!-- Generation Settings Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Generation Settings
                    </h2>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="uploaded_raw_file" value="<?php echo htmlspecialchars($uploadedRawFile); ?>">
                        
                        <div class="space-y-4">
                            <!-- Source File Selection -->
                            <div>
                                <label for="source_file" class="block text-sm font-medium text-gray-700 mb-2">
                                    Source Data File
                                </label>
                                <select name="source_file" id="source_file" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">Select source file...</option>
                                    <?php foreach ($sourceFiles as $file): ?>
                                    <option value="<?php echo htmlspecialchars($file); ?>" <?php echo (!empty($uploadedRawFile) && $file === $uploadedRawFile) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($file); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Raw attendance logs with employee clock records</p>
                            </div>

                            <!-- Employee Selection -->
                            <?php if (!empty($employeeNames)): ?>
                            <div>
                                <label for="employee_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Employee Selection (Optional)
                                </label>
                                <select name="employee_name" id="employee_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">All Employees (<?php echo count($employeeNames); ?>)</option>
                                    <?php foreach ($employeeNames as $name): ?>
                                    <option value="<?php echo htmlspecialchars($name); ?>"><?php echo htmlspecialchars($name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Leave blank to generate for all employees</p>
                            </div>

                            <!-- Schedule Filter -->
                            <?php if (!empty($employeeDataWithSchedules)): ?>
                            <div>
                                <label for="filter_schedule" class="block text-sm font-medium text-gray-700 mb-2">
                                    Filter by Schedule
                                </label>
                                <select name="filter_schedule" id="filter_schedule" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                    <option value="all">All Schedules</option>
                                    <?php if ($scheduleStats['7-4pm'] > 0): ?>
                                    <option value="7-4pm">7-4pm Workers (<?php echo $scheduleStats['7-4pm']; ?>)</option>
                                    <?php endif; ?>
                                    <?php if ($scheduleStats['8-5pm'] > 0): ?>
                                    <option value="8-5pm">8-5pm Workers (<?php echo $scheduleStats['8-5pm']; ?>)</option>
                                    <?php endif; ?>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Generate DTRs only for selected schedule</p>
                            </div>
                            <?php endif; ?>

                            <!-- Department Filter -->
                            <?php if (!empty($departments)): ?>
                            <div>
                                <label for="filter_department" class="block text-sm font-medium text-gray-700 mb-2">
                                    Filter by Department
                                </label>
                                <select name="filter_department" id="filter_department" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="all">All Departments</option>
                                    <?php foreach ($departments as $dept): ?>
                                    <option value="<?php echo htmlspecialchars($dept); ?>"><?php echo htmlspecialchars($dept); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Generate DTRs only for selected department</p>
                            </div>
                            <?php endif; ?>

                            <!-- Skip Already Generated -->
                            <div class="flex items-center">
                                <input type="checkbox" name="skip_generated" id="skip_generated" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="skip_generated" class="ml-2 block text-sm text-gray-700">
                                    Skip Already Generated Employees
                                </label>
                            </div>
                            <?php if ($totalGeneratedCount > 0): ?>
                            <p class="text-xs text-gray-500 ml-6">
                                <?php echo $totalGeneratedCount; ?> employees already have DTR files
                            </p>
                            <?php endif; ?>
                            <?php endif; ?>

                            <!-- Output Directory -->
                            <div>
                                <label for="output_dir" class="block text-sm font-medium text-gray-700 mb-2">
                                    Output Directory
                                </label>
                                <select name="output_dir" id="output_dir" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="output" selected>output</option>
                                    <option value="output_<?php echo date('Y-m-d'); ?>">output_<?php echo date('Y-m-d'); ?></option>
                                    <option value="generated_dtrs">generated_dtrs</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Folder where DTR files will be saved</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 space-y-3">
                            <button type="submit" class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 transition-all font-medium shadow-md">
                                Generate DTR Files
                            </button>

                            <!-- Batch Export Buttons for Schedules -->
                            <?php if ($scheduleStats['7-4pm'] > 0 || $scheduleStats['8-5pm'] > 0): ?>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-xs font-medium text-gray-600 mb-2 uppercase">Batch Export by Schedule</p>
                                <div class="grid grid-cols-2 gap-2">
                                    <?php if ($scheduleStats['7-4pm'] > 0): ?>
                                    <button type="submit" name="filter_schedule" value="7-4pm" class="bg-teal-600 text-white py-2 px-3 rounded-lg hover:bg-teal-700 transition-colors font-medium text-sm flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                        </svg>
                                        Download 7-4pm (<?php echo $scheduleStats['7-4pm']; ?>)
                                    </button>
                                    <?php endif; ?>
                                    <?php if ($scheduleStats['8-5pm'] > 0): ?>
                                    <button type="submit" name="filter_schedule" value="8-5pm" class="bg-teal-600 text-white py-2 px-3 rounded-lg hover:bg-teal-700 transition-colors font-medium text-sm flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                        </svg>
                                        Download 8-5pm (<?php echo $scheduleStats['8-5pm']; ?>)
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <button type="submit" name="generate_sample" value="1" class="w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                                Generate Sample Data (Testing)
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Quick Info Card -->
                <div class="bg-indigo-50 rounded-lg shadow-md p-6 border border-indigo-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Quick Guide
                    </h3>
                    <div class="text-sm text-gray-700 space-y-2">
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Upload your employee attendance logs file (.xlsx format recommended)</li>
                            <li>System automatically detects employee schedules (7-4pm or 8-5pm)</li>
                            <li>Select source file from dropdown (or use batch processing)</li>
                            <li>Choose optional filters by schedule, department, or employee</li>
                            <li>Use batch export for all employees of a specific schedule</li>
                            <li>Click "Generate DTR Files" to create individual employee DTRs</li>
                        </ol>
                        <p class="text-xs text-gray-600 mt-2 pt-2 border-t border-gray-200">
                            <strong>Template:</strong> System uses <?php echo htmlspecialchars($defaultTemplate); ?> for all generations.
                        </p>
                        <p class="text-xs text-gray-600 mt-1">
                            <strong>Note:</strong> DTR files are automatically named with the employee's schedule (e.g., DTR_7-4pm_John Smith.xlsx)
                        </p>
                        <p class="text-xs text-yellow-700 mt-1 bg-yellow-50 p-2 rounded">
                            <strong>⚠ Important:</strong> Use .xlsx format. Old .xls files must be converted first.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Batch File Processing Section -->
        <?php if (!empty($excelFolderFiles) && count($excelFolderFiles) > 0): ?>
        <div class="mb-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                Batch Process Excel Files
            </h2>
            
            <?php 
            $invalidXlsCount = 0;
            $convertedCount = 0;
            foreach ($excelFolderFiles as $file) {
                if (!$file['valid'] && strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) === 'xls') {
                    $invalidXlsCount++;
                }
                if (isset($file['converted']) && $file['converted']) {
                    $convertedCount++;
                }
            }
            
            // Show success message for auto-converted files
            if ($convertedCount > 0): 
            ?>
            <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-green-800">
                            ✓ <?php echo $convertedCount; ?> file<?php echo $convertedCount > 1 ? 's' : ''; ?> automatically converted to .xlsx format
                        </p>
                        <p class="text-xs text-green-700 mt-1">
                            Old .xls files were automatically converted and are now ready for processing.
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if ($invalidXlsCount > 0): ?>
            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-red-800">
                            ✗ <?php echo $invalidXlsCount; ?> .xls file<?php echo $invalidXlsCount > 1 ? 's' : ''; ?> could not be converted
                        </p>
                        <p class="text-xs text-red-700 mt-1">
                            Auto-conversion failed for these files. They may be corrupted or require manual conversion using Excel or LibreOffice.
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <p class="text-sm text-gray-600 mb-4">Select multiple Excel files from the excel-files folder and process them all at once.</p>
            
            <form method="POST" action="">
                <input type="hidden" name="output_dir" value="<?php echo htmlspecialchars($outputDir); ?>">
                <input type="hidden" name="batch_process" value="1">
                
                <!-- Template Info -->
                <div class="mb-4 p-3 bg-teal-50 border border-teal-200 rounded-lg">
                    <p class="text-sm text-gray-700">
                        <strong class="text-teal-800">Using Template:</strong> 
                        <span class="font-medium"><?php echo htmlspecialchars($defaultTemplate); ?></span>
                    </p>
                </div>
                
                <!-- File Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Available Excel Files (<?php echo count($excelFolderFiles); ?>)
                    </label>
                    <div class="space-y-2 max-h-72 overflow-y-auto border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <?php foreach ($excelFolderFiles as $idx => $fileInfo): ?>
                        <div class="flex items-start p-2 hover:bg-gray-100 rounded <?php echo !$fileInfo['valid'] ? 'bg-gray-100 opacity-60' : ''; ?>">
                            <input type="checkbox" 
                                   name="batch_files[]" 
                                   value="<?php echo htmlspecialchars($fileInfo['name']); ?>" 
                                   id="batch_file_<?php echo $idx; ?>" 
                                   class="mt-1 h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded"
                                   <?php echo !$fileInfo['valid'] ? 'disabled' : ''; ?>>
                            <label for="batch_file_<?php echo $idx; ?>" class="ml-3 flex-1 cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($fileInfo['name']); ?></span>
                                    <?php if (!$fileInfo['valid']): ?>
                                    <span class="bg-red-100 text-red-800 text-xs px-2 py-0.5 rounded">Invalid</span>
                                    <?php endif; ?>
                                    <?php if (isset($fileInfo['converted']) && $fileInfo['converted']): ?>
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded">✓ Auto-converted</span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($fileInfo['valid']): ?>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    <?php if (isset($fileInfo['converted']) && $fileInfo['converted']): ?>
                                    <span class="text-green-700">Converted from: <?php echo htmlspecialchars($fileInfo['original_name']); ?> | </span>
                                    <?php endif; ?>
                                    Size: <?php echo formatFileSize($fileInfo['size']); ?> | 
                                    Employees: <?php echo $fileInfo['employees']; ?>
                                    <?php if (!empty($fileInfo['schedules'])): ?>
                                    | Schedules: <?php echo implode(', ', array_keys($fileInfo['schedules'])); ?>
                                    <?php endif; ?>
                                </div>
                                <?php else: ?>
                                <div class="text-xs text-red-600 mt-0.5">
                                    ⚠ <?php echo htmlspecialchars($fileInfo['error']); ?>
                                </div>
                                <?php endif; ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Select one or more files to process</p>
                </div>
                
                <!-- Select All / Clear All -->
                <div class="mb-4 flex gap-2">
                    <button type="button" onclick="document.querySelectorAll('input[name=\"batch_files[]\"]').forEach(x => x.checked = true)" class="text-sm text-teal-600 hover:text-teal-700 font-medium">Select All</button>
                    <span class="text-gray-300">|</span>
                    <button type="button" onclick="document.querySelectorAll('input[name=\"batch_files[]\"]').forEach(x => x.checked = false)" class="text-sm text-gray-600 hover:text-gray-700 font-medium">Clear All</button>
                </div>
                
                <!-- Process Button -->
                <button type="submit" class="w-full bg-teal-600 text-white py-3 px-4 rounded-lg hover:bg-teal-700 transition-all font-medium shadow-md">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Process Selected Files
                </button>
            </form>
        </div>
        <?php endif; ?>

        <!-- Batch Processing Results -->
        <?php if (!empty($_SESSION['batch_results'])): 
            $results = $_SESSION['batch_results'];
            unset($_SESSION['batch_results']);
        ?>
        <div class="mb-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Batch Processing Results
            </h2>
            
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-teal-50 rounded-lg p-4 border-l-4 border-teal-600">
                    <p class="text-gray-600 text-sm">Files Processed</p>
                    <p class="text-3xl font-bold text-teal-600"><?php echo count($results['processed'] ?? []); ?></p>
                </div>
                <div class="bg-indigo-50 rounded-lg p-4 border-l-4 border-indigo-600">
                    <p class="text-gray-600 text-sm">Total Employees</p>
                    <p class="text-3xl font-bold text-indigo-600"><?php echo $results['total_employees'] ?? 0; ?></p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-gray-400">
                    <p class="text-gray-600 text-sm">Errors</p>
                    <p class="text-3xl font-bold text-gray-600"><?php echo count($results['errors'] ?? []); ?></p>
                </div>
            </div>
            
            <?php if (!empty($results['processed'])): ?>
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Successfully Processed</h3>
                <div class="space-y-2">
                    <?php foreach ($results['processed'] as $file => $status): ?>
                    <div class="flex items-center p-3 bg-teal-50 rounded-lg">
                        <svg class="w-5 h-5 text-teal-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800"><?php echo htmlspecialchars($file); ?></p>
                            <p class="text-xs text-gray-600"><?php echo $status['message']; ?></p>
                        </div>
                        <span class="bg-teal-100 text-teal-800 text-xs px-2 py-1 rounded"><?php echo $status['employees']; ?> employees</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($results['errors'])): ?>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Errors</h3>
                <div class="space-y-2">
                    <?php foreach ($results['errors'] as $file => $error): ?>
                    <div class="flex items-start p-3 bg-gray-50 rounded-lg border border-gray-300">
                        <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-800"><?php echo htmlspecialchars($file); ?></p>
                            <p class="text-xs text-gray-600"><?php echo htmlspecialchars($error); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Output Directory Browser -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                Output Directory: <span class="text-indigo-600 ml-2">/<?php echo htmlspecialchars($outputDir); ?></span>
            </h2>
            
            <?php if (empty($outputFiles)): ?>
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="mt-4 text-gray-500 text-lg">No DTR files generated yet</p>
                <p class="text-gray-400 text-sm mt-1">Generated files will appear here</p>
            </div>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modified</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($outputFiles as $file): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3 text-teal-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($file['name']); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo formatFileSize($file['size']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo date('M d, Y H:i', $file['modified']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="?download=<?php echo urlencode($file['name']); ?>&dir=<?php echo urlencode($outputDir); ?>" 
                                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download
                                </a>
                                <a href="?delete=<?php echo urlencode($file['name']); ?>&dir=<?php echo urlencode($outputDir); ?>" 
                                   onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars($file['name']); ?>?');"
                                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 py-3 px-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">
                    <strong>Total Files:</strong> <?php echo count($outputFiles); ?> | 
                    <strong>Total Size:</strong> <?php echo formatFileSize(array_sum(array_column($outputFiles, 'size'))); ?>
                </p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Footer -->
        <footer class="mt-8 text-center text-gray-500 text-sm">
            <p>DTR Generator v2.0 | Department of Education Evaluation System</p>
        </footer>
    </div>

    <script>
        // Drag and drop functionality for raw file upload
        const rawDropzone = document.getElementById('rawDropzone');
        const rawFileInput = document.getElementById('raw_file');
        const rawFilePicked = document.getElementById('rawFilePicked');

        if (rawDropzone && rawFileInput) {
            rawDropzone.addEventListener('click', () => rawFileInput.click());
            
            rawFileInput.addEventListener('change', () => {
                if (rawFileInput.files.length > 0) {
                    rawFilePicked.innerHTML = `<span class="font-medium text-indigo-600">Selected:</span> ${rawFileInput.files[0].name}`;
                }
            });

            ['dragenter', 'dragover'].forEach(evt => {
                rawDropzone.addEventListener(evt, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    rawDropzone.classList.add('dragover');
                });
            });

            ['dragleave', 'drop'].forEach(evt => {
                rawDropzone.addEventListener(evt, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    rawDropzone.classList.remove('dragover');
                });
            });

            rawDropzone.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    rawFileInput.files = files;
                    rawFilePicked.innerHTML = `<span class="font-medium text-indigo-600">Selected:</span> ${files[0].name}`;
                }
            });
        }
    </script>
</body>
</html>
