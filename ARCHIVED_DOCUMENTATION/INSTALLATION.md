# Installation Guide - Export Features

## Prerequisites

The export functionality (Word, PDF, Excel) requires additional PHP libraries. Follow these steps to install them.

## Step 1: Install Composer

If you don't have Composer installed:

1. Download Composer from: https://getcomposer.org/download/
2. Follow the installation instructions for your operating system
3. Verify installation by running: `composer --version`

## Step 2: Install PHP Libraries

Navigate to your project directory and run:

```bash
composer install
```

This will install the following libraries:
- **PHPWord** - For Word document (.docx) export
- **PhpSpreadsheet** - For Excel spreadsheet (.xlsx) export
- **TCPDF** - For PDF document (.pdf) export

## Step 3: Verify Installation

After running `composer install`, you should see a `vendor/` directory created in your project root.

## Step 4: Test Export Functionality

1. Open the evaluation system in your browser
2. Fill in the evaluation form
3. Select an export format (Word, PDF, or Excel)
4. Click "Generate Evaluation Report"
5. The file should download automatically

## Troubleshooting

### Error: "Class not found" or "Library not installed"

**Solution:** Make sure you've run `composer install` and the `vendor/` directory exists.

### Error: "Composer not found"

**Solution:** Install Composer first (see Step 1).

### Error: Memory limit exceeded

**Solution:** Increase PHP memory limit in `php.ini`:
```ini
memory_limit = 256M
```

### Error: Missing PHP extensions

**Solution:** Enable required PHP extensions:
- `php-xml` or `php-xmlwriter`
- `php-zip`
- `php-gd` (for PDF)
- `php-mbstring`

On Ubuntu/Debian:
```bash
sudo apt-get install php-xml php-zip php-gd php-mbstring
```

On Windows (XAMPP):
- Edit `php.ini` and uncomment:
  - `extension=xml`
  - `extension=zip`
  - `extension=gd`
  - `extension=mbstring`

## Manual Installation (Alternative)

If you cannot use Composer, you can manually download the libraries:

1. **PHPWord**: https://github.com/PHPOffice/PHPWord
2. **PhpSpreadsheet**: https://github.com/PHPOffice/PhpSpreadsheet
3. **TCPDF**: https://github.com/tecnickcom/TCPDF

Place them in a `vendor/` directory and update the autoload paths in `classes/IESExport.php`.

## System Requirements

- PHP 7.4 or higher
- Composer (for installation)
- PHP extensions: XML, ZIP, GD, MBString
- Web server (Apache/Nginx) with write permissions

## Notes

- The export files are generated on-the-fly and sent directly to the browser
- No files are stored on the server
- File names include the applicant name and timestamp for uniqueness

