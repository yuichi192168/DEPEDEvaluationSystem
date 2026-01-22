# 🔧 TROUBLESHOOTING - Common Problems and Solutions

Solutions for common problems and errors.

**Reading time: 20 minutes**

---

## 📋 Quick Problem Finder

**Page won't load?** → [Page Won't Load](#page-wont-load)

**Data not saving?** → [Database Issues](#database-issues)

**Results not showing?** → [No Results Showing](#no-results-showing)

**Print looks wrong?** → [Print Issues](#print-issues)

**Export not working?** → [Export Issues](#export-issues)

**Error message?** → [Error Messages](#error-messages)

**Form won't submit?** → [Form Issues](#form-issues)

---

## Quick Fixes (Try These First!)

### Fix 1: Refresh the Page
```
Press F5 (or Ctrl+R)
Wait for page to reload
Try again
```

### Fix 2: Full Browser Refresh
```
Press Ctrl+F5 (or Cmd+Shift+R on Mac)
This clears cache and reloads completely
Try again
```

### Fix 3: Restart XAMPP
```
1. Click XAMPP control panel
2. Stop Apache and MySQL
3. Wait 5 seconds
4. Start Apache and MySQL
5. Wait for them to fully start
6. Try again
```

### Fix 4: Close and Reopen Browser
```
1. Close browser completely
2. Wait 5 seconds
3. Reopen browser
4. Go to http://localhost/...
5. Try again
```

---

## Page Won't Load

### Problem: 404 - Page Not Found

**Error Message:**
```
404 - Not Found
The requested URL was not found on this server
```

**Causes:**
- Wrong URL spelling
- XAMPP not running
- File doesn't exist
- Wrong directory path

**Solutions:**

**Check XAMPP:**
1. Open XAMPP Control Panel
2. Verify Apache is running (green "Running" button)
3. Verify MySQL is running (green "Running" button)
4. If not, click "Start" for each

**Check URL:**
1. Verify URL spelling exactly:
   - http://localhost/DEPEDEvaluationSystem/index.php
2. Check for typos
3. Case sensitive on Linux (if on Linux server)

**Verify File Exists:**
1. Open file explorer
2. Navigate to: C:\xampp\htdocs\DEPEDEvaluationSystem\
3. Verify files exist:
   - index.php ✓
   - comparative_assessment_results.php ✓
   - insert_sample_applicants.php ✓

**Fix:**
- Correct the URL spelling
- Restart XAMPP
- Verify files exist

---

### Problem: 500 - Internal Server Error

**Error Message:**
```
500 - Internal Server Error
The server encountered an unexpected condition
```

**Causes:**
- PHP error in code
- Database connection failed
- Missing database
- Incorrect credentials
- PHP not enabled

**Symptoms:**
- Page loads but shows error
- No data displayed
- Error log has details

**Solutions:**

**Check PHP Errors:**
1. Press F12 (open Developer Tools)
2. Click "Console" tab
3. Look for red errors
4. Note the error message
5. Search for that specific error

**Check XAMPP PHP:**
1. Open XAMPP Control Panel
2. Verify Apache is running
3. Click "Apache > Config > PHP (php.ini)"
4. Scroll down, verify no syntax errors
5. Try restarting Apache

**Check Database Connection:**
1. Ensure MySQL is running
2. Try accessing phpMyAdmin: http://localhost/phpmyadmin
3. If phpMyAdmin works, database is running
4. If not, click MySQL "Start" button

**Get More Info:**
1. Open XAMPP control panel
2. Click "Apache > Logs > Error"
3. Scroll to bottom
4. Look for recent errors
5. Note the error message and line number

**Fix:**
- Fix PHP syntax errors
- Restart Apache
- Ensure MySQL running
- Check database credentials

---

### Problem: Blank White Page

**Symptom:**
- Page loads but completely blank
- No content visible
- No error message

**Causes:**
- PHP error with error reporting off
- CSS not loading (display issue)
- JavaScript blocking content
- Timeout or very slow loading

**Solutions:**

**Wait for Page:**
1. Wait 30 seconds for full load
2. Page might be very slow
3. Check for loading spinner
4. Try refreshing

**Enable Error Display:**
1. Contact your admin
2. Ask to enable PHP error reporting
3. This will show specific errors
4. Helps identify problem

**Check Browser Console:**
1. Press F12
2. Click "Console" tab
3. Look for red X or errors
4. JavaScript errors will show here

**Try Different Browser:**
1. Try Chrome, Firefox, Safari, Edge
2. Different browsers can behave differently
3. Helps identify browser-specific issue

**Clear Browser Cache:**
1. Press Ctrl+Shift+Del
2. Check "Cached images and files"
3. Click "Clear Data"
4. Refresh page

---

### Problem: Took Too Long to Load

**Error Message:**
```
This page took too long to load
Server stopped responding
```

**Causes:**
- Server overloaded
- Large database query taking time
- Network connection slow
- XAMPP performance issue

**Solutions:**

**Wait Longer:**
- These errors sometimes self-resolve
- Give it 2-3 minutes
- Try refreshing

**Restart XAMPP:**
1. Stop Apache and MySQL (click Stop)
2. Wait 10 seconds
3. Start both again (click Start)
4. Wait for green "Running" indicators
5. Try again

**Check Server:**
1. Go to http://localhost
2. Should show XAMPP welcome page
3. If this works, server is fine
4. Problem might be with specific page

**Try Different Time:**
1. Server might be temporarily busy
2. Try later when less busy
3. Or try during off-hours

---

## No Results Showing

### Problem: Evaluation Submitted But Results Empty

**Symptom:**
- Submitted evaluation successfully
- Go to results page
- No applicants showing
- Results page empty

**Causes:**
- Database save failed silently
- Evaluation data not inserted
- Results not generated
- Page not refreshed after submit
- Wrong position selected

**Solutions:**

**Refresh Page:**
1. Press F5 (refresh)
2. Wait for page reload
3. Check if data appears
4. Sometimes page doesn't auto-refresh

**Insert Sample Data:**
1. Go to: http://localhost/DEPEDEvaluationSystem/insert_sample_applicants.php
2. Click "Insert 4 Sample Applicants"
3. Verify success message
4. Go back to results
5. Sample data should appear

**Check Database:**
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Go to database: deped_evaluation
3. Click table: evaluations
4. Check if new rows exist
5. If not, data didn't save

**If Using Sample Data Tool:**
1. Note: It clears old SAMPLE data first
2. So SAMPLE-001 etc will be replaced
3. Make sure you want to do this
4. Then click Insert button

**Submit New Evaluation:**
1. Go to form: http://localhost/DEPEDEvaluationSystem/index.php
2. Fill out form completely
3. Make sure to fill ALL fields
4. Click Submit
5. Watch for success message
6. Then check results

---

### Problem: Results Show But Wrong Applicants

**Symptom:**
- Results showing
- But wrong names or codes
- Or missing some applicants
- Applicants in wrong positions

**Causes:**
- Wrong data entered in form
- Database corruption
- Filter active (showing wrong position)
- Results not refreshed

**Solutions:**

**Verify Form Data:**
1. Remember what you entered
2. Go to results
3. Check if data matches
4. If not matching, data entered wrong

**Check Position Selection:**
1. Go to results page
2. Verify correct position selected
3. Different positions show different applicants
4. Select each position to see all data

**Refresh Database:**
1. Contact system admin
2. Request to clear sample data
3. Re-insert sample data
4. Try again with fresh data

---

## Database Issues

### Problem: Database Connection Failed

**Error Message:**
```
Database Connection Failed
Unable to connect to database
Connection refused
```

**Causes:**
- MySQL not running
- Wrong credentials
- Database doesn't exist
- Firewall blocking
- MySQL service stopped

**Solutions:**

**Start MySQL:**
1. Open XAMPP Control Panel
2. Find "MySQL" row
3. If not green "Running", click "Start"
4. Wait for green indicator
5. Try again

**Verify phpMyAdmin:**
1. Go to: http://localhost/phpmyadmin
2. Should open without error
3. If error, MySQL isn't running properly
4. Restart XAMPP

**Check Database Exists:**
1. In phpMyAdmin, look at left sidebar
2. Scroll down
3. Look for database named: "deped_evaluation"
4. If not there, database needs to be created

**Check Credentials:**
1. Open config/database.php
2. Verify settings:
   ```
   Host: localhost
   Username: root
   Password: (usually blank)
   Database: deped_evaluation
   ```
3. Update if incorrect
4. Try again

**If Still Failing:**
1. Contact your system administrator
2. Provide error message
3. Provide steps you've tried
4. They can diagnose server issue

---

### Problem: Data Not Saving

**Symptom:**
- Submit evaluation
- Get success message
- But data missing from results
- Data doesn't persist

**Causes:**
- Database connection failing silently
- Wrong save parameters
- Transaction failed
- Constraints violated
- Permissions issue

**Solutions:**

**Check Success Message:**
- If you see success message, form submitted
- But data might not actually save
- This indicates database issue

**Verify Unique Code:**
1. Go to form again
2. Try submitting with NEW application code
3. Use code like APP-001, APP-002, etc
4. Never use same code twice
5. Duplicate codes will fail

**Check Database:**
1. Open phpMyAdmin
2. Go to evaluations table
3. Look for your data
4. If not there, save failed

**Try Insert Sample Data:**
1. Go to: http://localhost/DEPEDEvaluationSystem/insert_sample_applicants.php
2. Click Insert button
3. Check if sample data appears in results
4. If sample data works, form issue
5. If sample data fails, database issue

**Contact Admin:**
- If neither works
- Database might be corrupted
- Admin might need to reset
- Provide details of attempts

---

## Print Issues

### Problem: All Columns Don't Fit on Page

**Symptom:**
- Print preview shows columns cut off
- Right side of table missing
- Can't see all columns

**Causes:**
- Portrait orientation (should be Landscape)
- Page margins too large
- Font too large
- Columns too wide

**Solutions:**

**Change Orientation:**
1. Open Print dialog (Ctrl+P)
2. Find "Orientation" setting
3. Change from "Portrait" to "Landscape"
4. Check preview
5. All columns should fit now

**Adjust Margins:**
1. In Print dialog, find "Margins"
2. Set to "Minimum" or "0.5 inch"
3. This allows more content
4. Check preview

**Zoom Out:**
1. In Print dialog, find "Scale" or "Zoom"
2. Set to 80-90% (not 100%)
3. Makes content smaller to fit
4. Still readable
5. Check preview

**Use Landscape + Minimum Margins:**
1. Both together should work
2. All 13 columns will fit
3. Professional appearance
4. Readable text

---

### Problem: Print Quality Poor

**Symptom:**
- Printed page looks fuzzy
- Text not clear
- Lines broken
- Colors wrong

**Causes:**
- Printer quality low
- Ink cartridge low
- Print settings wrong
- Cheap paper

**Solutions:**

**Check Printer:**
1. Is printer on?
2. Is paper loaded?
3. Are cartridges full?
4. Does printer work for other documents?

**Check Print Settings:**
1. Open Print dialog
2. Verify correct printer selected
3. Set quality to "High" or "Best"
4. Set color mode correct (Color or B&W)
5. Try again

**Use Better Printer:**
1. Use higher quality printer if available
2. Use professional printer (not budget model)
3. Ink jets usually lower quality
4. Laser printers usually better

**Use Better Paper:**
1. Use quality paper (not cheap copy paper)
2. Use white paper (not colored)
3. A4 size
4. Better paper = better print

---

## Export Issues

### Problem: Export Button Doesn't Work

**Symptom:**
- Click Export button
- Nothing happens
- No file downloads
- No error message

**Causes:**
- JavaScript not enabled
- Browser blocking download
- Server error (silent)
- Button not responding

**Solutions:**

**Enable JavaScript:**
1. Chrome: Settings > Privacy > JavaScript
2. Firefox: about:config > search "javascript"
3. Safari: Preferences > Security
4. Edge: Settings > Privacy > JavaScript
5. Verify JavaScript enabled

**Check Browser Downloads:**
1. Browser might block download
2. Check download notification bar
3. Allow download if blocked
4. Try again

**Try Different Browser:**
1. Try Chrome, Firefox, Safari, Edge
2. Different browsers sometimes work better
3. Helps identify browser issue

**Check Console:**
1. Press F12
2. Click "Console" tab
3. Look for error messages
4. Red X or error text
5. Note the error and contact admin

---

### Problem: Exported File Won't Open

**Symptom:**
- File downloads
- Try to open in Excel
- File corrupted message
- Or file opens empty

**Causes:**
- File incomplete
- Server error during export
- File encoding issue
- Excel compatibility

**Solutions:**

**Re-Download File:**
1. Delete old downloaded file
2. Try exporting again
3. Wait for full download
4. Check file size (should be >0 bytes)
5. Try opening again

**Use Different Program:**
1. Try Google Sheets instead of Excel
2. Try LibreOffice (free alternative)
3. Try opening in text editor first
4. See if file has content

**Open as Text:**
1. Right-click file
2. Choose "Open With > Notepad"
3. Should see comma-separated values
4. If blank, file export failed
5. Try export again

**Contact Admin:**
- If still failing
- File might be corrupted
- Admin can check export function
- Might need to manually extract data

---

## Form Issues

### Problem: Form Won't Submit

**Symptom:**
- Fill out all fields
- Click Submit
- Form doesn't submit
- No success message

**Causes:**
- Required fields missing
- Invalid data in field
- JavaScript error
- Server not responding

**Solutions:**

**Check Required Fields:**
1. All fields marked with * are required
2. Make sure none are blank
3. Especially position and applicant name
4. Fill all 8 score fields

**Check Field Values:**
1. Scores must be within range:
   - Education: 0-10
   - Training: 0-5
   - Experience: 0-20
   - Performance: 0-25
   - Accomplishments: 0-5
   - App of Education: 0-10
   - App of L&D: 0-10
   - Potential: 0-10
2. Correct any out-of-range values

**Check Browser Console:**
1. Press F12
2. Click "Console" tab
3. Look for red error messages
4. JavaScript errors will show
5. Note error and contact admin

**Verify Application Code:**
1. Application code must be unique
2. Cannot repeat same code
3. If code exists, form will fail silently
4. Use different code (like APP-002)

**Refresh and Try Again:**
1. Press F5 to refresh
2. Fill form again
3. Try submitting
4. Sometimes helps

---

## Error Messages

### Common Error: "Missing Required Field"

**What It Means:**
- You left a field blank
- System requires all fields filled

**How to Fix:**
- Find which field is blank (marked with *)
- Fill in that field
- Usually position or applicant name
- Try submitting again

### Common Error: "Invalid Score - Must be between 0-X"

**What It Means:**
- Score you entered is outside allowed range
- Each field has different max value

**How to Fix:**
- Verify allowed range for that field
- Correct the value
- Common mistake: Entering 25 for Education (max is 10)
- Try submitting again

### Common Error: "Duplicate Application Code"

**What It Means:**
- Application code already used
- Each code must be unique

**How to Fix:**
- Use a different code
- Never reuse application codes
- Try NEW code like APP-002 instead of APP-001
- Try submitting again

### Common Error: "Database Connection Failed"

**What It Means:**
- Cannot connect to database
- Usually MySQL not running

**How to Fix:**
- See [Database Issues](#database-issues) section
- Start MySQL in XAMPP
- Verify database exists
- Try again

---

## ⏱️ Troubleshooting Checklist

**When Something Doesn't Work:**

- [ ] 1. Refresh page (F5)
- [ ] 2. Full browser refresh (Ctrl+F5)
- [ ] 3. Restart XAMPP
- [ ] 4. Close/reopen browser
- [ ] 5. Check XAMPP both running (green)
- [ ] 6. Verify URL correct
- [ ] 7. Check browser console (F12)
- [ ] 8. Try different browser
- [ ] 9. Clear browser cache
- [ ] 10. Contact admin if still failing

---

**More help →**

**1. [DATABASE_FIXES.md](./DATABASE_FIXES.md) - For database issues**
**2. [FAQ.md](./FAQ.md) - For common questions**
**3. [../REFERENCE/COMPLETE_GUIDE.md](../REFERENCE/COMPLETE_GUIDE.md) - For detailed info**
