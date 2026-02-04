## Admin Applicants Dashboard - Quick Setup Checklist

### [PRE-INSTALLATION] Pre-Installation
- [ ] Backup your database before proceeding
- [ ] Ensure you have admin access to your MySQL/MariaDB database
- [ ] Have phpMyAdmin or MySQL command-line access ready

### [DATABASE] Database Setup
- [ ] Execute the migration script: `database/migration_add_archiving.sql`
- [ ] Verify new columns added to applicants table:
  - [ ] `archive_status` (ENUM: active, archived)
  - [ ] `archived_at` (TIMESTAMP)
  - [ ] `archive_reason` (VARCHAR)
- [ ] Verify new table created: `archived_applicants_audit`
- [ ] Run SQL to verify:
  ```sql
  DESCRIBE applicants;
  SHOW TABLES LIKE 'archived_applicants_audit';
  ```

### [FILES] Files Created/Modified
- [ ] [COMPLETED] `classes/ApplicantManager.php` - Created
- [ ] [COMPLETED] `api/archive_applicant.php` - Created
- [ ] [COMPLETED] `api/restore_applicant.php` - Created
- [ ] [COMPLETED] `api/get_applicants.php` - Created
- [ ] [COMPLETED] `api/get_applicant_details.php` - Created
- [ ] [COMPLETED] `api/get_applicant_stats.php` - Created
- [ ] [COMPLETED] `api/bulk_archive_applicants.php` - Created
- [ ] [COMPLETED] `admin/index.php` - Created/Updated
- [ ] [COMPLETED] `admin/applicants.php` - Created
- [ ] [COMPLETED] `database/migration_add_archiving.sql` - Created
- [ ] [COMPLETED] `APPLICANTS_DASHBOARD_GUIDE.md` - Created
- [ ] [COMPLETED] `APPLICANTS_DASHBOARD_SETUP.md` - Created (this file)

### [TESTING] Testing the Installation

#### Test 1: Access Admin Dashboard
- [ ] Navigate to `/admin/index.php`
- [ ] Dashboard loads without errors
- [ ] All navigation buttons are visible

#### Test 2: Access Applicants Management
- [ ] Click "Open Dashboard" or navigate to `/admin/applicants.php`
- [ ] Dashboard displays without errors
- [ ] Statistics cards show correct counts
- [ ] Tables display existing applicants

#### Test 3: Search & Filter
- [ ] Enter a search term for applicant name
- [ ] Results filter correctly
- [ ] Position group filter works
- [ ] Combined filters work together

#### Test 4: Archive Individual Applicant
- [ ] Click "📦 Archive" button on an applicant
- [ ] Modal dialog appears
- [ ] Enter archive reason
- [ ] Click confirm
- [ ] Applicant moves to "Archived" tab
- [ ] Archive history shows in applicant details

#### Test 5: Restore Individual Applicant
- [ ] Go to "Archived Applicants" tab
- [ ] Click "↩️ Restore" button
- [ ] Confirm restoration
- [ ] Applicant moves back to active tab
- [ ] Archive history updated

#### Test 6: Bulk Operations
- [ ] Select multiple applicants (checkboxes)
- [ ] Click bulk archive button
- [ ] Enter common reason
- [ ] Confirm bulk operation
- [ ] All selected applicants archived
- [ ] Counts update correctly

#### Test 7: View Applicant Details
- [ ] Click "👁️ View" button on any applicant
- [ ] Modal shows complete details
- [ ] Archive history displays
- [ ] Modal closes properly

#### Test 8: Pagination
- [ ] Add 50+ applicants (or test with existing data)
- [ ] Navigate between pages
- [ ] Page numbers display correctly
- [ ] Correct applicants show on each page

### [CONFIGURATION] Configuration (Optional)

#### Adjust Items Per Page
Edit `admin/applicants.php` line ~18:
```php
$itemsPerPage = 25;  // Change this number
```

#### Add Authentication
Before production, add to `admin/applicants.php`:
```php
// After session_start()
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    die('Unauthorized access');
}
```

### [DEPLOYMENT] Deployment Checklist

- [ ] Database migration successfully executed
- [ ] All files properly uploaded to server
- [ ] File permissions correct (typically 644 for PHP files)
- [ ] API endpoints accessible and responding
- [ ] Frontend loads and functions properly
- [ ] Search, filter, and bulk operations working
- [ ] Archive/restore operations complete successfully
- [ ] Audit trail recording properly
- [ ] Statistics updating in real-time
- [ ] Pagination working with large datasets

### [DOCUMENTATION] Documentation
- [ ] Read `APPLICANTS_DASHBOARD_GUIDE.md` for full documentation
- [ ] Review API endpoints documentation
- [ ] Understand database schema changes
- [ ] Familiarize with best practices

### [SECURITY] Security Post-Setup

- [ ] Implement proper authentication/authorization
- [ ] Restrict admin access to authorized users only
- [ ] Set up regular database backups
- [ ] Monitor audit logs regularly
- [ ] Configure logging for all admin actions
- [ ] Test permission restrictions

### [SUPPORT] Support Resources

**If you encounter issues:**

1. **Database Migration Fails**
   - Check database credentials
   - Verify database exists and is accessible
   - Review MySQL error message carefully
   - Try importing in phpMyAdmin instead

2. **Features Not Working**
   - Check browser console (F12 → Console tab)
   - Verify API endpoints are accessible
   - Check PHP error logs
   - Ensure database tables exist

3. **Performance Issues**
   - Archive old applicants to reduce active list
   - Increase database index on archive_status
   - Adjust items per page if needed
   - Monitor database size

4. **Custom Modifications**
   - See ApplicantManager class for core logic
   - API endpoints in /api/ folder
   - Frontend interface in admin/applicants.php
   - Database schema in migration file

### [COMPLETION] Final Sign-Off

- [ ] All tests passed
- [ ] Team trained on new features
- [ ] Documentation reviewed
- [ ] Security measures in place
- [ ] Database backed up
- [ ] Ready for production use

---

## Quick Start Command

### If using MySQL CLI:
```bash
# Connect to MySQL
mysql -u your_username -p

# Select database
USE deped_evaluation;

# Run migration
SOURCE /path/to/database/migration_add_archiving.sql;

# Verify
DESCRIBE applicants;
```

### If using phpMyAdmin:
1. Go to phpMyAdmin → deped_evaluation database
2. Click SQL tab
3. Copy content from `database/migration_add_archiving.sql`
4. Paste into SQL editor
5. Click Go

---

**Setup Completed!** Your admin applicants management dashboard is now ready to use. Navigate to `/admin/index.php` to get started!
