<?php
/**
 * DTR Generator Web Interface
 * Provides a browser-based UI for generating DTR files
 */

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

// Handle form submission
$message = '';
$messageType = 'info'; // info, success, error
$employeeNames = [];
$selectedEmployee = '';
$outputDir = 'output';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sourceFile = $_POST['source_file'] ?? 'OSDS-January-2026.xlsx';
        $templateFile = $_POST['template_file'] ?? 'dtr-jan-2026.xlsx';
        $outputDir = $_POST['output_dir'] ?? 'output';
        $generateSample = isset($_POST['generate_sample']);
        $uploadLogs = isset($_POST['upload_logs']);
        $selectedEmployee = trim($_POST['employee_name'] ?? '');

        if ($uploadLogs) {
            if (!isset($_FILES['raw_logs_file']) || $_FILES['raw_logs_file']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Please upload a valid Excel file (.xls or .xlsx).');
            }

            $originalName = $_FILES['raw_logs_file']['name'] ?? '';
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            if (!in_array($extension, ['xls', 'xlsx'], true)) {
                throw new Exception('Invalid file type. Please upload only .xls or .xlsx files.');
            }

            $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($originalName));
            $targetFile = 'uploaded_logs_' . date('Ymd_His') . '_' . $safeName;
            $targetPath = __DIR__ . DIRECTORY_SEPARATOR . $targetFile;

            if (!move_uploaded_file($_FILES['raw_logs_file']['tmp_name'], $targetPath)) {
                throw new Exception('Failed to save uploaded file. Please try again.');
            }

            $employeeNames = extractUniqueEmployeeNames($targetPath);
            $sourceFile = $targetFile;

            if (empty($employeeNames)) {
                $message = "Upload successful ({$targetFile}), but no employee names were found in Column A.";
                $messageType = 'info';
            } else {
                // Auto-generate DTRs after successful upload
                $generator = new DTRGenerator($sourceFile, $templateFile, $outputDir);
                
                ob_start(); // Capture all output
                try {
                    $generator->loadSourceData();
                    $employeeCount = $generator->getEmployeeCount();
                    
                    if ($employeeCount === 0) {
                        ob_end_clean(); // Discard output
                        $message = "Upload successful, but no employee data found in the source file.";
                        $messageType = 'warning';
                    } else {
                        $generator->generateDTRs();
                        ob_end_clean(); // Discard all output
                        $message = "File uploaded and processed successfully! Generated DTR files for {$employeeCount} employees. Check the {$outputDir}/ folder.";
                        $messageType = 'success';
                    }
                } catch (Exception $e) {
                    ob_end_clean();
                    throw $e;
                }
            }
        } elseif ($generateSample) {
            $generator = new DTRGenerator($sourceFile, $templateFile, $outputDir);
            ob_start(); // Capture output
            $generator->generateSampleData();
            ob_end_clean(); // Discard output
            $message = "✓ Sample data generated successfully! File: OSDS-January-2026-SAMPLE.xlsx";
            $messageType = 'success';
        } else {
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
            
            ob_start(); // Capture output
            $generator->loadSourceData();
            $employeeCount = $generator->getEmployeeCount();
            
            if ($employeeCount === 0) {
                ob_end_clean(); // Discard output
                throw new Exception("No employee data found in the source file. Check file format and column headers.");
            }
            
            $generator->generateDTRs();
            ob_end_clean(); // Discard output
            $message = "✓ Successfully generated DTR files for {$employeeCount} employees! Check the {$outputDir}/ folder.";
            $messageType = 'success';
        }
        
    } catch (Exception $e) {
        $message = "✗ Error: " . htmlspecialchars($e->getMessage());
        $messageType = 'error';
    }
}

// Get available files
$excelFiles = array_filter(scandir(__DIR__), function($file) {
    return preg_match('/\.(xls|xlsx)$/i', $file) && !preg_match('/^\./', $file);
});

// Separate source and template files
$sourceFiles = array_values(array_filter($excelFiles, function($file) {
    return preg_match('/OSDS/i', $file) || preg_match('/source|log|attendance/i', $file);
}));

$templateFiles = array_values(array_filter($excelFiles, function($file) {
    return preg_match('/template|dtr|form/i', $file);
}));

// Fallback if no specific files found
if (empty($sourceFiles)) {
    $sourceFiles = array_values(array_filter($excelFiles, function($file) {
        return !preg_match('/sample|output/i', $file);
    }));
}

$outputDirs = ['output', 'output_' . date('Y-m-d'), 'generated_dtrs'];

// Get output files
$outputFiles = getOutputFiles($outputDir);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DTR Generator - DepEd Evaluation System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07), 0 10px 20px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            width: 100%;
            overflow: hidden;
        }
        
        .grid-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }
        
        @media (min-width: 768px) {
            .grid-container {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        .header {
            background: #4F46E5;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .content {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        
        select, input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        select:focus, input[type="text"]:focus {
            outline: none;
            border-color: #4F46E5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .upload-dropzone {
            border: 2px dashed #b5b5b5;
            border-radius: 8px;
            background: #fafafa;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .upload-dropzone.dragover {
            border-color: #4F46E5;
            background: #EEF2FF;
        }

        .upload-dropzone .upload-title {
            font-weight: 600;
            color: #444;
            margin-bottom: 6px;
        }

        .upload-dropzone .upload-subtitle {
            font-size: 12px;
            color: #777;
        }

        .file-picked {
            margin-top: 8px;
            font-size: 12px;
            color: #555;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #4F46E5;
        }
        
        .checkbox-group label {
            margin-bottom: 0;
            cursor: pointer;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        button {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-generate {
            background: #4F46E5;
            color: white;
        }
        
        .btn-generate:hover {
            background: #4338CA;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
        }
        
        .btn-sample {
            background: #F3F4F6;
            color: #374151;
            border: 1px solid #D1D5DB;
        }
        
        .btn-sample:hover {
            background: #E5E7EB;
            border-color: #9CA3AF;
        }
        
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .message.success {
            background: #ECFDF5;
            border-left: 4px solid #10B981;
            color: #047857;
        }
        
        .message.error {
            background: #FEF2F2;
            border-left: 4px solid #EF4444;
            color: #B91C1C;
        }
        
        .message.info {
            background: #EFF6FF;
            border-left: 4px solid #3B82F6;
            color: #1E40AF;
        }
        
        .message.warning {
            background: #FFFBEB;
            border-left: 4px solid #F59E0B;
            color: #92400E;
        }
        
        .help-text {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
            line-height: 1.4;
        }
        
        .info-box {
            background: #F9FAFB;
            border-left: 4px solid #4F46E5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #4B5563;
            line-height: 1.6;
        }
        
        .info-box strong {
            color: #333;
        }
        
        .file-list {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
            font-size: 13px;
        }
        
        .file-list h4 {
            color: #4F46E5;
            margin-bottom: 10px;
            font-size: 13px;
            font-weight: 600;
        }
        
        .file-list ul {
            list-style-position: inside;
            color: #666;
            margin-bottom: 10px;
        }
        
        .file-list li {
            margin-bottom: 5px;
        }
        
        .file-item {
            display: inline-block;
            background: white;
            padding: 5px 10px;
            border-radius: 4px;
            margin-right: 5px;
            font-family: monospace;
            font-size: 12px;
            color: #4F46E5;
            border: 1px solid #E5E7EB;
            margin-bottom: 5px;
        }
        
        .divider {
            height: 1px;
            background: #e0e0e0;
            margin: 25px 0;
        }
        
        .output-section {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #E5E7EB;
        }
        
        .output-section h3 {
            color: #1F2937;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .output-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .output-table thead {
            background: #F9FAFB;
        }
        
        .output-table th {
            padding: 12px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #6B7280;
            text-transform: uppercase;
            border-bottom: 1px solid #E5E7EB;
        }
        
        .output-table td {
            padding: 12px;
            border-bottom: 1px solid #F3F4F6;
            font-size: 14px;
            color: #374151;
        }
        
        .output-table tbody tr:hover {
            background: #F9FAFB;
        }
        
        .output-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 500;
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.2s;
            margin-right: 5px;
        }
        
        .action-btn-download {
            background: #4F46E5;
            color: white;
        }
        
        .action-btn-download:hover {
            background: #4338CA;
        }
        
        .action-btn-delete {
            background: #EF4444;
            color: white;
        }
        
        .action-btn-delete:hover {
            background: #DC2626;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #9CA3AF;
        }
        
        .empty-state svg {
            width: 64px;
            height: 64px;
            margin: 0 auto 15px;
            opacity: 0.5;
        }
        
        .file-stats {
            margin-top: 15px;
            padding: 12px;
            background: #F9FAFB;
            border-radius: 6px;
            font-size: 13px;
            color: #6B7280;
        }
        
        @media (max-width: 768px) {
            .header {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 22px;
            }
            
            .content {
                padding: 20px;
            }
            
            .button-group {
                flex-direction: column;
            }
            
            .output-table {
                font-size: 12px;
            }
            
            .output-table th,
            .output-table td {
                padding: 8px;
            }
            
            .action-btn {
                padding: 4px 8px;
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>DTR Generator</h1>
            <p>Department of Education Daily Time Record Automation</p>
        </div>
        
        <div class="content">
            <?php if (!empty($message)): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>
            
            <div class="info-box">
                <strong>How it works:</strong><br>
                Upload your attendance logs (OSDS file), select a DTR template, and generate individual DTR files for each employee with a single click.
            </div>
            
            <div class="grid-container">
                <!-- Left Column: Upload & Settings -->
                <div>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="raw_logs_file">Upload Raw Logs (Excel)</label>
                            <div class="upload-dropzone" id="uploadDropzone">
                                <div class="upload-title">Drag and drop your .xls/.xlsx file here</div>
                                <div class="upload-subtitle">or click to browse</div>
                                <input type="file" name="raw_logs_file" id="raw_logs_file" accept=".xls,.xlsx" style="display:none;">
                            </div>
                            <div id="pickedFileName" class="file-picked"></div>
                            <div class="help-text">
                                Upload first to automatically read employees from Column A (Name).
                            </div>
                            <div class="button-group" style="margin-top: 10px;">
                                <button type="submit" name="upload_logs" value="1" class="btn-sample">Upload & Read Employees</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="source_file">Source File (Raw Attendance Logs)</label>
                            <select name="source_file" id="source_file" required>
                                <option value="">-- Select OSDS File --</option>
                                <option value="OSDS-January-2026.xlsx" <?php echo ($sourceFile ?? '') === 'OSDS-January-2026.xlsx' ? 'selected' : ''; ?>>OSDS-January-2026.xlsx</option>
                                <?php foreach ($sourceFiles as $file): ?>
                                    <option value="<?php echo htmlspecialchars($file); ?>" <?php echo ($sourceFile ?? '') === $file ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($file); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="help-text">
                                Must contain columns: Name, Date, Timetable, Clock In, Clock Out, Department
                            </div>
                        </div>

                        <?php if (!empty($employeeNames)): ?>
                        <div class="form-group">
                            <label for="employee_name">Employee Selection (from uploaded/source logs)</label>
                            <select name="employee_name" id="employee_name">
                                <option value="">-- Select Employee --</option>
                                <?php foreach ($employeeNames as $employeeName): ?>
                                    <option value="<?php echo htmlspecialchars($employeeName); ?>" <?php echo $selectedEmployee === $employeeName ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($employeeName); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="help-text">
                                Found <?php echo count($employeeNames); ?> unique employees in the selected file.
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="template_file">Template File (DTR Format)</label>
                            <select name="template_file" id="template_file" required>
                                <option value="">-- Select Template --</option>
                                <option value="dtr-jan-2026.xlsx" selected>dtr-jan-2026.xlsx</option>
                                <?php foreach ($templateFiles as $file): ?>
                                    <?php if ($file !== 'dtr-jan-2026.xlsx'): ?>
                                    <option value="<?php echo htmlspecialchars($file); ?>">
                                        <?php echo htmlspecialchars($file); ?>
                                    </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <div class="help-text">
                                DepEd-formatted template with employee name at row 13, times in rows 19-49
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="output_dir">Output Directory</label>
                            <select name="output_dir" id="output_dir">
                                <option value="output">output</option>
                                <option value="output_<?php echo date('Y-m-d'); ?>">output_<?php echo date('Y-m-d'); ?></option>
                                <option value="generated_dtrs">generated_dtrs</option>
                            </select>
                            <div class="help-text">
                                Generated DTR files will be saved in this directory
                            </div>
                        </div>
                        
                        <div class="divider"></div>
                        
                        <div class="checkbox-group">
                            <input type="checkbox" name="generate_sample" id="generate_sample" value="1">
                            <label for="generate_sample">Generate Sample OSDS File (for testing)</label>
                        </div>
                        
                        <div class="button-group">
                            <button type="submit" class="btn-generate">Generate DTR Files</button>
                            <button type="submit" name="generate_sample" value="1" class="btn-sample">Generate Sample Data</button>
                        </div>
                    </form>
                </div>
                
                <!-- Right Column: File List -->
                <div>
                    <div class="file-list">
                        <h4>Available Files:</h4>
                        
                        <strong>Source Files:</strong><br>
                        <?php if (empty($sourceFiles)): ?>
                            <div style="color: #999; font-style: italic;">No source files found</div>
                        <?php else: ?>
                            <?php foreach ($sourceFiles as $file): ?>
                                <div class="file-item"><?php echo htmlspecialchars($file); ?></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <br><br>
                        
                        <strong>Template Files:</strong><br>
                        <?php if (empty($templateFiles)): ?>
                            <div style="color: #999; font-style: italic;">No template files found</div>
                        <?php else: ?>
                            <?php foreach ($templateFiles as $file): ?>
                                <div class="file-item"><?php echo htmlspecialchars($file); ?></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <br><br>
                        
                        <strong>All Available Excel Files:</strong><br>
                        <?php if (empty($excelFiles)): ?>
                            <div style="color: #999; font-style: italic;">No Excel files found</div>
                        <?php else: ?>
                            <?php foreach (array_slice($excelFiles, 0, 10) as $file): ?>
                                <div class="file-item"><?php echo htmlspecialchars($file); ?></div>
                            <?php endforeach; ?>
                            <?php if (count($excelFiles) > 10): ?>
                                <div style="color: #999; margin-top: 5px;">... and <?php echo count($excelFiles) - 10; ?> more files</div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Output Directory Browser -->
            <div class="output-section">
                <h3>
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                    </svg>
                    Output Directory: /<?php echo htmlspecialchars($outputDir); ?>
                </h3>
                
                <?php if (empty($outputFiles)): ?>
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p style="font-size: 16px; margin-bottom: 5px;">No DTR files generated yet</p>
                    <p style="font-size: 14px;">Generated files will appear here</p>
                </div>
                <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="output-table">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Size</th>
                                <th>Modified</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($outputFiles as $file): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <svg width="16" height="16" fill="#10B981" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                        </svg>
                                        <span style="font-weight: 500;"><?php echo htmlspecialchars($file['name']); ?></span>
                                    </div>
                                </td>
                                <td><?php echo formatFileSize($file['size']); ?></td>
                                <td><?php echo date('M d, Y H:i', $file['modified']); ?></td>
                                <td style="text-align: right;">
                                    <a href="?download=<?php echo urlencode($file['name']); ?>&dir=<?php echo urlencode($outputDir); ?>" 
                                       class="action-btn action-btn-download">
                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        Download
                                    </a>
                                    <a href="?delete=<?php echo urlencode($file['name']); ?>&dir=<?php echo urlencode($outputDir); ?>" 
                                       onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars($file['name']); ?>?');"
                                       class="action-btn action-btn-delete">
                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="file-stats">
                    <strong>Total Files:</strong> <?php echo count($outputFiles); ?> | 
                    <strong>Total Size:</strong> <?php echo formatFileSize(array_sum(array_column($outputFiles, 'size'))); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const dropzone = document.getElementById('uploadDropzone');
            const fileInput = document.getElementById('raw_logs_file');
            const pickedName = document.getElementById('pickedFileName');

            if (!dropzone || !fileInput) return;

            const updatePickedFile = () => {
                if (fileInput.files && fileInput.files.length > 0) {
                    pickedName.textContent = 'Selected file: ' + fileInput.files[0].name;
                } else {
                    pickedName.textContent = '';
                }
            };

            dropzone.addEventListener('click', function () {
                fileInput.click();
            });

            fileInput.addEventListener('change', updatePickedFile);

            ['dragenter', 'dragover'].forEach(function (eventName) {
                dropzone.addEventListener(eventName, function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('dragover');
                });
            });

            ['dragleave', 'drop'].forEach(function (eventName) {
                dropzone.addEventListener(eventName, function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('dragover');
                });
            });

            dropzone.addEventListener('drop', function (e) {
                const files = e.dataTransfer.files;
                if (files && files.length > 0) {
                    fileInput.files = files;
                    updatePickedFile();
                }
            });
        })();
    </script>
</body>
</html>
