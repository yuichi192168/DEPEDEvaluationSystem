# ⚙️ FEATURE GUIDE - All Features Explained

Guide to all system features with step-by-step instructions and examples.

**Reading time: 20 minutes**

---

## 📋 Table of Contents

1. [Evaluation Form](#evaluation-form)
2. [Viewing Results](#viewing-results)
3. [Printing CAR](#printing-car)
4. [Exporting Data](#exporting-data)
5. [Sample Data Tool](#sample-data-tool)
6. [Position Selector](#position-selector)
7. [Search and Filter](#search-and-filter)
8. [User Interface Tips](#user-interface-tips)

---

## Evaluation Form

### What It Does

The Evaluation Form allows you to:
- Enter applicant information
- Input evaluation scores
- Submit data to database
- Get confirmation with ranking

### How to Access

**URL:** http://localhost/DEPEDEvaluationSystem/index.php

**Or:** Click "Evaluation Form" link from menu

### Form Sections

The form is organized into clear sections:

```
1. Applicant Information
   ├─ Position
   ├─ Applicant Name
   └─ Application Code

2. Evaluation Criteria
   ├─ Education
   ├─ Training
   ├─ Experience
   ├─ Performance
   ├─ Outstanding Accomplishments
   ├─ Application of Education
   ├─ Application of L&D
   └─ Potential

3. Submit Button
   └─ Submit Evaluation
```

### Step-by-Step Instructions

**Step 1: Open Form**
- Go to http://localhost/DEPEDEvaluationSystem/index.php
- Form displays in browser

**Step 2: Select Position**
- Click "Position" dropdown
- Choose position applicant applying for
- Dropdown shows only positions with applicants

**Step 3: Enter Applicant Info**
- **Name:** Enter full name (first and last)
- **Application Code:** Enter unique code (e.g., APP-001)
- **Email:** Enter email address (optional)

**Step 4: Enter Education Score**
- Click "Education" field
- Enter score from 0-10
- Decimals allowed (e.g., 8.5)
- Based on degree/certification level

**Step 5: Enter Training Score**
- Click "Training" field
- Enter score from 0-5
- Based on hours/certifications completed

**Step 6: Enter Experience Score**
- Click "Experience" field
- Enter score from 0-20
- Based on years of relevant experience
- Highest individual category

**Step 7: Enter Performance Score**
- Click "Performance" field
- Enter score from 0-25
- Current job performance rating
- Most heavily weighted (25%)

**Step 8: Enter Accomplishments Score**
- Click "Outstanding Accomplishments" field
- Enter score from 0-5
- Based on awards/achievements

**Step 9: Enter Application of Education**
- Click field
- Enter score from 0-10
- How well applicant uses education

**Step 10: Enter Application of L&D**
- Click field
- Enter score from 0-10
- How well applicant uses learning/development

**Step 11: Enter Potential Score**
- Click field
- Enter score from 0-10
- Growth and advancement potential

**Step 12: Submit**
- Click "Submit Evaluation" button
- Wait for response
- See success message with ranking

### Success Message

After submitting, you'll see:

```
✅ EVALUATION SUBMITTED SUCCESSFULLY

Applicant: [Name]
Application Code: [Code]
Position: [Position]
Total Score: [Score]
Rank: [Rank Number]
Status: [Saved to Database]

[View Results Button]
```

### Error Messages

If something's wrong, you'll see error messages like:

```
❌ Error: Missing Required Field
Position must be selected

❌ Error: Invalid Score
Education score must be between 0 and 10

❌ Error: Duplicate Application Code
This application code already exists

❌ Error: Database Connection Failed
Please try again later
```

### Validation Rules

The form checks:
- ✅ Position selected
- ✅ Applicant name not empty
- ✅ Application code not empty
- ✅ Application code is unique
- ✅ All scores within allowed ranges
- ✅ All fields have valid input

---

## Viewing Results

### View Option 1: View All Applicants (List View)

**What It Shows:**
- All applicants across all positions
- Grouped by position
- List format with basic info
- Quick overview

**How to Access:**
```
URL: http://localhost/DEPEDEvaluationSystem/comparative_assessment_results.php?view=all
Or: From Results page, click "View All"
```

**What You See:**
```
Position: Information and Communications Technology
├─ Rank 1: Maria Santos (Score 9.38) - SAMPLE-001
└─ Rank 2: Juan Dela Cruz (Score 8.73) - SAMPLE-002

Position: [Another Position]
├─ Rank 1: Ana Reyes (Score 8.08) - SAMPLE-003
└─ Rank 2: Carlos Mendoza (Score 7.25) - SAMPLE-004
```

**Best For:**
- Quick overview of all results
- Checking multiple positions
- Getting summary view
- Comparing across positions

### View Option 2: View by Position (Detailed View)

**What It Shows:**
- Detailed CAR for specific position
- All 13 columns with scores
- Professional format
- Print and export options

**How to Access:**
```
URL: http://localhost/DEPEDEvaluationSystem/comparative_assessment_results.php
Steps:
1. Click "Select Position to View" dropdown
2. Choose position
3. Click "View Results" or position name
4. Detailed CAR displays
```

**What You See:**
```
COMPARATIVE ASSESSMENT RESULT - Annex I

Position: Information and Communications Technology
Office/Bureau: ICT Unit
Plantilla Item Number: n/a
Date: January 22, 2026

┌─────────────────────────────────────────────────────┐
│ NAME │ CODE │ Edu │ Train │ Exp │ Perf │ ... │ Total │ Rank
├─────────────────────────────────────────────────────┤
│Maria │SMPL-1│10.0 │ 5.0 │15.0 │ 9.0 │...  │ 9.38 │  1
│Juan  │SMPL-2│ 9.0 │ 4.0 │14.0 │ 8.0 │...  │ 8.73 │  2
└─────────────────────────────────────────────────────┘
```

**Best For:**
- Official CAR document
- Decision-making
- Printing
- Exporting
- Professional presentations

### Selecting Position

**Steps:**
1. Go to Results page
2. Find dropdown: "Select Position to View:"
3. Click dropdown arrow
4. List opens showing available positions
5. Each position shows applicant count: "(2 applicants)"
6. Click position to select
7. Click "View Results" button
8. Detailed CAR displays

**Note:** Only positions with applicants show in dropdown (empty positions are hidden)

### Refreshing Results

**After submitting new evaluation:**
1. Go to Results page
2. Press F5 to refresh
3. New applicant appears in list/dropdown
4. Ranking updates automatically

---

## Printing CAR

### Print Feature

**What It Does:**
- Opens print dialog
- Shows print preview
- Lets you print CAR document
- Professional A4 landscape format

### Steps to Print

**Step 1: View CAR Details**
- Go to Results page
- Select position
- Detailed CAR displays

**Step 2: Click Print**
- Look for "Print" button (usually top right)
- Click it

**Step 3: Print Dialog Opens**
- Browser's print dialog appears
- Shows print options

**Step 4: Choose Settings**
- **Printer:** Select printer to use
- **Copies:** Usually 1
- **Color:** Black & White or Color
- **Orientation:** Select "Landscape" (not Portrait)
- **Paper Size:** A4
- **Margins:** 0.5" or Normal

**Step 5: Preview**
- Click "Preview" to see how it will look
- Check that all columns fit
- Verify content is correct

**Step 6: Print**
- Click "Print" button
- Wait for printing to complete

### Print Tips

✅ **Use Landscape Orientation**
- Portrait cuts off columns
- Landscape fits all content
- Standard for CAR documents

✅ **Use A4 Paper Size**
- Standard DepEd format
- Fits content properly
- Professional appearance

✅ **Set Margins to 0.5"**
- Allows more content on page
- Still leaves adequate margins
- Professional appearance

✅ **Preview Before Printing**
- Check that all columns fit
- Verify no content is cut off
- See exact appearance on paper
- Make adjustments if needed

✅ **Use Quality Printer**
- Better print quality
- Professional appearance
- Clearer text and tables
- Better for archiving

### Print Preview

**What to Check in Preview:**
- [ ] All 13 columns visible
- [ ] Title and headers present
- [ ] All applicant rows present
- [ ] Rankings correct
- [ ] Signature section present
- [ ] No content cut off
- [ ] Professional appearance
- [ ] Readable and clear

### Saving as PDF

**Alternative to Printing:**
1. Open print dialog (Ctrl+P)
2. Choose printer: "Save as PDF"
3. Click "Save"
4. Choose save location
5. File saves as PDF
6. Can email or archive PDF

---

## Exporting Data

### Export Feature

**What It Does:**
- Saves CAR data to CSV file
- Opens in Excel or Google Sheets
- Allows analysis and further formatting
- Enables backup and sharing

### Steps to Export

**Step 1: View CAR Details**
- Go to Results page
- Select position
- Detailed CAR displays

**Step 2: Click Export**
- Look for "Export" or "Download CSV" button
- Usually top right near Print button
- Click it

**Step 3: File Downloads**
- Browser downloads CSV file
- File saves to Downloads folder (usually)
- File name: Something like "car_results.csv"
- CSV = Comma-Separated Values format

**Step 4: Open File**
- Find downloaded file in Downloads folder
- Right-click on file
- Choose "Open With > Excel" or "Google Sheets"
- File opens in spreadsheet app

**Step 5: Use Data**
- All data imported into spreadsheet
- Columns match CAR columns
- Can now:
  - Add formatting
  - Create charts
  - Perform calculations
  - Share with others
  - Backup data

### Using CSV in Excel

**After Opening CSV in Excel:**

1. **Format Data**
   - Make headers bold
   - Add borders around table
   - Color-code ranks
   - Adjust column widths

2. **Create Charts**
   - Select Score columns
   - Insert > Chart
   - Choose chart type (Bar, Column, etc.)
   - Shows score distribution visually

3. **Analyze Data**
   - Sort by score (highest first)
   - Filter by position
   - Calculate averages
   - Find score gaps

4. **Create Reports**
   - Add charts to report
   - Add commentary
   - Add graphs
   - Create summary sheets

5. **Share**
   - Email Excel file
   - Save as PDF
   - Print from Excel
   - Publish to SharePoint

### CSV Format

**What You Get:**

```
NAME,CODE,Education,Training,Experience,Performance,...,Total,Rank
Maria Santos,SAMPLE-001,10.0,5.0,15.0,9.0,...,9.38,1
Juan Dela Cruz,SAMPLE-002,9.0,4.0,14.0,8.0,...,8.73,2
Ana Reyes,SAMPLE-003,8.0,3.0,12.0,7.0,...,8.08,1
Carlos Mendoza,SAMPLE-004,7.0,2.0,10.0,6.0,...,7.25,2
```

**Each row is an applicant**
**Each column is a score category**
**Comma separates columns**

---

## Sample Data Tool

### What It Does

Inserts 4 pre-made applicants with realistic scores for testing and training.

### How to Access

**URL:** http://localhost/DEPEDEvaluationSystem/insert_sample_applicants.php

### Sample Data Included

**4 Applicants:**
1. Maria Santos - Position 1 - Score: 9.38 - Rank 1
2. Juan Dela Cruz - Position 1 - Score: 8.73 - Rank 2
3. Ana Reyes - Position 2 - Score: 8.08 - Rank 1
4. Carlos Mendoza - Position 2 - Score: 7.25 - Rank 2

**Distribution:**
- 2 positions
- 2 applicants per position
- Realistic score variation
- Auto-calculated rankings

### Steps to Use

**Step 1: Open Tool**
- Go to http://localhost/DEPEDEvaluationSystem/insert_sample_applicants.php

**Step 2: Click Button**
- Find green button: "➕ Insert 4 Sample Applicants"
- Click it

**Step 3: Wait for Response**
- System processes data
- Creates 4 applicant records
- Generates evaluation data
- Calculates rankings

**Step 4: See Success**
- Green message appears: "✅ Sample Data Inserted Successfully"
- Shows: "4 applicants have been inserted"
- Sample data ready to use

**Step 5: View Results**
- Go to Results page
- Select position
- See 4 applicants with data
- Test all features

### When to Use

✅ **First Time Setup**
- Verify system is working
- See sample data
- Learn how results look

✅ **Training Users**
- Show how system works
- Provide test data
- Practice with realistic data

✅ **Testing Changes**
- Test new features
- Verify updates
- Check functionality

✅ **Resetting Data**
- Clear old sample data
- Start fresh with known data
- Reset for next test

### Using Sample Data

**With sample data inserted, you can:**
1. ✅ View results page
2. ✅ Select positions
3. ✅ See detailed CAR
4. ✅ Practice printing
5. ✅ Practice exporting
6. ✅ Test all features
7. ✅ Train other users
8. ✅ Verify system works

---

## Position Selector

### What It Does

Dropdown menu to select which position to view detailed CAR for.

### How It Works

**Smart Filtering:**
- Only shows positions with applicants
- Empty positions are hidden
- Reduces clutter
- Shows applicant count

### Using Position Selector

**Steps:**
1. Go to Results page
2. Find dropdown: "Select Position to View:"
3. Click dropdown arrow
4. Available positions appear:
   ```
   ┌─ Information and Communications Technology (2 applicants)
   └─ [Another Position] (2 applicants)
   ```
5. Click position
6. Selection shows in dropdown
7. Click "View Results" or position name
8. Detailed CAR displays

### Position Dropdown Shows

✅ Position name
✅ Number of applicants: "(2 applicants)"
✅ Only positions with data
❌ Not empty positions
❌ Not closed positions

---

## Search and Filter

### Current Filters

**Position Filter:**
- Dropdown to select position
- Shows only that position's applicants
- Displays detailed CAR

**View Type Filter:**
- "View All" = all positions, list format
- "View by Position" = one position, detailed format

### Sorting

**Results Sort By:**
- **Rank:** Automatically (Rank 1, 2, 3...)
- **Score:** By total score (highest first)
- **Name:** Alphabetically (optional)

### Manual Sorting in Exported CSV

**After exporting to Excel:**
1. Select data range
2. Data > Sort
3. Choose column to sort by
4. Choose ascending/descending
5. Apply sort

---

## User Interface Tips

### Responsive Design

- ✅ Works on desktop computers
- ✅ Works on tablets (landscape mode better)
- ⚠️ Mobile phones (challenging, use landscape)

### Keyboard Shortcuts

**Browser Shortcuts:**
- F5 = Refresh page
- Ctrl+F5 = Full refresh (clear cache)
- Ctrl+P = Print dialog
- Tab = Move between fields
- Enter = Submit form or confirm dialog

### Making Changes

**If Something Wrong:**
1. Page won't load: F5 (refresh)
2. Data looks old: Ctrl+F5 (full refresh)
3. Form won't submit: Check required fields (*)
4. Columns cut off: Maximize window or zoom out
5. Print looks wrong: Check printer settings

### Browser Compatibility

Works best on:
- ✅ Chrome (latest version)
- ✅ Firefox (latest version)
- ✅ Safari (latest version)
- ✅ Edge (latest version)

### Tips for Best Results

- ✅ Use landscape mode for printing
- ✅ Use full screen (F11)
- ✅ Close other browser tabs
- ✅ Keep browser updated
- ✅ Clear cache monthly
- ✅ Use latest PHP/MySQL

---

**Next: [../../TROUBLESHOOTING/FAQ.md](../../TROUBLESHOOTING/FAQ.md) - Frequently asked questions**
