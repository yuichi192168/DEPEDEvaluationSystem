# DTR Generator
## User Manual

---

## Title Page

**System Name:** DTR Generator (Daily Time Record Automation System)  
**Module/File:** `dtr_generator_redesigned.php`  
**Version:** 1.0  
**Author / Developer:** Aljay Plantado  
**Year:** 2026  

---

## Introduction

### Brief Description of the System
The DTR Generator is a web-based tool that helps users generate employee Daily Time Record (DTR) Excel files from source attendance files. It is designed for non-technical users and supports batch processing, file upload, template selection, and downloadable output files.

### Purpose of the System
This module is designed to:
1. Upload and validate attendance source files (`.xlsx`).
2. Process multiple files in one run.
3. Generate DTR output files per employee.
4. Let users download generated files individually or by schedule batch.
5. Reduce manual encoding of DTR forms.

### Who This Manual Is For
This manual is for:
1. HR staff and administrative users preparing DTRs.
2. Office personnel with little technical knowledge.
3. Users who need upload-process-download workflow.
4. Staff managing monthly DTR templates.

---

## System Overview

### Main Features
1. Drag-and-drop or browse upload for Excel files.
2. File list with employee count and file validity status.
3. Search and filter for input and output files.
4. Multi-file batch processing (`Generate DTRs`).
5. Monthly template upload and active template selection.
6. Download output files one by one.
7. Batch download by schedule (`7-4` and `8-5`) as ZIP.
8. Delete single or multiple files (input/output).
9. Dashboard cards for quick statistics.
10. Built-in messages for success, errors, and warnings.

### User Roles
1. **User / Operator**
   - Uploads `.xlsx` source files.
   - Selects template and files.
   - Generates DTR outputs.
   - Downloads results.

2. **Admin / Maintainer (if assigned by organization)**
   - Maintains template library.
   - Cleans input and output folders.
   - Supports troubleshooting and file hygiene.

---

## System Requirements

### Browser Requirements
Use a modern browser:
1. Google Chrome
2. Microsoft Edge
3. Mozilla Firefox
4. Safari

### Internet Requirements
1. **Local XAMPP setup:** Internet is optional for local use.
2. **Important:** The page loads Tailwind CSS from CDN. If internet is unavailable and CDN is blocked, visual styling may not load correctly.

### Device Compatibility
1. Desktop or laptop (recommended).
2. Tablet (supported).
3. Mobile phone (basic use possible, desktop preferred for bulk operations).

### Server/Environment Requirements
1. PHP 8.0+ (recommended).
2. Apache/XAMPP.
3. PhpSpreadsheet installed (`vendor/autoload`).
4. Writable folders:
   - `excel-files/`
   - `output/`
   - `templates/`

---

## Getting Started

### How to Access the System
1. Start Apache in XAMPP.
2. Open your browser.
3. Go to:  
   `http://localhost/DEPEDEvaluationSystem/dtr_generator_redesigned.php`

**Screenshot: Open DTR Generator Page**  
`[Insert Screenshot Here: DTR Generator landing page with header and dashboard cards]`  
**Caption:** Figure 1. DTR Generator main page.

### Login Instructions
1. This page does not require separate login in the current setup.
2. Access is based on local deployment and folder permissions.

### Account Creation (If Applicable)
1. No account creation flow is required for this module.
2. If your organization adds authentication later, follow local admin policy.

---

## Dashboard Overview

### What Users Can See
1. **Getting Started Guide** (4-step visual quick guide).
2. **Dashboard Statistics Cards**
   - Available Files
   - Total Employees
   - Generated Files
   - Active Template
3. **Upload Section (Step 0)**
   - Drag/drop or browse upload
4. **Input File Selection (Step 1)**
   - Search, checkbox select, delete selected
   - Template selector
   - Generate button
5. **Help & Information Panel**
6. **Monthly Templates Panel**
7. **Output Download Section (Step 2)**
   - Search output files
   - Download/delete actions
   - Batch download by schedule

**Screenshot: DTR Dashboard Sections**  
`[Insert Screenshot Here: Full page showing Step 0, Step 1, and Step 2]`  
**Caption:** Figure 2. Main sections of the DTR Generator workflow.

---

## Step by Step Instructions for Each Feature

## Feature: Upload Excel Files
### Purpose
To add source attendance files to the system for processing.

### Steps
1. Go to **0. Upload Excel Files**.
2. Drag and drop `.xlsx` files into the upload area, or click **Browse Files**.
3. Review selected files list.
4. Click **Upload X File(s)**.
5. Wait for success message.

**Screenshot: Upload Files Section**  
`[Insert Screenshot Here: Drag-drop area and selected file list]`  
**Caption:** Figure 3. Uploading input Excel files.

## Feature: Select Template for the Month
### Purpose
To define which DTR template will be used during generation.

### Steps
1. In Step 1, find the **Template for Selected Month** dropdown.
2. Select a template from the list.
3. If template is not available, upload it from **Monthly Templates** panel.
4. Confirm selected template before clicking Generate.

**Screenshot: Template Selector**  
`[Insert Screenshot Here: Template dropdown in processing form]`  
**Caption:** Figure 4. Selecting the active monthly template.

## Feature: Upload Monthly Template
### Purpose
To add a new `.xlsx` DTR template and set it active.

### Steps
1. In the **Monthly Templates** panel, click file picker.
2. Select a `.xlsx` template file.
3. Click **Upload Monthly Template**.
4. Confirm success message and active template label.

**Screenshot: Template Upload Panel**  
`[Insert Screenshot Here: Monthly template upload form and template list]`  
**Caption:** Figure 5. Uploading and activating a monthly DTR template.

## Feature: Select Files to Process
### Purpose
To choose which uploaded files will be included in generation.

### Steps
1. Go to **1. Select Files to Process**.
2. Use search if needed.
3. Tick checkboxes for valid files.
4. Optional: Use **Select All** or **Clear All**.
5. Ensure unreadable files are not selected.

**Screenshot: Input File Selection**  
`[Insert Screenshot Here: Input list with checkboxes and file metadata]`  
**Caption:** Figure 6. Selecting source files for batch processing.

## Feature: Generate DTRs
### Purpose
To process selected files and create DTR outputs.

### Steps
1. Confirm at least one valid file is selected.
2. Confirm template is correct.
3. Click **Generate DTRs**.
4. Wait for completion alert.
5. Check **Latest Result** card for summary.

**Screenshot: Generate DTR Action**  
`[Insert Screenshot Here: Generate button and success alert]`  
**Caption:** Figure 7. Running batch generation and viewing success message.

## Feature: Download Generated File (Single)
### Purpose
To download one generated DTR file.

### Steps
1. Go to **2. Download Generated DTR Files**.
2. Find target file using search if needed.
3. Click **Download** in the file row.
4. Save file to your local computer.

**Screenshot: Output File Table (Single Download)**  
`[Insert Screenshot Here: Output table with Download link]`  
**Caption:** Figure 8. Downloading an individual generated DTR file.

## Feature: Batch Download by Schedule
### Purpose
To download multiple generated files grouped by schedule.

### Steps
1. In output section, look for **Batch Download by Schedule**.
2. Click:
   - `Download All 7-4pm Staff`, or
   - `Download All 8-5pm Staff`
3. Save the generated ZIP file.

**Screenshot: Batch Download Buttons**  
`[Insert Screenshot Here: 7-4 and 8-5 batch download buttons]`  
**Caption:** Figure 9. Downloading grouped files by schedule as ZIP.

## Feature: Delete Input or Output Files
### Purpose
To remove unused or incorrect files.

### Steps (Single Delete)
1. Click **Delete** beside target file.
2. Confirm prompt.

### Steps (Bulk Delete)
1. Tick multiple file checkboxes.
2. Click **Delete Selected**.
3. Confirm prompt.

**Screenshot: Delete Workflow**  
`[Insert Screenshot Here: Selected rows and Delete Selected action]`  
**Caption:** Figure 10. Removing selected files from input/output lists.

---

## Common Tasks

## Task 1: Monthly DTR Generation (Standard)
1. Upload monthly attendance source files (`.xlsx`).
2. Upload or select monthly template.
3. Select all valid files.
4. Click **Generate DTRs**.
5. Download outputs (single or by schedule ZIP).

**Screenshot: Full Monthly Workflow**  
`[Insert Screenshot Here: End-to-end flow from upload to output download]`  
**Caption:** Figure 11. Typical monthly DTR processing workflow.

## Task 2: Replace Wrong Template and Reprocess
1. Upload corrected template.
2. Set it as selected template.
3. Re-select files.
4. Run **Generate DTRs** again.
5. Download updated outputs.

**Screenshot: Template Replacement Task**  
`[Insert Screenshot Here: New template active + regeneration action]`  
**Caption:** Figure 12. Updating template and regenerating files.

## Task 3: Clean Old Generated Files
1. Open output section.
2. Tick old files.
3. Click **Delete Selected**.
4. Confirm deletion.

**Screenshot: Output Cleanup Task**  
`[Insert Screenshot Here: Output cleanup using multi-select delete]`  
**Caption:** Figure 13. Cleaning old output files.

---

## Troubleshooting

## Common Issue: Upload Failed
Possible solutions:
1. Ensure files are `.xlsx` only.
2. Check file names are unique (no same-name duplicate in folder).
3. Confirm `excel-files/` folder is writable.

## Common Issue: File Marked as Not Readable
Possible solutions:
1. Open file manually in Excel to verify it is not corrupted.
2. Re-save file as `.xlsx` and upload again.
3. Remove unreadable files using **Delete Selected**.

## Common Issue: Generate DTRs Button Shows Error
Possible solutions:
1. Select at least one valid file.
2. Ensure selected files are readable.
3. Confirm template exists and is selected.
4. Check PHP/Apache error logs if problem continues.

## Common Issue: No Output Files Generated
Possible solutions:
1. Verify source files contain expected employee data.
2. Confirm template format is valid.
3. Check `output/` folder write permission.

## Common Issue: Download Does Not Start
Possible solutions:
1. Verify file still exists in output list.
2. Disable popup/download blockers for localhost.
3. Try another browser.

## Common Issue: Batch Download ZIP Is Empty
Possible solutions:
1. Confirm schedule naming in output files (`DTR_7-4_...` or `DTR_8-5_...`).
2. Generate files first, then retry batch download.

---

## Frequently Asked Questions (FAQ)

## 1. What file type can I upload?
Only `.xlsx` files are accepted for source files and templates.

## 2. Can I process multiple files at once?
Yes. Select multiple files in Step 1 and click **Generate DTRs**.

## 3. Can I change template every month?
Yes. Upload a new monthly template and select it before processing.

## 4. Where are generated files saved?
Generated files are stored in the `output/` folder and listed in Step 2.

## 5. Can I download all files in one click?
Yes, use schedule batch download buttons when available.

## 6. Why is a file counted but has 0 employees?
The file may not contain valid employee names in expected rows/columns.

## 7. Do I need internet to run this locally?
Core processing is local, but the page style uses online Tailwind CDN.

---

## Contact or Support Information

Before reporting issues, prepare:
1. Screenshot of the error message.
2. File name(s) used.
3. Action performed before the error.
4. Date and time.
5. Browser used.

Suggested support channels:
1. ICT Support Team
2. HRMPSB System Administrator
3. Internal Helpdesk

**Support Ticket Template**
- Subject: `DTR Generator Issue - [Short Description]`
- Include: browser, error screenshot, steps to reproduce, and sample filename.

---

## Document Notes

This manual is written in clear, beginner-friendly language and is suitable for:
1. PDF conversion
2. Word conversion
3. Website documentation

Before final release, replace screenshot placeholders with actual images and keep figure captions directly below each screenshot.
