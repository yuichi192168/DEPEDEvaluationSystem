# Convert .xls to .xlsx using LibreOffice
# Install LibreOffice first from: https://www.libreoffice.org/download

param(
    [string]$FolderPath = ".\excel-files",
    [string]$LibreOfficePath = "C:\Program Files\LibreOffice\program\soffice.exe"
)

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "LibreOffice .xls to .xlsx Converter" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# Check if LibreOffice exists
if (-not (Test-Path $LibreOfficePath)) {
    Write-Host "ERROR: LibreOffice not found at: $LibreOfficePath" -ForegroundColor Red
    Write-Host "Please install LibreOffice or update the path in this script." -ForegroundColor Red
    Write-Host "Download from: https://www.libreoffice.org/download" -ForegroundColor Yellow
    exit 1
}

# Check if folder exists
if (-not (Test-Path $FolderPath)) {
    Write-Host "ERROR: Folder not found: $FolderPath" -ForegroundColor Red
    exit 1
}

$FolderPath = Resolve-Path $FolderPath

Write-Host "Scanning folder: $FolderPath" -ForegroundColor Yellow
Write-Host ""

# Find all .xls files
$xlsFiles = Get-ChildItem -Path $FolderPath -Filter "*.xls" | Where-Object { $_.Extension -eq ".xls" }

if ($xlsFiles.Count -eq 0) {
    Write-Host "No .xls files found to convert." -ForegroundColor Green
    exit 0
}

Write-Host "Found $($xlsFiles.Count) .xls file(s) to convert:" -ForegroundColor Yellow
$xlsFiles | ForEach-Object { Write-Host "  - $($_.Name)" -ForegroundColor Gray }
Write-Host ""

# Ask for confirmation
$confirm = Read-Host "Do you want to convert these files to .xlsx using LibreOffice? (Y/N)"
if ($confirm -ne "Y" -and $confirm -ne "y") {
    Write-Host "Conversion cancelled." -ForegroundColor Yellow
    exit 0
}

Write-Host ""
Write-Host "Starting conversion..." -ForegroundColor Cyan
Write-Host ""

$successCount = 0
$errorCount = 0

foreach ($file in $xlsFiles) {
    try {
        Write-Host "Converting: $($file.Name)..." -ForegroundColor White -NoNewline
        
        # Check if .xlsx already exists
        $xlsxPath = Join-Path $FolderPath ($file.BaseName + ".xlsx")
        if (Test-Path $xlsxPath) {
            Write-Host " SKIPPED (already exists)" -ForegroundColor Yellow
            continue
        }
        
        # Convert using LibreOffice command line
        # --headless: run without GUI
        # --convert-to xlsx: output format
        # --outdir: output directory
        $process = Start-Process -FilePath $LibreOfficePath `
            -ArgumentList "--headless --convert-to xlsx --outdir `"$FolderPath`" `"$($file.FullName)`"" `
            -Wait -PassThru -WindowStyle Hidden
        
        if ($process.ExitCode -eq 0 -and (Test-Path $xlsxPath)) {
            Write-Host " SUCCESS" -ForegroundColor Green
            $successCount++
        } else {
            Write-Host " FAILED (exit code: $($process.ExitCode))" -ForegroundColor Red
            $errorCount++
        }
        
    } catch {
        Write-Host " FAILED" -ForegroundColor Red
        Write-Host "  Error: $($_.Exception.Message)" -ForegroundColor Red
        $errorCount++
    }
}

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "Conversion Complete!" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Results:" -ForegroundColor Yellow
Write-Host "  Success: $successCount" -ForegroundColor Green
Write-Host "  Failed: $errorCount" -ForegroundColor Red
Write-Host ""

if ($successCount -gt 0) {
    Write-Host "Next steps:" -ForegroundColor Yellow
    Write-Host "1. Refresh the DTR Generator page in your browser" -ForegroundColor Gray
    Write-Host "2. Files should now appear as valid for processing" -ForegroundColor Gray
    Write-Host "3. Delete original .xls files after confirming .xlsx works" -ForegroundColor Gray
}

Write-Host ""
