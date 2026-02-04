# ✅ QUICK START TEST - Complete Testing Guide

Comprehensive testing procedures to verify everything is working correctly.

---

## 🧪 Testing Overview

This guide provides:
- ✅ Complete testing checklist
- ✅ Expected results for each test
- ✅ Verification procedures
- ✅ Troubleshooting tips

**Estimated time: 10-15 minutes**

---

## 🎯 Pre-Test Requirements

Before starting tests, ensure:
- [ ] Sample data inserted (from QUICK_START.md)
- [ ] You can access http://localhost/DEPEDEvaluationSystem/
- [ ] Browser is Chrome, Firefox, Safari, or Edge
- [ ] PHP and MySQL are running
- [ ] No error messages in browser console

---

## 🧪 TEST 1: Sample Data Insertion

### Test Steps

1. **Open URL:**
   ```
   http://localhost/DEPEDEvaluationSystem/insert_sample_applicants.php
   ```

2. **Verify page loads:**
   - [ ] Page displays without errors
   - [ ] No PHP error messages
   - [ ] Green button visible: "➕ Insert 4 Sample Applicants"
   - [ ] Instructions visible below button

3. **Click the button:**
   - [ ] Click "➕ Insert 4 Sample Applicants"

4. **Check for success message:**
   - [ ] Green success box appears
   - [ ] Message says: "✅ Sample Data Inserted Successfully"
   - [ ] Message shows: "4 applicants have been inserted"

### Expected Results

```
✅ PASS: If you see:
  - Green success message
  - "4 applicants have been inserted"
  - No red error messages
```

```
❌ FAIL: If you see:
  - Red error message
  - "Database error" or "Connection failed"
  - No success message
```

### Troubleshooting TEST 1

**If page doesn't load:**
- Check if PHP is running
- Check URL spelling
- Check if file exists at path

**If button doesn't work:**
- Try clicking again
- Refresh page and retry
- Check browser console (F12 > Console)

**If error about database:**
- Verify MySQL is running
- Check database connection (see [../../TROUBLESHOOTING/DATABASE_FIXES.md](../../TROUBLESHOOTING/DATABASE_FIXES.md))

---

## 🧪 TEST 2: View All Results Display

### Test Steps

1. **Open URL:**
   ```
   http://localhost/DEPEDEvaluationSystem/comparative_assessment_results.php?view=all
   ```

2. **Verify page loads:**
   - [ ] Page displays
   - [ ] No error messages
   - [ ] "COMPARATIVE ASSESSMENT RESULT - Annex I" appears

3. **Check for applicants:**
   - [ ] 4 applicant rows visible
   - [ ] Names match: Maria Santos, Juan Dela Cruz, Ana Reyes, Carlos Mendoza
   - [ ] Application codes match: SAMPLE-001, SAMPLE-002, SAMPLE-003, SAMPLE-004

4. **Check grouping:**
   - [ ] Applicants grouped by position
   - [ ] Maria + Juan in one position
   - [ ] Ana + Carlos in another position

5. **Check rankings:**
   - [ ] Maria has Rank 1
   - [ ] Juan has Rank 2
   - [ ] Ana has Rank 1
   - [ ] Carlos has Rank 2

### Expected Results

```
✅ PASS: If you see:
  - All 4 applicants displayed
  - Grouped by 2 positions
  - Rankings: 1, 2, 1, 2
  - All scores visible (9.38, 8.73, 8.08, 7.25)
```

```
❌ FAIL: If you see:
  - Fewer than 4 applicants
  - Wrong names or codes
  - Missing rankings
  - Error messages
```

### Troubleshooting TEST 2

**If applicants not showing:**
- Verify sample data was inserted (TEST 1)
- Try refreshing page
- Clear browser cache (Ctrl+Shift+Del)

**If scores are 0.00:**
- Data insertion may have failed
- Check database (see [../../TROUBLESHOOTING/DATABASE_FIXES.md](../../TROUBLESHOOTING/DATABASE_FIXES.md))

**If positions not grouped:**
- Page might not be loading fully
- Try different browser
- Check browser console for JavaScript errors

---

## 🧪 TEST 3: Position Selector

### Test Steps

1. **Open URL:**
   ```
   http://localhost/DEPEDEvaluationSystem/comparative_assessment_results.php
   ```

2. **Verify page loads:**
   - [ ] Page displays
   - [ ] Dropdown visible with label "Select Position to View:"
   - [ ] No error messages

3. **Click dropdown:**
   - [ ] Click the position dropdown
   - [ ] [ ] List opens showing positions

4. **Count positions:**
   - [ ] Exactly 2 positions appear (not empty ones)
   - [ ] Each shows applicant count in parentheses
   - [ ] Example: "Position Name (2 applicants)"

5. **Select first position:**
   - [ ] Click first position in list
   - [ ] Dropdown closes
   - [ ] Selected position appears in dropdown

6. **View results:**
   - [ ] Click "View Results" or position link
   - [ ] Detailed CAR table loads
   - [ ] 2 applicants shown for selected position

7. **Select second position:**
   - [ ] Repeat steps 5-6 for second position
   - [ ] Different 2 applicants shown

### Expected Results

```
✅ PASS: If you see:
  - Only 2 positions in dropdown (not empty ones)
  - Each position shows applicant count
  - Can select any position
  - Detailed table shows for selected position
  - Different applicants for each position
  - No empty positions in dropdown
```

```
❌ FAIL: If you see:
  - Dropdown empty or missing
  - More than 2 positions showing
  - Empty positions with 0 applicants
  - Error when selecting position
  - Wrong applicants showing
```

### Troubleshooting TEST 3

**If dropdown empty:**
- Sample data not inserted (do TEST 1 first)
- Database connection error (see [../../TROUBLESHOOTING/DATABASE_FIXES.md](../../TROUBLESHOOTING/DATABASE_FIXES.md))

**If showing empty positions:**
- Database query needs update (see [../../TROUBLESHOOTING/DATABASE_FIXES.md](../../TROUBLESHOOTING/DATABASE_FIXES.md))

**If selection doesn't work:**
- JavaScript error (F12 > Console > check for red errors)
- Page refresh needed

---

## 🧪 TEST 4: Detailed CAR Format

### Test Steps

1. **From TEST 3, open detailed view:**
   - Ensure you have a position selected
   - Detailed CAR table is displayed

2. **Verify header information:**
   - [ ] Position name displayed
   - [ ] Office/Bureau displayed
   - [ ] Plantilla Item Number displayed
   - [ ] Date of Final Deliberation displayed (January 22, 2026)

3. **Verify all columns present:**
   - [ ] NAME column visible
   - [ ] CODE column visible
   - [ ] Education column visible
   - [ ] Training column visible
   - [ ] Experience column visible
   - [ ] Performance column visible
   - [ ] Accomplishments column visible
   - [ ] Total column visible
   - [ ] Rank column visible

4. **Verify data accuracy:**
   - [ ] Maria Santos shows 9.38 score
   - [ ] Juan Dela Cruz shows 8.73 score
   - [ ] Ana Reyes shows 8.08 score
   - [ ] Carlos Mendoza shows 7.25 score

5. **Verify rankings per position:**
   - [ ] Position 1: Maria = Rank 1, Juan = Rank 2
   - [ ] Position 2: Ana = Rank 1, Carlos = Rank 2

6. **Check signature section:**
   - [ ] Signature lines visible at bottom
   - [ ] Professional format maintained

### Expected Results

```
✅ PASS: If you see:
  - All header info displayed
  - All 9+ columns visible and populated
  - Correct scores for each applicant
  - Correct rankings per position
  - Professional signature section
  - Format matches your reference image
```

```
❌ FAIL: If you see:
  - Missing columns
  - Wrong scores
  - Missing ranking information
  - Unprofessional format
  - Header info missing
```

### Troubleshooting TEST 4

**If columns missing:**
- Browser window too narrow
- Try maximizing browser window
- Try landscape mode if on tablet

**If scores incorrect:**
- Sample data has wrong values
- Re-run sample data insertion (clear first)

**If format looks wrong:**
- Check if using modern browser
- Try different browser
- Clear browser cache

---

## 🧪 TEST 5: Print Functionality

### Test Steps

1. **From detailed CAR view:**
   - Look for "Print" button
   - [ ] Print button visible

2. **Click Print button:**
   - [ ] Click "Print"
   - [ ] Print dialog opens (Ctrl+P opens if not automatic)

3. **Check print preview:**
   - [ ] Format looks professional
   - [ ] All columns visible
   - [ ] Text is readable
   - [ ] No overlapping content

4. **Don't print (just preview):**
   - [ ] Close print dialog without printing
   - [ ] Escape key or "Cancel"

### Expected Results

```
✅ PASS: If you see:
  - Print dialog opens
  - Preview shows professional format
  - A4 landscape layout
  - All content fits on page
  - Text is readable
```

```
❌ FAIL: If you see:
  - No print button
  - Print dialog doesn't open
  - Content cut off
  - Unreadable format
```

---

## 🧪 TEST 6: Export Functionality

### Test Steps

1. **From detailed CAR view:**
   - Look for "Export" button
   - [ ] Export button visible

2. **Click Export button:**
   - [ ] Click "Export to CSV"
   - [ ] File download starts
   - [ ] File saves to Downloads folder

3. **Verify downloaded file:**
   - [ ] File named correctly
   - [ ] File has .csv extension
   - [ ] File size is > 0 bytes

4. **Open with spreadsheet app:**
   - [ ] Right-click downloaded file
   - [ ] Choose "Open with Excel" or "Sheets"
   - [ ] Data displays in spreadsheet format
   - [ ] All columns visible
   - [ ] All applicants visible

5. **Verify data in spreadsheet:**
   - [ ] Headers in first row
   - [ ] Applicant data below
   - [ ] Scores are numbers (not text)
   - [ ] Rankings correct

### Expected Results

```
✅ PASS: If you see:
  - File downloads successfully
  - File has .csv extension
  - Opens in Excel/Sheets
  - All data visible
  - Professional spreadsheet layout
```

```
❌ FAIL: If you see:
  - No Export button
  - Download fails
  - File corrupt or empty
  - Data doesn't format correctly
```

---

## 📋 COMPLETE TEST CHECKLIST

Use this checklist to track all tests:

```
TEST 1: Sample Data Insertion
├─ [ ] Page loads without errors
├─ [ ] Green button visible
├─ [ ] Button works
├─ [ ] Success message shows
└─ [ ] Message confirms 4 applicants

TEST 2: View All Results
├─ [ ] Page loads without errors
├─ [ ] All 4 applicants visible
├─ [ ] Names and codes correct
├─ [ ] Applicants grouped by position
└─ [ ] Rankings correct (1,2,1,2)

TEST 3: Position Selector
├─ [ ] Dropdown visible
├─ [ ] Only 2 positions showing (no empty)
├─ [ ] Applicant counts showing
├─ [ ] Can select positions
└─ [ ] Correct applicants shown per position

TEST 4: Detailed CAR Format
├─ [ ] Header info complete
├─ [ ] All columns present
├─ [ ] Data accurate
├─ [ ] Rankings correct per position
└─ [ ] Format professional

TEST 5: Print
├─ [ ] Print button works
├─ [ ] Print dialog opens
├─ [ ] Preview looks professional
└─ [ ] All content visible

TEST 6: Export
├─ [ ] Export button works
├─ [ ] File downloads
├─ [ ] File opens in spreadsheet
└─ [ ] Data formatted correctly
```

---

## ✅ Pass/Fail Criteria

**PASS:** All 6 tests show all checkboxes marked ✅

**FAIL:** Any test has unchecked boxes ❌

**If FAILED:** Run troubleshooting steps in that test section

---

## 🚀 Next Steps After Testing

**If all tests PASS:**
✅ System is working correctly
✅ Ready to add real data
✅ Ready for production use

**If any test FAILS:**
❌ Run troubleshooting steps in that test
❌ Check [../../TROUBLESHOOTING/FAQ.md](../../TROUBLESHOOTING/FAQ.md) for solutions
❌ Check [../../TROUBLESHOOTING/DATABASE_FIXES.md](../../TROUBLESHOOTING/DATABASE_FIXES.md) for database issues

---

## 📞 Support Resources

| Issue | See |
|-------|-----|
| General questions | [../../TROUBLESHOOTING/FAQ.md](../../TROUBLESHOOTING/FAQ.md) |
| Database errors | [../../TROUBLESHOOTING/DATABASE_FIXES.md](../../TROUBLESHOOTING/DATABASE_FIXES.md) |
| Setup problems | [START_HERE.md](./START_HERE.md) |
| Detailed info | [../../REFERENCE/COMPLETE_GUIDE.md](../../REFERENCE/COMPLETE_GUIDE.md) |

---

## ⏱️ Testing Time Breakdown

- TEST 1: 2 minutes
- TEST 2: 2 minutes
- TEST 3: 3 minutes
- TEST 4: 2 minutes
- TEST 5: 2 minutes
- TEST 6: 2 minutes
- **Total: 13 minutes**

---

**Ready to test? Start with TEST 1 →**

**Verify: http://localhost/DEPEDEvaluationSystem/insert_sample_applicants.php**
