# Admin Authentication System - Implementation Checklist

## 📋 Pre-Implementation Verification

### Environment Requirements
- [ ] PHP 7.0+ installed (check: `php -v`)
- [ ] MySQL/MariaDB running (check via phpMyAdmin)
- [ ] XAMPP/Apache running
- [ ] PHP sessions enabled (check: `php.ini` has `session.save_path`)
- [ ] Database connection working

### File Verification
- [ ] `classes/DBConnection.php` exists and has correct credentials
- [ ] Workspace is: `c:\xampp\htdocs\DEPEDEvaluationSystemV2`
- [ ] `admin/` folder exists
- [ ] `classes/` folder exists
- [ ] `database/` folder exists

---

## 🔧 Implementation Steps

### Step 1: Database Migration
**Status**: ⬜ NOT STARTED → ✅ COMPLETED

**File**: `database/migration_add_user_authentication.sql`

**Action**: Execute the migration script using ONE of these methods:

**Method A: phpMyAdmin (Easiest)**
- [ ] Open phpMyAdmin in browser: `http://localhost/phpmyadmin`
- [ ] Select your DepEd database from left sidebar
- [ ] Click "SQL" tab at top
- [ ] Open/Copy file: `database/migration_add_user_authentication.sql`
- [ ] Paste entire content into SQL text box
- [ ] Click "Go" button (blue button at bottom)
- [ ] See message: "Query successful"

**Method B: MySQL Command Line**
- [ ] Open Command Prompt/PowerShell
- [ ] Navigate to: `c:\xampp\htdocs\DEPEDEvaluationSystemV2`
- [ ] Run: `mysql -u root -p your_database < database/migration_add_user_authentication.sql`
- [ ] Enter password (usually blank for XAMPP)
- [ ] See: `Query OK` messages

**Method C: MySQL GUI Tool**
- [ ] Open MySQL Workbench or similar
- [ ] Connect to your database
- [ ] File → Open SQL Script
- [ ] Select: `database/migration_add_user_authentication.sql`
- [ ] Execute all queries
- [ ] Verify: All queries completed without errors

**Verification**: After running migration:
```sql
-- Run these queries in phpMyAdmin to verify

-- Check 1: users table exists
DESC users;
-- Expected: Shows 10 columns

-- Check 2: login_audit table exists
DESC login_audit;
-- Expected: Shows 9 columns

-- Check 3: admin user exists
SELECT username, email, role FROM users WHERE username='admin';
-- Expected: Returns 1 row with admin/admin@deped.gov.ph/admin
```

- [ ] **Migration completed successfully**

---

### Step 2: Verify Files Were Updated

**Check admin pages have authentication**:
- [ ] Open: `admin/index.php`
  - Search for: `AuthenticationHelper`
  - Should find: `require_once(__DIR__ . '/../classes/AuthenticationHelper.php');`
  
- [ ] Open: `admin/applicants.php`
  - Search for: `$auth->requireAdmin()`
  - Should find: One instance at top of file

- [ ] Open: `admin/login.php`
  - File should exist
  - Should contain: HTML form with email and password fields

- [ ] Open: `admin/logout.php`
  - File should exist
  - Should contain: Session destruction and redirect

**Check API endpoints have protection**:
- [ ] Open: `api/archive_applicant.php`
  - Search for: `$auth->isAdmin()`
  - Should find: Admin check with 403 error

- [ ] Open: `api/bulk_archive_applicants.php`
  - Search for: `$auth->isAdmin()`
  - Should find: Admin check

- [ ] All 6 protected APIs have been updated:
  - [ ] `archive_applicant.php`
  - [ ] `bulk_archive_applicants.php`
  - [ ] `get_applicants.php`
  - [ ] `get_applicant_details.php`
  - [ ] `get_applicant_stats.php`
  - [ ] `restore_applicant.php`

- [ ] **All files verified**

---

### Step 3: Test Login System

**Test 1: Access Login Page**
- [ ] Open browser
- [ ] Go to: `http://localhost/xampp/htdocs/DEPEDEvaluationSystemV2/admin/login.php`
- [ ] Expected: See login form with email/username and password fields
- [ ] Expected: See "DepEd HRMPSB Evaluation System" header
- [ ] Expected: See "Admin Login" title

**Test 2: Login with Correct Credentials**
- [ ] On login page, enter:
  - [ ] Email/Username: `admin`
  - [ ] Password: `admin123`
- [ ] Click "Login" button
- [ ] Expected: Page loads and redirects to `/admin/index.php`
- [ ] Expected: See "Admin Dashboard" title
- [ ] Expected: Header shows "Logged in as: Administrator"
- [ ] Expected: See "Logout" button in top right

**Test 3: Test Invalid Credentials**
- [ ] Go back to login page: `http://localhost/xampp/htdocs/DEPEDEvaluationSystemV2/admin/login.php`
- [ ] Enter:
  - [ ] Email/Username: `admin`
  - [ ] Password: `wrongpassword`
- [ ] Click "Login"
- [ ] Expected: Stay on login page
- [ ] Expected: See error message "Invalid email/username or password"
- [ ] Expected: No session created

**Test 4: Test Invalid Username**
- [ ] On login page, enter:
  - [ ] Email/Username: `nonexistent`
  - [ ] Password: `admin123`
- [ ] Click "Login"
- [ ] Expected: See error message
- [ ] Expected: Stay on login page

**Test 5: Dashboard Access After Login**
- [ ] (Make sure logged in from Test 2)
- [ ] Click "Open Dashboard" button OR go to: `/admin/applicants.php`
- [ ] Expected: Page loads with applicant list
- [ ] Expected: Header shows your name
- [ ] Expected: Logout button visible
- [ ] Expected: All features work

**Test 6: Protected Page Without Login**
- [ ] Open new browser tab (or open in Incognito)
- [ ] Go to: `http://localhost/xampp/htdocs/DEPEDEvaluationSystemV2/admin/applicants.php`
- [ ] Expected: Redirect to login page
- [ ] Expected: Cannot access dashboard without login

**Test 7: Logout Functionality**
- [ ] (Make sure logged in from Test 2)
- [ ] Click "Logout" button
- [ ] Expected: Redirect to login page
- [ ] Expected: See message "You have been logged out successfully"
- [ ] Try to access `/admin/applicants.php` directly
- [ ] Expected: Redirect to login page again

**Test 8: Session Persistence**
- [ ] Login with `admin/admin123`
- [ ] Go to: `/admin/index.php`
- [ ] Verify logged in
- [ ] Go to: `/admin/applicants.php`
- [ ] Expected: Still logged in (user name visible)
- [ ] Refresh page with F5
- [ ] Expected: Still logged in
- [ ] Open different page in new tab
- [ ] Expected: Still logged in

- [ ] **All login tests passed**

---

### Step 4: Test API Protection

**Test 1: Get Applicants API Without Auth**
- [ ] Open new browser tab in Incognito mode
- [ ] Go to: `http://localhost/xampp/htdocs/DEPEDEvaluationSystemV2/api/get_applicants.php`
- [ ] Expected: See JSON error with message "Unauthorized: Admin access required"
- [ ] Expected: HTTP 403 status

**Test 2: API Access With Auth**
- [ ] (Make sure logged in from previous test)
- [ ] Go to: `http://localhost/xampp/htdocs/DEPEDEvaluationSystemV2/api/get_applicants.php`
- [ ] Expected: See JSON response with applicant data
- [ ] Expected: Works normally

**Test 3: Archive API Protection**
- [ ] In Incognito (not logged in)
- [ ] Try to access: `/api/archive_applicant.php`
- [ ] Expected: JSON error with 403 status
- [ ] Verify authentication required

- [ ] **API protection verified**

---

### Step 5: Database Audit Trail Verification

**Check Login Audit Log**
- [ ] Open phpMyAdmin
- [ ] Select your DepEd database
- [ ] Click "login_audit" table
- [ ] Browse data
- [ ] Expected: See entries for your login attempts
- [ ] Columns should show:
  - [ ] `user_id` - 1
  - [ ] `username` - "admin"
  - [ ] `login_status` - "success" or "failed"
  - [ ] `ip_address` - Your IP (e.g., 127.0.0.1)
  - [ ] `user_agent` - Your browser info
  - [ ] `attempted_at` - Timestamp

- [ ] **Audit trail verified**

---

### Step 6: Security Verification

**Test 1: Password Hashing**
- [ ] Open phpMyAdmin
- [ ] Select: users table
- [ ] Look at "password" column for admin user
- [ ] Expected: See a hash starting with `$2y$10$` (BCrypt)
- [ ] Expected: NOT plain text "admin123"

**Test 2: Session Security**
- [ ] Login and open browser dev tools (F12)
- [ ] Go to Application → Cookies
- [ ] Check for `PHPSESSID` cookie
- [ ] Expected: Cookie exists with secure session ID
- [ ] Close browser
- [ ] Reopen and go to `/admin/applicants.php`
- [ ] Expected: Not logged in anymore (session destroyed)

**Test 3: IP Blocking**
- [ ] Go to login page
- [ ] Try wrong password 5 times in quick succession
- [ ] On 6th attempt
- [ ] Expected: Error message "Too many login attempts from your IP"
- [ ] Wait 15 minutes OR clear `login_audit` table for your IP
- [ ] Try again
- [ ] Expected: Can login normally

- [ ] **Security verified**

---

## ✅ Final Verification

### System Status
- [ ] Database migration executed
- [ ] Login page accessible
- [ ] Admin can login with `admin/admin123`
- [ ] Dashboard protected (requires login)
- [ ] User name displays in header
- [ ] Logout works properly
- [ ] API endpoints require authentication
- [ ] Audit trail recording events
- [ ] Password is hashed (not plain text)
- [ ] Session timeout working

### Files Status
- [ ] ✅ `classes/AuthenticationHelper.php` - Created
- [ ] ✅ `admin/login.php` - Created
- [ ] ✅ `admin/logout.php` - Created
- [ ] ✅ `admin/index.php` - Updated with protection
- [ ] ✅ `admin/applicants.php` - Updated with protection
- [ ] ✅ `admin/drafts.php` - Updated with protection
- [ ] ✅ `api/archive_applicant.php` - Updated with protection
- [ ] ✅ `api/bulk_archive_applicants.php` - Updated with protection
- [ ] ✅ `api/get_applicants.php` - Updated with protection
- [ ] ✅ `api/get_applicant_details.php` - Updated with protection
- [ ] ✅ `api/get_applicant_stats.php` - Updated with protection
- [ ] ✅ `api/restore_applicant.php` - Updated with protection
- [ ] ✅ `database/migration_add_user_authentication.sql` - Created
- [ ] ✅ `ADMIN_AUTHENTICATION_SETUP.md` - Created
- [ ] ✅ `AUTHENTICATION_IMPLEMENTATION_SUMMARY.md` - Created
- [ ] ✅ `AUTHENTICATION_QUICK_REFERENCE.md` - Created
- [ ] ✅ `AUTHENTICATION_IMPLEMENTATION_CHECKLIST.md` - Created

---

## 🚀 Production Deployment

Before going live, complete these additional steps:

- [ ] **Change default password**
  - [ ] Login as admin
  - [ ] Change password from `admin123` to strong password (12+ chars, mixed case, numbers, symbols)
  
- [ ] **Create additional admin users** (if needed)
  - [ ] Add users to `users` table
  - [ ] Use `AuthenticationHelper::hashPassword()` to hash passwords
  
- [ ] **Configure HTTPS**
  - [ ] Use SSL certificate for admin pages
  - [ ] Set secure cookie flag in `php.ini`
  
- [ ] **Review audit logs**
  - [ ] Check `login_audit` table regularly
  - [ ] Look for suspicious patterns
  
- [ ] **Set session timeout**
  - [ ] Configure in `classes/AuthenticationHelper.php`
  - [ ] Default: 3600 seconds (1 hour)
  
- [ ] **Backup database**
  - [ ] Export `users` and `login_audit` tables
  - [ ] Keep encrypted backup
  
- [ ] **Document credentials**
  - [ ] Store admin credentials securely
  - [ ] Keep backup login info in safe location

---

## 🐛 Troubleshooting During Testing

### Issue: "Database connection failed"
**Solution**:
1. Check `classes/DBConnection.php` has correct host/user/password
2. Verify MySQL service is running
3. Test connection: phpMyAdmin should open
4. Check database name is correct

### Issue: "Login page shows 404"
**Solution**:
1. Check file exists: `admin/login.php`
2. Check path is correct
3. Check file was created successfully
4. Refresh browser (hard refresh with Ctrl+Shift+R)

### Issue: "Login doesn't work, stays on login page"
**Solution**:
1. Verify migration was executed
2. Check admin user exists: `SELECT * FROM users WHERE username='admin';`
3. Check PHP errors: Look in `php error log`
4. Try clearing browser cookies
5. Try different browser

### Issue: "Get 'Class not found: AuthenticationHelper' error"
**Solution**:
1. Check file exists: `classes/AuthenticationHelper.php`
2. Check require_once path is correct
3. Check file has `<?php class AuthenticationHelper {`
4. Check spelling is exact (case-sensitive on Linux)

### Issue: "Sessions not persisting"
**Solution**:
1. Check PHP sessions enabled: `phpinfo()` and search "session"
2. Check `session.save_path` is writable: `chmod 755 /path/to/session`
3. Clear browser cookies completely
4. Try different browser
5. Check `session_start()` is at top of file

### Issue: "Cannot see user name in header"
**Solution**:
1. Verify you're logged in (check `$_SESSION` in code)
2. Check `$currentUser` variable is set
3. Check PHP `htmlspecialchars()` is working
4. Try login again

---

## 📞 Quick Support

| Problem | Check |
|---------|-------|
| Login not working | Migration executed? Admin user exists? |
| Page redirects to login | Logged in? Session valid? |
| API returns 403 | Logged in as admin? Check role in database |
| Password wrong | Using `admin123`? Not `admin`? |
| IP blocked | Wait 15 mins or clear `login_audit` for your IP |
| Cannot access database | MySQL running? Correct host/user/password? |

---

## 📝 Sign-Off Checklist

When all tests pass, sign here:

**Tester Name**: ___________________

**Date Tested**: ___________________

**Database Migration Executed**: _____ (Date/Time)

**Login Test Passed**: _____ (Date/Time)

**Dashboard Protection Verified**: _____ (Date/Time)

**API Protection Verified**: _____ (Date/Time)

**All Security Features Tested**: _____ (Date/Time)

**Ready for Production**: ☐ YES ☐ NO

**Comments/Issues**: 
```
[Write any issues found and how they were resolved]
```

---

**This Checklist Version**: 1.0  
**Last Updated**: 2025-01-31  
**Status**: Ready for Implementation Testing
