# ❓ FAQ - Frequently Asked Questions

Answers to common questions about the system and how to use it.

**Reading time: 15 minutes**

---

## 📋 Table of Contents

1. [General Questions](#general-questions)
2. [How-To Questions](#how-to-questions)
3. [Data Questions](#data-questions)
4. [Technical Questions](#technical-questions)
5. [Troubleshooting Questions](#troubleshooting-questions)

---

## General Questions

### Q: What is the DepEd HRMPSB Evaluation System?

**A:** It's a web-based tool for managing teacher recruitment and evaluation. It:
- Accepts evaluation scores for applicants
- Calculates weighted total scores
- Generates Comparative Assessment Results (CAR)
- Ranks applicants by position
- Produces professional reports

### Q: Who can use this system?

**A:** Authorized personnel involved in teacher recruitment:
- Evaluators (submit scores)
- HR staff (review results)
- Department heads (make decisions)
- Administrators (manage system)

### Q: Is this system official/authorized?

**A:** Yes! It follows:
- DepEd standards
- HRMPSB guidelines
- Official evaluation format
- Professional compliance requirements

### Q: What data does the system store?

**A:** The system stores:
- Applicant names and contact info
- Application codes
- Position information
- All evaluation scores
- Calculated rankings
- Comparative assessment results

### Q: Is my data secure?

**A:** Yes, the system:
- Stores data in secure MySQL database
- Uses web server (Apache/PHP)
- Requires XAMPP/local server
- Access controlled by IT administrator
- Should implement user login for production

### Q: Can I delete data after submitting?

**A:** The system doesn't have a built-in delete feature for evaluations. You should:
- Contact system administrator
- Provide evaluation details
- Request deletion from database
- Get confirmation of deletion

### Q: How long does data stay in system?

**A:** Data stays indefinitely unless:
- You manually delete it (contact admin)
- You run sample data tool (clears SAMPLE-* records)
- System administrator archives old data
- Database maintenance removes it

---

## How-To Questions

### Q: How do I submit an evaluation?

**A:** Follow these steps:
1. Go to http://localhost/DEPEDEvaluationSystem/index.php
2. Select position from dropdown
3. Enter applicant name and code
4. Enter all 8 evaluation scores
5. Click "Submit Evaluation"
6. See success message with ranking

For detailed instructions, see [../REFERENCE/FEATURE_GUIDE.md#evaluation-form](../REFERENCE/FEATURE_GUIDE.md#evaluation-form)

### Q: How do I view the results?

**A:** Two ways to view:

**Option 1: View All (quick overview)**
- Go to http://localhost/DEPEDEvaluationSystem/comparative_assessment_results.php?view=all
- See all applicants grouped by position

**Option 2: View by Position (detailed)**
- Go to http://localhost/DEPEDEvaluationSystem/comparative_assessment_results.php
- Select position from dropdown
- Click "View Results"
- See detailed CAR

For details, see [../REFERENCE/FEATURE_GUIDE.md#viewing-results](../REFERENCE/FEATURE_GUIDE.md#viewing-results)

### Q: How do I print the CAR?

**A:** Steps:
1. View detailed CAR (select position)
2. Click "Print" button
3. Choose printer and set orientation to "Landscape"
4. Preview to verify content
5. Click "Print"

For details, see [../REFERENCE/FEATURE_GUIDE.md#printing-car](../REFERENCE/FEATURE_GUIDE.md#printing-car)

### Q: How do I export data to Excel?

**A:** Steps:
1. View detailed CAR (select position)
2. Click "Export" or "Download CSV" button
3. File downloads
4. Open in Excel or Google Sheets
5. Format and analyze as needed

For details, see [../REFERENCE/FEATURE_GUIDE.md#exporting-data](../REFERENCE/FEATURE_GUIDE.md#exporting-data)

### Q: How do I insert sample data?

**A:** Steps:
1. Go to http://localhost/DEPEDEvaluationSystem/insert_sample_applicants.php
2. Click "➕ Insert 4 Sample Applicants" button
3. Wait for success message
4. 4 applicants now in system ready for testing

For details, see [../REFERENCE/FEATURE_GUIDE.md#sample-data-tool](../REFERENCE/FEATURE_GUIDE.md#sample-data-tool)

### Q: How do I get sample data again after deleting?

**A:** Simply re-run the sample data tool:
1. Go to http://localhost/DEPEDEvaluationSystem/insert_sample_applicants.php
2. Click the button
3. New sample data inserted
4. Old sample data cleared

### Q: How do I start with empty database?

**A:** Contact your system administrator:
- Request database reset
- They can clear all data
- Or you can run sample data tool (clears previous sample data first)

---

## Data Questions

### Q: How are the evaluation scores weighted?

**A:** Each score has a different weight in final calculation:

| Criterion | Weight | Points |
|-----------|--------|--------|
| Performance | 25% | 0-25 |
| Experience | 20% | 0-20 |
| Education | 15% | 0-10 |
| App of Education | 10% | 0-10 |
| App of L&D | 10% | 0-10 |
| Potential | 10% | 0-10 |
| Accomplishments | 5% | 0-5 |
| Training | 5% | 0-5 |
| **TOTAL** | **100%** | **0-100** |

For explanation, see [../REFERENCE/COMPLETE_GUIDE.md#scoring-system](../REFERENCE/COMPLETE_GUIDE.md#scoring-system)

### Q: How are rankings calculated?

**A:** Rankings are calculated per position:
1. All applicants for a position ranked by total score
2. Highest score = Rank 1
3. Next highest = Rank 2
4. And so on...
5. Ties get same rank (next rank skips)

For details, see [../REFERENCE/CAR_FORMAT_GUIDE.md#ranking-system](../REFERENCE/CAR_FORMAT_GUIDE.md#ranking-system)

### Q: Can an applicant have different ranks for different positions?

**A:** Yes! Applicants are ranked separately per position:
- Same person can be Rank 1 for Position A
- But Rank 3 for Position B
- Rankings are position-specific, not system-wide

### Q: What does each scoring column mean?

**A:** See the 13 columns in the CAR:

1. **Education** (0-10): Qualification level
2. **Training** (0-5): Professional development
3. **Experience** (0-20): Years in field
4. **Performance** (0-25): Current job rating
5. **Accomplishments** (0-5): Awards/achievements
6. **App of Education** (0-10): Uses education on job
7. **App of L&D** (0-10): Uses learning on job
8. **Potential** (0-10): Growth capability

For details, see [../REFERENCE/CAR_FORMAT_GUIDE.md#data-columns](../REFERENCE/CAR_FORMAT_GUIDE.md#data-columns)

### Q: Can I change scores after submitting?

**A:** The system doesn't have built-in edit feature. To change scores:
1. Contact system administrator
2. Request modification to specific evaluation
3. They can update database
4. Ranking automatically recalculates

OR

1. Submit a new evaluation with corrected scores
2. Use different application code
3. Old evaluation stays, new one created
4. Use new evaluation for final decision

### Q: What if two applicants have the same score?

**A:** They are tied:
- Both get same rank number
- Next applicant's rank skips (e.g., 1, 2, 2, 4)
- Both equally qualified
- HR makes tie-breaking decision if needed

### Q: What's a good score?

**A:**
- 9.0+: Excellent (top candidate)
- 8.0-8.9: Very Good (strong candidate)
- 7.0-7.9: Good (qualified candidate)
- 6.0-6.9: Acceptable (meets requirements)
- 5.0-5.9: Below Average (concerning)
- <5.0: Poor (significant concerns)

---

## Technical Questions

### Q: What technology does this system use?

**A:** The system uses:
- **Server:** Apache (from XAMPP)
- **Language:** PHP 7+
- **Database:** MySQL/MariaDB
- **Frontend:** HTML5, CSS3, JavaScript
- **Framework:** Custom PHP (no external framework)

### Q: Do I need to be online?

**A:** No! System runs on local server:
- XAMPP running on your computer
- Accessible at http://localhost
- No internet connection needed
- No cloud storage
- All data on your local machine

### Q: What's required to run the system?

**A:** You need:
- ✅ Windows/Mac/Linux computer
- ✅ XAMPP installed and running
- ✅ PHP enabled
- ✅ MySQL enabled
- ✅ Web browser (Chrome, Firefox, Safari, Edge)

For setup details, see [../../SETUP/START_HERE.md](../../SETUP/START_HERE.md)

### Q: How do I back up my data?

**A:** Several backup options:

**Option 1: Database Backup**
- Use database management tool (phpMyAdmin)
- Export database as SQL file
- Save file to USB/cloud

**Option 2: CSV Export**
- Export each CAR as CSV
- Save to multiple locations
- Can re-import if needed

**Option 3: Full XAMPP Backup**
- Backup entire htdocs folder
- Backup MySQL data folder
- Store copies elsewhere

For more details, contact your system administrator.

### Q: Can multiple people use it at the same time?

**A:** If all on same local computer: No, one at a time

If on network with proper setup:
- Yes, multiple users possible
- Requires network XAMPP setup
- Requires user authentication
- Not standard setup for this system

### Q: Can I access it from outside my office?

**A:** Not recommended in current setup:
- System is local/internal only
- Not designed for remote access
- Would require significant setup changes
- Security implications

Contact your IT administrator to discuss options.

---

## Troubleshooting Questions

### Q: The page won't load (404 error)

**A:** Check:
- XAMPP is running (Apache and MySQL both on)
- URL is spelled correctly
- File exists at path
- Browser shows exactly: http://localhost/DEPEDEvaluationSystem/index.php

See [TROUBLESHOOTING.md#404-errors](./TROUBLESHOOTING.md#404-errors)

### Q: I get a PHP error

**A:** Check:
- PHP is enabled in XAMPP
- Apache is running
- No syntax errors in form
- Database connection working

See [TROUBLESHOOTING.md#php-errors](./TROUBLESHOOTING.md#php-errors)

### Q: Sample data won't insert

**A:** Check:
- XAMPP MySQL is running
- Database connection working
- Clear SAMPLE-* data first
- No duplicates in table

See [DATABASE_FIXES.md#data-insertion-errors](./DATABASE_FIXES.md#data-insertion-errors)

### Q: Results not showing

**A:** Check:
- Sample data inserted
- Page refreshed (F5)
- Position selected (if viewing by position)
- No errors in console (F12)

See [TROUBLESHOOTING.md#no-results](./TROUBLESHOOTING.md#no-results-showing)

### Q: Print doesn't look right

**A:** Check:
- Orientation set to "Landscape"
- Paper size set to "A4"
- Margins set to 0.5"
- Preview checked
- All columns fit on page

See [../REFERENCE/FEATURE_GUIDE.md#print-tips](../REFERENCE/FEATURE_GUIDE.md#print-tips)

### Q: Export file won't open

**A:** Check:
- File downloaded completely
- Using Excel or Google Sheets
- File isn't corrupted
- Try right-click > Open With...

See [TROUBLESHOOTING.md#export-issues](./TROUBLESHOOTING.md#export-issues)

### Q: Database connection failing

**A:** Check:
- MySQL running in XAMPP
- Database "deped_evaluation" exists
- Database credentials correct
- No firewall blocking access

See [DATABASE_FIXES.md#connection-issues](./DATABASE_FIXES.md#connection-issues)

---

## ⏱️ Quick Reference

| Question | Answer | See |
|----------|--------|-----|
| How to submit | See 7-step form | [FEATURE_GUIDE.md](../REFERENCE/FEATURE_GUIDE.md) |
| How to view | Two options: all or by position | [FEATURE_GUIDE.md](../REFERENCE/FEATURE_GUIDE.md) |
| How to print | Use Print button, Landscape | [FEATURE_GUIDE.md](../REFERENCE/FEATURE_GUIDE.md) |
| How to export | Use Export button | [FEATURE_GUIDE.md](../REFERENCE/FEATURE_GUIDE.md) |
| How rankings work | Highest score = Rank 1 | [CAR_FORMAT_GUIDE.md](../REFERENCE/CAR_FORMAT_GUIDE.md) |
| Scoring weights | Performance 25%, Experience 20% | [COMPLETE_GUIDE.md](../REFERENCE/COMPLETE_GUIDE.md) |
| Getting help | See troubleshooting guides | [README.md](./README.md) |

---

**More help →**

**1. [TROUBLESHOOTING.md](./TROUBLESHOOTING.md) - For problems**
**2. [DATABASE_FIXES.md](./DATABASE_FIXES.md) - For database issues**
**3. [../REFERENCE/COMPLETE_GUIDE.md](../REFERENCE/COMPLETE_GUIDE.md) - For detailed info**
