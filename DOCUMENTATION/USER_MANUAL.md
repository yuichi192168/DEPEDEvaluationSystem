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
