# Login System Troubleshooting Guide
**DepEd HRMPSB Evaluation System**  
**Date**: February 4, 2026

## Summary of Findings

The login system diagnostic revealed **8 potential issues** that could prevent successful login:

---

## ✅ VERIFIED WORKING

1. **Database Connection** - ✓ Working correctly
2. **Users Table** - ✓ Exists with correct schema
3. **Admin User** - ✓ Exists with correct credentials
4. **Password Hash** - ✓ Valid and verifies correctly with `admin123`
5. **User Role** - ✓ Set to `admin`
6. **User Status** - ✓ Active
7. **AuthenticationHelper Class** - ✓ Loads and authenticates correctly
8. **File Permissions** - ✓ All required files accessible

---

## ⚠️ POTENTIAL ISSUES IDENTIFIED

### Issue #1: Redirect Path Problem (CRITICAL)
**Location**: `classes/AuthenticationHelper.php` line 139  
**Problem**: The `requireAdmin()` method uses absolute path `/admin/login`  
**Impact**: Redirects may fail or go to wrong URL

```php
// CURRENT (WRONG):
public function requireAdmin($redirectUrl = '/admin/login') {
    
// SHOULD BE:
public function requireAdmin($redirectUrl = 'admin/login.php') {
```

**Why This Matters**:
- Absolute paths like `/admin/login` work only if your site is at domain root
- Your system is at `/DEPEDEvaluationSystemV2/`, so `/admin/login` → tries to access `http://localhost/admin/login` (404 error)
- Should be: `http://localhost/DEPEDEvaluationSystemV2/admin/login.php`

---

### Issue #2: Missing .php Extension in Redirects
**Location**: Multiple files  
**Problem**: Redirect to `index.php` without checking if file exists  
**Impact**: May cause 404 errors on some servers

**Files Affected**:
- `admin/login.php` line 21: `header('Location: index.php');`
- `admin/login.php` line 54: `header('Location: index.php');`

---

### Issue #3: Session Timeout Too Short
**Location**: `classes/AuthenticationHelper.php` line 15  
**Problem**: Session timeout is 1 hour (3600 seconds)  
**Impact**: Users get logged out after 1 hour

```php
private $sessionTimeout = 3600; // 1 hour - may be too short
```

**Recommendation**: Increase to 8 hours (28800) or disable for development:
```php
private $sessionTimeout = 28800; // 8 hours
```

---

### Issue #4: Session Cookie Settings
**Location**: PHP session configuration  
**Problem**: Session cookies may not persist across page loads  
**Impact**: Login appears successful but session lost on redirect

**Check**: `php.ini` settings:
```ini
session.cookie_lifetime = 0
session.cookie_path = /
session.cookie_domain = 
session.cookie_secure = 0
session.cookie_httponly = 1
session.cookie_samesite = Lax
```

---

### Issue #5: Output Before Headers
**Location**: Any file that calls `header()`  
**Problem**: If there's any output (even whitespace) before `header()`, redirect fails  
**Impact**: "Headers already sent" error

**Files to Check**:
- `admin/login.php` - Must have NO output before line 8 `session_start()`
- `includes/favicon.php` - Should not have trailing whitespace after `?>`
- All included files

**Fix**: Ensure:
1. No whitespace before `<?php`
2. No `echo` or `print` before `header()`
3. No BOM (Byte Order Mark) in UTF-8 files

---

### Issue #6: XAMPP URL Rewrite Rules
**Location**: `.htaccess` or Apache configuration  
**Problem**: URL rewriting may interfere with redirects  
**Impact**: Redirects go to wrong location

**Check**: Look for `.htaccess` in root directory

---

### Issue #7: Browser Cache
**Location**: Client browser  
**Problem**: Browser caching old login page or redirects  
**Impact**: Changes not visible, appears broken

**Fix**: 
- Clear browser cache (Ctrl+Shift+Delete)
- Use incognito/private browsing mode
- Hard refresh (Ctrl+F5)

---

### Issue #8: JavaScript Form Validation
**Location**: `admin/login.php` lines 488-518  
**Problem**: JavaScript may prevent form submission  
**Impact**: Form doesn't submit

**JavaScript Code**:
```javascript
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email_or_username').value.trim();
    const password = document.getElementById('password').value;

    if (!email || !password) {
        e.preventDefault(); // <-- May prevent submission
        alert('Please fill in all required fields');
        return;
    }
    
    // Show loading state
    document.getElementById('loading').style.display = 'block';
    document.getElementById('loginBtn').disabled = true;
});
```

---

## 🔧 RECOMMENDED FIXES

### Priority 1: Fix Redirect Paths (CRITICAL)

**File**: `classes/AuthenticationHelper.php`

Change line 139 from:
```php
public function requireAdmin($redirectUrl = '/admin/login') {
```

To:
```php
public function requireAdmin($redirectUrl = 'admin/login.php') {
```

And update line 141 from:
```php
header('Location: ' . $redirectUrl);
```

To:
```php
// Build proper relative path
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($redirectUrl[0] !== '/') {
    $redirectUrl = $baseUrl . '/' . $redirectUrl;
}
header('Location: ' . $redirectUrl);
```

### Priority 2: Add Debugging to Login Page

**File**: `admin/login.php`

Add after line 28 (after POST check):
```php
// DEBUG: Log what's happening
error_log("Login attempt - User: {$emailOrUsername}");
error_log("POST data: " . print_r($_POST, true));
```

And after line 51:
```php
// DEBUG: Log successful auth
error_log("Auth successful, redirecting to index.php");
error_log("Session data: " . print_r($_SESSION, true));
```

### Priority 3: Check Session Storage

Add to top of `admin/login.php` after `session_start()`:
```php
// DEBUG: Check session
error_log("Session ID: " . session_id());
error_log("Session save path: " . session_save_path());
```

### Priority 4: Add Error Display

Temporarily enable errors in `admin/login.php` (line 1):
```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');
```

---

## 🧪 TESTING PROCEDURE

### Step 1: Run Detailed Test Page
```
http://localhost/DEPEDEvaluationSystemV2/test_login_detailed.php
```

This will show you:
1. PHP environment status
2. Database connection status
3. AuthenticationHelper status
4. Simulated login test
5. Session creation test
6. isAdmin() check
7. Manual form test

### Step 2: Check Browser Console

1. Open browser DevTools (F12)
2. Go to Console tab
3. Go to Network tab
4. Try logging in
5. Check for:
   - JavaScript errors (red text in Console)
   - Failed requests (red in Network tab)
   - Redirect chains (multiple 302 responses)

### Step 3: Check PHP Error Log

**Location**: 
- XAMPP: `C:\xampp\php\logs\php_error_log`
- Or: `C:\xampp\htdocs\DEPEDEvaluationSystemV2\logs\php_errors.log`

Look for:
- "Headers already sent" errors
- Database errors
- Session errors

### Step 4: Test Direct Authentication

Run:
```
http://localhost/DEPEDEvaluationSystemV2/setup_admin_credentials.php
```

Then:
```
http://localhost/DEPEDEvaluationSystemV2/diagnose_login_system.php
```

### Step 5: Manual Login Test

1. Clear browser cookies and cache
2. Go to `http://localhost/DEPEDEvaluationSystemV2/admin/login.php`
3. Enter:
   - Username: `admin`
   - Password: `admin123`
4. Click "Sign In"
5. Watch browser Network tab for redirects

---

## 📊 EXPECTED BEHAVIOR

### Successful Login Flow:

1. User submits form → `POST admin/login.php`
2. PHP processes → `authenticate()` returns success
3. Session created → `$_SESSION['user_id']`, `$_SESSION['role']` set
4. Redirect sent → `header('Location: index.php')`
5. Browser follows → `GET admin/index.php`
6. Auth check → `requireAdmin()` checks session
7. Page loads → Admin dashboard displays

### Current Problem Flow:

**Scenario A: Redirect Path Wrong**
1. User submits form ✓
2. Authentication succeeds ✓
3. Redirect to `/admin/login` → Goes to `http://localhost/admin/login` (404)

**Scenario B: Session Lost**
1. User submits form ✓
2. Authentication succeeds ✓
3. Session created ✓
4. Redirect to `index.php` ✓
5. `requireAdmin()` checks session → Session empty!
6. Redirect back to `login.php` → Loop!

**Scenario C: Headers Already Sent**
1. User submits form ✓
2. Authentication succeeds ✓
3. Try to redirect → ERROR: "Headers already sent"
4. Page displays with error
5. No redirect happens

---

## 🛠️ QUICK FIX SCRIPT

Run this to apply all fixes:

```php
<?php
// quick_login_fix.php
// Applies all critical fixes

echo "Applying login fixes...\n\n";

// Fix 1: Update requireAdmin redirect path
$authFile = __DIR__ . '/classes/AuthenticationHelper.php';
$content = file_get_contents($authFile);
$content = str_replace(
    "public function requireAdmin(\$redirectUrl = '/admin/login') {",
    "public function requireAdmin(\$redirectUrl = 'admin/login.php') {",
    $content
);
file_put_contents($authFile, $content);
echo "✓ Fixed requireAdmin() redirect path\n";

// Fix 2: Increase session timeout
$content = file_get_contents($authFile);
$content = str_replace(
    "private \$sessionTimeout = 3600;",
    "private \$sessionTimeout = 28800; // 8 hours",
    $content
);
file_put_contents($authFile, $content);
echo "✓ Increased session timeout to 8 hours\n";

echo "\nFixes applied! Try logging in again.\n";
?>
```

---

## 📞 SUPPORT CHECKLIST

When reporting login issues, provide:

- [ ] PHP version (`php -v`)
- [ ] Browser and version
- [ ] Exact error message (if any)
- [ ] Browser console errors (F12 → Console)
- [ ] Network tab redirects (F12 → Network)
- [ ] PHP error log contents
- [ ] Result from `test_login_detailed.php`
- [ ] Result from `diagnose_login_system.php`

---

## ✅ VERIFICATION

After fixes applied, verify:

1. Navigate to `admin/login.php`
2. Enter username: `admin`
3. Enter password: `admin123`
4. Click Sign In
5. Should redirect to `admin/index.php`
6. Should see "Admin Dashboard" page
7. Should see user name in header
8. Should not redirect back to login

If still failing, run `test_login_detailed.php` and share results.
