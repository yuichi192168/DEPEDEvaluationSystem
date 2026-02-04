# Export Features - DepEd HRMPSB Evaluation System

## Overview

The system now supports exporting evaluation reports in multiple formats:
- **Word Document** (.docx)
- **PDF Document** (.pdf)
- **Excel Spreadsheet** (.xlsx)
- **HTML** (View in browser)
- **Plain Text** (.txt)

## Quick Start

### 1. Install Dependencies

```bash
composer install
```

This installs:
- PHPWord (for Word export)
- PhpSpreadsheet (for Excel export)
- TCPDF (for PDF export)

### 2. Use Export Feature

1. Fill in the evaluation form
2. Select export format from dropdown:
   - Word Document (.docx)
   - PDF Document (.pdf)
   - Excel Spreadsheet (.xlsx)
   - HTML (View in Browser)
   - Plain Text (.txt)
3. Click "Generate Evaluation Report"
4. File downloads automatically

## Export Formats Details

### Word Document (.docx)
- Professional formatting matching Annex G format
- Includes all applicant information
- Complete evaluation table
- Attestation section
- Signature lines
- Ready for printing and signing

### PDF Document (.pdf)
- Print-ready format
- Exact Annex G layout
- Professional appearance
- Suitable for official documents
- Can be digitally signed

### Excel Spreadsheet (.xlsx)
- Data in spreadsheet format
- Easy to edit and manipulate
- Can be imported into other systems
- Useful for data analysis
- All calculations preserved

### HTML Format
- View directly in browser
- Can be printed from browser
- Can be saved as HTML file
- No additional libraries needed

### Plain Text (.txt)
- Simple text format
- Compatible with all systems
- Easy to copy/paste
- No formatting dependencies

## File Naming Convention

Exported files are automatically named:
```
IES_[ApplicantName]_[YYYYMMDDHHMMSS].[extension]
```

Example:
- `IES_Juan_Dela_Cruz_20241201143025.docx`
- `IES_Juan_Dela_Cruz_20241201143025.pdf`
- `IES_Juan_Dela_Cruz_20241201143025.xlsx`

## Features Included in All Formats

✅ Complete applicant information
✅ Position details
✅ Evaluation table with all criteria
✅ Computation column (e.g., "6-6=0")
✅ Weight allocation
✅ Actual scores
✅ Total score
✅ Attestation paragraphs
✅ Signature sections

## Troubleshooting

### "Library not installed" Error

**Solution:** Run `composer install` in the project directory.

### "Composer not found" Error

**Solution:** Install Composer first. See INSTALLATION.md for details.

### Export Fails or Shows Blank Page

**Possible Causes:**
1. PHP memory limit too low
2. Missing PHP extensions
3. Write permissions issue

**Solutions:**
- Increase `memory_limit` in php.ini
- Install required PHP extensions (xml, zip, gd, mbstring)
- Check file permissions

### File Downloads but Won't Open

**Solution:** Make sure you have the correct software:
- Word files: Microsoft Word or LibreOffice Writer
- PDF files: Adobe Reader or any PDF viewer
- Excel files: Microsoft Excel or LibreOffice Calc

## Technical Details

### Libraries Used

1. **PHPWord** (phpoffice/phpword)
   - Version: ^1.2
   - Purpose: Generate Word documents

2. **PhpSpreadsheet** (phpoffice/phpspreadsheet)
   - Version: ^1.29
   - Purpose: Generate Excel spreadsheets

3. **TCPDF** (tecnickcom/tcpdf)
   - Version: ^6.6
   - Purpose: Generate PDF documents

### File Locations

- Export handler: `classes/IESExport.php`
- Composer config: `composer.json`
- Vendor libraries: `vendor/` (created after composer install)

## Notes

- Export files are generated on-the-fly
- No files are stored on the server
- Each export is unique (includes timestamp)
- All formats maintain the same data and structure
- Formats are optimized for their respective use cases

## Support

For installation issues, see `INSTALLATION.md`.
For general system usage, see `README.md`.

