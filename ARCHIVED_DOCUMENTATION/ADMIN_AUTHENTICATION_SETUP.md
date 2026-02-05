# Admin Authentication System - Complete Setup Guide

## Overview

This document provides comprehensive instructions for setting up and testing the new admin authentication system that protects the admin dashboard and all admin operations.

**Status**: ✅ Complete Implementation  
**Default Credentials**: `admin` / `admin123`  
**Database Changes Required**: Yes (migration must be executed)

---

## 1. Database Setup

### Step 1: Execute the Migration Script

The authentication system requires two new database tables: `users` and `login_audit`. You must run the migration script to create these tables.

**File Location**: `database/migration_add_user_authentication.sql`

#### Option A: Using phpMyAdmin
1. Open phpMyAdmin
2. Select your DepEd database
3. Click "SQL" tab
4. Copy the entire contents of `migration_add_user_authentication.sql`
5. Paste into the SQL query box
6. Click "Go" to execute

#### Option B: Using MySQL Command Line
```bash
mysql -u your_user -p your_database < database/migration_add_user_authentication.sql
```

#### Option C: Using Database GUI Tools
- Open the file in your MySQL GUI tool
- Execute all queries

### Step 2: Verify Database Setup

After running the migration, verify the tables were created:

```sql
-- Check users table
DESC users;

-- Check login_audit table
DESC login_audit;

-- Verify default admin user
SELECT id, username, email, role, status FROM users WHERE username='admin';
```

**Expected Output**:
- `users` table with 10 columns (id, username, email, password, full_name, role, status, last_login, created_at, updated_at)
- `login_audit` table with 9 columns (id, user_id, username, email, login_status, ip_address, user_agent, reason, attempted_at)
- One default user: `admin` with `email='admin@deped.gov.ph'` and `role='admin'`

---

## 2. System Files Created/Modified

### New Files Created

#### 1. **classes/AuthenticationHelper.php** (Core Authentication Logic)
- Location: `classes/AuthenticationHelper.php`
- Purpose: Handles all authentication operations
- Key Methods:
  - `authenticate()` - Validates credentials
  - `createSession()` - Creates authenticated user session
  - `isAdmin()` - Checks admin status
  - `requireAdmin()` - Enforces admin access control
  - `logout()` - Destroys session
  - `logLoginAttempt()` - Records login attempts for audit trail
  - `isIPBlocked()` - Implements IP-based rate limiting
  - `checkSessionTimeout()` - Validates session timeout
  - `static hashPassword()` - BCrypt password hashing utility

#### 2. **admin/login.php** (Admin Login Page)
- Location: `admin/login.php`
- Purpose: User-facing login interface
- Features:
  - Professional UI with gradient header
  - Form validation
  - Error messaging
  - Session management on success
  - IP blocking detection
  - Remember me checkbox (optional)

#### 3. **admin/logout.php** (Logout Handler)
- Location: `admin/logout.php`
- Purpose: Clean logout functionality
- Actions:
  - Destroys session
  - Clears cookies
  - Redirects to login page

#### 4. **database/migration_add_user_authentication.sql** (Database Schema)
- Location: `database/migration_add_user_authentication.sql`
- Contains:
  - `users` table creation
  - `login_audit` table creation
  - Indexes for performance
  - Default admin user insert
  - Foreign key constraints

### Files Modified for Protection

#### 1. **admin/index.php** (Admin Dashboard Home)
- Added: Authentication check at top
- Added: User info display in header
- Added: Logout button in header
- Behavior: Redirects to `/admin/login` if not authenticated

#### 2. **admin/applicants.php** (Applicants Management Dashboard)
- Added: Authentication check at top
- Added: User info display in header
- Added: Logout button in header
- Behavior: Redirects to `/admin/login` if not authenticated

#### 3. **admin/drafts.php** (Drafts Management)
- Added: Authentication check at top
- Behavior: Redirects to `/admin/login` if not authenticated

#### 4. **API Endpoints** (6 files)
- `api/archive_applicant.php` - Protected
- `api/bulk_archive_applicants.php` - Protected
- `api/get_applicants.php` - Protected
- `api/get_applicant_details.php` - Protected
- `api/get_applicant_stats.php` - Protected
- `api/restore_applicant.php` - Protected
- Behavior: Returns 403 error if not admin

---

## 3. Security Features

### Password Security
- **Hashing Algorithm**: BCrypt (PHP `password_hash()`)
- **Cost Factor**: 10 (industry standard)
- **Storage**: SHA-256 hash stored in database
- **Verification**: Constant-time comparison using `password_verify()`

### Session Management
- **Session ID**: PHP auto-generated, cryptographically secure
- **Session Data**: Stored server-side in `$_SESSION`
- **Timeout**: 3600 seconds (1 hour) of inactivity
- **Session Destruction**: Secure destruction with cookie clearing

### IP-Based Protection
- **Rate Limiting**: 5 failed attempts in 15 minutes triggers IP block
- **Tracking**: All login attempts logged with IP address
- **User Agent**: Captured for device identification

### Audit Logging
- **All Attempts Logged**: Both successful and failed logins
- **Information Captured**:
  - Username/Email
  - IP Address
  - User Agent (browser/device info)
  - Success/Failure status
  - Failure reason (if applicable)
  - Timestamp
- **Query**: View audit logs at `/admin/login.php` (future enhancement)

### Input Validation
- **Prepared Statements**: All database queries use prepared statements
- **Type Casting**: Integer casting for numeric inputs
- **Trimming**: Whitespace trimming for text inputs
- **HTML Escaping**: Output escaping to prevent XSS

---

## 4. Login Workflow

### Step-by-Step Login Process

```
User visits /admin/login
    ↓
[Form shows with username/email and password fields]
    ↓
User enters credentials and clicks "Login"
    ↓
System checks if IP is blocked (5+ failures in 15 minutes)
    ├─ YES → Show error "Too many login attempts, please try later"
    └─ NO → Continue
    ↓
System queries user from database by username or email
    ├─ NOT FOUND → Log failed attempt, show error
    └─ FOUND → Continue
    ↓
System verifies password using bcrypt
    ├─ MISMATCH → Log failed attempt, show error
    └─ MATCH → Continue
    ↓
System checks user role is 'admin'
    ├─ NOT ADMIN → Log failed attempt, show error "Access denied"
    └─ IS ADMIN → Continue
    ↓
System creates session:
  $_SESSION['user_id'] = user.id
  $_SESSION['username'] = user.username
  $_SESSION['email'] = user.email
  $_SESSION['full_name'] = user.full_name
  $_SESSION['role'] = user.role
  $_SESSION['login_time'] = current_time
    ↓
System logs successful login attempt
    ↓
User redirected to /admin/index.php
    ↓
Dashboard displays "Logged in as: [Full Name]" with Logout button
```

### Protected Page Access

When user tries to access any protected page (e.g., `/admin/applicants.php`):

```
User requests /admin/applicants.php
    ↓
Page calls: $auth->requireAdmin('/admin/login')
    ↓
System checks: Is $_SESSION['user_id'] set AND $_SESSION['role'] === 'admin'?
    ├─ NO → Redirect to /admin/login
    └─ YES → Continue loading page
    ↓
Page loads normally with user context available
    ├─ User name displayed in header
    ├─ All admin operations available
    └─ Logout button visible
```

### Logout Process

```
User clicks "Logout" button
    ↓
Browser requests /admin/logout.php
    ↓
System calls: $auth->logout()
    ├─ Destroys $_SESSION
    ├─ Clears session cookies
    └─ Logs logout event (optional)
    ↓
User redirected to /admin/login.php?logged_out=1
    ↓
Login page shows success message: "You have been logged out successfully"
```

---

## 5. Testing the System

### Test 1: Default Login

**Objective**: Verify login system works with default credentials

1. Open browser
2. Navigate to: `http://localhost/xampp/htdocs/DEPEDEvaluationSystemV2/admin/login.php`
3. Enter credentials:
   - Email/Username: `admin`
   - Password: `admin123`
4. Click "Login"
5. **Expected Result**: 
   - Redirect to `/admin/index.php`
   - Header shows "Logged in as: Administrator"
   - Logout button visible

### Test 2: Invalid Credentials

**Objective**: Verify login rejection with wrong password

1. Navigate to login page
2. Enter:
   - Email/Username: `admin`
   - Password: `wrongpassword`
3. Click "Login"
4. **Expected Result**: 
   - Error message: "Invalid email/username or password"
   - Stay on login page
   - No session created

### Test 3: Invalid Username

**Objective**: Verify login rejection with non-existent user

1. Navigate to login page
2. Enter:
   - Email/Username: `nonexistent`
   - Password: `admin123`
3. Click "Login"
4. **Expected Result**: 
   - Error message: "Invalid email/username or password"
   - Stay on login page

### Test 4: Session Protection

**Objective**: Verify protected pages require login

1. Logout (or open incognito window)
2. Navigate directly to: `http://localhost/xampp/htdocs/DEPEDEvaluationSystemV2/admin/applicants.php`
3. **Expected Result**: 
   - Redirect to `/admin/login.php`
   - Cannot access dashboard without login

### Test 5: Dashboard Access After Login

**Objective**: Verify all admin pages work after login

1. Login with `admin/admin123`
2. Click "Open Dashboard" or navigate to `/admin/applicants.php`
3. Verify page loads with:
   - Applicants list visible
   - User name in header
   - Logout button in header
   - All functionality works

### Test 6: Logout Functionality

**Objective**: Verify logout works and session ends

1. Login with valid credentials
2. Click "Logout" button
3. **Expected Result**: 
   - Redirect to login page
   - Message: "You have been logged out successfully"
4. Try to access `/admin/applicants.php` directly
5. **Expected Result**: 
   - Redirected back to login page

### Test 7: IP Blocking (Optional)

**Objective**: Verify rate limiting after 5 failed attempts

1. Navigate to login page
2. Attempt login with wrong password 5 times in quick succession
3. On 6th attempt:
4. **Expected Result**: 
   - Error message: "Too many login attempts from your IP. Please try again later."

---

## 6. Code Integration Examples

### How to Use in Admin Pages

#### Basic Protection
```php
<?php
session_start();
require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');

$conn = DBConnection::getConnection();
$auth = new AuthenticationHelper($conn);

// This line forces login if not authenticated as admin
$auth->requireAdmin('/admin/login');

// Get current user info
$currentUser = $auth->getCurrentUser();
echo "Welcome, " . htmlspecialchars($currentUser['full_name']);
?>
```

#### API Endpoint Protection
```php
<?php
session_start();
require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');

header('Content-Type: application/json');
$conn = DBConnection::getConnection();
$auth = new AuthenticationHelper($conn);

// Check admin access
if (!$auth->isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$currentUser = $auth->getCurrentUser();
// Perform admin operation...
?>
```

---

## 7. User Management

### Current Users

The system comes with one default user:

| Username | Email | Password | Role | Status |
|----------|-------|----------|------|--------|
| admin | admin@deped.gov.ph | admin123 | admin | active |

### Adding New Users

To add new admin users, insert records into the `users` table:

```php
<?php
require_once(__DIR__ . '/classes/AuthenticationHelper.php');

$password = 'new_password_here';
$hashedPassword = AuthenticationHelper::hashPassword($password);

// SQL to insert
$sql = "INSERT INTO users (username, email, password, full_name, role, status) 
        VALUES ('newuser', 'newuser@deped.gov.ph', '$hashedPassword', 'New User Name', 'admin', 'active')";

// Execute with your database connection
$conn->query($sql);
?>
```

### Changing Passwords

To change a user's password:

```sql
UPDATE users 
SET password = '$2y$10$[NEW_BCRYPT_HASH_HERE]' 
WHERE username = 'admin';
```

Generate the hash using PHP:
```php
<?php
require_once(__DIR__ . '/classes/AuthenticationHelper.php');
$hash = AuthenticationHelper::hashPassword('newpassword');
echo $hash;  // Copy this hash into SQL UPDATE
?>
```

---

## 8. Common Issues and Solutions

### Issue: "Login page shows but doesn't authenticate"

**Causes**:
1. Database migration not run
2. PHP sessions not working
3. Database connection issue

**Solutions**:
1. Verify migration was executed: `SELECT * FROM users;` should show admin user
2. Check PHP session settings: `php.ini` should have `session.save_path` writable
3. Verify DB connection: Test `classes/DBConnection.php` directly

### Issue: "Redirected to login even after successful login"

**Causes**:
1. Session timeout (1 hour)
2. Cookie settings not allowing sessions
3. Session data not persisting

**Solutions**:
1. Clear browser cache and cookies
2. Check `php.ini`: `session.save_path` and permissions
3. Verify `session_start()` called at top of protected pages

### Issue: "API endpoints return 403 Unauthorized"

**Causes**:
1. Session not created from login
2. Not authenticated before calling API
3. User role not admin

**Solutions**:
1. Verify you're logged in by checking header with user info
2. Logout and login again to refresh session
3. Check user role in database: `SELECT role FROM users WHERE username='admin';`

### Issue: "Too many login attempts" after failed password attempts

**Causes**:
1. By design - IP blocking activates after 5 failures in 15 minutes
2. Prevents brute force attacks

**Solutions**:
1. Wait 15 minutes for IP block to expire
2. Use different IP address if available
3. Contact database administrator to manually clear `login_audit` table

---

## 9. Security Best Practices

### For Administrators

1. **Change Default Password**
   - Immediately change the default `admin123` password
   - Use strong passwords (12+ characters, mixed case, numbers, symbols)

2. **Monitor Audit Logs**
   - Regularly review login attempts in `login_audit` table
   - Look for suspicious failed login patterns

3. **Session Security**
   - Logout when leaving workstation
   - Sessions auto-expire after 1 hour of inactivity
   - Clear browser cache after logout

4. **User Account Management**
   - Only grant admin role to trusted users
   - Regularly audit active users
   - Disable accounts for inactive users

### For Developers

1. **Password Handling**
   - Never display or log raw passwords
   - Always use `password_verify()` for comparison
   - Never implement custom hashing - use BCrypt

2. **Session Handling**
   - Always check both `isAdmin()` on protected pages
   - Never trust `$_SESSION` without verification
   - Always call `session_start()` first

3. **Database Queries**
   - Always use prepared statements
   - Never concatenate user input into SQL
   - Use bound parameters for all values

4. **Error Messages**
   - Don't reveal whether username exists or not
   - Generic message: "Invalid credentials"
   - Log detailed errors server-side only

---

## 10. API Reference

### AuthenticationHelper Class

#### `authenticate($emailOrUsername, $password)`
**Purpose**: Authenticates user with credentials

**Parameters**:
- `$emailOrUsername` (string): Username or email address
- `$password` (string): Plain text password

**Returns**: Array with keys:
```php
[
    'success' => true|false,
    'user' => [...],  // User data if successful
    'message' => '...' // Error message if failed
]
```

#### `createSession($user)`
**Purpose**: Creates authenticated session

**Parameters**:
- `$user` (array): User data from authenticate()

**Effect**: Populates `$_SESSION` with user info

#### `isAdmin()`
**Purpose**: Checks if current user is admin

**Returns**: boolean

#### `requireAdmin($redirectUrl = '/admin/login')`
**Purpose**: Enforce admin access, redirect if not

**Parameters**:
- `$redirectUrl` (string): URL to redirect if not admin

**Effect**: Calls `exit()` if not admin

#### `logout()`
**Purpose**: Destroys session and clears cookies

**Effect**: Empties `$_SESSION`, clears cookies, user logged out

#### `getCurrentUser()`
**Purpose**: Get current authenticated user data

**Returns**: Array with keys: id, username, email, full_name, role, login_time

#### `static hashPassword($password)`
**Purpose**: Generate BCrypt hash from plain password

**Parameters**:
- `$password` (string): Plain text password

**Returns**: BCrypt hash string

#### `checkSessionTimeout()`
**Purpose**: Validate session hasn't exceeded timeout

**Returns**: boolean

#### `isIPBlocked($ipAddress)`
**Purpose**: Check if IP is blocked due to rate limiting

**Parameters**:
- `$ipAddress` (string): IP address to check

**Returns**: boolean

---

## 11. Troubleshooting Checklist

- [ ] Migration script executed successfully
- [ ] `users` table exists with admin user
- [ ] `login_audit` table exists
- [ ] Can access `/admin/login.php`
- [ ] Default login works: `admin/admin123`
- [ ] Redirected to `/admin/index.php` after login
- [ ] User name displays in dashboard header
- [ ] Logout button visible and functional
- [ ] Protected pages deny access when logged out
- [ ] API endpoints return 403 when not authenticated
- [ ] Session persists for multiple page loads
- [ ] Failed login attempts logged to `login_audit`

---

## 12. Support and Maintenance

### Regular Maintenance Tasks

1. **Weekly**: Review login attempt logs
2. **Monthly**: Audit active user accounts
3. **Quarterly**: Review and update security policies
4. **Annually**: Update password requirements

### Emergency Procedures

**If you forget admin password**:
1. Access database directly (phpMyAdmin or command line)
2. Generate new hash: `php -r "echo password_hash('newpass', PASSWORD_BCRYPT, ['cost'=>10]);"`
3. Update users table: `UPDATE users SET password='[hash]' WHERE username='admin';`

**If IP is blocked**:
1. Wait 15 minutes for automatic unlock
2. Or clear `login_audit` table entries for that IP

---

## Document Version

- **Version**: 1.0
- **Last Updated**: 2025-01-31
- **Status**: Complete Implementation
- **Author**: DepEd HRMPSB System Team
