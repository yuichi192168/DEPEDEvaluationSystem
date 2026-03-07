<?php
/**
 * DTR Generator - User-Friendly Redesign
 * Simplified interface for non-technical users with intuitive navigation
 */

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/generate_dtr.php';

// Helper Functions - Simplified
function formatFileSize($bytes) {
    if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
    if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
    return $bytes . ' B';
}

function autoConvertXlsToXlsx($xlsFilePath, $deleteOriginal = false) {
    if (!file_exists($xlsFilePath)) {
        throw new Exception("File not found: {$xlsFilePath}");
    }
    
    $extension = strtolower(pathinfo($xlsFilePath, PATHINFO_EXTENSION));
    if ($extension !== 'xls') {
        return $xlsFilePath;
    }
    
    $xlsxPath = preg_replace('/\.xls$/i', '.xlsx', $xlsFilePath);
    if (file_exists($xlsxPath)) {
        if ($deleteOriginal && file_exists($xlsFilePath)) {
            @unlink($xlsFilePath);
        }
        return $xlsxPath;
    }
    
    // Try PhpSpreadsheet first
    try {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $spreadsheet = $reader->load($xlsFilePath);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($xlsxPath);
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        
        if (file_exists($xlsxPath)) {
            if ($deleteOriginal) @unlink($xlsFilePath);
            return $xlsxPath;
        }
    } catch (Exception $e) {
        // Fallback: try COM if available
        if (class_exists('COM')) {
            try {
                $excel = new COM("Excel.Application");
                $excel->Visible = false;
                $excel->DisplayAlerts = false;
                $workbook = $excel->Workbooks->Open(realpath($xlsFilePath));
                $workbook->SaveAs(realpath(dirname($xlsFilePath)) . DIRECTORY_SEPARATOR . basename($xlsxPath), 51);
                $workbook->Close(false);
                $excel->Quit();
                unset($excel);
                
                if (file_exists($xlsxPath)) {
                    if ($deleteOriginal) @unlink($xlsFilePath);
                    return $xlsxPath;
                }
            } catch (Exception $e) {}
        }
    }
    
    throw new Exception("Could not convert .xls file. Please convert manually using Excel or LibreOffice.");
}

function getExcelFilesFromFolder($folderPath = 'excel-files', $autoConvert = true, $deleteOriginal = false) {
    $fullPath = __DIR__ . DIRECTORY_SEPARATOR . $folderPath;
    
    if (!is_dir($fullPath)) {
        return [];
    }
    
    $files = array_filter(scandir($fullPath), function($file) {
        return !in_array($file, ['.', '..']) && preg_match('/\.xlsx$/i', $file);
    });
    
    $fileList = [];
    foreach ($files as $file) {
        $filePath = $fullPath . DIRECTORY_SEPARATOR . $file;
        
        $fileInfo = [
            'name' => $file,
            'original_name' => $file,
            'path' => $filePath,
            'size' => filesize($filePath),
            'modified' => filemtime($filePath),
            'employees' => 0,
            'converted' => false,
            'valid' => true,
            'error' => null
        ];
        
        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            
            if (count($rows) > 1) {
                array_shift($rows);
                $employees = [];
                foreach ($rows as $row) {
                    $name = trim((string)($row[0] ?? ''));
                    if ($name !== '') {
                        $employees[$name] = true;
                    }
                }
                $fileInfo['employees'] = count($employees);
            }
            
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        } catch (Exception $e) {
            $fileInfo['valid'] = false;
            $fileInfo['error'] = 'Unable to read file. It may be corrupted.';
        }
        
        $fileList[] = $fileInfo;
    }
    
    usort($fileList, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });
    
    return $fileList;
}

function getOutputFiles($outputDir) {
    $outputPath = __DIR__ . DIRECTORY_SEPARATOR . $outputDir;
    
    if (!is_dir($outputPath)) {
        return [];
    }
    
    $files = array_filter(scandir($outputPath), function($file) {
        return !in_array($file, ['.', '..']) && preg_match('/\.xlsx$/i', $file);
    });
    
    $fileList = [];
    foreach ($files as $file) {
        $filePath = $outputPath . DIRECTORY_SEPARATOR . $file;
        
        // Detect schedule from filename (DTR_7-4_Name.xlsx or DTR_8-5_Name.xlsx)
        $schedule = 'Unknown';
        if (preg_match('/^DTR_(7-4|8-5)_/', $file, $matches)) {
            $schedule = $matches[1];
        }
        
        $fileList[] = [
            'name' => $file,
            'size' => filesize($filePath),
            'modified' => filemtime($filePath),
            'path' => $filePath,
            'schedule' => $schedule
        ];
    }
    
    usort($fileList, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });
    
    return $fileList;
}

function getTemplateFilesFromFolder($folderPath = 'templates') {
    $fullPath = __DIR__ . DIRECTORY_SEPARATOR . $folderPath;

    if (!is_dir($fullPath)) {
        return [];
    }

    $files = array_filter(scandir($fullPath), function($file) {
        return !in_array($file, ['.', '..']) && preg_match('/\.xlsx$/i', $file);
    });

    $fileList = [];
    foreach ($files as $file) {
        $filePath = $fullPath . DIRECTORY_SEPARATOR . $file;
        $fileList[] = [
            'name' => $file,
            'size' => filesize($filePath),
            'modified' => filemtime($filePath),
            'path' => $filePath,
            'relative_path' => $folderPath . DIRECTORY_SEPARATOR . $file
        ];
    }

    usort($fileList, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });

    return $fileList;
}

function resolveTemplatePath($selectedTemplate, $templateFolder, $fallbackTemplate) {
    $selected = basename((string)$selectedTemplate);

    if ($selected !== '' && preg_match('/\.xlsx$/i', $selected)) {
        $templateInFolder = __DIR__ . DIRECTORY_SEPARATOR . $templateFolder . DIRECTORY_SEPARATOR . $selected;
        if (file_exists($templateInFolder)) {
            return $templateFolder . DIRECTORY_SEPARATOR . $selected;
        }
    }

    $fallbackPath = __DIR__ . DIRECTORY_SEPARATOR . $fallbackTemplate;
    if (file_exists($fallbackPath)) {
        return $fallbackTemplate;
    }

    throw new Exception('No valid template found. Upload a .xlsx template to continue.');
}

// Initialize variables
$message = '';
$messageType = 'info';
$outputDir = 'output';
$defaultTemplate = 'DTR-TEMPLATE-TEST.xlsx';
$templateFolder = 'templates';
$autoConvertXlsFiles = false;
$deleteOriginalXlsAfterConversion = false;

if (!is_dir(__DIR__ . DIRECTORY_SEPARATOR . $templateFolder)) {
    mkdir(__DIR__ . DIRECTORY_SEPARATOR . $templateFolder, 0755, true);
}

$selectedTemplate = isset($_SESSION['selected_template']) ? basename((string)$_SESSION['selected_template']) : basename($defaultTemplate);

try {
    $activeTemplatePath = resolveTemplatePath($selectedTemplate, $templateFolder, $defaultTemplate);
} catch (Exception $e) {
    $activeTemplatePath = $defaultTemplate;
    $message = $e->getMessage();
    $messageType = 'error';
}

$selectedTemplate = basename($activeTemplatePath);
$_SESSION['selected_template'] = $selectedTemplate;
$templateFiles = getTemplateFilesFromFolder($templateFolder);

// AJAX endpoint for file uploads
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_files']) && isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    
    $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'excel-files';
    
    // Create directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $uploadedCount = 0;
    $uploadErrors = [];
    
    foreach ($_FILES['excel_files']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['excel_files']['error'][$key] !== UPLOAD_ERR_OK) {
            if ($_FILES['excel_files']['error'][$key] !== UPLOAD_ERR_NO_FILE) {
                $uploadErrors[] = $_FILES['excel_files']['name'][$key] . ': Upload error code ' . $_FILES['excel_files']['error'][$key];
            }
            continue;
        }
        
        $filename = basename($_FILES['excel_files']['name'][$key]);
        $fileext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Validate file type - only .xlsx allowed
        if (!in_array($fileext, ['xlsx'])) {
            $uploadErrors[] = $filename . ': Invalid file type. Only .xlsx files are allowed.';
            continue;
        }
        
        $destination = $uploadDir . DIRECTORY_SEPARATOR . $filename;
        
        // Check if file already exists
        if (file_exists($destination)) {
            $uploadErrors[] = $filename . ': File already exists. Please rename and try again.';
            continue;
        }
        
        if (move_uploaded_file($tmp_name, $destination)) {
            $uploadedCount++;
        } else {
            $uploadErrors[] = $filename . ': Could not save file.';
        }
    }
    
    $response = [
        'success' => $uploadedCount > 0,
        'uploadedCount' => $uploadedCount,
        'errors' => $uploadErrors,
        'files' => getExcelFilesFromFolder('excel-files', false, false)
    ];
    
    echo json_encode($response);
    exit;
}

// Handle file uploads (non-AJAX fallback)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_files'])) {
    $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'excel-files';
    
    // Create directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $uploadedCount = 0;
    $uploadErrors = [];
    
    foreach ($_FILES['excel_files']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['excel_files']['error'][$key] !== UPLOAD_ERR_OK) {
            if ($_FILES['excel_files']['error'][$key] !== UPLOAD_ERR_NO_FILE) {
                $uploadErrors[] = $_FILES['excel_files']['name'][$key] . ': Upload error code ' . $_FILES['excel_files']['error'][$key];
            }
            continue;
        }
        
        $filename = basename($_FILES['excel_files']['name'][$key]);
        $fileext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Validate file type - only .xlsx allowed
        if (!in_array($fileext, ['xlsx'])) {
            $uploadErrors[] = $filename . ': Invalid file type. Only .xlsx files are allowed.';
            continue;
        }
        
        $destination = $uploadDir . DIRECTORY_SEPARATOR . $filename;
        
        // Check if file already exists
        if (file_exists($destination)) {
            $uploadErrors[] = $filename . ': File already exists. Please rename and try again.';
            continue;
        }
        
        if (move_uploaded_file($tmp_name, $destination)) {
            $uploadedCount++;
        } else {
            $uploadErrors[] = $filename . ': Could not save file.';
        }
    }
    
    if ($uploadedCount > 0) {
        $message = "Successfully uploaded $uploadedCount file(s).";
        if (count($uploadErrors) > 0) {
            $message .= " However, " . count($uploadErrors) . " file(s) had errors: " . implode("; ", $uploadErrors);
            $messageType = 'success';
        } else {
            $messageType = 'success';
        }
        
        // Refresh to show newly uploaded files
        header('Location: ' . $_SERVER['PHP_SELF'] . '?uploaded=' . $uploadedCount);
        exit;
    } elseif (count($uploadErrors) > 0) {
        $message = "Upload failed: " . implode("; ", $uploadErrors);
        $messageType = 'error';
    }
}

// Handle template upload (.xlsx only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload_template') {
    $templateUploadDir = __DIR__ . DIRECTORY_SEPARATOR . $templateFolder;

    if (!isset($_FILES['template_file']) || $_FILES['template_file']['error'] === UPLOAD_ERR_NO_FILE) {
        $message = 'Please select a template file to upload.';
        $messageType = 'error';
    } elseif ($_FILES['template_file']['error'] !== UPLOAD_ERR_OK) {
        $message = 'Template upload failed with error code ' . $_FILES['template_file']['error'] . '.';
        $messageType = 'error';
    } else {
        $originalName = basename($_FILES['template_file']['name']);
        $fileExt = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if ($fileExt !== 'xlsx') {
            $message = 'Invalid template file type. Only .xlsx files are allowed.';
            $messageType = 'error';
        } else {
            $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
            $destination = $templateUploadDir . DIRECTORY_SEPARATOR . $safeName;

            if (file_exists($destination)) {
                $message = 'Template already exists. Please rename the file and try again.';
                $messageType = 'error';
            } elseif (move_uploaded_file($_FILES['template_file']['tmp_name'], $destination)) {
                $activeTemplatePath = $templateFolder . DIRECTORY_SEPARATOR . $safeName;
                $selectedTemplate = $safeName;
                $_SESSION['selected_template'] = $safeName;
                $message = 'Template uploaded successfully and set as active template.';
                $messageType = 'success';
            } else {
                $message = 'Could not save uploaded template file.';
                $messageType = 'error';
            }
        }
    }

    $templateFiles = getTemplateFilesFromFolder($templateFolder);
}

// Show upload success message from redirect
if (isset($_GET['uploaded'])) {
    $count = intval($_GET['uploaded']);
    $message = "Successfully uploaded $count file(s). The files are now available in the list below.";
    $messageType = 'success';
}

// Get files
$excelFolderFiles = getExcelFilesFromFolder('excel-files', $autoConvertXlsFiles, $deleteOriginalXlsAfterConversion);
$outputFiles = getOutputFiles($outputDir);
$displayFiles = $excelFolderFiles;
$validFiles = array_filter($excelFolderFiles, function($f) { return $f['valid']; });
$invalidFiles = array_filter($excelFolderFiles, function($f) { return !$f['valid']; });

// Handle batch processing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        if ($_POST['action'] === 'batch_process') {
            $requestedTemplate = isset($_POST['selected_template']) ? basename((string)$_POST['selected_template']) : $selectedTemplate;
            $activeTemplatePath = resolveTemplatePath($requestedTemplate, $templateFolder, $defaultTemplate);
            $selectedTemplate = basename($activeTemplatePath);
            $_SESSION['selected_template'] = $selectedTemplate;

            $selectedFiles = isset($_POST['files']) ? array_filter((array)$_POST['files']) : [];
            
            if (empty($selectedFiles)) {
                throw new Exception('Please select at least one file to process.');
            }
            
            $batchResults = [
                'success' => 0,
                'failed' => 0,
                'total_employees' => 0,
                'files' => []
            ];
            
            ob_start();
            foreach ($selectedFiles as $file) {
                $safeFile = basename($file);
                $fullPath = 'excel-files' . DIRECTORY_SEPARATOR . $safeFile;
                
                if (!file_exists($fullPath)) continue;
                
                try {
                    $generator = new DTRGenerator($fullPath, $activeTemplatePath, $outputDir);
                    $generator->loadSourceData();
                    $empCount = $generator->getEmployeeCount();
                    $generator->generateDTRs();
                    
                    $batchResults['success']++;
                    $batchResults['total_employees'] += $empCount;
                    $batchResults['files'][$safeFile] = "✓ Processed $empCount employees";
                } catch (Exception $e) {
                    $batchResults['failed']++;
                    $batchResults['files'][$safeFile] = "✗ " . $e->getMessage();
                }
            }
            ob_end_clean();
            
            $_SESSION['batch_results'] = $batchResults;
            $_SESSION['last_batch_time'] = date('Y-m-d H:i:s');
            
            $message = "Processing complete! Successfully generated DTRs for {$batchResults['success']} file(s) with {$batchResults['total_employees']} total employees using template {$selectedTemplate}.";
            $messageType = 'success';
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
        $messageType = 'error';
    }
}

// AJAX endpoint for single file deletion
if (isset($_GET['delete']) && isset($_GET['from']) && isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    
    $fileToDelete = basename($_GET['delete']);
    $from = $_GET['from'];
    $fullPath = __DIR__ . DIRECTORY_SEPARATOR . $from . DIRECTORY_SEPARATOR . $fileToDelete;
    
    $success = file_exists($fullPath) && unlink($fullPath);
    
    $response = [
        'success' => $success,
        'message' => $success ? 'File deleted successfully.' : 'Could not delete file.',
        'files' => $from === 'output' ? getOutputFiles($outputDir) : getExcelFilesFromFolder('excel-files', $autoConvertXlsFiles, $deleteOriginalXlsAfterConversion)
    ];
    
    echo json_encode($response);
    exit;
}

// Handle file deletion (non-AJAX fallback)
if (isset($_GET['delete']) && isset($_GET['from'])) {
    $fileToDelete = basename($_GET['delete']);
    $from = $_GET['from'];
    $fullPath = __DIR__ . DIRECTORY_SEPARATOR . $from . DIRECTORY_SEPARATOR . $fileToDelete;
    
    if (file_exists($fullPath) && unlink($fullPath)) {
        $message = "File deleted successfully.";
        $messageType = 'success';
    } else {
        $message = "Could not delete file.";
        $messageType = 'error';
    }
    
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// AJAX endpoint for bulk file deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_delete']) && isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    
    $filesToDelete = isset($_POST['delete_files']) ? (array)$_POST['delete_files'] : [];
    $from = isset($_POST['delete_from']) ? $_POST['delete_from'] : '';
    
    if (empty($filesToDelete)) {
        echo json_encode([
            'success' => false,
            'message' => 'No files selected for deletion.',
            'files' => $from === 'output' ? getOutputFiles($outputDir) : getExcelFilesFromFolder('excel-files', $autoConvertXlsFiles, $deleteOriginalXlsAfterConversion)
        ]);
        exit;
    }
    
    $deletedCount = 0;
    $failedCount = 0;
    
    foreach ($filesToDelete as $file) {
        $safeFile = basename($file);
        $fullPath = __DIR__ . DIRECTORY_SEPARATOR . $from . DIRECTORY_SEPARATOR . $safeFile;
        
        if (file_exists($fullPath) && unlink($fullPath)) {
            $deletedCount++;
        } else {
            $failedCount++;
        }
    }
    
    $success = $deletedCount > 0;
    $message = $success ? "Successfully deleted $deletedCount file(s)." : "Failed to delete files.";
    if ($failedCount > 0 && $deletedCount > 0) {
        $message .= " Failed to delete $failedCount file(s).";
    }
    
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'deletedCount' => $deletedCount,
        'failedCount' => $failedCount,
        'files' => $from === 'output' ? getOutputFiles($outputDir) : getExcelFilesFromFolder('excel-files', $autoConvertXlsFiles, $deleteOriginalXlsAfterConversion)
    ]);
    exit;
}

// Handle bulk file deletion (non-AJAX fallback)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_delete'])) {
    $filesToDelete = isset($_POST['delete_files']) ? (array)$_POST['delete_files'] : [];
    $from = isset($_POST['delete_from']) ? $_POST['delete_from'] : '';
    
    if (empty($filesToDelete)) {
        $message = "No files selected for deletion.";
        $messageType = 'error';
    } else {
        $deletedCount = 0;
        $failedCount = 0;
        
        foreach ($filesToDelete as $file) {
            $safeFile = basename($file);
            $fullPath = __DIR__ . DIRECTORY_SEPARATOR . $from . DIRECTORY_SEPARATOR . $safeFile;
            
            if (file_exists($fullPath) && unlink($fullPath)) {
                $deletedCount++;
            } else {
                $failedCount++;
            }
        }
        
        if ($deletedCount > 0) {
            $message = "Successfully deleted $deletedCount file(s).";
            if ($failedCount > 0) {
                $message .= " Failed to delete $failedCount file(s).";
            }
            $messageType = 'success';
        } else {
            $message = "Failed to delete files.";
            $messageType = 'error';
        }
    }
}

// Handle file download
if (isset($_GET['download']) && isset($_GET['from'])) {
    $fileToDownload = basename($_GET['download']);
    $from = $_GET['from'];
    $fullPath = __DIR__ . DIRECTORY_SEPARATOR . $from . DIRECTORY_SEPARATOR . $fileToDownload;
    
    if (file_exists($fullPath) && preg_match('/\.xlsx$/i', $fileToDownload)) {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileToDownload . '"');
        header('Content-Length: ' . filesize($fullPath));
        readfile($fullPath);
        exit;
    }
}

// Handle batch download by schedule
if (isset($_GET['download_schedule']) && isset($_GET['from'])) {
    $schedule = $_GET['download_schedule'];
    $from = $_GET['from'];
    $outputPath = __DIR__ . DIRECTORY_SEPARATOR . $from;
    
    if (!is_dir($outputPath)) {
        die('Output directory not found');
    }
    
    // Create a temporary zip file
    $zipFilename = "DTR_{$schedule}_Batch_" . date('Y-m-d_His') . '.zip';
    $zipPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $zipFilename;
    
    $zip = new ZipArchive();
    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        $files = scandir($outputPath);
        $fileCount = 0;
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            // Check if file matches the schedule
            if (preg_match('/^DTR_' . preg_quote($schedule, '/') . '_/', $file)) {
                $filePath = $outputPath . DIRECTORY_SEPARATOR . $file;
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $file);
                    $fileCount++;
                }
            }
        }
        
        $zip->close();
        
        if ($fileCount > 0) {
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipFilename . '"');
            header('Content-Length: ' . filesize($zipPath));
            readfile($zipPath);
            unlink($zipPath);
            exit;
        } else {
            unlink($zipPath);
            $message = "No files found for schedule: {$schedule}";
            $messageType = 'error';
        }
    }
}

// Search/Filter
$searchFiles = isset($_GET['search']) ? strtolower($_GET['search']) : '';
if ($searchFiles) {
    $displayFiles = array_filter($displayFiles, function($f) use ($searchFiles) {
        return strpos(strtolower($f['name']), $searchFiles) !== false;
    });
}

$searchOutput = isset($_GET['search_output']) ? strtolower($_GET['search_output']) : '';
if ($searchOutput) {
    $outputFiles = array_filter($outputFiles, function($f) use ($searchOutput) {
        return strpos(strtolower($f['name']), $searchOutput) !== false;
    });
}

// Count files by schedule
$schedule74Count = 0;
$schedule85Count = 0;
foreach ($outputFiles as $file) {
    if ($file['schedule'] === '7-4') {
        $schedule74Count++;
    } elseif ($file['schedule'] === '8-5') {
        $schedule85Count++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once(__DIR__ . '/includes/favicon.php'); ?>
    <title>DTR Generator - DepEd Evaluation System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .wizard-step { display: none; }
        .wizard-step.active { display: block; }
        .help-tooltip { position: relative; }
        .header-logo {
            max-width: 90px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        .help-tooltip:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
            width: 200px;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation Header -->
    <nav class="bg-gradient-to-r from-indigo-600 to-indigo-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img src="images/deped_logo.svg" alt="DepEd Logo" class="header-logo" style="width: 60px; height: auto; margin: 0;">
                    <h1 class="text-2xl font-bold">DTR Generator</h1>
                </div>
                <div class="text-sm text-indigo-100">
                    Department of Education - Daily Time Record System
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Alert Messages -->
        <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-lg flex items-start gap-3 <?php 
            echo $messageType === 'success' ? 'bg-green-50 border border-green-200' : 
                 ($messageType === 'error' ? 'bg-red-50 border border-red-200' : 'bg-blue-50 border border-blue-200');
        ?>">
            <?php if ($messageType === 'success'): ?>
            <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="<?php echo $messageType === 'success' ? 'text-green-800' : ($messageType === 'error' ? 'text-red-800' : 'text-blue-800'); ?>"><?php echo $message; ?></p>
            <?php elseif ($messageType === 'error'): ?>
            <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-red-800"><?php echo $message; ?></p>
            <?php else: ?>
            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <p class="text-blue-800"><?php echo $message; ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Quick Start Guide -->
        <div class="mb-8 bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-600">
            <div class="flex items-start justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Getting Started</h2>
                <button onclick="document.getElementById('guide').classList.toggle('hidden')" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                    <span id="guide-toggle">Hide</span> Guide
                </button>
            </div>
            <div id="guide" class="grid md:grid-cols-4 gap-4">
                <div class="bg-indigo-50 p-4 rounded-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">1</div>
                        <h3 class="font-semibold text-gray-800">Upload Files</h3>
                    </div>
                    <p class="text-sm text-gray-700">Start with Excel files in the excel-files folder (.xlsx format only).</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">2</div>
                        <h3 class="font-semibold text-gray-800">Select Files</h3>
                    </div>
                    <p class="text-sm text-gray-700">Choose which files to process from the list below. Use the checkboxes to select multiple files.</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">3</div>
                        <h3 class="font-semibold text-gray-800">Generate DTRs</h3>
                    </div>
                    <p class="text-sm text-gray-700">Click "Generate DTRs" to automatically create daily time records for all selected files.</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="bg-purple-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">4</div>
                        <h3 class="font-semibold text-gray-800">Download Results</h3>
                    </div>
                    <p class="text-sm text-gray-700">Find your generated DTR files in the Results section below.</p>
                </div>
            </div>
        </div>

        <!-- Dashboard Stats -->
        <div class="mb-8 grid md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-indigo-600">
                <div class="text-sm text-gray-600 mb-1">Available Files</div>
                <div class="text-3xl font-bold text-indigo-600"><?php echo count($validFiles); ?></div>
                <div class="text-xs text-gray-500 mt-2"><?php echo count($invalidFiles) > 0 ? count($invalidFiles) . ' file(s) need attention' : 'All files ready'; ?></div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-green-600">
                <div class="text-sm text-gray-600 mb-1">Total Employees</div>
                <div class="text-3xl font-bold text-green-600"><?php echo array_sum(array_column($validFiles, 'employees')); ?></div>
                <div class="text-xs text-gray-500 mt-2">Across all files</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-blue-600">
                <div class="text-sm text-gray-600 mb-1">Generated Files</div>
                <div class="text-3xl font-bold text-blue-600"><?php echo count($outputFiles); ?></div>
                <div class="text-xs text-gray-500 mt-2">Ready to download</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-purple-600">
                <div class="text-sm text-gray-600 mb-1">DTR Template</div>
                <div class="text-lg font-bold text-purple-600"><?php echo htmlspecialchars($selectedTemplate); ?></div>
                <div class="text-xs text-gray-500 mt-2">Active template</div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Left Column: File Management -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Upload Files Section -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">0. Upload Excel Files</h2>
                    
                    <!-- File Upload Form -->
                    <form method="POST" enctype="multipart/form-data" id="upload-form" onsubmit="return handleUploadSubmit(event)">
                        <div class="border-2 border-dashed border-indigo-300 rounded-lg p-6 bg-indigo-50 hover:bg-indigo-100 transition" id="drop-zone" ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-indigo-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-gray-700 font-medium">Drag and drop Excel files here or click to select</p>
                                <p class="text-sm text-gray-600 mt-1">Supported format: .xlsx (Multiple files allowed)</p>
                                <input type="file" id="file-input" name="excel_files[]" multiple accept=".xlsx" class="hidden" onchange="handleFileSelect(event)">
                                <button type="button" onclick="document.getElementById('file-input').click()" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">Browse Files</button>
                            </div>
                        </div>
                        
                        <!-- Selected Files Preview -->
                        <div id="selected-files" class="mt-4 hidden">
                            <h3 class="font-semibold text-gray-700 mb-2">Selected Files:</h3>
                            <div id="file-list" class="space-y-2 mb-4 max-h-48 overflow-y-auto"></div>
                            <div class="flex gap-2">
                                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    Upload <span id="file-count"></span> File(s)
                                </button>
                                <button type="button" onclick="clearFileSelection()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium">Clear Selection</button>
                            </div>
                        </div>
                        
                        <div id="upload-status" class="mt-2 text-sm text-gray-600"></div>
                    </form>
                </div>

                <!-- Available Files Section -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">1. Select Files to Process</h2>
                        <span class="bg-indigo-100 text-indigo-800 text-sm px-3 py-1 rounded-full"><?php echo count($displayFiles); ?> available</span>
                    </div>

                    <!-- Search Box -->
                    <div class="mb-4">
                        <form method="GET" class="flex gap-2">
                            <input type="text" name="search" placeholder="Search files..." value="<?php echo htmlspecialchars($searchFiles); ?>" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">Search</button>
                            <?php if ($searchFiles): ?>
                            <a href="?" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Clear</a>
                            <?php endif; ?>
                        </form>
                    </div>

                    <!-- File List -->
                    <?php if (empty($displayFiles)): ?>
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-4 text-gray-700 font-medium">No files available</p>
                        <p class="text-sm text-gray-600">Upload Excel files to the <code class="bg-gray-200 px-2 py-1 rounded">excel-files</code> folder</p>
                    </div>
                    <?php else: ?>
                    <form method="POST" class="space-y-2 max-h-96 overflow-y-auto" id="process-form">
                        <input type="hidden" name="action" value="batch_process">

                        <div class="mb-4 p-3 bg-purple-50 border border-purple-200 rounded-lg">
                            <label for="selected-template" class="block text-sm font-semibold text-purple-900 mb-2">Template for Selected Month</label>
                            <select id="selected-template" name="selected_template" class="w-full px-3 py-2 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <?php if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $defaultTemplate)): ?>
                                <option value="<?php echo htmlspecialchars(basename($defaultTemplate)); ?>" <?php echo $selectedTemplate === basename($defaultTemplate) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars(basename($defaultTemplate)); ?> (Default)
                                </option>
                                <?php endif; ?>
                                <?php foreach ($templateFiles as $templateFile): ?>
                                <?php if ($templateFile['name'] === basename($defaultTemplate)) continue; ?>
                                <option value="<?php echo htmlspecialchars($templateFile['name']); ?>" <?php echo $selectedTemplate === $templateFile['name'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($templateFile['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="text-xs text-purple-700 mt-2">Upload monthly templates in the panel on the right, then select one here before generating DTRs.</p>
                        </div>
                        
                        <div class="space-y-2">
                            <?php foreach ($displayFiles as $file): ?>
                            <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="files[]" value="<?php echo htmlspecialchars($file['name']); ?>" class="mt-1 h-4 w-4 text-indigo-600 rounded process-file-checkbox" data-valid="<?php echo $file['valid'] ? '1' : '0'; ?>">
                                <div class="ml-3 flex-1">
                                    <p class="font-medium text-gray-800"><?php echo htmlspecialchars($file['name']); ?></p>
                                    <p class="text-sm text-gray-600">
                                        <?php if ($file['valid']): ?>
                                        <span><?php echo $file['employees']; ?> employees</span> | 
                                        <?php else: ?>
                                        <span class="text-red-600">Not readable</span> | 
                                        <?php endif; ?>
                                        <span><?php echo formatFileSize($file['size']); ?></span>
                                    </p>
                                    <?php if (!$file['valid']): ?>
                                    <p class="text-xs text-red-600 mt-1"><?php echo htmlspecialchars($file['error'] ?? 'Unable to read file'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </label>
                            <?php endforeach; ?>
                        </div>

                        <!-- Select All / Clear / Delete -->
                        <div class="flex gap-2 pt-4 border-t border-gray-200 flex-wrap">
                            <button type="button" onclick="toggleSelectAll('process-file-checkbox', true)" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Select All</button>
                            <span class="text-gray-300">|</span>
                            <button type="button" onclick="toggleSelectAll('process-file-checkbox', false)" class="text-sm text-gray-600 hover:text-gray-800 font-medium">Clear All</button>
                            <span class="text-gray-300">|</span>
                            <button type="button" onclick="deleteSelectedInputFiles()" class="text-sm text-red-600 hover:text-red-800 font-medium">Delete Selected</button>
                        </div>

                        <!-- Generate Button -->
                        <button type="submit" class="w-full mt-4 bg-indigo-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 font-bold text-lg shadow-lg transition-colors">
                            <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a1 1 0 001 1h12a1 1 0 001-1V6a2 2 0 00-2-2H4zm12 12H4a2 2 0 01-2-2v-4a1 1 0 00-1-1H.5a.5.5 0 00-.5.5v4a4 4 0 004 4h12a4 4 0 004-4v-4a.5.5 0 00-.5-.5H17a1 1 0 00-1 1v4a2 2 0 01-2 2z" clip-rule="evenodd"/>
                            </svg>
                            Generate DTRs
                        </button>
                    </form>
                    
                    <!-- Hidden form for bulk delete input files -->
                    <form method="POST" id="delete-input-form" class="hidden">
                        <input type="hidden" name="bulk_delete" value="1">
                        <input type="hidden" name="delete_from" value="excel-files">
                        <div id="delete-input-files-container"></div>
                    </form>
                    <?php endif; ?>
                </div>

                <!-- Warnings Section -->
                <?php if (count($invalidFiles) > 0): ?>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-yellow-800">Files Need Attention</h3>
                            <p class="text-sm text-yellow-700 mt-1"><?php echo count($invalidFiles); ?> file(s) could not be read. These files may be corrupted, in an unsupported format, or not .xlsx files.</p>
                            <details class="mt-3">
                                <summary class="cursor-pointer text-sm text-yellow-700 font-medium">Show Details</summary>
                                <ul class="mt-2 space-y-1 text-sm text-yellow-700">
                                    <?php foreach ($invalidFiles as $file): ?>
                                    <li>• <?php echo htmlspecialchars($file['name']); ?> - <?php echo htmlspecialchars($file['error'] ?? 'Unknown error'); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </details>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Info & Results Summary -->
            <div class="space-y-6">
                <!-- Help Section -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="font-bold text-blue-900 mb-3">Help & Information</h3>
                    <ul class="space-y-2 text-sm text-blue-800">
                        <li class="flex gap-2">
                            <span class="font-bold text-blue-600">•</span>
                            <span>Only .xlsx Excel files are supported</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="font-bold text-blue-600">•</span>
                            <span>Select multiple files using the checkboxes</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="font-bold text-blue-600">•</span>
                            <span>Processing takes a few seconds per file</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="font-bold text-blue-600">•</span>
                            <span>Download results from the Generated Files section</span>
                        </li>
                    </ul>
                </div>

                <!-- Recent Results -->
                <?php if (!empty($_SESSION['batch_results']) && isset($_SESSION['batch_results']['success'])): ?>
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <h3 class="font-bold text-green-900 mb-3">Latest Result</h3>
                    <div class="space-y-2 text-sm">
                        <p class="text-green-800"><strong>Time:</strong> <?php echo isset($_SESSION['last_batch_time']) ? $_SESSION['last_batch_time'] : 'N/A'; ?></p>
                        <p class="text-green-800"><strong>Files:</strong> <?php echo $_SESSION['batch_results']['success']; ?> successful</p>
                        <p class="text-green-800"><strong>Employees:</strong> <?php echo isset($_SESSION['batch_results']['total_employees']) ? $_SESSION['batch_results']['total_employees'] : 0; ?> total</p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Template Info -->
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                    <h3 class="font-bold text-purple-900 mb-3">Monthly Templates</h3>
                    <p class="text-sm text-purple-800 mb-3">Active: <strong><?php echo htmlspecialchars($selectedTemplate); ?></strong></p>
                    <form method="POST" enctype="multipart/form-data" class="space-y-3">
                        <input type="hidden" name="action" value="upload_template">
                        <input type="file" name="template_file" accept=".xlsx" required class="block w-full text-sm text-purple-900 border border-purple-300 rounded-lg p-2 bg-white">
                        <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium">Upload Monthly Template</button>
                    </form>
                    <div class="mt-4">
                        <p class="text-xs text-purple-700 font-semibold mb-1">Available uploaded templates</p>
                        <?php if (empty($templateFiles)): ?>
                        <p class="text-xs text-purple-700">No uploaded templates yet.</p>
                        <?php else: ?>
                        <ul class="space-y-1 text-xs text-purple-800 max-h-28 overflow-y-auto">
                            <?php foreach ($templateFiles as $templateFile): ?>
                            <li>
                                <?php echo htmlspecialchars($templateFile['name']); ?>
                                <?php if ($templateFile['name'] === $selectedTemplate): ?>
                                    <span class="text-green-700 font-semibold">(Active)</span>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Generated Files Section -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">2. Download Generated DTR Files</h2>
                <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full"><?php echo count($outputFiles); ?> files</span>
            </div>

            <!-- Search Output Files -->
            <div class="mb-4">
                <form method="GET" class="flex gap-2">
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchFiles); ?>">
                    <input type="text" name="search_output" placeholder="Search generated files..." value="<?php echo htmlspecialchars($searchOutput); ?>" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">Search</button>
                    <?php if ($searchOutput): ?>
                    <a href="?<?php echo $searchFiles ? "search=".urlencode($searchFiles)."&" : ""; ?>" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Clear</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- File List or Empty State -->
            <?php if (empty($outputFiles)): ?>
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="mt-4 text-gray-700 font-medium">No generated files yet</p>
                <p class="text-sm text-gray-600">Generate DTRs from available files above</p>
            </div>
            <?php else: ?>
            <form method="POST" id="output-files-form">
                <input type="hidden" name="bulk_delete" value="1">
                <input type="hidden" name="delete_from" value="<?php echo $outputDir; ?>">
                
                <!-- Batch Download Buttons -->
                <?php if ($schedule74Count > 0 || $schedule85Count > 0): ?>
                <div class="mb-4 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200">
                    <h3 class="font-semibold text-gray-800 mb-3">Batch Download by Schedule:</h3>
                    <div class="flex flex-wrap gap-2">
                        <?php if ($schedule74Count > 0): ?>
                        <a href="?download_schedule=7-4&from=<?php echo $outputDir; ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Download All 7-4pm Staff (<?php echo $schedule74Count; ?> files)
                        </a>
                        <?php endif; ?>
                        <?php if ($schedule85Count > 0): ?>
                        <a href="?download_schedule=8-5&from=<?php echo $outputDir; ?>" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium transition">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Download All 8-5pm Staff (<?php echo $schedule85Count; ?> files)
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Bulk Actions -->
                <div class="mb-4 flex gap-2 items-center flex-wrap">
                    <button type="button" onclick="toggleSelectAll('output-file-checkbox', true)" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Select All</button>
                    <span class="text-gray-300">|</span>
                    <button type="button" onclick="toggleSelectAll('output-file-checkbox', false)" class="text-sm text-gray-600 hover:text-gray-800 font-medium">Clear All</button>
                    <span class="text-gray-300">|</span>
                    <button type="button" onclick="deleteSelectedOutputFiles()" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 font-medium">Delete Selected</button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 w-12">
                                    <input type="checkbox" onclick="toggleSelectAll('output-file-checkbox', this.checked)" class="h-4 w-4 text-indigo-600 rounded">
                                </th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Schedule</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Filename</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Size</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Date</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($outputFiles as $file): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <input type="checkbox" name="delete_files[]" value="<?php echo htmlspecialchars($file['name']); ?>" class="h-4 w-4 text-indigo-600 rounded output-file-checkbox">
                                </td>
                                <td class="px-4 py-3">
                                    <?php if ($file['schedule'] === '7-4'): ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        7-4pm
                                    </span>
                                    <?php elseif ($file['schedule'] === '8-5'): ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        8-5pm
                                    </span>
                                    <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        N/A
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-800"><?php echo htmlspecialchars($file['name']); ?></td>
                                <td class="px-4 py-3 text-gray-600"><?php echo formatFileSize($file['size']); ?></td>
                                <td class="px-4 py-3 text-gray-600"><?php echo date('M d, Y H:i', $file['modified']); ?></td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="?download=<?php echo urlencode($file['name']); ?>&from=<?php echo $outputDir; ?>" class="text-green-600 hover:text-green-800 font-medium text-sm">Download</a>
                                    <a href="#" onclick="deleteSingleFile('<?php echo htmlspecialchars($file['name'], ENT_QUOTES); ?>', '<?php echo $outputDir; ?>'); return false;" class="text-red-600 hover:text-red-800 font-medium text-sm">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer style="text-align:center;font-size:12px;color:#6c757d;margin-top:40px;padding:10px 0;font-family:Arial,sans-serif;opacity:.8;"><?php echo hex2bin("446576656c6f70656420627920416c6a617920506c616e7461646f2032303236"); ?></footer>

    <script>
        // Toggle guide visibility
        document.addEventListener('DOMContentLoaded', function() {
            const guideBtn = document.getElementById('guide-toggle');
            const guide = document.getElementById('guide');
            
            // Show guide by default
            guide.classList.remove('hidden');
        });

        // Store selected files globally
        let selectedFilesArray = [];

        // Handle file selection for upload
        function handleFileSelect(event) {
            const files = event.target.files;
            displaySelectedFiles(files);
        }

        // Handle drag and drop
        function handleDragOver(event) {
            event.preventDefault();
            event.stopPropagation();
            document.getElementById('drop-zone').classList.add('border-indigo-500', 'bg-indigo-100');
        }

        function handleDragLeave(event) {
            event.preventDefault();
            event.stopPropagation();
            document.getElementById('drop-zone').classList.remove('border-indigo-500', 'bg-indigo-100');
        }

        function handleDrop(event) {
            event.preventDefault();
            event.stopPropagation();
            document.getElementById('drop-zone').classList.remove('border-indigo-500', 'bg-indigo-100');
            
            const files = event.dataTransfer.files;
            
            // Create a new FileList-like object
            const input = document.getElementById('file-input');
            input.files = files;
            
            displaySelectedFiles(files);
        }

        // Display selected files
        function displaySelectedFiles(files) {
            const fileListDiv = document.getElementById('file-list');
            const selectedFilesDiv = document.getElementById('selected-files');
            const fileCountSpan = document.getElementById('file-count');
            
            if (files.length === 0) {
                selectedFilesDiv.classList.add('hidden');
                return;
            }
            
            // Clear previous list
            fileListDiv.innerHTML = '';
            selectedFilesArray = Array.from(files);
            
            // Display each file
            selectedFilesArray.forEach((file, index) => {
                const fileExt = file.name.split('.').pop().toLowerCase();
                const isValid = fileExt === 'xlsx';
                
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between p-2 bg-gray-50 rounded border ' + 
                                    (isValid ? 'border-gray-200' : 'border-red-300 bg-red-50');
                
                fileItem.innerHTML = `
                    <div class="flex items-center gap-2 flex-1">
                        <svg class="w-5 h-5 ${isValid ? 'text-green-600' : 'text-red-600'}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium ${isValid ? 'text-gray-800' : 'text-red-800'}">${file.name}</p>
                            <p class="text-xs ${isValid ? 'text-gray-600' : 'text-red-600'}">${formatBytes(file.size)}${!isValid ? ' - Invalid file type' : ''}</p>
                        </div>
                    </div>
                    <button type="button" onclick="removeFile(${index})" class="text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                `;
                
                fileListDiv.appendChild(fileItem);
            });
            
            fileCountSpan.textContent = files.length;
            selectedFilesDiv.classList.remove('hidden');
        }

        // Remove a file from selection
        function removeFile(index) {
            selectedFilesArray.splice(index, 1);
            
            // Create a new DataTransfer to update the input
            const dt = new DataTransfer();
            selectedFilesArray.forEach(file => dt.items.add(file));
            document.getElementById('file-input').files = dt.files;
            
            displaySelectedFiles(dt.files);
        }

        // Clear all selected files
        function clearFileSelection() {
            document.getElementById('file-input').value = '';
            selectedFilesArray = [];
            document.getElementById('selected-files').classList.add('hidden');
        }

        // Format bytes to readable size
        function formatBytes(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Toggle select all checkboxes
        function toggleSelectAll(className, checked) {
            document.querySelectorAll('.' + className).forEach(function(checkbox) {
                checkbox.checked = checked;
            });
        }

        // Handle upload form submission with AJAX
        function handleUploadSubmit(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            formData.append('ajax', '1');
            
            const uploadStatus = document.getElementById('upload-status');
            uploadStatus.textContent = 'Uploading files...';
            uploadStatus.className = 'mt-2 text-sm text-blue-600';
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let message = `Successfully uploaded ${data.uploadedCount} file(s).`;
                    if (data.errors.length > 0) {
                        message += ` However, ${data.errors.length} file(s) had errors: ${data.errors.join('; ')}`;
                    }
                    showAlert(message, 'success');
                    clearFileSelection();
                    refreshInputFileList(data.files);
                } else {
                    showAlert('Upload failed: ' + data.errors.join('; '), 'error');
                }
                uploadStatus.textContent = '';
            })
            .catch(error => {
                showAlert('Upload error: ' + error.message, 'error');
                uploadStatus.textContent = '';
            });
            
            return false;
        }

        // Delete single file with AJAX
        function deleteSingleFile(filename, from) {
            if (!confirm('Delete this file?')) {
                return;
            }
            
            fetch(`?delete=${encodeURIComponent(filename)}&from=${from}&ajax=1`)
            .then(response => response.json())
            .then(data => {
                showAlert(data.message, data.success ? 'success' : 'error');
                if (data.success) {
                    if (from === 'output') {
                        refreshOutputFileList(data.files);
                    } else {
                        refreshInputFileList(data.files);
                    }
                }
            })
            .catch(error => {
                showAlert('Delete error: ' + error.message, 'error');
            });
        }

        // Delete selected input files with AJAX
        function deleteSelectedInputFiles() {
            const checkboxes = document.querySelectorAll('.process-file-checkbox:checked');
            
            if (checkboxes.length === 0) {
                alert('Please select files to delete.');
                return;
            }
            
            if (!confirm('Are you sure you want to delete ' + checkboxes.length + ' file(s)?')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('bulk_delete', '1');
            formData.append('delete_from', 'excel-files');
            formData.append('ajax', '1');
            
            checkboxes.forEach(function(checkbox) {
                formData.append('delete_files[]', checkbox.value);
            });
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showAlert(data.message, data.success ? 'success' : 'error');
                if (data.success) {
                    refreshInputFileList(data.files);
                }
            })
            .catch(error => {
                showAlert('Delete error: ' + error.message, 'error');
            });
        }

        // Delete selected output files with AJAX
        function deleteSelectedOutputFiles() {
            const checkboxes = document.querySelectorAll('.output-file-checkbox:checked');
            
            if (checkboxes.length === 0) {
                alert('Please select files to delete.');
                return;
            }
            
            if (!confirm('Are you sure you want to delete ' + checkboxes.length + ' file(s)?')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('bulk_delete', '1');
            formData.append('delete_from', '<?php echo $outputDir; ?>');
            formData.append('ajax', '1');
            
            checkboxes.forEach(function(checkbox) {
                formData.append('delete_files[]', checkbox.value);
            });
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showAlert(data.message, data.success ? 'success' : 'error');
                if (data.success) {
                    refreshOutputFileList(data.files);
                }
            })
            .catch(error => {
                showAlert('Delete error: ' + error.message, 'error');
            });
        }

        // Show alert message dynamically
        function showAlert(message, type) {
            // Remove existing alerts
            const existingAlert = document.querySelector('.dynamic-alert');
            if (existingAlert) {
                existingAlert.remove();
            }
            
            const alertDiv = document.createElement('div');
            alertDiv.className = 'dynamic-alert mb-6 p-4 rounded-lg flex items-start gap-3 ' + 
                (type === 'success' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200');
            
            const icon = type === 'success' ? 
                '<svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>' :
                '<svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';
            
            alertDiv.innerHTML = icon + `<p class="${type === 'success' ? 'text-green-800' : 'text-red-800'}">${message}</p>`;
            
            const main = document.querySelector('main');
            main.insertBefore(alertDiv, main.firstChild);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                alertDiv.style.transition = 'opacity 0.5s';
                alertDiv.style.opacity = '0';
                setTimeout(() => alertDiv.remove(), 500);
            }, 5000);
        }

        // Refresh input file list dynamically
        function refreshInputFileList(files) {
            const processForm = document.getElementById('process-form');
            if (!processForm) return;
            
            // Find the file list container
            const fileListContainer = processForm.querySelector('.space-y-2');
            if (!fileListContainer) return;
            
            // Clear current list
            fileListContainer.innerHTML = '';
            
            if (files.length === 0) {
                processForm.parentElement.innerHTML = `
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-4 text-gray-700 font-medium">No files available</p>
                        <p class="text-sm text-gray-600">Upload Excel files to the <code class="bg-gray-200 px-2 py-1 rounded">excel-files</code> folder</p>
                    </div>`;
                return;
            }
            
            // Rebuild file list
            files.forEach(file => {
                const label = document.createElement('label');
                label.className = 'flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer';
                
                const validText = file.valid ? 
                    `<span>${file.employees} employees</span> | ` : 
                    '<span class="text-red-600">Not readable</span> | ';
                const errorText = !file.valid && file.error ? 
                    `<p class="text-xs text-red-600 mt-1">${escapeHtml(file.error)}</p>` : '';
                
                label.innerHTML = `
                    <input type="checkbox" name="files[]" value="${escapeHtml(file.name)}" 
                           class="mt-1 h-4 w-4 text-indigo-600 rounded process-file-checkbox" 
                           data-valid="${file.valid ? '1' : '0'}">
                    <div class="ml-3 flex-1">
                        <p class="font-medium text-gray-800">${escapeHtml(file.name)}</p>
                        <p class="text-sm text-gray-600">
                            ${validText}<span>${formatFileSize(file.size)}</span>
                        </p>
                        ${errorText}
                    </div>
                `;
                
                fileListContainer.appendChild(label);
            });
            
            // Update stats
            const validFiles = files.filter(f => f.valid);
            const invalidFiles = files.filter(f => !f.valid);
            const totalEmployees = validFiles.reduce((sum, f) => sum + f.employees, 0);
            
            // Update section badge (find by looking for the badge near the file list)
            const availableFilesBadge = processForm.closest('.bg-white').querySelector('.bg-indigo-100');
            if (availableFilesBadge) {
                availableFilesBadge.textContent = files.length + ' available';
            }
            
            updateDashboardStats(validFiles.length, totalEmployees, invalidFiles.length);
        }

        // Refresh output file list dynamically
        function refreshOutputFileList(files) {
            const tbody = document.querySelector('#output-files-form tbody');
            if (!tbody) return;
            
            // Clear current list
            tbody.innerHTML = '';
            
            if (files.length === 0) {
                const container = document.querySelector('#output-files-form').parentElement;
                container.innerHTML = `
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-4 text-gray-700 font-medium">No generated files yet</p>
                        <p class="text-sm text-gray-600">Generate DTRs from available files above</p>
                    </div>`;
                return;
            }
            
            // Rebuild file list
            files.forEach(file => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50';
                
                let scheduleBadge = '';
                if (file.schedule === '7-4') {
                    scheduleBadge = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">7-4pm</span>';
                } else if (file.schedule === '8-5') {
                    scheduleBadge = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">8-5pm</span>';
                } else {
                    scheduleBadge = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">N/A</span>';
                }
                
                const modifiedDate = new Date(file.modified * 1000);
                const dateStr = modifiedDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) + ' ' +
                                modifiedDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                
                tr.innerHTML = `
                    <td class="px-4 py-3">
                        <input type="checkbox" name="delete_files[]" value="${escapeHtml(file.name)}" 
                               class="h-4 w-4 text-indigo-600 rounded output-file-checkbox">
                    </td>
                    <td class="px-4 py-3">${scheduleBadge}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">${escapeHtml(file.name)}</td>
                    <td class="px-4 py-3 text-gray-600">${formatFileSize(file.size)}</td>
                    <td class="px-4 py-3 text-gray-600">${dateStr}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="?download=${encodeURIComponent(file.name)}&from=<?php echo $outputDir; ?>" 
                           class="text-green-600 hover:text-green-800 font-medium text-sm">Download</a>
                        <a href="#" onclick="deleteSingleFile('${escapeHtml(file.name).replace(/'/g, "\\'")}', '<?php echo $outputDir; ?>'); return false;" 
                           class="text-red-600 hover:text-red-800 font-medium text-sm">Delete</a>
                    </td>
                `;
                
                tbody.appendChild(tr);
            });
            
            // Update file count badge
            const outputSection = document.querySelector('#output-files-form').closest('.bg-white');
            const badge = outputSection.querySelector('.bg-green-100');
            if (badge) {
                badge.textContent = files.length + ' files';
            }
            
            // Update dashboard stats (Generated Files card - 3rd card)
            const dashboardCards = document.querySelectorAll('.bg-white.rounded-lg.shadow-md.p-6');
            if (dashboardCards[2]) {
                const countElement = dashboardCards[2].querySelector('.text-3xl');
                if (countElement) countElement.textContent = files.length;
            }
        }

        // Update dashboard stats
        function updateDashboardStats(availableFiles, totalEmployees, invalidFiles) {
            const dashboardCards = document.querySelectorAll('.bg-white.rounded-lg.shadow-md');
            
            // Available Files card
            if (dashboardCards[0]) {
                const countElement = dashboardCards[0].querySelector('.text-3xl');
                const statusElement = dashboardCards[0].querySelector('.text-xs');
                if (countElement) countElement.textContent = availableFiles;
                if (statusElement) {
                    statusElement.textContent = invalidFiles > 0 ? 
                        invalidFiles + ' file(s) need attention' : 
                        'All files ready';
                }
            }
            
            // Total Employees card
            if (dashboardCards[1]) {
                const countElement = dashboardCards[1].querySelector('.text-3xl');
                if (countElement) countElement.textContent = totalEmployees;
            }
        }

        // Utility: Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Utility: Format file size (JavaScript version)
        function formatFileSize(bytes) {
            if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
            if (bytes >= 1024) return (bytes / 1024).toFixed(2) + ' KB';
            return bytes + ' B';
        }

        // Prevent processing unreadable files while still allowing deletion selection
        document.addEventListener('DOMContentLoaded', function() {
            const processForm = document.getElementById('process-form');
            if (!processForm) return;

            processForm.addEventListener('submit', function(event) {
                const selected = Array.from(document.querySelectorAll('.process-file-checkbox:checked'));
                const hasInvalid = selected.some(function(checkbox) {
                    return checkbox.getAttribute('data-valid') === '0';
                });

                if (hasInvalid) {
                    event.preventDefault();
                    alert('One or more selected files are unreadable. Please uncheck unreadable files before generating DTRs, or use Delete Selected to remove them.');
                }
            });
        });
    </script>
</body>
</html>
