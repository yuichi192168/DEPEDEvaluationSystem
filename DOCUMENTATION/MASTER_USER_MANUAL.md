# DepEd HRMPSB Systems Master User Handbook

This master handbook compiles three user manuals into one document:
1. DepEd HRMPSB Evaluation System
2. Reclassification Form for Teaching Positions (RFTP)
3. DTR Generator

**Document Version:** 1.1  
**Last Updated:** March 19, 2026  
**Maintainer:** HRMPSB System Administrator

---

## How to Use This Handbook

1. Use the table of contents to jump to the module you are working on.
2. Follow the **Step by Step Instructions** per feature.
3. Use **Common Tasks** for quick day-to-day workflows.
4. Check **Troubleshooting** and **FAQ** before filing support tickets.
5. Replace all screenshot placeholders before final publication.

---

## Table of Contents

1. Part I - DepEd HRMPSB Evaluation System
2. Part II - Reclassification Form for Teaching Positions (RFTP)
3. Part III - DTR Generator
4. Release Checklist

### Quick Access URLs (Localhost)

1. Main System: `http://localhost/DEPEDEvaluationSystem/`
2. Admin Login: `http://localhost/DEPEDEvaluationSystem/admin/login.php`
3. Reclassification Module: `http://localhost/DEPEDEvaluationSystem/reclassification_form.php`
4. DTR Generator: `http://localhost/DEPEDEvaluationSystem/dtr_generator_redesigned.php`

---

# Part I - DepEd HRMPSB Evaluation System

# DepEd HRMPSB Evaluation System
## User Manual

---

## Title Page

**System Name:** DepEd HRMPSB Evaluation System  
**Version:** 2.1 (Enhanced Version)  
**Author / Developer:** Aljay Plantado  
**Year:** 2026  

---

## Introduction

### Brief Description of the System
The DepEd HRMPSB Evaluation System is a web-based tool used to evaluate applicants for DepEd positions using comparative assessment standards. It helps users compute scores, generate reports, and organize applicant records in a faster and more accurate way.

### Purpose of the System
The system is designed to:
1. Standardize applicant evaluation based on DepEd criteria.
2. Automatically compute scores using baseline qualifications and applicant data.
3. Generate Individual Evaluation Sheet (IES) and Comparative Assessment Result (CAR) outputs.
4. Help offices maintain organized and searchable applicant records.

### Who This Manual Is For
This manual is for:
1. HR staff and evaluators who will encode and assess applicants.
2. School/division personnel who need to generate IES and CAR reports.
3. System administrators who manage applicants, drafts, and archived records.
4. Beginner users with little technical background.

---

## System Overview

### Main Features
1. Position selection with automatic baseline loading.
2. Real-time score preview and total score computation.
3. Multiple output formats: HTML, Word, PDF, Excel, and Text.
4. Save and load draft applications.
5. Generate Individual Evaluation Report (IES).
6. Generate Comparative Assessment Result (CAR).
7. View all submitted results.
8. Admin panel for applicant management, archive/restore, and dashboards.

### User Roles
1. **Evaluator/User**
   - Accesses the main form (`index.php`).
   - Encodes applicant data.
   - Generates reports and comparative assessments.
   - Saves and loads drafts.

2. **Admin**
   - Logs in through `admin/login.php`.
   - Views admin dashboard and statistics.
   - Manages applicants (active/archived).
   - Performs archive and restore operations.

---

## System Requirements

### Browser Requirements
Use any modern browser (latest version recommended):
1. Google Chrome
2. Microsoft Edge
3. Mozilla Firefox
4. Safari

### Internet Requirements
1. **For local setup (XAMPP):** Internet is not required after setup, but local server services must be running.
2. **For hosted setup:** Stable internet connection is required.

### Device Compatibility
1. Desktop or laptop (recommended for full form and report workflow).
2. Tablet (supported for basic use).
3. Mobile phone (supported, but desktop is recommended for encoding many fields).

---

## Getting Started

### How to Access the System
1. Start Apache and MySQL in XAMPP.
2. Open your browser.
3. Go to the main system URL:  
   `http://localhost/DEPEDEvaluationSystem/`

**Screenshot: Open Main System URL**  
`[Insert Screenshot Here: Browser showing main system URL and loaded home form]`  
**Caption:** Figure 1. Opening the DepEd HRMPSB Evaluation System in a browser.

### Login Instructions

#### A. Main Evaluation Form
1. Open `http://localhost/DEPEDEvaluationSystem/`.
2. No login is required for the main evaluator form.

#### B. Admin Login
1. Open `http://localhost/DEPEDEvaluationSystem/admin/login.php`.
2. Enter your admin username or email.
3. Enter your password.
4. Click the **Login** button.

**Screenshot: Admin Login Page**  
`[Insert Screenshot Here: Admin login form with username/email and password fields]`  
**Caption:** Figure 2. Admin login page for secure dashboard access.

### Account Creation (If Applicable)
Admin accounts are created by system setup, not by self-registration.

1. Run the setup script: `setup_admin_credentials.php`.
2. Default admin credentials (initial setup) are usually:
   - Username: `admin`
   - Password: `admin123`
3. Change the default password immediately after first login.

**Screenshot: Admin Credentials Setup Output**  
`[Insert Screenshot Here: Terminal/Browser output of setup_admin_credentials.php]`  
**Caption:** Figure 3. Example output when creating or updating admin credentials.

---

## Dashboard Overview

### A. Main Evaluation Dashboard (`index.php`)
Users can see:
1. Banner and system title.
2. Position Information section.
3. Applicant Qualifications section.
4. Baseline (minimum qualification standards) section.
5. Live Calculation Preview table.
6. Additional Information section.
7. Output Options section.
8. Sticky Action Bar with Save/Load/Generate/View actions.

**Screenshot: Main Evaluation Dashboard**  
`[Insert Screenshot Here: Full main form with major sections visible]`  
**Caption:** Figure 4. Main evaluation dashboard used by evaluators.

### B. Admin Dashboard (`admin/dashboard.php`)
Admins can see:
1. Sidebar navigation.
2. Applicants management area.
3. Draft management access.
4. Statistics and filtering tools.
5. Archive and restore functions.

**Screenshot: Admin Dashboard**  
`[Insert Screenshot Here: Admin dashboard with sidebar and applicants panel]`  
**Caption:** Figure 5. Admin dashboard for management and monitoring.

---

## Step by Step Instructions for Each Feature

## Feature: Login to the Admin System
### Purpose
To securely access admin-only tools and applicant management features.

### Steps
1. Open `http://localhost/DEPEDEvaluationSystem/admin/login.php`.
2. Enter admin username/email.
3. Enter password.
4. Click **Login**.
5. Wait for redirection to the admin dashboard.

**Screenshot: Admin Login Flow**  
`[Insert Screenshot Here: Filled login form before clicking Login]`  
**Caption:** Figure 6. Entering admin credentials before sign-in.

## Feature: Encode an Applicant and Generate IES Report
### Purpose
To evaluate one applicant and generate an Individual Evaluation Sheet.

### Steps
1. Open `http://localhost/DEPEDEvaluationSystem/`.
2. Select **Position Group**.
3. Select **Position**.
4. Confirm auto-filled **Position Applied For** and **Job Group/Salary Grade**.
5. Enter applicant details (name, division office, contact number, etc.).
6. Select education, training, and experience levels.
7. Enter ratings for performance, application of education, application of L&D, and potential.
8. Review baseline values and adjust only if needed.
9. Check the **Live Calculation Preview** table.
10. Select output format (HTML/Word/PDF/Excel/Text).
11. Click **Generate Report**.

**Screenshot: Applicant Encoding Form**  
`[Insert Screenshot Here: Completed applicant form before report generation]`  
**Caption:** Figure 7. Completed applicant information and qualification entries.

**Screenshot: Generated IES Report**  
`[Insert Screenshot Here: Sample generated Individual Evaluation Sheet output]`  
**Caption:** Figure 8. Example IES report after successful generation.

## Feature: Generate Comparative Assessment Result (CAR)
### Purpose
To produce comparative ranking results for applicants.

### Steps
1. Complete applicant data in the main form.
2. Ensure all required fields are filled.
3. Click **Generate CAR** in the sticky action bar.
4. Confirm the action in the confirmation dialog.
5. Open the CAR results page when prompted.

**Screenshot: Generate CAR Button and Confirmation Modal**  
`[Insert Screenshot Here: Generate CAR button and confirmation popup]`  
**Caption:** Figure 9. Confirmation step before generating CAR output.

**Screenshot: CAR Results Page**  
`[Insert Screenshot Here: comparative_assessment_results.php with ranking table]`  
**Caption:** Figure 10. Comparative Assessment Result page showing ranked applicants.

## Feature: Save Draft
### Purpose
To save unfinished applicant data and continue later.

### Steps
1. Fill in available applicant and evaluation information.
2. Click **Save Draft** in the sticky action bar.
3. Wait for success confirmation.

**Screenshot: Save Draft Action**  
`[Insert Screenshot Here: Save Draft button and success message]`  
**Caption:** Figure 11. Saving current form data as a draft.

## Feature: Load Draft
### Purpose
To continue from previously saved draft records.

### Steps
1. Open the main form page.
2. Click **Load Drafts**.
3. Select a draft from the drafts modal/list.
4. Click **Load**.
5. Verify restored values in the form.

**Screenshot: Drafts Modal**  
`[Insert Screenshot Here: Draft list modal with load options]`  
**Caption:** Figure 12. Loading a previously saved application draft.

## Feature: View All Results
### Purpose
To review all comparative assessment entries and rankings.

### Steps
1. Click **View All Results** in the sticky action bar, or open:  
   `http://localhost/DEPEDEvaluationSystem/comparative_assessment_results.php?view=all`
2. Use available filters (if needed).
3. Review applicant rankings and scores.

**Screenshot: View All Results Page**  
`[Insert Screenshot Here: All results listing with score and ranking columns]`  
**Caption:** Figure 13. Full CAR results list for monitoring and reporting.

## Feature: Archive and Restore Applicants (Admin)
### Purpose
To keep active lists clean while preserving history.

### Steps (Archive)
1. Log in to admin panel.
2. Open **Applicants Management**.
3. Search or filter applicants.
4. Select one or multiple applicants.
5. Click **Archive**.
6. Enter archive reason (if prompted).
7. Confirm action.

### Steps (Restore)
1. Open archived applicants tab/list.
2. Select applicant(s) to restore.
3. Click **Restore**.
4. Confirm action.

**Screenshot: Admin Archive/Restore Interface**  
`[Insert Screenshot Here: Admin applicants table with archive/restore controls]`  
**Caption:** Figure 14. Admin tools for archiving and restoring applicant records.

---

## Common Tasks

## Task 1: Evaluate One New Applicant Quickly
1. Open main page.
2. Select position group and position.
3. Fill applicant details.
4. Input qualification levels and ratings.
5. Check live preview total score.
6. Click **Generate Report**.

**Screenshot: Quick Single Evaluation**  
`[Insert Screenshot Here: Form and preview for one applicant]`  
**Caption:** Figure 15. Typical workflow for one applicant evaluation.

## Task 2: Save Work and Continue Later
1. Encode partial data.
2. Click **Save Draft**.
3. Later, click **Load Drafts**.
4. Select saved draft and continue.

**Screenshot: Save and Reload Draft Workflow**  
`[Insert Screenshot Here: Save Draft success then draft load list]`  
**Caption:** Figure 16. Draft workflow for interrupted encoding sessions.

## Task 3: Check Rankings of All Applicants
1. Open **View All Results**.
2. Review total scores and ranking positions.
3. Use filters to narrow by position.

**Screenshot: Ranking Review Screen**  
`[Insert Screenshot Here: CAR ranking table with filters]`  
**Caption:** Figure 17. Reviewing applicant rankings and final scores.

## Task 4: Manage Applicant Records as Admin
1. Log in to admin dashboard.
2. Open applicant management.
3. Archive completed/inactive records.
4. Restore records when needed.

**Screenshot: Admin Applicant Management**  
`[Insert Screenshot Here: Admin panel applicant management page]`  
**Caption:** Figure 18. Administrative management of active and archived records.

---

## Troubleshooting

## Common Issue: Page Does Not Load
Possible solutions:
1. Ensure Apache and MySQL are running in XAMPP.
2. Confirm URL path is correct.
3. Check for local firewall or port conflicts.

## Common Issue: Login Failed (Admin)
Possible solutions:
1. Verify correct username/email and password.
2. Run `setup_admin_credentials.php` to reset credentials.
3. Confirm database is connected and users table exists.

## Common Issue: Position or Baseline Not Auto-Loading
Possible solutions:
1. Re-select position group and position.
2. Refresh browser and try again.
3. Verify `config/baseline_library.php` and related APIs are accessible.

## Common Issue: Cannot Generate Report/CAR
Possible solutions:
1. Fill all required fields marked with `*`.
2. Check numeric fields for valid values.
3. Make sure browser JavaScript is enabled.
4. Review PHP logs for server-side errors.

## Common Issue: Draft Not Appearing
Possible solutions:
1. Ensure draft was saved successfully.
2. Reload page then click **Load Drafts** again.
3. Check database or API endpoints related to drafts.

---

## Frequently Asked Questions (FAQ)

## 1. Do I need to log in to use the main evaluation form?
No. The main form is accessible directly. Only admin pages require login.

## 2. Can I export reports in different formats?
Yes. You can select HTML, Word, PDF, Excel, or Text output formats.

## 3. What happens if I close the page while encoding?
If you saved a draft first, you can reload it later using **Load Drafts**.

## 4. Can I edit auto-loaded baseline values?
Yes. Baseline fields are auto-filled but can be adjusted when needed.

## 5. Can archived applicants be restored?
Yes. Admin users can restore archived records anytime.

## 6. Is the system mobile-friendly?
Yes, but desktop/laptop is recommended for full encoding and easier report handling.

---

## Contact or Support Information

For support, prepare the following before reporting an issue:
1. Exact page/URL where the issue occurred.
2. Screenshot of the error message.
3. Steps you performed before the issue.
4. Date and time of occurrence.

Suggested support channels (organization-defined):
1. HRMPSB System Administrator
2. Division ICT Support Team
3. Internal helpdesk email/phone

**Support Template**
- Subject: `DepEd HRMPSB System Issue - [Short Description]`
- Include: user role, browser, error screenshot, and reproduction steps.

---

## Document Notes

This manual is written in beginner-friendly language and is suitable for:
1. PDF export
2. Word conversion
3. Web documentation publishing

When finalizing this manual for release, replace all screenshot placeholders with actual system screenshots and keep the figure captions below each image.

### Section Change Log (Part I)

1. Standardized wording for beginner-friendly instructions.
2. Confirmed key URLs and admin access flow.
3. Retained screenshot placeholders for controlled documentation release.


---

# Part II - Reclassification Form for Teaching Positions (RFTP)

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

### Section Change Log (Part II)

1. Preserved current reclassification workflow and position path guidance.
2. Kept troubleshooting and FAQ entries aligned for non-technical users.
3. Retained screenshot placeholder format for consistent publishing.


---

# Part III - DTR Generator

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

### Section Change Log (Part III)

1. Maintained upload-process-download workflow instructions.
2. Kept template and batch download guidance explicit for monthly operations.
3. Retained troubleshooting focus for file quality and output generation issues.

---

## Release Checklist

Use this checklist before publishing the handbook to staff:

1. Replace every `[Insert Screenshot Here: ...]` placeholder with actual screenshots.
2. Verify all localhost URLs match the deployment path.
3. Validate admin credentials policy and remove obsolete defaults from distributed copies (if required by policy).
4. Export and proofread PDF and Word versions.
5. Confirm figure numbering and captions are still correct after edits.
6. Have one evaluator and one admin perform a dry run using this handbook.

---

## Revision History

1. **v1.1 (2026-03-19):** Added document control metadata, quick access links, section-level change logs, and release checklist.
2. **v1.0 (2026):** Initial consolidated master handbook for Part I, Part II, and Part III.
