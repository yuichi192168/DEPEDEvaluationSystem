# Automatic .xls to .xlsx Conversion Guide

## Overview

The DTR Generator now includes **automatic conversion** of legacy .xls (Excel 97-2003) files to modern .xlsx format. This eliminates the need for manual file conversion and allows seamless processing of mixed file formats.

## How It Works

When you load the DTR Generator page, the system:

1. **Scans** the `excel-files` folder for all Excel files
2. **Detects** any .xls files automatically
3. **Attempts conversion** using multiple methods (in order):
   - **PhpSpreadsheet**: Built-in PHP library (fastest)
   - **COM Automation**: Uses installed Microsoft Excel (Windows)
   - **LibreOffice**: Uses LibreOffice in headless mode (cross-platform)
4. **Displays results** with green badges for successfully converted files
5. **Processes files** normally - converted files are treated like regular .xlsx files

## Configuration

Edit the settings at the top of `dtr_generator_ui_v2.php` (around line 638):

```php
// Auto-conversion settings for .xls files
$autoConvertXlsFiles = true;      // Enable/disable auto-conversion
$deleteOriginalXlsAfterConversion = false; // Delete .xls after successful conversion
```

### Configuration Options

| Option | Default | Description |
|--------|---------|-------------|
| `$autoConvertXlsFiles` | `true` | Enable automatic conversion of .xls files |
| `$deleteOriginalXlsAfterConversion` | `false` | Delete original .xls files after successful conversion |

## Conversion Methods

### Method 1: PhpSpreadsheet (Primary)
- **Requirement**: None (built-in with Composer)
- **Speed**: Fast ⚡
- **Limitation**: Only works for valid OLE-format .xls files

### Method 2: COM Automation (Fallback #1)
- **Requirement**: Microsoft Excel installed (Windows only)
- **Speed**: Medium
- **Advantage**: Handles corrupted/non-standard .xls files
- **Note**: May trigger Excel Trust Center warnings (see troubleshooting)

### Method 3: LibreOffice (Fallback #2)
- **Requirement**: LibreOffice installed
- **Speed**: Slower
- **Advantage**: Cross-platform, handles most .xls files
- **Install**: Download from https://www.libreoffice.org/download

## Visual Indicators

### Successfully Converted Files
```
Book1.xlsx                          ✓ Auto-converted
Converted from: Book1.xls | Size: 311 KB | Employees: 4
```

### Failed Conversions
```
CorruptedFile.xls                   Invalid
⚠ Auto-conversion failed. File may be corrupted or require Excel/LibreOffice to open.
```

## Troubleshooting

### All conversions fail with "File Block Settings" error

**Cause**: Excel's Trust Center is blocking .xls files

**Solution**:
1. Open Excel
2. Go to **File → Options → Trust Center → Trust Center Settings**
3. Click **File Block Settings**
4. Uncheck "Excel 97-2003 workbooks and templates"
5. Click OK twice
6. Refresh the DTR Generator page

### Some files fail to convert

**Possible causes**:
- File is corrupted
- File is password-protected
- File uses unsupported Excel features

**Solutions**:
1. Try opening the file in Excel/LibreOffice manually
2. Save as .xlsx using Excel (File → Save As)
3. Check if file is password-protected (remove protection first)

### COM/Excel conversion not working

**Check**:
- Microsoft Excel is installed on the server
- PHP can create COM objects (`class_exists('COM')` returns true)
- Excel is not blocked by antivirus or security policies

### LibreOffice conversion not working

**Check**:
- LibreOffice is installed at one of these paths:
  - `C:\Program Files\LibreOffice\program\soffice.exe` (Windows)
  - `C:\Program Files (x86)\LibreOffice\program\soffice.exe` (Windows 32-bit)
  - `/usr/bin/soffice` (Linux)
  - `/usr/bin/libreoffice` (Linux)
- If installed elsewhere, edit the `$libreOfficePaths` array in `autoConvertXlsToXlsx()` function

## Performance

### Conversion Speed
- **PhpSpreadsheet**: ~0.5-2 seconds per file
- **COM/Excel**: ~2-5 seconds per file
- **LibreOffice**: ~3-8 seconds per file

### Tips for Faster Processing
1. Convert files in advance using the PowerShell script
2. Enable `$deleteOriginalXlsAfterConversion` to avoid re-scanning .xls files
3. If you have many files, use batch conversion via LibreOffice CLI

## Manual Conversion (Alternative)

If automatic conversion doesn't work, use one of these methods:

### PowerShell Script (Batch)
```powershell
powershell -ExecutionPolicy Bypass .\convert_xls_to_xlsx.ps1
```

### LibreOffice CLI (Batch)
```bash
soffice --headless --convert-to xlsx --outdir excel-files excel-files/*.xls
```

### Individual File Conversion
1. **Excel**: Open file → File → Save As → Excel Workbook (.xlsx)
2. **LibreOffice**: Open file → File → Save As → Excel 2007-365 (.xlsx)
3. **Google Sheets**: Upload → File → Download → Microsoft Excel (.xlsx)

## Error Messages Reference

| Error Message | Meaning | Solution |
|--------------|---------|----------|
| "Auto-conversion failed. File may be corrupted..." | All conversion methods failed | Try manual conversion or check file integrity |
| "Failed to convert .xls to .xlsx. Tried: ..." | System attempted all methods | Install Excel or LibreOffice for better compatibility |
| ".xls format not supported..." | Auto-conversion is disabled | Enable `$autoConvertXlsFiles = true` |

## Best Practices

1. **Test First**: Try auto-conversion with a few files before processing all
2. **Backup Original Files**: Keep .xls files until you verify .xlsx conversions are correct
3. **Enable Deletion Carefully**: Only enable `$deleteOriginalXlsAfterConversion` after testing
4. **Monitor Logs**: Check PHP error logs for conversion issues
5. **Regular Cleanup**: Delete old .xls files manually after confirming .xlsx files work

## Technical Details

### File Storage
- Converted files are saved in the same folder as original files
- Naming: `filename.xls` → `filename.xlsx`
- If `.xlsx` already exists, original is kept (no overwrite)

### Error Handling
- Graceful degradation: tries multiple conversion methods
- Non-blocking: failed conversions don't stop page load
- User-friendly: clear error messages for troubleshooting

### Security
- Only processes files from `excel-files` folder
- Validates file extensions before conversion
- Uses `escapeshellarg()` for external command calls
- No user input directly passed to system commands

## Support

For issues or questions:
1. Check this guide's troubleshooting section
2. Review `EXCEL_FILE_CONVERSION_GUIDE.md` for manual conversion steps
3. Check PHP error logs at `C:\xampp\php\logs\php_error_log`
4. Verify PhpSpreadsheet is installed: `composer require phpoffice/phpspreadsheet`

---

**Last Updated**: March 5, 2026  
**Version**: 2.0  
**Feature**: Automatic .xls to .xlsx conversion
