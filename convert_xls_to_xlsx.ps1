# Convert all .xls files to .xlsx format
# Place this script in the project root directory

param(
    [string]$FolderPath = ".\excel-files"
)

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "Excel .xls to .xlsx Converter" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# Check if folder exists
if (-not (Test-Path $FolderPath)) {
    Write-Host "ERROR: Folder not found: $FolderPath" -ForegroundColor Red
    exit 1
}

# Get absolute path
$FolderPath = Resolve-Path $FolderPath

Write-Host "Scanning folder: $FolderPath" -ForegroundColor Yellow
Write-Host ""

# Find all .xls files (excluding .xlsx)
$xlsFiles = Get-ChildItem -Path $FolderPath -Filter "*.xls" | Where-Object { $_.Extension -eq ".xls" }

if ($xlsFiles.Count -eq 0) {
    Write-Host "No .xls files found to convert." -ForegroundColor Green
    Write-Host "All files are already in .xlsx format!" -ForegroundColor Green
    exit 0
}

Write-Host "Found $($xlsFiles.Count) .xls file(s) to convert:" -ForegroundColor Yellow
$xlsFiles | ForEach-Object { Write-Host "  - $($_.Name)" -ForegroundColor Gray }
Write-Host ""

# Ask for confirmation
$confirm = Read-Host "Do you want to convert these files to .xlsx? (Y/N)"
if ($confirm -ne "Y" -and $confirm -ne "y") {
    Write-Host "Conversion cancelled." -ForegroundColor Yellow
    exit 0
}

Write-Host ""
Write-Host "Starting conversion..." -ForegroundColor Cyan
Write-Host ""

# Create Excel COM object
try {
    $excel = New-Object -ComObject Excel.Application
    $excel.Visible = $false
    $excel.DisplayAlerts = $false
} catch {
    Write-Host "ERROR: Could not start Excel application." -ForegroundColor Red
    Write-Host "Make sure Microsoft Excel is installed on this computer." -ForegroundColor Red
    exit 1
}

$successCount = 0
$errorCount = 0
$errorFiles = @()

foreach ($file in $xlsFiles) {
    try {
        Write-Host "Converting: $($file.Name)..." -ForegroundColor White -NoNewline
        
        # Open the workbook
        $workbook = $excel.Workbooks.Open($file.FullName)
        
        # Create new filename with .xlsx extension
        $xlsxPath = Join-Path $FolderPath ($file.BaseName + ".xlsx")
        
        # Check if .xlsx already exists
        if (Test-Path $xlsxPath) {
            Write-Host " SKIPPED (already exists)" -ForegroundColor Yellow
            $workbook.Close($false)
            continue
        }
        
        # Save as .xlsx (51 = xlOpenXMLWorkbook)
        $workbook.SaveAs($xlsxPath, 51)
        $workbook.Close($false)
        
        Write-Host " SUCCESS" -ForegroundColor Green
        $successCount++
        
    } catch {
        Write-Host " FAILED" -ForegroundColor Red
        Write-Host "  Error: $($_.Exception.Message)" -ForegroundColor Red
        $errorCount++
        $errorFiles += $file.Name
    }
}

# Cleanup
$excel.Quit()
[System.Runtime.Interopservices.Marshal]::ReleaseComObject($excel) | Out-Null
[System.GC]::Collect()
[System.GC]::WaitForPendingFinalizers()

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "Conversion Complete!" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Results:" -ForegroundColor Yellow
Write-Host "  Success: $successCount" -ForegroundColor Green
Write-Host "  Failed: $errorCount" -ForegroundColor Red
Write-Host ""

if ($errorCount -gt 0) {
    Write-Host "Failed files:" -ForegroundColor Red
    $errorFiles | ForEach-Object { Write-Host "  - $_" -ForegroundColor Red }
    Write-Host ""
}

if ($successCount -gt 0) {
    Write-Host "Next steps:" -ForegroundColor Yellow
    Write-Host "1. Verify the .xlsx files open correctly" -ForegroundColor Gray
    Write-Host "2. Refresh the DTR Generator page in your browser" -ForegroundColor Gray
    Write-Host "3. Files should now appear as valid for processing" -ForegroundColor Gray
    Write-Host "4. Delete original .xls files after confirming .xlsx works" -ForegroundColor Gray
}

Write-Host ""
