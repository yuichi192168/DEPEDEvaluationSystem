# Reclassification Form for Teaching Positions (RFTP)
## User Manual

---

## Title Page

**System Name:** Reclassification Form for Teaching Positions (RFTP)  
**Module:** `reclassification_form.php`  
**Version:** 1.0 (Based on DBM-DepEd JC 01, s. 2025)  
**Author / Developer:** Aljay Plantado  
**Year:** 2026  

---

## Introduction

### Brief Description of the System
The Reclassification Form for Teaching Positions is a web-based module used to evaluate applicants for teaching position reclassification. It helps evaluators verify Qualification Standards, check PPST indicator achievements, and determine if an applicant PASSED or FAILED based on required performance rules.

### Purpose of the System
This module is designed to:
1. Capture required applicant and position details.
2. Auto-load Qualification Standards (QS) based on selected position applied.
3. Compute PPST performance totals and compare them with required rules.
4. Generate a comparative assessment result.
5. Provide print and Excel export options.
6. Allow admin users to manage saved evaluation records.

### Who This Manual Is For
This manual is for:
1. HR evaluators and school/division personnel processing reclassification applications.
2. Administrative staff who need printable and exportable reclassification forms.
3. Admin users who maintain saved evaluation records.
4. Beginner users with limited technical experience.

---

## System Overview

### Main Features
1. **Form Type Selection**
   - Form 1: Teacher II to Teacher VII, Master Teacher I
   - Form 2: Master Teacher II to Master Teacher III
2. **Applicant Information Capture** (name, current position, item number, station, SG/salary, level).
3. **Auto-filled Qualification Standards** based on position applied.
4. **PPST Indicator Checklist** with automatic counts for O and VS ratings.
5. **Automatic Performance Computation**
   - COI and NCOI totals
   - PASSED/FAILED result
6. **Comparative Assessment Result Output**
7. **Action Tables** for Schools Division and Regional Office processing.
8. **Utility Buttons**
   - Evaluate Performance
   - Autofill Last Applicant
   - Clear Form
   - Print
   - Export Excel
9. **Admin Record Management**
   - View saved records
   - Search records
   - Edit record
   - Delete record

### User Roles
1. **Evaluator/User**
   - Opens and fills the reclassification form.
   - Evaluates performance and prints/exports outputs.
   - Uses local draft behavior (automatic browser save while typing).

2. **Admin**
   - Can access additional saved record list in the same page.
   - Can edit and delete existing evaluation records.

---

## System Requirements

### Browser Requirements
Use modern browsers for best results:
1. Google Chrome
2. Microsoft Edge
3. Mozilla Firefox
4. Safari

### Internet Requirements
1. **Local setup (XAMPP):** Internet not required after setup.
2. **Hosted setup:** Stable internet required.
3. **Excel Export Note:** The page loads an external Excel library (`xlsx-js-style`) from CDN. Internet is needed if this library is not cached locally.

### Device Compatibility
1. Desktop/Laptop (recommended for full form and table work).
2. Tablet (supported).
3. Mobile phone (supported, but less convenient for long forms).

---

## Getting Started

### How to Access the Form
1. Start Apache and MySQL in XAMPP.
2. Open your browser.
3. Go to:  
   `http://localhost/DEPEDEvaluationSystem/reclassification_form.php`

**Screenshot: Open RFTP Page**  
`[Insert Screenshot Here: Browser showing reclassification_form.php loaded]`  
**Caption:** Figure 1. Opening the Reclassification Form page.

### Login Instructions
1. The reclassification form page can open without forcing login.
2. Admin-only tools (saved evaluation records management actions) appear when logged in as admin.

### Account Creation (If Applicable)
1. Admin accounts are created during system setup.
2. If needed, run `setup_admin_credentials.php` to create/reset admin access.
3. Use admin login page:  
   `http://localhost/DEPEDEvaluationSystem/admin/login.php`

**Screenshot: Admin Login (Optional for Admin Features)**  
`[Insert Screenshot Here: Admin login page]`  
**Caption:** Figure 2. Admin login used to access record management features.

---

## Dashboard Overview

### What Users Can See
1. **Form Header and Scope**
   - Form metadata and selected form scope.
2. **Applicant Information Table**
   - Basic data and position selection.
3. **Qualification Standards Section**
   - QS of Position (read-only auto-filled) and QS of Applicant fields.
4. **Performance Requirements Table**
   - Required counts per position applied.
5. **PPST Indicators Summary Table**
   - Checkbox-based O/VS selection per indicator.
6. **Performance Data Section**
   - COI/NCOI and total counts (auto-computed).
7. **Action Buttons**
   - Evaluate, Autofill, Clear, Print, Export Excel.
8. **Result Section** (after evaluation)
   - Comparative assessment outcome and office action tables.
9. **Admin Saved Records Section** (admin users only)
   - Searchable list with edit/delete actions.

**Screenshot: Reclassification Form Layout**  
`[Insert Screenshot Here: Full page with major sections visible]`  
**Caption:** Figure 3. Complete RFTP interface overview.

---

## Step by Step Instructions for Each Feature

## Feature: Select Form Type and Position Path
### Purpose
To apply the correct reclassification path and valid position options.

### Steps
1. In **Form Type**, choose:
   - `Form 1 (Teacher II-VII, MT I)`, or
   - `Form 2 (Master Teacher II-III)`
2. Select **Current Position**.
3. Select **Position Applied**.
4. Ensure the position applied is 1 to 3 levels higher than current position.

**Screenshot: Form Type and Position Selection**  
`[Insert Screenshot Here: Form Type, Current Position, Position Applied fields]`  
**Caption:** Figure 4. Selecting the correct form scope and promotion path.

## Feature: Fill Applicant Information
### Purpose
To encode required applicant profile details for evaluation.

### Steps
1. Enter **Name**.
2. Enter **Item Number**.
3. Enter **Station/School**.
4. Enter **SG / Annual Salary**.
5. Select **Level**:
   - Kindergarten
   - Elementary
   - Junior High School
   - Senior High School

**Screenshot: Applicant Information Fields**  
`[Insert Screenshot Here: Filled applicant information section]`  
**Caption:** Figure 5. Required applicant profile details before evaluation.

## Feature: Encode Qualification Standards (QS of Applicant)
### Purpose
To compare applicant qualifications against required QS for the target position.

### Steps
1. Confirm **QS of the Position** fields are auto-filled (read-only).
2. Fill **QS of the Applicant** fields:
   - Education
   - Training
   - Experience
   - Eligibility
   - Competency (if applicable)
3. Optionally add remarks per row.

**Screenshot: Qualification Standards Table**  
`[Insert Screenshot Here: QS table showing auto-filled QS and applicant entries]`  
**Caption:** Figure 6. Comparing required qualification standards with applicant credentials.

## Feature: Mark PPST Indicators (O and VS)
### Purpose
To record indicator achievements used for performance evaluation.

### Steps
1. Go to **Summary of the Achievement of PPST Indicators**.
2. For each indicator row, check either:
   - **O** (Outstanding), or
   - **VS** (Very Satisfactory)
3. Do not mark both O and VS in the same row.
4. Observe auto-updated totals at the bottom:
   - Total Number of O
   - Total Number of VS

**Screenshot: PPST Indicator Checklist**  
`[Insert Screenshot Here: PPST rows with O/VS checkboxes and totals]`  
**Caption:** Figure 7. PPST indicator encoding and automatic totals.

## Feature: Evaluate Performance
### Purpose
To compute the final performance result based on required rules.

### Steps
1. Ensure required fields are complete.
2. Click **Evaluate Performance**.
3. Review the result banner (PASSED/FAILED).
4. Review **III. Comparative Assessment Result** table.

**Screenshot: Evaluation Result Banner and Table**  
`[Insert Screenshot Here: PASSED/FAILED result with assessment table]`  
**Caption:** Figure 8. Evaluation output after clicking Evaluate Performance.

## Feature: Autofill Last Applicant
### Purpose
To quickly reuse or review the most recent saved evaluation data.

### Steps
1. Click **Autofill Last Applicant**.
2. Confirm fields are populated.
3. Edit details if needed.
4. Re-evaluate or update record.

**Screenshot: Autofill Action**  
`[Insert Screenshot Here: Form populated after Autofill Last Applicant]`  
**Caption:** Figure 9. Using auto-fill to load recent applicant data.

## Feature: Clear Form
### Purpose
To reset all current inputs without deleting saved records.

### Steps
1. Click **Clear Form**.
2. Confirm the prompt.
3. Re-enter new applicant data.

**Screenshot: Clear Form Confirmation**  
`[Insert Screenshot Here: Clear form confirmation dialog]`  
**Caption:** Figure 10. Confirmation before resetting form fields.

## Feature: Print Form
### Purpose
To produce a paper-ready copy of the evaluation form and result.

### Steps
1. Complete and evaluate the form.
2. Click **Print**.
3. Choose printer and settings.
4. Click **Print** in browser print dialog.

**Screenshot: Print Preview**  
`[Insert Screenshot Here: Browser print preview of reclassification form]`  
**Caption:** Figure 11. Print-ready layout for official documentation.

## Feature: Export Excel
### Purpose
To download the completed form data in Excel format.

### Steps
1. Complete and evaluate the form.
2. Click **Export Excel**.
3. Wait for file download (`reclassification_form.xlsx`).
4. Open the file in Excel.

**Screenshot: Excel Export Result**  
`[Insert Screenshot Here: Downloaded reclassification_form.xlsx file]`  
**Caption:** Figure 12. Exported Excel file from the completed form.

## Feature: Manage Saved Evaluation Records (Admin)
### Purpose
To maintain saved evaluation entries.

### Steps
1. Log in as admin.
2. Scroll to **Saved Evaluation Records**.
3. Use search box to find records.
4. Click **Edit** to load record into form.
5. Click **Delete** to remove record permanently.

**Screenshot: Admin Saved Records Table**  
`[Insert Screenshot Here: Saved records with search/edit/delete actions]`  
**Caption:** Figure 13. Admin-only record management interface.

---

## Common Tasks

## Task 1: Evaluate a New Applicant
1. Open `reclassification_form.php`.
2. Select form type and positions.
3. Fill applicant details and QS of applicant.
4. Mark PPST O/VS indicators.
5. Click **Evaluate Performance**.
6. Review PASSED/FAILED result.

**Screenshot: End-to-End New Evaluation**  
`[Insert Screenshot Here: Completed new applicant evaluation flow]`  
**Caption:** Figure 14. Typical full evaluation process.

## Task 2: Print an Evaluated Record
1. Complete evaluation first.
2. Click **Print**.
3. Confirm print settings.
4. Print or save as PDF.

**Screenshot: Printed Output View**  
`[Insert Screenshot Here: Final print preview with result section]`  
**Caption:** Figure 15. Final output ready for printing.

## Task 3: Export to Excel for Reporting
1. Complete evaluation data.
2. Click **Export Excel**.
3. Verify downloaded `.xlsx` file.
4. Archive/share file as needed.

**Screenshot: Excel File Download**  
`[Insert Screenshot Here: Browser download bar showing exported file]`  
**Caption:** Figure 16. Exported workbook for digital reporting.

## Task 4: Edit Existing Record (Admin)
1. Log in as admin.
2. Locate record in saved list.
3. Click **Edit**.
4. Modify fields.
5. Click **Update Performance**.

**Screenshot: Admin Edit Record Workflow**  
`[Insert Screenshot Here: Loaded record in edit mode]`  
**Caption:** Figure 17. Updating a previously saved evaluation record.

---

## Troubleshooting

## Common Issue: Required Fields Warning Appears
Possible solutions:
1. Fill all fields marked as required.
2. Ensure one **Level** radio option is selected.
3. Check form type and position selections.

## Common Issue: Position Applied Cannot Be Selected
Possible solutions:
1. Confirm **Current Position** is selected first.
2. Choose a position applied that is 1 to 3 levels higher.
3. Switch Form Type if target position is in another scope.

## Common Issue: Duplicate Applicant Error
Possible solutions:
1. Check if the same Name + Item Number already exists.
2. For existing records, edit the current record instead of creating duplicate.
3. Update item number if applicant has a different valid item entry.

## Common Issue: Excel Export Not Working
Possible solutions:
1. Check internet connection (CDN library needed).
2. Reload page and try again.
3. Try another supported browser.

## Common Issue: Saved Records Not Showing (Admin)
Possible solutions:
1. Confirm admin login is active.
2. Refresh page and check session.
3. Verify backend APIs and database connection are running.

## Common Issue: Print Layout Looks Different
Possible solutions:
1. Use browser print preview.
2. Set scale to default/recommended.
3. Use Chrome or Edge for best print consistency.

---

## Frequently Asked Questions (FAQ)

## 1. Is login required to open the reclassification form?
No. Basic form use is available directly, but admin management features require admin session.

## 2. What is the difference between Form 1 and Form 2?
Form 1 is for Teacher II-VII and Master Teacher I. Form 2 is for Master Teacher II and III transitions.

## 3. Can I choose any position applied?
No. The target position must follow allowed progression (1 to 3 levels above current position).

## 4. Are QS fields entered manually?
Required QS of the position is auto-filled. You enter the applicant's corresponding QS details.

## 5. How is performance result computed?
The system counts PPST O/VS selections into COI/NCOI totals and checks if they meet the required thresholds for the chosen position.

## 6. Can I print and export both?
Yes. You may print the form and also export it to Excel.

## 7. Can I edit a record after saving?
Yes, if you are an admin user and use the Edit action in saved records.

---

## Contact or Support Information

For support, provide:
1. URL of the page.
2. Screenshot of the issue.
3. Applicant name/item number used (if applicable).
4. Exact steps done before error.
5. Browser and date/time.

Recommended support channels:
1. HRMPSB System Administrator
2. Division ICT Support Team
3. Internal Helpdesk

**Support Message Template**
- Subject: `RFTP Module Issue - [Short Description]`
- Include: role, browser, screenshot, and reproduction steps.

---

## Document Notes

This manual is written in simple, beginner-friendly language and is ready for:
1. PDF output
2. Word conversion
3. Website documentation

Before official release, replace screenshot placeholders with actual screenshots and keep captions directly below each image.
