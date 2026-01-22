# 🗄️ DATABASE FIXES - Database Solutions

Solutions for database-related problems and issues.

**Reading time: 15 minutes**

---

## 📋 Quick Problem Finder

**Can't connect to database?** → [Connection Issues](#connection-issues)

**Data not saving?** → [Data Saving Issues](#data-saving-issues)

**Data not appearing?** → [Data Retrieval Issues](#data-retrieval-issues)

**Getting database errors?** → [Database Errors](#database-errors)

**Need to reset database?** → [Database Reset](#database-reset)

---

## Connection Issues

### Problem: MySQL Not Running

**Error Message:**
```
Connection refused
Unable to connect
Host not found
```

**What's Happening:**
- MySQL server not started
- XAMPP not running MySQL
- Port already in use

**Step-by-Step Fix:**

**Step 1: Check XAMPP Control Panel**
1. Find XAMPP Control Panel on desktop/taskbar
2. Open it (might be running minimized)
3. Look for MySQL row
4. Check if it says "Running" in green

**Step 2: If Not Running - Start MySQL**
1. Find MySQL row in XAMPP
2. Click "Start" button for MySQL
3. Wait 5 seconds for it to start
4. Should turn green and say "Running"

**Step 3: Verify It's Running**
1. MySQL shows green "Running" status
2. Port should be "3306"
3. Should see process ID (PID)

**Step 4: Verify Connection**
1. Open web browser
2. Go to: http://localhost/phpmyadmin
3. Should load without error
4. Shows database list on left
5. If works, MySQL is connected

**Step 5: Try Your Application Again**
1. Refresh application page
2. Try submitting data
3. Should work now

**If Still Not Working:**
1. Stop MySQL (click Stop)
2. Wait 10 seconds
3. Start MySQL again (click Start)
4. Wait for green status
5. Try application again

### Problem: Database Doesn't Exist

**Error Message:**
```
Unknown database 'deped_evaluation'
Database not found
```

**What's Happening:**
- MySQL is running
- But database wasn't created
- Application looking for database that doesn't exist

**Step-by-Step Fix:**

**Step 1: Verify Database**
1. Open: http://localhost/phpmyadmin
2. Look at left sidebar
3. Scroll down list of databases
4. Look for "deped_evaluation"
5. If not there, need to create it

**Step 2: Create Database (if missing)**
1. In phpMyAdmin, go to top
2. Click "Databases" tab
3. Find field "Create new database"
4. Type exactly: deped_evaluation
5. Make sure "utf8_general_ci" is selected
6. Click "Create"
7. Database created

**Step 3: Import Tables (if missing)**
1. Go to newly created database
2. Database should be empty
3. Go to "Import" tab
4. Click "Choose File"
5. Navigate to: database/schema.sql
6. Click "Go" or "Import"
7. Tables created from schema file

**Step 4: Verify Tables**
1. Refresh database
2. Left side should show tables:
   - applicants
   - evaluations
   - positions
   - comparative_assessment_results
   - users (optional)

**Step 5: Try Application Again**
1. Go to application
2. Try inserting sample data
3. Should work now

### Problem: Wrong Credentials

**Error Message:**
```
Access denied for user 'root'@'localhost'
Permission denied
Authentication failed
```

**What's Happening:**
- Database username/password wrong
- Config file has incorrect credentials

**Step-by-Step Fix:**

**Step 1: Check Default Credentials**
- Default XAMPP credentials:
  - Username: root
  - Password: (blank/empty)

**Step 2: Find Config File**
1. Open file: config/database.php
2. Look for these lines:
   ```php
   'host' => 'localhost',
   'user' => 'root',
   'password' => '',
   'database' => 'deped_evaluation',
   ```

**Step 3: Verify Username**
- If not "root", change to "root"
- XAMPP default is "root"

**Step 4: Verify Password**
- If password is set, leave it
- If blank, remove the password
- For XAMPP, usually blank

**Step 5: Verify Host**
- Should be "localhost"
- Not an IP address
- Not a domain name

**Step 6: Verify Database**
- Should be "deped_evaluation"
- Correct spelling
- Correct case

**Step 7: Save and Refresh**
1. Save config/database.php
2. Go back to application
3. Refresh page
4. Should connect now

**If Still Failing:**
1. Verify in phpMyAdmin
2. phpMyAdmin URL: http://localhost/phpmyadmin
3. If phpMyAdmin loads, credentials are right
4. If phpMyAdmin doesn't load, credentials wrong

---

## Data Saving Issues

### Problem: Evaluation Submits But Doesn't Save

**Symptom:**
- Fill form, click Submit
- See success message
- But data missing from results
- Data not in database

**Causes:**
- Database connection failing
- Insert statement failing
- Transaction rolling back
- Constraint violation

**Step-by-Step Fix:**

**Step 1: Check Form Submission**
1. Open Developer Console (F12)
2. Click "Network" tab
3. Try submitting form
4. Look for POST request
5. Should see 200 status (success) or 500 (error)
6. If 500, server error occurred

**Step 2: Check Application Code Uniqueness**
1. This is most common cause
2. Each application code must be unique
3. Never submit same code twice
4. Try with NEW code like:
   - Change APP-001 to APP-002
   - Change SAMPLE-001 to TEST-001
5. Try submitting again

**Step 3: Check Database Directly**
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Go to database: deped_evaluation
3. Click table: evaluations
4. Look for your data
5. If there:
   - Data saved OK
   - Problem is with display
6. If not there:
   - Data save failed
   - See next steps

**Step 4: Check Applicant Exists**
1. In phpMyAdmin
2. Click table: applicants
3. Look for applicant with your name
4. If not there, applicant record missing
5. Might need to be created first

**Step 5: Check Logs**
1. Open XAMPP error log
2. Click Apache > Logs > Error
3. Scroll to bottom
4. Look for recent errors
5. Note error message
6. Helps identify problem

**Step 6: Insert Sample Data Instead**
1. Go to: http://localhost/DEPEDEvaluationSystem/insert_sample_applicants.php
2. Click "Insert 4 Sample Applicants"
3. Check if sample data appears
4. If sample works, issue is with form
5. If sample fails, issue is with database

### Problem: Data Duplicated

**Symptom:**
- Submit evaluation
- See same applicant twice
- Same code appears multiple times
- Duplicate entries

**Causes:**
- Submitted form twice (double-click)
- Form submitted twice by accident
- System created duplicate records
- Application code already exists

**Step-by-Step Fix:**

**Step 1: Don't Worry**
- Duplicates can be deleted
- Database intact, just extra records

**Step 2: Identify Duplicates**
1. Open phpMyAdmin
2. Go to table: evaluations
3. Look for duplicate rows
4. Sort by application_code
5. Same codes next to each other = duplicates

**Step 3: Delete Duplicates (CAREFULLY)**
1. Click on duplicate row (checkbox)
2. At bottom, find "With selected"
3. Choose "Delete"
4. Click "Go"
5. Row deleted

**Important: Double-check before deleting!**

**Step 4: Clear Results Cache**
1. Go to results page
2. Refresh (F5) or Full refresh (Ctrl+F5)
3. Duplicates should be gone

**Step 5: Be Careful Submitting**
- Don't double-click Submit button
- Wait for success message
- One click only

---

## Data Retrieval Issues

### Problem: Data Saved But Not Appearing in Results

**Symptom:**
- Data saves to database
- Results page shows nothing
- Submitted correctly but can't see it

**Causes:**
- Results page query failing
- Filter hiding data
- Wrong position selected
- Page not refreshed
- Ranking not calculated

**Step-by-Step Fix:**

**Step 1: Refresh Results Page**
1. Go to results page
2. Press F5 (normal refresh)
3. Wait for full load
4. Might just need refresh

**Step 2: Full Browser Refresh**
1. Press Ctrl+F5
2. Clears cache
3. Reloads completely
4. Try again

**Step 3: Check Position Selection**
1. Go to results page (View by Position)
2. Find dropdown: "Select Position to View:"
3. Make sure correct position selected
4. Select different positions
5. Your data should appear in correct position

**Step 4: Verify Database**
1. Open phpMyAdmin
2. Table: comparative_assessment_results (CAR)
3. Should see your applicant here
4. If not, CAR record wasn't created
5. See [CAR Record Not Created](#car-record-not-created)

**Step 5: Check All Results**
1. Go to View All results
2. Use URL: ...comparative_assessment_results.php?view=all
3. Scroll through all positions
4. Your data might be in different position
5. Check all positions before assuming missing

**Step 6: Try Sample Data**
1. Insert sample data
2. Sample data should appear
3. If sample appears but yours doesn't:
   - Might be issue with your specific data
   - Check application code for typos
   - Check position exists
   - Re-submit with corrected data

### Problem: CAR Record Not Created

**Symptom:**
- Data in evaluations table
- But not in comparative_assessment_results table
- Results page empty
- CAR not generating

**Causes:**
- CAR generation code not running
- Position doesn't exist
- Evaluation incomplete
- Hidden form fields missing

**Step-by-Step Fix:**

**Step 1: Verify Form Hidden Fields**
1. Open form: http://localhost/DEPEDEvaluationSystem/index.php
2. Press F12 (Developer Tools)
3. Click "Elements" or "Inspector"
4. Find form tag
5. Look for these hidden fields:
   ```html
   <input type="hidden" name="save_to_database" value="1">
   <input type="hidden" name="save_to_car" value="1">
   ```
6. If missing, CAR won't generate
7. Contact admin to add them

**Step 2: Check Position Exists**
1. Form must select valid position
2. Position must exist in positions table
3. Open phpMyAdmin
4. Table: positions
5. Look for position you selected
6. If not there, must create it first

**Step 3: Manual CAR Generation**
1. If CAR not generating automatically
2. Contact system administrator
3. They can manually trigger CAR generation
4. Using database query or PHP command

**Step 4: Check CAR Table**
1. Open phpMyAdmin
2. Table: comparative_assessment_results
3. Should have rows for each evaluation
4. If empty, CAR generation not working
5. Admin needs to investigate

---

## Database Errors

### Error: "Constraint Violation"

**Error Message:**
```
Integrity constraint violation
Unique constraint failed
Foreign key constraint violation
```

**What It Means:**
- Database rule was violated
- Usually duplicate key or missing foreign key
- Most common: duplicate application code

**How to Fix:**
1. Check what constraint failed (from error message)
2. Most likely: duplicate application code
3. Use NEW, unique code
4. Never reuse application codes
5. Try submitting again

### Error: "Column Not Found"

**Error Message:**
```
Unknown column 'column_name'
Column does not exist
```

**What It Means:**
- Database table is missing a column
- Schema not created properly
- Table corrupted or incomplete

**How to Fix:**
1. Check database schema
2. Run schema.sql file again
3. This recreates all tables with correct columns
4. Contact admin if persists
5. Might need full database reset

### Error: "Too Many Connections"

**Error Message:**
```
Too many connections
Maximum connections exceeded
```

**What It Means:**
- Database has too many open connections
- Usually from testing or bugs
- Might be memory leak

**How to Fix:**
1. Restart MySQL
   - Open XAMPP Control Panel
   - Click MySQL "Stop"
   - Wait 10 seconds
   - Click MySQL "Start"
2. Restart XAMPP
   - Stop all services
   - Wait 20 seconds
   - Start all services
3. If happens again, contact admin

---

## Database Reset

### Scenario: Need to Clear All Data

**When You Might Need This:**
- Testing complete, want fresh start
- Old data no longer needed
- Database corrupted, need to reset
- Want to start with clean slate

**How to Reset:**

**Option 1: Delete Database**
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Find database: deped_evaluation
3. Right-click on it
4. Choose "Delete"
5. Confirm deletion
6. Database deleted

**Option 2: Create Fresh Database**
1. After deleting, go to Databases tab
2. Create new database: deped_evaluation
3. Go to Import tab
4. Select file: database/schema.sql
5. Click Import
6. Tables recreated

**Option 3: Use Sample Data Tool**
1. Don't delete entire database
2. Just run sample data tool
3. It clears SAMPLE-* records
4. But keeps structure
5. Simpler than full reset

**Option 4: Clear Table Contents**
1. Open phpMyAdmin
2. Select table (e.g., evaluations)
3. Right-click
4. Choose "Empty"
5. Table cleared but structure remains
6. Can still insert data

### Scenario: Corrupted Database

**Symptoms:**
- Getting repeated errors
- Data missing
- Queries failing
- Can't insert data anymore

**How to Fix:**

**Step 1: Try to Repair**
1. Open phpMyAdmin
2. Right-click database
3. Look for "Repair" or "Check" option
4. Select "Repair"
5. Wait for completion
6. See if fixed

**Step 2: If Repair Doesn't Work**
1. Export data first (backup)
2. Delete entire database
3. Create new database from schema.sql
4. Re-insert data from export

**Step 3: If Still Problems**
1. Delete database
2. Delete all tables
3. Create new from scratch
4. Use sample data tool
5. Contact admin for help

---

## Backup and Maintenance

### Regular Backups

**Why Backup?**
- Protect data from loss
- Recover if database corrupted
- Archive historical data
- Prepare for system upgrades

**How to Backup:**

**Method 1: Export Database**
1. phpMyAdmin > Select database
2. Go to "Export" tab
3. Choose "SQL" format
4. Click "Go"
5. File downloads
6. Save to USB/cloud

**Method 2: Export Tables**
1. phpMyAdmin > Select table
2. Go to "Export" tab
3. Choose "CSV" format (for spreadsheets)
4. Click "Go"
5. File downloads

**Method 3: Full XAMPP Backup**
1. Stop XAMPP
2. Backup entire htdocs folder
3. Backup MySQL data folder
4. Store copies on external drive
5. Can restore from backup if needed

### Database Maintenance

**Periodic Tasks:**

**Weekly:**
- Export data
- Backup to external drive
- Verify data correct

**Monthly:**
- Check for duplicates
- Clean up test data
- Archive old evaluations

**Quarterly:**
- Full database backup
- Check for corruption
- Optimize tables

---

## Common Database Queries

**View All Applicants:**
```sql
SELECT * FROM applicants;
```

**View All Evaluations:**
```sql
SELECT * FROM evaluations;
```

**View CAR Results:**
```sql
SELECT * FROM comparative_assessment_results ORDER BY position_id, rank;
```

**Clear Sample Data:**
```sql
DELETE FROM comparative_assessment_results WHERE applicant_code LIKE 'SAMPLE-%';
DELETE FROM evaluations WHERE applicant_id IN (SELECT id FROM applicants WHERE application_code LIKE 'SAMPLE-%');
DELETE FROM applicants WHERE application_code LIKE 'SAMPLE-%';
```

---

## ⏱️ Troubleshooting Checklist

**For Database Issues:**

- [ ] 1. Verify MySQL running (green in XAMPP)
- [ ] 2. Check phpMyAdmin works
- [ ] 3. Verify database exists
- [ ] 4. Verify tables exist
- [ ] 5. Check data in tables (phpMyAdmin)
- [ ] 6. Try inserting sample data
- [ ] 7. Check browser console for errors
- [ ] 8. Restart XAMPP
- [ ] 9. Check application code uniqueness
- [ ] 10. Contact admin if still failing

---

**More help →**

**1. [TROUBLESHOOTING.md](./TROUBLESHOOTING.md) - For general problems**
**2. [FAQ.md](./FAQ.md) - For common questions**
**3. [../IMPLEMENTATION/DATABASE_STRUCTURE.md](../IMPLEMENTATION/DATABASE_STRUCTURE.md) - Database schema details**
