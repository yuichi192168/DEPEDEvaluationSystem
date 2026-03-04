<?php
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

echo "=== Checking Template File ===\n";
$templatePath = __DIR__ . '/../dtr-jan-2026.xlsx';

if (!file_exists($templatePath)) {
    echo "Template file not found at: $templatePath\n";
    exit(1);
}

echo "Template file exists, size: " . filesize($templatePath) . " bytes\n";

try {
    $reader = IOFactory::createReader('Xlsx');
    $template = $reader->load($templatePath);
    $sheet = $template->getActiveSheet();
    
    echo "\nTemplate structure:\n";
    echo "Max Row: " . $sheet->getHighestRow() . "\n";
    echo "Max Column: " . $sheet->getHighestColumn() . "\n";
    
    echo "\nFirst 25 rows (all columns):\n";
    for ($i = 1; $i <= 25; $i++) {
        echo sprintf("Row %2d: ", $i);
        for ($col = 'A'; $col <= 'F'; $col++) {
            $val = $sheet->getCell($col . $i)->getValue();
            $display = substr((string)$val, 0, 15);
            echo "[$col: $display] ";
        }
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "Error reading template: " . $e->getMessage() . "\n";
}
?>


