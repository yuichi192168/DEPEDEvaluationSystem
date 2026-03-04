<?php
/**
 * DTR Generator - Advanced Configuration & Customization Examples
 * 
 * This file contains examples for advanced usage, customization, and integration
 * Copy and modify these examples as needed for your specific use case
 */

// ============================================================================
// EXAMPLE 1: Custom DTR Generation with Email Notification
// ============================================================================

/**
 * Generate DTRs and send notification email
 */
function generateDTRsWithNotification($sourceFile, $templateFile, $outputDir) {
    require __DIR__ . '/generate_dtr.php';
    
    try {
        $generator = new DTRGenerator($sourceFile, $templateFile, $outputDir);
        $generator->loadSourceData();
        
        $employeeCount = count($generator->logData);
        $startTime = microtime(true);
        
        $generator->generateDTRs();
        
        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);
        
        // Send email notification
        $subject = "DTR Generation Complete - $employeeCount employees";
        $message = "
        DTR Generation Report
        =====================
        Date: " . date('Y-m-d H:i:s') . "
        Employees Processed: $employeeCount
        Time Taken: {$duration} seconds
        Output Directory: $outputDir
        
        Files Generated:
        " . implode("\n", array_map(function($e) {
            return "- DTR_Generated_" . str_replace(' ', '_', $e) . ".xlsx";
        }, array_keys($generator->logData))) . "
        ";
        
        // Uncomment to send email
        // mail('admin@example.com', $subject, $message);
        
        echo $message;
        return true;
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Usage:
// generateDTRsWithNotification('OSDS-January-2026.xlsx', 'dtr-jan-2026.xlsx', 'output');


// ============================================================================
// EXAMPLE 2: Batch Processing Multiple Months
// ============================================================================

/**
 * Generate DTRs for multiple months at once
 */
function batchGenerateDTRs($months = []) {
    require __DIR__ . '/generate_dtr.php';
    
    // Default: Jan through Aug
    if (empty($months)) {
        $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august'];
    }
    
    $results = [];
    
    foreach ($months as $month) {
        $monthLower = strtolower($month);
        $sourceFile = "OSDS-" . ucfirst($monthLower) . "-2026.xlsx";
        $templateFile = "dtr-" . substr($monthLower, 0, 3) . "-2026.xlsx";
        $outputDir = "output_" . $monthLower;
        
        if (!file_exists($sourceFile) || !file_exists($templateFile)) {
            $results[$month] = "SKIPPED - Files not found";
            continue;
        }
        
        try {
            $generator = new DTRGenerator($sourceFile, $templateFile, $outputDir);
            $generator->loadSourceData();
            $generator->generateDTRs();
            
            $results[$month] = "SUCCESS - " . count($generator->logData) . " employees";
        } catch (Exception $e) {
            $results[$month] = "ERROR - " . $e->getMessage();
        }
    }
    
    return $results;
}

// Usage:
// $results = batchGenerateDTRs(['january', 'february']);
// foreach ($results as $month => $status) {
//     echo "$month: $status\n";
// }


// ============================================================================
// EXAMPLE 3: Generate DTR for Specific Department Only
// ============================================================================

/**
 * Generate DTRs for a specific department only
 */
function generateDTRsByDepartment($sourceFile, $templateFile, $department, $outputDir) {
    require __DIR__ . '/generate_dtr.php';
    
    $generator = new DTRGenerator($sourceFile, $templateFile, $outputDir);
    
    try {
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($sourceFile);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        
        // Parse data for specific department
        $employeeCount = 0;
        foreach (array_slice($rows, 1) as $row) { // Skip header
            if (empty($row[0])) continue;
            
            $name = $row[0];
            $dept = trim($row[5] ?? ''); // Department is column F
            
            // Only process if department matches
            if (strtolower($dept) === strtolower($department)) {
                // ... process this employee ...
                $employeeCount++;
            }
        }
        
        echo "Generated DTRs for $employeeCount employees in $department\n";
        return true;
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Usage:
// generateDTRsByDepartment('OSDS-January-2026.xlsx', 'dtr-jan-2026.xlsx', 'City Schools Division', 'output_csd');


// ============================================================================
// EXAMPLE 4: Validate Source File Before Generation
// ============================================================================

/**
 * Validate source file structure and content
 */
function validateSourceFile($sourceFile) {
    require __DIR__ . '/generate_dtr.php';
    require __DIR__ . '/vendor/autoload.php';
    
    use PhpOffice\PhpSpreadsheet\IOFactory;
    
    $errors = [];
    $warnings = [];
    
    // Check file exists
    if (!file_exists($sourceFile)) {
        return ['valid' => false, 'errors' => ["File not found: $sourceFile"]];
    }
    
    try {
        // Load file
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($sourceFile);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        
        if (empty($rows)) {
            $errors[] = "Source file is empty";
            return ['valid' => false, 'errors' => $errors];
        }
        
        // Check header row
        $expectedHeaders = ['Name', 'Date', 'Timetable', 'Clock In', 'Clock Out', 'Department'];
        $actualHeaders = array_map('trim', array_slice($rows[0], 0, 6));
        
        $headerMatch = true;
        for ($i = 0; $i < 6; $i++) {
            if (strtolower($actualHeaders[$i] ?? '') !== strtolower($expectedHeaders[$i])) {
                $warnings[] = "Column " . chr(65+$i) . ": Expected '{$expectedHeaders[$i]}', found '{$actualHeaders[$i]}'";
                $headerMatch = false;
            }
        }
        
        if (!$headerMatch) {
            $errors[] = "Column headers don't match expected format";
        }
        
        // Validate data rows
        $dataRows = 0;
        $invalidRows = 0;
        
        foreach (array_slice($rows, 1) as $rowNum => $row) {
            if (empty($row[0])) continue; // Skip empty rows
            
            $dataRows++;
            
            // Check required fields
            if (empty($row[0]) || empty($row[1]) || empty($row[2])) {
                $invalidRows++;
                continue;
            }
            
            // Validate date format
            $date = $row[1];
            if (!is_numeric($date) && !preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}/', $date)) {
                $warnings[] = "Row " . ($rowNum + 2) . ": Unusual date format: $date";
            }
        }
        
        if ($dataRows === 0) {
            $errors[] = "No data rows found in file";
        }
        
        if ($invalidRows > 0) {
            $warnings[] = "Found $invalidRows rows with missing required fields";
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
            'stats' => [
                'total_rows' => count($rows),
                'data_rows' => $dataRows,
                'invalid_rows' => $invalidRows
            ]
        ];
        
    } catch (Exception $e) {
        return [
            'valid' => false,
            'errors' => ["Failed to read file: " . $e->getMessage()]
        ];
    }
}

// Usage:
// $validation = validateSourceFile('OSDS-January-2026.xlsx');
// if ($validation['valid']) {
//     echo "✓ File is valid\n";
// } else {
//     echo "✗ File has errors:\n";
//     foreach ($validation['errors'] as $error) {
//         echo "  - $error\n";
//     }
// }
// if (!empty($validation['warnings'])) {
//     echo "⚠ Warnings:\n";
//     foreach ($validation['warnings'] as $warn) {
//         echo "  - $warn\n";
//     }
// }


// ============================================================================
// EXAMPLE 5: Generate PDF Report of DTR Generation
// ============================================================================

/**
 * Create a summary report of DTR generation
 */
function generateDTRReport($sourceFile, $templateFile, $outputDir) {
    require __DIR__ . '/generate_dtr.php';
    
    $reportFile = $outputDir . '/DTR_GENERATION_REPORT_' . date('Y-m-d_H-i-s') . '.txt';
    
    $report = [];
    $report[] = "DepEd DTR Generation Report";
    $report[] = str_repeat("=", 50);
    $report[] = "Generated: " . date('Y-m-d H:i:s');
    $report[] = "";
    
    try {
        $generator = new DTRGenerator($sourceFile, $templateFile, $outputDir);
        $generator->loadSourceData();
        
        $report[] = "Source File: $sourceFile";
        $report[] = "Template File: $templateFile";
        $report[] = "Output Directory: $outputDir";
        $report[] = "";
        
        $report[] = "Employee Summary:";
        $report[] = "Total Employees: " . count($generator->logData);
        $report[] = "";
        
        $report[] = "Employee List:";
        foreach ($generator->logData as $name => $data) {
            $dayCount = count(array_filter($data['dates']));
            $report[] = "  - $name: {$dayCount} days logged";
        }
        
        // Write report
        file_put_contents($reportFile, implode("\n", $report));
        
        return $reportFile;
        
    } catch (Exception $e) {
        return null;
    }
}

// Usage:
// $reportPath = generateDTRReport('OSDS-January-2026.xlsx', 'dtr-jan-2026.xlsx', 'output');
// echo "Report saved to: $reportPath\n";


// ============================================================================
// EXAMPLE 6: API Endpoint for DTR Generation
// ============================================================================

/**
 * Simple API endpoint for DTR generation (POST request)
 */
function handleDTRGenerationAPI() {
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }
    
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $sourceFile = $data['source_file'] ?? 'OSDS-January-2026.xlsx';
        $templateFile = $data['template_file'] ?? 'dtr-jan-2026.xlsx';
        $outputDir = $data['output_dir'] ?? 'output';
        
        require __DIR__ . '/generate_dtr.php';
        
        $generator = new DTRGenerator($sourceFile, $templateFile, $outputDir);
        $generator->loadSourceData();
        $generator->generateDTRs();
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'DTR generation completed',
            'employees' => count($generator->logData),
            'output_dir' => $outputDir
        ]);
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

// Usage:
// Add to a file like dtr_api.php and call:
// curl -X POST http://localhost/DEPEDEvaluationSystem/dtr_api.php \
//   -H "Content-Type: application/json" \
//   -d '{"source_file": "OSDS-January-2026.xlsx"}'


// ============================================================================
// EXAMPLE 7: Scheduled Task (Windows Scheduler or Cron)
// ============================================================================

/**
 * Script for scheduled DTR generation
 * 
 * Windows Task Scheduler:
 * - Start a program: C:\xampp\php\php.exe
 * - Arguments: C:\xampp\htdocs\DEPEDEvaluationSystem\dtr_scheduler.php
 * - Run on: First day of month at 6:00 AM
 * 
 * Linux Cron:
 * 0 6 1 * * /usr/bin/php /var/www/html/DEPEDEvaluationSystem/dtr_scheduler.php
 */

if (php_sapi_name() === 'cli') {
    $logFile = __DIR__ . '/logs/dtr_generation_' . date('Y-m-d') . '.log';
    
    if (!is_dir(__DIR__ . '/logs')) {
        mkdir(__DIR__ . '/logs', 0755, true);
    }
    
    $output = fopen($logFile, 'a');
    
    fwrite($output, "\n\n=== DTR Generation Started at " . date('Y-m-d H:i:s') . " ===\n");
    
    try {
        require __DIR__ . '/generate_dtr.php';
        
        $generator = new DTRGenerator(
            'OSDS-' . date('F') . '-' . date('Y') . '.xlsx',
            'dtr-' . strtolower(substr(date('F'), 0, 3)) . '-' . date('Y') . '.xlsx',
            'output_' . date('Y-m')
        );
        
        fwrite($output, "Loading source data...\n");
        $generator->loadSourceData();
        
        fwrite($output, "Generating DTRs for " . count($generator->logData) . " employees...\n");
        $generator->generateDTRs();
        
        fwrite($output, "✓ DTR generation completed successfully\n");
        
    } catch (Exception $e) {
        fwrite($output, "✗ ERROR: " . $e->getMessage() . "\n");
    }
    
    fwrite($output, "=== DTR Generation Ended at " . date('Y-m-d H:i:s') . " ===\n");
    fclose($output);
}


?>
<!-- 
HTML TEMPLATE FOR WEB ADMIN INTERFACE
=====================================

<!DOCTYPE html>
<html>
<head>
    <title>DTR Generator - Admin Panel</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .section { margin: 20px 0; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .button { padding: 10px 20px; background: #667eea; color: white; border: none; cursor: pointer; border-radius: 5px; }
        .button:hover { background: #764ba2; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>DTR Generation Admin Panel</h1>
    
    <div class="section">
        <h2>Generate DTR</h2>
        <form method="POST">
            <label>Source File:
                <input type="text" name="source_file" value="OSDS-January-2026.xlsx">
            </label><br><br>
            
            <label>Template File:
                <input type="text" name="template_file" value="dtr-jan-2026.xlsx">
            </label><br><br>
            
            <label>Output Directory:
                <input type="text" name="output_dir" value="output">
            </label><br><br>
            
            <button type="submit" class="button">Generate</button>
        </form>
    </div>
</body>
</html>
-->

?>
