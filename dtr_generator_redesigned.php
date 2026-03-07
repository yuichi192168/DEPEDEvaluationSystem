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
$defaultTemplate = 'DTR-TEMPLATE.xlsx';
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
    <title>DTR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .wizard-step { display: none; }
        .wizard-step.active { display: block; }
        .help-tooltip { position: relative; }
        body {
            background-color: #055489;
            background-image: linear-gradient(rgba(5, 84, 137, 0.62), rgba(5, 84, 137, 0.62)), url('images/sdocabuyao-cover.svg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        body.dark-mode {
            background-image: linear-gradient(rgba(15, 23, 42, 0.78), rgba(15, 23, 42, 0.78)), url('images/sdocabuyao-cover.svg');
            color: #e5e7eb;
        }
        main .bg-white.rounded-lg.shadow-md {
            background: rgba(240, 247, 252, 0.95) !important;
            border: 1px solid rgba(5, 84, 137, 0.28);
            backdrop-filter: blur(2px);
        }
        body.dark-mode main .bg-white.rounded-lg.shadow-md {
            background: rgba(17, 24, 39, 0.9) !important;
            border: 1px solid rgba(5, 84, 137, 0.55);
        }
        main .bg-white.rounded-lg.shadow-md.p-6.border-t-4,
        main .bg-white.rounded-lg.shadow-md.p-8.border-t-4 {
            border-top-color: #055489 !important;
        }
        body.dark-mode main .text-gray-900,
        body.dark-mode main .text-gray-800,
        body.dark-mode main .text-gray-700,
        body.dark-mode main .text-gray-600,
        body.dark-mode main .text-gray-500 {
            color: #e5e7eb !important;
        }
        body.dark-mode main .bg-gray-50,
        body.dark-mode main .bg-indigo-50,
        body.dark-mode main .bg-blue-50,
        body.dark-mode main .bg-purple-50,
        body.dark-mode main .bg-green-50,
        body.dark-mode main .bg-yellow-50 {
            background: rgba(31, 41, 55, 0.85) !important;
        }
        body.dark-mode main .border-gray-200,
        body.dark-mode main .border-gray-300,
        body.dark-mode main .border-indigo-200,
        body.dark-mode main .border-indigo-300,
        body.dark-mode main .border-purple-200,
        body.dark-mode main .border-blue-200,
        body.dark-mode main .border-green-200,
        body.dark-mode main .border-yellow-200 {
            border-color: rgba(75, 85, 99, 0.9) !important;
        }
        body.dark-mode main input,
        body.dark-mode main select {
            background-color: #111827 !important;
            color: #e5e7eb !important;
            border-color: #4b5563 !important;
        }
        body.dark-mode main select option {
            background-color: #111827;
            color: #e5e7eb;
        }
        body.dark-mode main input::placeholder {
            color: #9ca3af !important;
        }
        body.dark-mode main .text-purple-900,
        body.dark-mode main .text-purple-800,
        body.dark-mode main .text-purple-700 {
            color: #e5e7eb !important;
        }
        body.dark-mode main .template-files-panel {
            background: #111827 !important;
            border-color: #4b5563 !important;
        }
        body.dark-mode main .template-files-list,
        body.dark-mode main .template-files-item {
            color: #e5e7eb !important;
        }
        body.dark-mode main .template-files-item:hover {
            background: rgba(55, 65, 81, 0.92) !important;
        }
        body.dark-mode main .template-active-badge {
            color: #86efac !important;
        }
        .clear-selection-btn {
            background: #e5e7eb !important;
            color: #374151 !important;
            border: 1px solid #d1d5db;
        }
        .clear-selection-btn:hover {
            background: #d1d5db !important;
        }
        body.dark-mode main .clear-selection-btn {
            background: #374151 !important;
            color: #f3f4f6 !important;
            border-color: #4b5563 !important;
        }
        body.dark-mode main .clear-selection-btn:hover {
            background: #4b5563 !important;
        }
        body.dark-mode main .text-indigo-800,
        body.dark-mode main .text-blue-800,
        body.dark-mode main .text-purple-800,
        body.dark-mode main .text-green-800,
        body.dark-mode main .text-yellow-800,
        body.dark-mode main .text-red-800 {
            color: #f3f4f6 !important;
        }
        body.dark-mode main .text-indigo-600,
        body.dark-mode main .text-blue-600,
        body.dark-mode main .text-purple-600,
        body.dark-mode main .text-green-600,
        body.dark-mode main .text-yellow-600,
        body.dark-mode main .text-red-600 {
            color: #cbd5e1 !important;
        }
        body.dark-mode .hover\:bg-gray-50:hover,
        body.dark-mode .hover\:bg-indigo-50:hover,
        body.dark-mode .hover\:bg-blue-50:hover,
        body.dark-mode .hover\:bg-purple-50:hover,
        body.dark-mode .hover\:bg-indigo-100:hover,
        body.dark-mode .hover\:bg-red-200:hover,
        body.dark-mode .hover\:bg-green-700:hover,
        body.dark-mode .hover\:bg-blue-700:hover,
        body.dark-mode .hover\:bg-purple-700:hover {
            background-color: rgba(55, 65, 81, 0.95) !important;
        }
        body.dark-mode main .bg-green-100,
        body.dark-mode main .bg-blue-100,
        body.dark-mode main .bg-purple-100,
        body.dark-mode main .bg-indigo-100 {
            background: rgba(55, 65, 81, 0.95) !important;
        }
        body.dark-mode main .border-green-300,
        body.dark-mode main .border-blue-300,
        body.dark-mode main .border-purple-300,
        body.dark-mode main .border-indigo-300 {
            border-color: rgba(107, 114, 128, 0.9) !important;
        }
        body.dark-mode main .bg-gradient-to-r.from-blue-50.to-purple-50,
        body.dark-mode main .bg-gradient-to-r.from-green-50.to-blue-50,
        body.dark-mode main .bg-gradient-to-r.from-indigo-50.to-blue-50 {
            background-image: none !important;
            background-color: rgba(31, 41, 55, 0.92) !important;
        }
        body.dark-mode main #output-files-table thead,
        body.dark-mode main #input-files-table thead {
            background-image: none !important;
            background-color: rgba(31, 41, 55, 0.96) !important;
        }
        body.dark-mode main #output-files-table th,
        body.dark-mode main #input-files-table th,
        body.dark-mode main #output-files-table td,
        body.dark-mode main #input-files-table td {
            color: #f3f4f6 !important;
        }
        body.dark-mode main .output-file-row:hover,
        body.dark-mode main .input-file-row:hover {
            background-color: rgba(55, 65, 81, 0.92) !important;
        }
        body.dark-mode .help-drawer {
            background: #111827;
            border-left-color: #374151;
            color: #e5e7eb;
        }
        body.dark-mode .help-drawer h3,
        body.dark-mode .help-section h4,
        body.dark-mode .help-section p {
            color: #e5e7eb;
        }
        body.dark-mode .help-section {
            background: rgba(31, 41, 55, 0.9);
            border-color: #4b5563;
        }
        body.dark-mode .btn-secondary {
            background: #1f2937;
            color: #e5e7eb;
            border-color: #4b5563;
        }
        body.dark-mode .btn-secondary:hover {
            background: #374151;
        }
        .theme-toggle {
            position: fixed;
            right: 24px;
            bottom: 90px;
            padding: 10px 14px;
            background: #111827;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.02em;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            z-index: 1001;
            transition: transform 0.2s ease, background-color 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .theme-toggle-icon {
            width: 16px;
            height: 16px;
            display: inline-block;
        }
        .theme-toggle-icon.is-hidden {
            display: none;
        }
        .theme-toggle-label {
            font-size: 12px;
            line-height: 1;
        }
        .theme-toggle:hover {
            background: #1f2937;
            transform: translateY(-2px);
        }
        body.dark-mode .theme-toggle {
            background: #055489;
            color: #f3f4f6;
        }
        body.dark-mode .theme-toggle:hover {
            background: #0668aa;
        }
        body.dark-mode .help-toggle {
            background: #055489;
            box-shadow: 0 10px 30px rgba(5, 84, 137, 0.42);
        }
        body.dark-mode .help-toggle:hover {
            background: #0668aa;
        }
        .header-logo {
            max-width: 90px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        .help-toggle {
            position: fixed;
            right: 24px;
            bottom: 24px;
            width: 52px;
            height: 52px;
            border-radius: 9999px;
            background: #055489;
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(5, 84, 137, 0.35);
            z-index: 1001;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }
        .help-toggle:hover {
            background: #055489;
            transform: translateY(-2px);
        }
        .help-drawer {
            position: fixed;
            top: 0;
            right: -420px;
            width: min(420px, 92vw);
            height: 100vh;
            background: #ffffff;
            border-left: 1px solid #e5e7eb;
            box-shadow: -16px 0 40px rgba(15, 23, 42, 0.18);
            padding: 24px;
            overflow-y: auto;
            z-index: 1002;
            transition: right 0.25s ease;
        }
        .help-drawer.is-open {
            right: 0;
        }
        .help-drawer h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 14px;
        }
        .help-section {
            margin-bottom: 16px;
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #f8fafc;
        }
        .help-section h4 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 6px;
        }
        .help-section p {
            font-size: 0.875rem;
            line-height: 1.55;
            color: #374151;
        }
        .btn-secondary {
            background: #e5e7eb;
            color: #111827;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-secondary:hover {
            background: #d1d5db;
        }
        .help-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.35);
            z-index: 1000;
            display: none;
        }
        .help-backdrop.is-open {
            display: block;
        }
        .generate-btn-floating {
            position: fixed;
            left: 24px;
            bottom: 24px;
            width: auto;
            padding: 14px 20px;
            background: #059669;
            color: #ffffff;
            font-size: 1rem;
            font-weight: 700;
            border: none;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(5, 150, 105, 0.35);
            z-index: 999;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }
        .generate-btn-floating:hover {
            background: #047857;
            transform: translateY(-2px);
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

        @media print {
            nav,
            .theme-toggle,
            a[href="home.php"] {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation Header -->
    <nav style="background: linear-gradient(135deg, #055489 0%, #044073 100%);" class="text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img src="images/sdocabuyao_logo.svg" alt="DepEd Logo" class="header-logo" style="width: 60px; height: auto; margin: 0;">
                    <h1 class="text-2xl font-bold">DTR Generator</h1>
                </div>
                <div class="flex items-center gap-3">
                    <a href="home.php" class="px-4 py-2 rounded-lg text-sm font-semibold" style="background: rgba(255, 255, 255, 0.2); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.35); text-decoration: none;">Home</a>
                    <div class="text-sm" style="color: rgba(255, 255, 255, 0.9);">
                        Department of Education - Daily Time Record System
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="w-full mx-auto px-6 lg:px-8 py-8">
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

        <button type="button" id="themeToggle" class="theme-toggle" aria-pressed="false" aria-label="Switch to dark mode" title="Switch to dark mode">
            <svg id="themeSunIcon" class="theme-toggle-icon is-hidden" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M12 4a1 1 0 011 1v1a1 1 0 11-2 0V5a1 1 0 011-1zm0 13a4 4 0 100-8 4 4 0 000 8zm7-5a1 1 0 011 1h1a1 1 0 110 2h-1a1 1 0 110-2zM3 12a1 1 0 011-1h1a1 1 0 110 2H4a1 1 0 01-1-1zm14.364 5.95a1 1 0 011.414 0l.707.707a1 1 0 11-1.414 1.414l-.707-.707a1 1 0 010-1.414zM4.515 4.515a1 1 0 011.414 0l.707.707A1 1 0 015.222 6.636l-.707-.707a1 1 0 010-1.414zm13.435 0a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM6.636 17.364a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0z"/>
            </svg>
            <svg id="themeMoonIcon" class="theme-toggle-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M21 12.8A9 9 0 1111.2 3a1 1 0 01.97 1.24 7 7 0 008.59 8.59A1 1 0 0121 12.8z"/>
            </svg>
            <span id="themeToggleLabel" class="theme-toggle-label">Dark</span>
        </button>
        <div id="helpToggle" class="help-toggle" title="Help" role="button" aria-pressed="false" tabindex="0">?</div>
        <div id="helpBackdrop" class="help-backdrop" aria-hidden="true"></div>
        <aside id="helpDrawer" class="help-drawer" aria-hidden="true">
            <h3>DTR Form Help</h3>
            <div class="help-section">
                <h4>What this form is</h4>
                <p>This page helps you generate Daily Time Record (DTR) files from uploaded attendance Excel files using the selected monthly template.</p>
            </div>
            <div class="help-section">
                <h4>How generation works</h4>
                <p>Upload your .xlsx files, choose which files to process, pick the correct monthly template, then click Generate DTRs. The system reads each employee record and creates one DTR file per employee.</p>
            </div>
            <div class="help-section">
                <h4>What happens after generation</h4>
                <p>Generated files appear in the Download Generated DTR Files section. You can search, download one by one, download by schedule, or delete files you no longer need.</p>
            </div>
            <div class="help-section">
                <h4>Help & Information</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>• Only .xlsx Excel files are supported</li>
                    <li>• Select multiple files using the checkboxes</li>
                    <li>• Processing takes a few seconds per file</li>
                    <li>• Download results from the Generated Files section</li>
                </ul>
            </div>
            <div>
                <button type="button" id="helpClose" class="btn-secondary">Close Help</button>
            </div>
        </aside>

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
        <div class="space-y-6">
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
                <div class="bg-white rounded-lg shadow-md p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">1. Select Files to Process</h2>
                        <span class="bg-indigo-100 text-indigo-800 text-sm font-semibold px-4 py-2 rounded-full"><?php echo count($displayFiles); ?> available</span>
                    </div>

                    <?php if (empty($displayFiles)): ?>
                    <div class="text-center py-16 bg-gray-50 rounded-lg">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-4 text-gray-700 font-medium text-lg">No files available</p>
                        <p class="text-sm text-gray-600 mt-2">Upload Excel files to the <code class="bg-gray-200 px-2 py-1 rounded">excel-files</code> folder</p>
                    </div>
                    <?php else: ?>
                    
                    <!-- Template Selection & Upload Box -->
                    <div class="mb-8 p-6 bg-purple-50 rounded-xl border-2 border-purple-200 shadow-sm">
                        <h3 class="text-base font-bold text-purple-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                                <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                            </svg>
                            DTR Template Management
                        </h3>
                        
                        <!-- Upload New Template Form -->
                        <form method="POST" enctype="multipart/form-data" class="mb-4">
                            <input type="hidden" name="action" value="upload_template">
                            <label class="block text-sm font-semibold text-purple-900 mb-2">Upload New Monthly Template</label>
                            <div class="flex gap-3">
                                <input type="file" name="template_file" accept=".xlsx" required class="flex-1 text-sm text-purple-900 border border-purple-300 rounded-lg p-2 bg-white file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200">
                                <button type="submit" class="px-5 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium whitespace-nowrap shadow-sm">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    Upload
                                </button>
                            </div>
                        </form>

                        <!-- Available Templates List -->
                        <?php if (!empty($templateFiles)): ?>
                        <div class="pt-3 border-t border-purple-200">
                            <p class="text-xs font-semibold text-purple-900 mb-2">Available Templates (<?php echo count($templateFiles); ?>):</p>
                            <div class="max-h-24 overflow-y-auto bg-white rounded-lg border border-purple-200 p-2 template-files-panel">
                                <ul class="space-y-1 text-xs text-purple-800 template-files-list">
                                    <?php foreach ($templateFiles as $templateFile): ?>
                                    <li class="flex items-center justify-between py-1 px-2 hover:bg-purple-50 rounded template-files-item">
                                        <span>📄 <?php echo htmlspecialchars($templateFile['name']); ?></span>
                                        <?php if ($templateFile['name'] === $selectedTemplate): ?>
                                            <span class="text-green-700 font-bold text-xs template-active-badge">✓ Active</span>
                                        <?php endif; ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <form method="POST" id="process-form">
                        <input type="hidden" name="action" value="batch_process">

                        <!-- Active Template Selection (for processing) -->
                        <div class="mb-8 p-4 bg-purple-50 rounded-lg border border-purple-200">
                            <label for="selected-template" class="block text-sm font-semibold text-purple-900 mb-2">Active Template for Selected Month</label>
                            <select id="selected-template" name="selected_template" class="w-full px-4 py-3 text-base border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white">
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
                        </div>

                        <!-- Search Input Files -->
                        <div class="mb-6">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <input type="text" id="live-search-input" placeholder="Type to search input files instantly..." class="w-full pl-12 pr-36 py-4 text-base border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" autocomplete="off">
                                <div id="search-input-counter" class="absolute inset-y-0 right-0 pr-4 flex items-center text-sm font-semibold text-gray-600">
                                    <span id="search-input-results-count"></span>
                                </div>
                            </div>
                            <p class="mt-3 text-sm text-gray-600 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span><span class="font-semibold">Live Search:</span> Results update as you type. Search by filename or employee count. Press ESC to clear.</span>
                            </p>
                        </div>

                        <!-- Bulk Actions -->
                        <div class="mb-6 p-5 bg-gray-50 rounded-xl flex gap-4 items-center flex-wrap border-2 border-gray-200">
                            <span class="text-base font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                                </svg>
                                Bulk Actions:
                            </span>
                            <button type="button" onclick="toggleSelectAll('process-file-checkbox', true)" class="text-sm px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold transition shadow-sm">Select All</button>
                            <span class="text-gray-400">|</span>
                            <button type="button" onclick="toggleSelectAll('process-file-checkbox', false)" class="clear-selection-btn text-sm px-5 py-2 rounded-lg font-semibold transition">Clear Selection</button>
                            <span class="text-gray-400">|</span>
                            <button type="button" onclick="deleteSelectedInputFiles()" class="text-sm px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition shadow-sm inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Delete Selected
                            </button>
                        </div>

                        <!-- File List Table -->
                        <div class="overflow-x-auto mb-6">
                            <div id="no-search-input-results" class="hidden text-center py-20 bg-yellow-50 rounded-xl border-2 border-yellow-200">
                                <svg class="mx-auto h-20 w-20 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <p class="mt-5 text-xl text-gray-800 font-bold">No matching files found</p>
                                <p class="text-sm text-gray-600 mt-3">Try different search terms or press ESC to clear your search.</p>
                            </div>
                            <table class="w-full" id="input-files-table">
                                <thead class="bg-gradient-to-r from-indigo-50 to-blue-50 border-b-2 border-indigo-300">
                                <tr>
                                    <th class="px-6 py-5 text-left font-bold text-gray-900 w-14">
                                        <input type="checkbox" onclick="toggleSelectAll('process-file-checkbox', this.checked)" class="h-4 w-4 text-indigo-600 rounded">
                                    </th>
                                    <th class="px-6 py-5 text-left font-bold text-gray-900 w-32">Status</th>
                                    <th class="px-6 py-5 text-left font-bold text-gray-900">File Name</th>
                                    <th class="px-6 py-5 text-left font-bold text-gray-900 w-44">Employee Count</th>
                                    <th class="px-6 py-5 text-left font-bold text-gray-900 w-32">File Size</th>
                                    <th class="px-6 py-5 text-right font-bold text-gray-900 w-36">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($displayFiles as $file): ?>
                                    <tr class="hover:bg-indigo-50 transition-colors input-file-row" data-filename="<?php echo strtolower(htmlspecialchars($file['name'])); ?>">
                                        <td class="px-6 py-5">
                                            <input type="checkbox" name="files[]" value="<?php echo htmlspecialchars($file['name']); ?>" class="h-4 w-4 text-indigo-600 rounded process-file-checkbox" data-valid="<?php echo $file['valid'] ? '1' : '0'; ?>">
                                        </td>
                                        <td class="px-6 py-5">
                                            <?php if ($file['valid']): ?>
                                                <span class="inline-flex items-center px-3 py-2 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-300">
                                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Ready
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-3 py-2 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-300">
                                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Invalid
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="font-semibold text-gray-900 text-base"><?php echo htmlspecialchars($file['name']); ?></div>
                                            <div class="text-xs text-gray-500 mt-2 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                                </svg>
                                                Excel File
                                            </div>
                                            <?php if (!$file['valid']): ?>
                                            <div class="text-xs text-red-600 mt-2 bg-red-50 px-3 py-2 rounded border border-red-200">
                                                <strong>Error:</strong> <?php echo htmlspecialchars($file['error'] ?? 'Unable to read file'); ?>
                                            </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-5">
                                            <?php if ($file['valid']): ?>
                                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                                    <?php echo $file['employees']; ?> employees
                                                </span>
                                            <?php else: ?>
                                                <span class="text-gray-500 text-sm">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-5 text-gray-800 font-semibold text-base"><?php echo formatFileSize($file['size']); ?></td>
                                        <td class="px-6 py-5 text-right">
                                            <div class="flex gap-2 justify-end">
                                                <button type="button" onclick="deleteSingleInputFile('<?php echo htmlspecialchars($file['name'], ENT_QUOTES); ?>');" class="inline-flex items-center px-4 py-2.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 font-semibold text-sm transition shadow-sm">
                                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Generate Button (visible only when file(s) selected) -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t-2 border-gray-200">
                            <button type="submit" id="generate-btn" class="px-8 py-3.5 bg-green-600 text-white rounded-xl hover:bg-green-700 font-bold transition shadow-md hover:shadow-lg inline-flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed text-base" disabled>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a1 1 0 001 1h12a1 1 0 001-1V6a2 2 0 00-2-2H4zm12 12H4a2 2 0 01-2-2v-4a1 1 0 00-1-1H.5a.5.5 0 00-.5.5v4a4 4 0 004 4h12a4 4 0 004-4v-4a.5.5 0 00-.5-.5H17a1 1 0 00-1 1v4a2 2 0 01-2 2z" clip-rule="evenodd"/>
                                </svg>
                                Generate DTRs
                            </button>
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span>Select at least one file above to generate DTRs</span>
                            </p>
                        </div>
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

        <!-- Generated Files Section -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">2. Download Generated DTR Files</h2>
                <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full"><?php echo count($outputFiles); ?> files</span>
            </div>

            <!-- Search Output Files -->
            <div class="mb-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <input type="text" id="live-search-output" placeholder="Type to search generated files instantly..." class="w-full pl-10 pr-32 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm" autocomplete="off">
                    <div id="search-counter" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm font-medium text-gray-600">
                        <span id="search-results-count"></span>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-600 flex items-center gap-1">
                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">Live Search:</span> Results update as you type. Search by employee name, schedule, or filename. Press ESC to clear.
                </p>
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
                    <div class="mb-6 p-5 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border-2 border-blue-200 shadow-sm">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            <h3 class="font-bold text-gray-900 text-base">Quick Download by Schedule</h3>
                        </div>
                        <p class="text-sm text-gray-700 mb-4">Download all DTR files for a specific work schedule in one ZIP file.</p>
                        <div class="flex flex-wrap gap-3">
                        <?php if ($schedule74Count > 0): ?>
                            <a href="?download_schedule=7-4&from=<?php echo $outputDir; ?>" class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition shadow-sm hover:shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                                7:00 AM - 4:00 PM (<?php echo $schedule74Count; ?> files)
                        </a>
                        <?php endif; ?>
                        <?php if ($schedule85Count > 0): ?>
                            <a href="?download_schedule=8-5&from=<?php echo $outputDir; ?>" class="inline-flex items-center px-5 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold transition shadow-sm hover:shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                                8:00 AM - 5:00 PM (<?php echo $schedule85Count; ?> files)
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Bulk Actions -->
                    <div class="mb-5 p-4 bg-gray-50 rounded-lg flex gap-3 items-center flex-wrap border-2 border-gray-200">
                        <span class="text-sm font-bold text-gray-800 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                            </svg>
                            Bulk Actions:
                        </span>
                        <button type="button" onclick="toggleSelectAll('output-file-checkbox', true)" class="text-sm px-4 py-1.5 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-medium transition shadow-sm">Select All</button>
                        <span class="text-gray-400">|</span>
                        <button type="button" onclick="toggleSelectAll('output-file-checkbox', false)" class="clear-selection-btn text-sm px-4 py-1.5 rounded-md font-medium transition">Clear Selection</button>
                        <span class="text-gray-400">|</span>
                        <button type="button" onclick="deleteSelectedOutputFiles()" class="text-sm px-4 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 font-semibold transition shadow-sm inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Delete Selected
                        </button>
                </div>
                
                <div class="overflow-x-auto">
                        <div id="no-search-results" class="hidden text-center py-16 bg-yellow-50 rounded-lg border-2 border-yellow-200">
                            <svg class="mx-auto h-16 w-16 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <p class="mt-4 text-lg text-gray-800 font-bold">No matching files found</p>
                            <p class="text-sm text-gray-600 mt-2">Try different search terms or press ESC to clear your search.</p>
                        </div>
                        <table class="w-full text-sm" id="output-files-table">
                            <thead class="bg-gradient-to-r from-green-50 to-blue-50 border-b-2 border-green-300">
                            <tr>
                                    <th class="px-4 py-4 text-left font-bold text-gray-900 w-12">
                                    <input type="checkbox" onclick="toggleSelectAll('output-file-checkbox', this.checked)" class="h-4 w-4 text-indigo-600 rounded">
                                </th>
                                    <th class="px-4 py-4 text-left font-bold text-gray-900">Schedule</th>
                                    <th class="px-4 py-4 text-left font-bold text-gray-900">Employee Name & File</th>
                                    <th class="px-4 py-4 text-left font-bold text-gray-900">File Size</th>
                                    <th class="px-4 py-4 text-left font-bold text-gray-900">Date Created</th>
                                    <th class="px-4 py-4 text-right font-bold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($outputFiles as $file): ?>
                                <tr class="hover:bg-blue-50 transition-colors output-file-row" data-filename="<?php echo strtolower(htmlspecialchars($file['name'])); ?>" data-schedule="<?php echo htmlspecialchars($file['schedule']); ?>">
                                    <td class="px-4 py-4">
                                    <input type="checkbox" name="delete_files[]" value="<?php echo htmlspecialchars($file['name']); ?>" class="h-4 w-4 text-indigo-600 rounded output-file-checkbox">
                                </td>
                                    <td class="px-4 py-4">
                                    <?php if ($file['schedule'] === '7-4'): ?>
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-300">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            7-4
                                    </span>
                                    <?php elseif ($file['schedule'] === '8-5'): ?>
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-300">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            8-5
                                    </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gray-100 text-gray-700 border border-gray-300">
                                        N/A
                                    </span>
                                    <?php endif; ?>
                                </td>
                                    <td class="px-4 py-4">
                                        <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($file['name']); ?></div>
                                        <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                            </svg>
                                            Excel DTR File
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-gray-800 font-semibold"><?php echo formatFileSize($file['size']); ?></td>
                                    <td class="px-4 py-4 text-gray-700"><?php echo date('M d, Y H:i', $file['modified']); ?></td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex gap-2 justify-end">
                                            <a href="?download=<?php echo urlencode($file['name']); ?>&from=<?php echo $outputDir; ?>" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-semibold text-sm transition shadow-sm hover:shadow-md">
                                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                                Download
                                            </a>
                                            <button onclick="deleteSingleFile('<?php echo htmlspecialchars($file['name'], ENT_QUOTES); ?>', '<?php echo $outputDir; ?>'); return false;" class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200 font-semibold text-sm transition">
                                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </div>
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
    <footer style="text-align:center;font-size:12px;color:#6c757d;margin-top:40px;padding:10px 0;font-family:Arial,sans-serif;opacity:.08;"><?php echo hex2bin("446576656c6f70656420627920416c6a617920506c616e7461646f2032303236"); ?></footer>

    <script>
        // Initialize UI interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Setup live search for input files
            setupLiveSearchInput();

            // Setup live search for output files
            setupLiveSearch();

            // Setup checkbox listeners for Generate button visibility
            setupProcessCheckboxListeners();

            const themeToggle = document.getElementById('themeToggle');
            const themeSunIcon = document.getElementById('themeSunIcon');
            const themeMoonIcon = document.getElementById('themeMoonIcon');
            const themeToggleLabel = document.getElementById('themeToggleLabel');
            const themeStorageKey = 'dtr_theme_mode';

            function applyTheme(mode) {
                const isDark = mode === 'dark';
                document.body.classList.toggle('dark-mode', isDark);
                if (themeToggle) {
                    themeToggle.setAttribute('aria-pressed', isDark ? 'true' : 'false');
                    themeToggle.setAttribute('title', isDark ? 'Switch to light mode' : 'Switch to dark mode');
                    themeToggle.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
                }
                if (themeSunIcon && themeMoonIcon) {
                    themeSunIcon.classList.toggle('is-hidden', !isDark);
                    themeMoonIcon.classList.toggle('is-hidden', isDark);
                }
                if (themeToggleLabel) {
                    themeToggleLabel.textContent = isDark ? 'Light' : 'Dark';
                }
            }

            try {
                const savedTheme = localStorage.getItem(themeStorageKey);
                applyTheme(savedTheme === 'dark' ? 'dark' : 'light');
            } catch (error) {
                applyTheme('light');
            }

            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const nextTheme = document.body.classList.contains('dark-mode') ? 'light' : 'dark';
                    applyTheme(nextTheme);
                    try {
                        localStorage.setItem(themeStorageKey, nextTheme);
                    } catch (error) {
                        // Ignore storage errors and keep current runtime theme.
                    }
                });
            }

            const helpToggle = document.getElementById('helpToggle');
            const helpDrawer = document.getElementById('helpDrawer');
            const helpClose = document.getElementById('helpClose');
            const helpBackdrop = document.getElementById('helpBackdrop');

            function openHelpDrawer() {
                if (!helpDrawer || !helpToggle || !helpBackdrop) return;
                helpDrawer.classList.add('is-open');
                helpBackdrop.classList.add('is-open');
                helpDrawer.setAttribute('aria-hidden', 'false');
                helpToggle.setAttribute('aria-pressed', 'true');
                helpBackdrop.setAttribute('aria-hidden', 'false');
            }

            function closeHelpDrawer() {
                if (!helpDrawer || !helpToggle || !helpBackdrop) return;
                helpDrawer.classList.remove('is-open');
                helpBackdrop.classList.remove('is-open');
                helpDrawer.setAttribute('aria-hidden', 'true');
                helpToggle.setAttribute('aria-pressed', 'false');
                helpBackdrop.setAttribute('aria-hidden', 'true');
            }

            if (helpToggle) {
                helpToggle.addEventListener('click', function() {
                    const isOpen = helpDrawer && helpDrawer.classList.contains('is-open');
                    if (isOpen) {
                        closeHelpDrawer();
                    } else {
                        openHelpDrawer();
                    }
                });

                helpToggle.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        const isOpen = helpDrawer && helpDrawer.classList.contains('is-open');
                        if (isOpen) {
                            closeHelpDrawer();
                        } else {
                            openHelpDrawer();
                        }
                    }
                });
            }

            if (helpClose) {
                helpClose.addEventListener('click', closeHelpDrawer);
            }

            if (helpBackdrop) {
                helpBackdrop.addEventListener('click', closeHelpDrawer);
            }

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && helpDrawer && helpDrawer.classList.contains('is-open')) {
                    closeHelpDrawer();
                }
            });
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

        // Delete single input file with confirmation
        function deleteSingleInputFile(filename) {
            if (!confirm('Delete this file?')) {
                return;
            }
            
            deleteSingleFile(filename, 'excel-files');
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

            // Update Generate button visibility based on checkbox selection
            function updateGenerateButtonState() {
                const generateBtn = document.getElementById('generate-btn');
                const checkboxes = document.querySelectorAll('.process-file-checkbox');
                const selectedCheckboxes = document.querySelectorAll('.process-file-checkbox:checked');
                
                if (!generateBtn) return;
                
                // Enable Generate button only if at least one file is selected
                if (selectedCheckboxes.length > 0) {
                    generateBtn.disabled = false;
                } else {
                    generateBtn.disabled = true;
                }
            }

            // Setup checkbox listeners for Generate button state
            function setupProcessCheckboxListeners() {
                const checkboxes = document.querySelectorAll('.process-file-checkbox');
                checkboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('change', updateGenerateButtonState);
                });
                
                // Initial state
                updateGenerateButtonState();
            }

            // Live search for input files (instant filtering as you type)
            function setupLiveSearchInput() {
                const searchInput = document.getElementById('live-search-input');
                const inputTable = document.getElementById('input-files-table');
                const noResultsDiv = document.getElementById('no-search-input-results');
                const searchCounter = document.getElementById('search-input-results-count');
            
                if (!searchInput || !inputTable) return;
            
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    const rows = inputTable.querySelectorAll('.input-file-row');
                    let visibleCount = 0;
                
                    rows.forEach(function(row) {
                        const filename = row.getAttribute('data-filename') || '';
                    
                        const matches = filename.includes(searchTerm);
                    
                        if (matches || searchTerm === '') {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });
                
                    // Update counter display
                    if (searchTerm) {
                        searchCounter.textContent = visibleCount + ' of ' + rows.length;
                    } else {
                        searchCounter.textContent = '';
                    }
                
                    // Show/hide no results message
                    if (visibleCount === 0 && searchTerm !== '') {
                        noResultsDiv.classList.remove('hidden');
                        inputTable.classList.add('hidden');
                    } else {
                        noResultsDiv.classList.add('hidden');
                        inputTable.classList.remove('hidden');
                    }
                });
            
                // Clear search on Escape key
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        this.value = '';
                        this.dispatchEvent(new Event('input'));
                        this.blur(); // Remove focus
                    }
                });
            }

            // Live search for output files (instant filtering as you type)
            function setupLiveSearch() {
                const searchInput = document.getElementById('live-search-output');
                const outputTable = document.getElementById('output-files-table');
                const noResultsDiv = document.getElementById('no-search-results');
                const searchCounter = document.getElementById('search-results-count');
            
                if (!searchInput || !outputTable) return;
            
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    const rows = outputTable.querySelectorAll('.output-file-row');
                    let visibleCount = 0;
                
                    rows.forEach(function(row) {
                        const filename = row.getAttribute('data-filename') || '';
                        const schedule = row.getAttribute('data-schedule') || '';
                    
                        const matches = filename.includes(searchTerm) || schedule.includes(searchTerm);
                    
                        if (matches || searchTerm === '') {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });
                
                    // Update counter display
                    if (searchTerm) {
                        searchCounter.textContent = visibleCount + ' of ' + rows.length;
                    } else {
                        searchCounter.textContent = '';
                    }
                
                    // Show/hide no results message
                    if (visibleCount === 0 && searchTerm !== '') {
                        noResultsDiv.classList.remove('hidden');
                        outputTable.classList.add('hidden');
                    } else {
                        noResultsDiv.classList.add('hidden');
                        outputTable.classList.remove('hidden');
                    }
                });
            
                // Clear search on Escape key
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        this.value = '';
                        this.dispatchEvent(new Event('input'));
                        this.blur(); // Remove focus
                    }
                });
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
