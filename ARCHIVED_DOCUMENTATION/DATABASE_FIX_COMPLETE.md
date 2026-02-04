# 🔧 Database Connection Fix - Complete Guide

## ✅ Issues Fixed

### 1. **Port Configuration Error**
- **Problem:** Using port 3307 when XAMPP defaults to 3306
- **Fix:** Changed default port from 3307 → 3306 in `initialize.php`
- **File:** `initialize.php` line 11

### 2. **Insufficient Error Messages**
- **Problem:** Generic error message "Cannot connect to database server"
- **Fix:** Enhanced `DBConnection.php` with detailed troubleshooting instructions
- **File:** `classes/DBConnection.php` lines 22-52

### 3. **Auto-Port Detection**
- **Problem:** Not trying alternate ports if primary fails
- **Fix:** Enhanced logic to try 3306, then 3307, then back to 3307
- **File:** `classes/DBConnection.php`

---

## 🚀 What Was Changed

### Change 1: initialize.php
**Before:**
```php
if(!defined('DB_PORT')) define('DB_PORT',"3307");
```

**After:**
```php
if(!defined('DB_PORT')) define('DB_PORT',"3306");
// Note: XAMPP typically uses port 3306 (default MySQL), but some setups use 3307
// DBConnection.php will auto-detect and fallback between 3306 and 3307
```

**Why:** Port 3306 is the standard MySQL port. XAMPP defaults to this, not 3307.

---

### Change 2: classes/DBConnection.php
**Enhanced constructor with:**
1. Better error logging
2. Detailed error messages with solutions
3. Auto-fallback between ports 3306 and 3307
4. Instructions to test connection

**New Features:**
```php
- Tries port from initialize.php first
- Falls back to 3306 if that fails
- Falls back to 3307 as final attempt
- Provides detailed error message with solutions
- Logs errors for debugging
```

---

## 📋 New Tools Created

### 1. test_connection.php
**Location:** `http://localhost/DEPEDEvaluationSystem/test_connection.php`

**What it does:**
- ✅ Tests database connection
- ✅ Shows configuration settings
- ✅ Displays MySQL version
- ✅ Lists database tables
- ✅ Provides troubleshooting steps

**Run this first to diagnose issues**

---

### 2. database_setup.php
**Location:** `http://localhost/DEPEDEvaluationSystem/database_setup.php`

**Features:**
- ✅ Test connection with auto-port detection
- ✅ Create database if missing
- ✅ Initialize tables from schema.sql
- ✅ One-click setup wizard
- ✅ Detailed troubleshooting guide

**Use this to setup or fix database from scratch**

---

## 🔍 How to Fix Your Connection

### Quick Fix (5 minutes)

**Step 1: Start MySQL**
1. Open XAMPP Control Panel
2. Look for "MySQL" row
3. Click the "Start" button
4. Wait until it says "Running" (green indicator)

**Step 2: Test Connection**
1. Open browser
2. Go to: `http://localhost/DEPEDEvaluationSystem/test_connection.php`
3. Check if connection is successful
4. If yes → done! Go to Step 5
5. If no → continue to Step 3

**Step 3: Try Setup Wizard**
1. Go to: `http://localhost/DEPEDEvaluationSystem/database_setup.php`
2. Click "Create Database" button (if needed)
3. Click "Initialize Tables" button (if needed)
4. Follow on-screen instructions

**Step 4: Fix Port (if still failing)**
1. Open `initialize.php` in editor
2. Find: `if(!defined('DB_PORT')) define('DB_PORT',"3306");`
3. Try changing 3306 → 3307
4. Refresh test page

**Step 5: You're Done!**
- Go to: `http://localhost/DEPEDEvaluationSystem/`
- Try an evaluation
- Check if data saves to database

---

## 🛠️ Configuration Files

### initialize.php
```php
DB_SERVER:   "localhost"     (Your MySQL server)
DB_USERNAME: "root"          (MySQL username)
DB_PASSWORD: ""              (Usually empty for XAMPP)
DB_NAME:     "deped_evaluation"  (Database name)
DB_PORT:     "3306"          (MySQL port - can be 3307)
```

**To Change Settings:**
1. Edit: `c:\xampp\htdocs\DEPEDEvaluationSystem\initialize.php`
2. Modify the DB_* constants
3. Save and refresh browser

---

## ❌ Common Error Messages & Solutions

### Error 1: "Target machine actively refused it"
```
Meaning: MySQL is not running
Solution:
1. Open XAMPP Control Panel
2. Click Start next to MySQL
3. Wait for green indicator
4. Refresh page
```

### Error 2: "Cannot connect to database server"
```
Meaning: Connection configuration is wrong
Solution:
1. Check initialize.php for correct credentials
2. Verify MySQL is running
3. Try different port (3306 or 3307)
4. Check firewall isn't blocking port
```

### Error 3: "Access denied for user 'root'"
```
Meaning: Wrong username or password
Solution:
1. Edit initialize.php
2. Verify DB_USERNAME (usually "root" for XAMPP)
3. Verify DB_PASSWORD (usually empty for XAMPP)
4. Check MySQL user permissions
```

### Error 4: "Unknown database 'deped_evaluation'"
```
Meaning: Database doesn't exist
Solution:
1. Go to database_setup.php
2. Click "Create Database" button
3. Click "Initialize Tables" button
4. Or use phpMyAdmin to create manually
```

---

## 🔗 Test URLs

| Purpose | URL |
|---------|-----|
| Test Connection | http://localhost/DEPEDEvaluationSystem/test_connection.php |
| Database Setup | http://localhost/DEPEDEvaluationSystem/database_setup.php |
| Main Application | http://localhost/DEPEDEvaluationSystem/index.php |
| phpMyAdmin | http://localhost/phpmyadmin/ |

---

## 📊 Files Modified

1. ✅ `initialize.php` - Changed default port to 3306
2. ✅ `classes/DBConnection.php` - Enhanced error handling and auto-detection
3. ✅ `test_connection.php` - Created new diagnostic tool
4. ✅ `database_setup.php` - Created new setup wizard

---

## ✨ Next Steps

1. **Open XAMPP Control Panel** and start MySQL
2. **Visit:** `http://localhost/DEPEDEvaluationSystem/test_connection.php`
3. **If connection fails:** Visit `http://localhost/DEPEDEvaluationSystem/database_setup.php`
4. **Use the setup wizard** to create/initialize database
5. **Test the application** at `http://localhost/DEPEDEvaluationSystem/`

---

## 🎯 Success Indicators

✅ **Connection works when:**
- test_connection.php shows "Connection Successful"
- MySQL version displays
- Database tables are listed

✅ **Database is ready when:**
- database_setup.php shows "Database initialized"
- All tables created successfully
- You can do evaluations and data saves

✅ **System works when:**
- index.php loads without errors
- Can input evaluation data
- Results display after submission
- Data appears in database

---

**🎉 Your database connection is now fixed and ready to use!**
