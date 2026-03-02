# Reclassification Form System - Access Guide

## System Architecture

The reclassification evaluation system now has two separate access points:

### 1. Public Evaluation Form
**URL:** `http://localhost/DEPEDEvaluationSystem/reclassification_form.php`

**Access:** Open to everyone (no login required)

**Features:**
- Complete performance evaluations
- Fill out all qualification standards and performance data
- Submit evaluation results
- Print or export evaluations to Excel
- Saved evaluations are stored in the database

**Who can use it:** 
- Any user can access and complete evaluation forms
- Regular staff members
- Evaluators
- General public

---

### 2. Admin Dashboard
**URL:** `http://localhost/DEPEDEvaluationSystem/reclassification_admin/`

**Access:** Admin Login Required

**Features:**
- View all evaluation records from all users
- Search and filter records by name, position, station, result
- Statistics dashboard showing:
  - Total records
  - Passed evaluations
  - Failed evaluations
  - Pending reviews
- **Admin Actions:**
  - View detailed records
  - Edit any evaluation
  - Delete evaluations
- Real-time record management

**Who can use it:**
- Administrators only
- Requires admin credentials to access

---

## Quick Access

### From Public Form to Admin Dashboard
- Click the **"🔐 Admin Dashboard"** button in the top-right corner of the public form
- You'll be redirected to the admin login page

### From Admin Dashboard to Public Form
- Click **"+ New Evaluation"** button to create a new evaluation
- Click **"← Back to Evaluation Form"** link on the login page

---

## Admin Login Credentials

**Default Admin Account:**
- Username: `admin`
- Password: `admin123`

**Important:** Change the default password after first login for security.

---

## User Flow Examples

### Regular User (No Login Required)
1. Visit `reclassification_form.php`
2. Fill out the evaluation form
3. Submit the evaluation
4. Print or export results
5. Evaluation is automatically saved to database

### Administrator Workflow
1. Visit `reclassification_admin/`
2. Login with admin credentials
3. View dashboard with all evaluation records
4. Search/filter records as needed
5. Click **View** to see full details
6. Click **Edit** to modify an evaluation
7. Click **Delete** to remove a record
8. Logout when finished

---

## Technical Details

### Database Integration
- All evaluations are stored in `performance_evaluations` table
- Records include: name, positions, station, item number, results, performance data
- Each record tracks the creator (user_id) and creation timestamp

### File Locations
- **Public Form:** `/reclassification_form.php`
- **Admin Dashboard:** `/reclassification_admin/index.php`
- **Login API:** `/api/login.php`
- **Records API:** `/api/performance_evaluations_list.php`
- **Save API:** `/api/performance_evaluations_save.php`
- **Delete API:** `/api/performance_evaluations_delete.php`

### Security Features
- Admin authentication required for dashboard access
- Session-based security
- IP blocking for failed login attempts
- Role-based access control (RBAC)
- SQL injection protection via prepared statements
- XSS protection with HTML escaping

---

## Support

For issues or questions:
1. Check browser console for JavaScript errors
2. Verify database connection in `/classes/DBConnection.php`
3. Ensure SQL migration has been run: `/database/migration_add_rbac_performance_evaluations.sql`
4. Check PHP error logs for server-side issues

---

**Last Updated:** March 2, 2026  
**System Version:** 2.0 (RBAC with Public Access)
