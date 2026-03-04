# DTR Generator UI v2.0 - User Guide

## Overview
The redesigned DTR Generator features a modern, responsive interface built with Tailwind CSS. This version introduces dual file uploads, improved user experience, and comprehensive output file management.

## New Features

### 1. **Dual File Upload System**
- **Raw Data Upload**: Upload OSDS attendance logs (`.xls` or `.xlsx`)
- **Master Template Upload**: Upload the DepEd DTR template (`.xlsx`)
- Both upload zones support drag-and-drop functionality
- Real-time file selection feedback

### 2. **Modern UI Design**
- Clean, professional interface using Tailwind CSS
- Fully responsive layout (mobile-friendly)
- Grid-based layout: 1 column on mobile, 2 columns on desktop
- No emojis - text-based headers with SVG icons
- Color-coded sections for better visual hierarchy

### 3. **Output Directory Browser**
The system now displays all generated DTR files in a comprehensive table with:
- **File Name**: Full filename with Excel icon
- **File Size**: Human-readable format (KB/MB)
- **Modified Date**: Last modification timestamp
- **Actions**:
  - **Download**: Direct download of individual DTR files
  - **Delete**: Remove files with confirmation prompt

### 4. **Enhanced File Management**
- Automatic employee name extraction after upload
- Dropdown selection for individual employees or bulk generation
- Configurable output directory
- File statistics (total files, total size)

## How to Use

### Step-by-Step Workflow

1. **Upload Raw Data**
   - Drag and drop your OSDS file or click to browse
   - Click "Upload Raw Data File"
   - System will extract and display employee count

2. **Upload Template**
   - Drag and drop the DTR template or click to browse
   - Click "Upload Template File"
   - Confirmation appears after successful upload

3. **Configure Generation Settings**
   - Select source file from dropdown (auto-selects uploaded file)
   - Select template file from dropdown
   - Optional: Choose specific employee or leave for all
   - Select output directory

4. **Generate DTR Files**
   - Click "Generate DTR Files" button
   - Success message displays employee count
   - Files appear in Output Directory Browser

5. **Manage Generated Files**
   - View all generated files in the table
   - Download individual files by clicking Download button
   - Delete unwanted files with confirmation

## Technical Specifications

### Responsive Breakpoints
- **Mobile**: Single column layout (< 1024px)
- **Desktop**: Two-column grid layout (≥ 1024px)

### File Upload Specifications
- **Raw Data**: `.xls` or `.xlsx` files
- **Template**: `.xlsx` files only
- **Max Size**: Determined by PHP settings (default: 8MB)
- **Storage**: Timestamped filenames with `uploaded_raw_` or `uploaded_template_` prefix

### Output Directory Features
- Auto-creates directory if it doesn't exist
- Sorts files by modification date (newest first)
- File size display in KB/MB format
- Delete confirmation to prevent accidental removal

### Security Features
- File type validation (only Excel files accepted)
- Filename sanitization to prevent directory traversal
- Confirmation dialogs for destructive actions
- URL encoding for file operations

## Color Scheme
- **Primary**: Indigo gradient (`#667eea` to `#764ba2`)
- **Raw Upload**: Indigo accent (`#667eea`)
- **Template Upload**: Purple accent (`#764ba2`)
- **Success**: Green (`#4caf50`)
- **Error**: Red (`#f44336`)
- **Info**: Blue (`#2196f3`)

## Browser Compatibility
- Modern browsers with ES6+ support
- Drag-and-drop API support required
- Tailwind CSS loaded via CDN
- No additional JavaScript frameworks required

## File Locations
- **Main UI**: `dtr_generator_ui_v2.php`
- **Legacy UI**: `dtr_generator_ui.php` (still available)
- **Generator Class**: `generate_dtr.php`
- **Output Directory**: `output/` (default)

## Accessing the New UI
Navigate to:
```
http://localhost/DEPEDEvaluationSystem/dtr_generator_ui_v2.php
```

## Differences from v1
| Feature | v1 | v2 |
|---------|----|----|
| UI Framework | Custom CSS | Tailwind CSS |
| File Upload | Single upload zone | Dual upload zones |
| Design Style | Emoji-based | SVG icons |
| Output Management | No file browser | Full file browser with actions |
| Responsive | Basic | Mobile-first grid |
| File Actions | None | Download & Delete |
| Layout | Single column | Adaptive grid |

## Troubleshooting

### Upload Issues
- Ensure file is `.xls` or `.xlsx` format
- Check PHP upload size limits in `php.ini`
- Verify write permissions on root directory

### Generation Errors
- Confirm both source and template files are selected
- Verify output directory has write permissions
- Check that source file has required columns

### Output Directory Empty
- Ensure DTR generation completed successfully
- Check selected output directory name
- Verify files were created in correct location

## Future Enhancements
- Batch download (ZIP all files)
- File preview before download
- Advanced filtering (by date, employee)
- Generation progress indicator
- Email notification after generation

---

**Version**: 2.0  
**Last Updated**: March 4, 2026  
**Author**: DTR Generator Development Team
