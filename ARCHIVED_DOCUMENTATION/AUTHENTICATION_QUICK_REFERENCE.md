# Admin Authentication System - Quick Reference Guide

## 🚀 Quick Start (5 Minutes)

### Step 1: Run Database Migration
```bash
# Copy & paste database/migration_add_user_authentication.sql into phpMyAdmin
# OR run in command line:
mysql -u username -p database_name < database/migration_add_user_authentication.sql
```

### Step 2: Test Login
```
URL: http://localhost/xampp/htdocs/DEPEDEvaluationSystemV2/admin/login.php
Username: admin
Password: admin123
Click: Login
```

### Step 3: Verify Dashboard
```
Should see: Admin Dashboard with user name in top right
Should see: Logout button
```

---

## 📁 File Structure

```
DEPEDEvaluationSystemV2/
├── admin/
│   ├── index.php              ✅ PROTECTED (Dashboard home)
│   ├── applicants.php         ✅ PROTECTED (Main dashboard)
│   ├── login.php              ✨ NEW (Login page)
│   ├── logout.php             ✨ NEW (Logout handler)
│   └── drafts.php             ✅ PROTECTED
├── api/
│   ├── archive_applicant.php                    ✅ PROTECTED
│   ├── bulk_archive_applicants.php              ✅ PROTECTED
│   ├── get_applicants.php                       ✅ PROTECTED
│   ├── get_applicant_details.php                ✅ PROTECTED
│   ├── get_applicant_stats.php                  ✅ PROTECTED
│   ├── restore_applicant.php                    ✅ PROTECTED
│   └── [other APIs - unchanged]
├── classes/
│   ├── AuthenticationHelper.php                 ✨ NEW (Auth logic)
│   ├── DBConnection.php                         (unchanged)
│   └── ApplicantManager.php                     (unchanged)
├── database/
│   ├── migration_add_user_authentication.sql    ✨ NEW (DB schema)
│   └── [other migrations]
├── ADMIN_AUTHENTICATION_SETUP.md                ✨ NEW (Full guide)
├── AUTHENTICATION_IMPLEMENTATION_SUMMARY.md     ✨ NEW (Summary)
└── AUTHENTICATION_QUICK_REFERENCE.md            ✨ NEW (This file)
```

---

## 🔐 Authentication Flow

```
┌─────────────────────────────────────────────────────────────┐
│                     LOGIN FLOW                               │
├─────────────────────────────────────────────────────────────┤
│ 1. User visits /admin/login.php                              │
│ 2. Enters username/email and password                        │
│ 3. System checks IP (blocked?)                               │
│    └─ YES → "Too many login attempts"                        │
│    └─ NO → Continue                                          │
│ 4. Query user from database                                  │
│    └─ NOT FOUND → "Invalid credentials"                      │
│    └─ FOUND → Continue                                       │
│ 5. Verify password (BCrypt)                                  │
│    └─ WRONG → "Invalid credentials"                          │
│    └─ CORRECT → Continue                                     │
│ 6. Check role = admin                                        │
│    └─ NOT ADMIN → "Access denied"                            │
│    └─ IS ADMIN → Continue                                    │
│ 7. Create session with user data                             │
│ 8. Log successful attempt                                    │
│ 9. Redirect to /admin/index.php                              │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│              PROTECTED PAGE ACCESS FLOW                       │
├─────────────────────────────────────────────────────────────┤
│ 1. User requests /admin/applicants.php                       │
│ 2. Page runs: $auth->requireAdmin()                          │
│ 3. System checks: $_SESSION['role'] === 'admin'?             │
│    └─ NO → Redirect to /admin/login.php                      │
│    └─ YES → Load page normally                               │
│ 4. Display user name: "Logged in as: John Doe"               │
│ 5. Show logout button                                        │
│ 6. All functionality enabled                                 │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                    LOGOUT FLOW                                │
├─────────────────────────────────────────────────────────────┤
│ 1. User clicks Logout button                                 │
│ 2. Request to /admin/logout.php                              │
│ 3. Session destroyed                                         │
│ 4. Cookies cleared                                           │
│ 5. Redirect to /admin/login.php?logged_out=1                 │
│ 6. Show success message                                      │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔑 Default Credentials

```
Username: admin
Email: admin@deped.gov.ph
Password: admin123
Role: admin
```

**⚠️ Change immediately in production!**

---

## 🛡️ Security Features

| Feature | Details |
|---------|---------|
| **Password Hashing** | BCrypt (cost=10) |
| **Session Timeout** | 1 hour (3600 seconds) |
| **IP Rate Limiting** | Block after 5 failures in 15 minutes |
| **Audit Logging** | All attempts logged with IP + User Agent |
| **SQL Injection** | Protected via prepared statements |
| **XSS Protection** | Output HTML escaped |
| **Session Storage** | Server-side (secure) |

---

## 📝 Code Examples

### Protecting a Page
```php
<?php
session_start();
require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');

$conn = DBConnection::getConnection();
$auth = new AuthenticationHelper($conn);

// This line enforces admin access
$auth->requireAdmin('/admin/login');

// Get logged-in user
$user = $auth->getCurrentUser();
echo "Welcome, " . htmlspecialchars($user['full_name']);
?>
```

### Protecting an API Endpoint
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

// Get user for audit trail
$user = $auth->getCurrentUser();

// Perform operation...
?>
```

### Checking Authentication Status
```php
<?php
$auth = new AuthenticationHelper($conn);

// Check if authenticated
if (!$auth->isAuthenticated()) {
    // Not logged in
}

// Check if admin
if ($auth->isAdmin()) {
    // Is admin
}

// Check if session timed out
if (!$auth->checkSessionTimeout()) {
    $auth->logout();
    header('Location: /admin/login.php?timeout=1');
}

// Get current user
$user = $auth->getCurrentUser();
echo $user['username'];  // admin
echo $user['email'];     // admin@deped.gov.ph
echo $user['full_name']; // Administrator
echo $user['role'];      // admin
?>
```

---

## 🧪 Testing Scenarios

### Test 1: Valid Login
```
Input: admin / admin123
Expected: Redirect to /admin/index.php with dashboard
```

### Test 2: Invalid Password
```
Input: admin / wrongpassword
Expected: Stay on login, show error message
```

### Test 3: Non-existent User
```
Input: nonexistent / password
Expected: Stay on login, show error message
```

### Test 4: Unprotected Access
```
Action: Try /admin/applicants.php without login
Expected: Redirect to /admin/login.php
```

### Test 5: Session Persistence
```
Action: Login, navigate between pages
Expected: Stay logged in across pages
```

### Test 6: Logout
```
Action: Click Logout
Expected: Session destroyed, redirect to login
```

### Test 7: IP Blocking
```
Action: Wrong password 5 times quickly
Expected: 6th attempt blocked with error
```

---

## 🗄️ Database Schema

### Users Table
```sql
users (
  id INT PRIMARY KEY,
  username VARCHAR(100) UNIQUE,
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255),          -- BCrypt hash
  full_name VARCHAR(255),
  role ENUM('admin','staff','evaluator'),
  status ENUM('active','disabled','inactive'),
  last_login DATETIME,
  created_at DATETIME,
  updated_at DATETIME
)
```

### Login Audit Table
```sql
login_audit (
  id INT PRIMARY KEY,
  user_id INT,
  username VARCHAR(100),
  email VARCHAR(100),
  login_status ENUM('success','failed'),
  ip_address VARCHAR(45),
  user_agent TEXT,
  reason VARCHAR(255),
  attempted_at DATETIME
)
```

---

## 📊 Session Data

After login, `$_SESSION` contains:
```php
$_SESSION['user_id']    // 1
$_SESSION['username']   // "admin"
$_SESSION['email']      // "admin@deped.gov.ph"
$_SESSION['full_name']  // "Administrator"
$_SESSION['role']       // "admin"
$_SESSION['login_time'] // timestamp
```

---

## ⚡ Common Commands

### Verify Login Works
```
Visit: http://localhost/xampp/htdocs/DEPEDEvaluationSystemV2/admin/login.php
Login with: admin / admin123
```

### Check Database
```sql
-- Verify users table
SELECT * FROM users;

-- Verify admin user exists
SELECT username, email, role FROM users WHERE username='admin';

-- View login attempts
SELECT username, login_status, ip_address, attempted_at FROM login_audit
ORDER BY attempted_at DESC
LIMIT 10;
```

### Generate Password Hash
```php
<?php
require_once('classes/AuthenticationHelper.php');
$hash = AuthenticationHelper::hashPassword('newpassword');
echo $hash;
?>
```

### Reset Admin Password
```sql
-- First generate hash in PHP (see above)
UPDATE users 
SET password = '[paste_hash_here]' 
WHERE username = 'admin';
```

---

## 🐛 Troubleshooting

### "Cannot connect to database"
- Check `classes/DBConnection.php` settings
- Verify MySQL service is running
- Check username/password/database name

### "Login button does nothing"
- Check browser console for errors
- Verify `admin/login.php` exists
- Check PHP error logs

### "Session not persisting"
- Check PHP session settings in `php.ini`
- Verify `session.save_path` is writable
- Clear browser cookies

### "API returns 403 Unauthorized"
- Verify you're logged in
- Check `$_SESSION['role']` is 'admin'
- Logout and login again

### "IP is blocked"
- Wait 15 minutes for automatic unlock
- Or clear `login_audit` table for that IP

---

## 📚 Documentation

| Document | Purpose |
|----------|---------|
| **ADMIN_AUTHENTICATION_SETUP.md** | Complete setup guide (12 sections) |
| **AUTHENTICATION_IMPLEMENTATION_SUMMARY.md** | Implementation overview |
| **AUTHENTICATION_QUICK_REFERENCE.md** | This file - quick lookup |

---

## 🔗 Key URLs

| URL | Purpose |
|-----|---------|
| `/admin/login.php` | Login page |
| `/admin/index.php` | Dashboard home (protected) |
| `/admin/applicants.php` | Applicants management (protected) |
| `/admin/logout.php` | Logout handler |

---

## ✅ Implementation Checklist

- [x] AuthenticationHelper class created
- [x] Login page created
- [x] Logout handler created
- [x] Database migration created
- [x] Admin index.php protected
- [x] Admin applicants.php protected
- [x] Admin drafts.php protected
- [x] API endpoints protected (6 files)
- [x] Documentation complete
- [ ] **Database migration executed** ← YOU ARE HERE
- [ ] Test login with admin/admin123
- [ ] Verify dashboard protection works

---

## 🎯 Next Steps

1. **Run the migration script** (`database/migration_add_user_authentication.sql`)
2. **Test the login** (admin / admin123)
3. **Verify dashboard protection** (logout and try to access)
4. **Change default password** (production only)

---

## 📞 Support

For detailed information, see:
- **Setup Guide**: `ADMIN_AUTHENTICATION_SETUP.md`
- **Implementation Summary**: `AUTHENTICATION_IMPLEMENTATION_SUMMARY.md`
- **Code Comments**: `classes/AuthenticationHelper.php`

---

**Version**: 1.0  
**Status**: Ready for Testing  
**Last Updated**: 2025-01-31
