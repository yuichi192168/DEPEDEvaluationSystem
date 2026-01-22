# 📖 COMPLETE GUIDE - Comprehensive System Documentation

Complete guide covering all system features, how to use them, and best practices.

**Reading time: 25 minutes**

---

## 📋 Table of Contents

1. [System Overview](#system-overview)
2. [How It Works](#how-it-works)
3. [Evaluation Process](#evaluation-process)
4. [Scoring System](#scoring-system)
5. [Results and CAR](#results-and-car)
6. [All Features](#all-features)
7. [Best Practices](#best-practices)
8. [Common Scenarios](#common-scenarios)
9. [Tips and Tricks](#tips-and-tricks)

---

## System Overview

### What is This System?

The DepEd HRMPSB Evaluation System is a web-based application for managing teacher recruitment and evaluation processes. It:

- ✅ Accepts evaluation submissions from authorized evaluators
- ✅ Calculates weighted scores based on HRMPSB criteria
- ✅ Generates Comparative Assessment Results (CAR) with rankings
- ✅ Displays professional reports for decision-making
- ✅ Exports data for analysis and record-keeping

### Who Uses It?

- **Evaluators:** Submit evaluation scores for applicants
- **Managers:** Review CAR results and rankings
- **HR Staff:** Export data and generate reports
- **Administrators:** Manage the system and data

### What Can You Do?

1. **Submit Evaluations:** Input applicant scores on web form
2. **View Results:** See all applicants ranked by position
3. **Print Reports:** Generate professional CAR documents
4. **Export Data:** Save results to Excel for analysis
5. **Manage Data:** Insert sample data for testing

---

## How It Works

### System Flow (Step by Step)

```
Step 1: Evaluator Opens Form
        ↓
Step 2: Evaluator Enters Applicant Info and Scores
        ↓
Step 3: Evaluator Submits Form
        ↓
Step 4: System Saves to Database
        ↓
Step 5: System Calculates Total Score
        ↓
Step 6: System Generates CAR Record
        ↓
Step 7: System Calculates Ranking (per position)
        ↓
Step 8: System Returns Success Message
        ↓
Step 9: Manager Views Results
        ↓
Step 10: Manager Makes Decision Based on Rankings
```

### Data Storage

When you submit an evaluation:

```
evaluation_data
    ├─ applicant_id
    ├─ applicant_name
    ├─ application_code
    ├─ position
    ├─ education_score (0-10)
    ├─ training_score (0-5)
    ├─ experience_score (0-20)
    ├─ performance_score (0-25)
    ├─ accomplishments_score (0-5)
    ├─ app_education_score (0-10)
    ├─ app_lnd_score (0-10)
    ├─ potential_score (0-10)
    └─ total_score (calculated)

            ↓ saved to

database
    ├─ applicants table (basic info)
    ├─ evaluations table (all scores)
    └─ comparative_assessment_results table (ranking)
```

---

## Evaluation Process

### Step 1: Open Evaluation Form

**URL:** http://localhost/DEPEDEvaluationSystem/index.php

**What You See:**
- Web form with multiple sections
- Fields for applicant info
- Fields for all scoring criteria
- Submit button at bottom

### Step 2: Enter Applicant Information

**Typical Fields:**
```
Position: [Select from dropdown]
Applicant Name: [Enter full name]
Application Code: [Enter code]
Email: [Enter email]
```

**Why This Matters:**
- Position determines where applicant will be ranked
- Code is unique identifier (must be unique)
- Information helps identify applicant later

### Step 3: Enter Evaluation Scores

**Score Fields (by criteria):**

1. **Education (0-10 scale)**
   - Qualification level: BS, MS, PhD
   - Relevant to position: Yes/No
   - Score: 0-10

2. **Training (0-5 scale)**
   - Hours of training completed
   - Relevant skills acquired
   - Score: 0-5

3. **Experience (0-20 scale)**
   - Years in current field
   - Years in related positions
   - Relevant experience only
   - Score: 0-20

4. **Performance (0-25 scale)**
   - Current job performance rating
   - Skills demonstrated
   - Professional conduct
   - Score: 0-25

5. **Outstanding Accomplishments (0-5 scale)**
   - Notable achievements
   - Special recognitions
   - Contributions to organization
   - Score: 0-5

6. **Application of Education (0-10 scale)**
   - How education is applied on job
   - Relevance to position
   - Demonstrated knowledge
   - Score: 0-10

7. **Application of L&D (0-10 scale)**
   - How learning & development applied
   - Continuous improvement
   - Professional development
   - Score: 0-10

8. **Potential (0-10 scale)**
   - Growth potential in position
   - Leadership capability
   - Future development
   - Score: 0-10

**Important Notes:**
- Each score uses a different maximum (5, 10, 20, 25)
- These are weighted differently in final score
- Enter decimals if needed (e.g., 4.5, 7.25)
- All fields should be filled

### Step 4: Submit Form

**What Happens:**
1. Click "Submit Evaluation" button
2. Form sends data to server
3. System validates data
4. System calculates total score
5. System generates CAR record
6. System calculates ranking
7. Browser shows success message

**Success Message Shows:**
```
✅ Evaluation Submitted Successfully
Applicant: [Name]
Application Code: [Code]
Position: [Position]
Total Score: [Score]
Rank: [Ranking]
```

---

## Scoring System

### How Scores Are Calculated

**Formula:**
```
Total Score = 
  (Education × 0.15) +
  (Training × 0.05) +
  (Experience × 0.20) +
  (Performance × 0.25) +
  (Accomplishments × 0.05) +
  (App of Education × 0.10) +
  (App of L&D × 0.10) +
  (Potential × 0.10)
```

### Weighting Breakdown

| Criterion | Weight | Reason |
|-----------|--------|--------|
| Performance | 25% | Most important - current capability |
| Experience | 20% | Background and proven ability |
| Education | 15% | Qualification level |
| App of Education | 10% | How knowledge is used |
| App of L&D | 10% | Continuous learning |
| Potential | 10% | Future capability |
| Accomplishments | 5% | Recognition achievements |
| Training | 5% | Skill development |

### Example Calculation

**Scenario:** Maria Santos

```
Education:                10.0 × 0.15 = 1.50
Training:                  5.0 × 0.05 = 0.25
Experience:               15.0 × 0.20 = 3.00
Performance:               9.0 × 0.25 = 2.25
Accomplishments:           4.0 × 0.05 = 0.20
Application of Education:  8.0 × 0.10 = 0.80
Application of L&D:        7.0 × 0.10 = 0.70
Potential:                 9.0 × 0.10 = 0.90
                                      ─────────
TOTAL SCORE:                           9.38
```

### Score Ranges

```
Maximum Possible: 100.00 (if all scores maxed)
Typical High: 8.0 - 9.0
Typical Average: 5.0 - 7.0
Typical Low: 2.0 - 4.0
Minimum Possible: 0.00 (if all scores zero)
```

---

## Results and CAR

### What is CAR?

**CAR = Comparative Assessment Result**

It's a professional report showing:
- All applicants for a position
- Their evaluation scores
- Their ranking (1st, 2nd, 3rd, etc.)
- Professional format for decision-making

### CAR Structure

```
COMPARATIVE ASSESSMENT RESULT - Annex I

Position: [Position Name]
Office/Bureau: [Department]
Plantilla Item: [Item Number]
Date: [Date of Assessment]

┌─ Applicant 1 (Rank 1 - Score 9.38)
├─ Applicant 2 (Rank 2 - Score 8.73)
└─ Applicant 3 (Rank 3 - Score 8.08)

[Signature Section]
```

### Viewing CAR

**Option 1: View All Applicants**
```
URL: http://localhost/DEPEDEvaluationSystem/comparative_assessment_results.php?view=all
Shows: All applicants across all positions
Format: List view, grouped by position
```

**Option 2: View by Position**
```
URL: http://localhost/DEPEDEvaluationSystem/comparative_assessment_results.php
Steps:
  1. Select position from dropdown
  2. Click "View Results"
  3. Detailed CAR displays for selected position
Format: Professional table with all columns
```

### CAR Columns Explained

| Column | Description | Example |
|--------|-------------|---------|
| NAME | Applicant full name | Maria Santos |
| CODE | Application code | SAMPLE-001 |
| Education | Education score (0-10) | 10.0 |
| Training | Training score (0-5) | 5.0 |
| Experience | Experience score (0-20) | 15.0 |
| Performance | Performance score (0-25) | 9.0 |
| Accomplishments | Accomplishments (0-5) | 4.0 |
| App of Education | Applies education (0-10) | 8.0 |
| App of L&D | Applies learning (0-10) | 7.0 |
| Potential | Growth potential (0-10) | 9.0 |
| Total | Weighted total (0-100) | 9.38 |
| Rank | Position ranking | 1 |

---

## All Features

### Feature 1: Evaluation Form

**What It Does:** Allows entry of evaluation data

**How to Use:**
1. Open http://localhost/DEPEDEvaluationSystem/index.php
2. Select position
3. Enter applicant info
4. Enter all scores
5. Click Submit

**Tips:**
- Fill all fields (don't leave blank)
- Use decimals if needed (e.g., 4.5)
- Double-check scores before submitting
- Data saves automatically

### Feature 2: View Results

**What It Does:** Display CAR results

**Options:**
- View All: See all applicants in list
- View by Position: See detailed table per position

**How to Use:**
1. Go to http://localhost/DEPEDEvaluationSystem/comparative_assessment_results.php
2. Choose view option:
   - For list: Add ?view=all to URL
   - For detailed: Select position from dropdown
3. Review applicants and rankings

### Feature 3: Print CAR

**What It Does:** Generate printable CAR document

**How to Use:**
1. View CAR details (see Feature 2)
2. Look for "Print" button
3. Click it
4. Print dialog opens
5. Choose printer settings
6. Click "Print"

**Tips:**
- Use landscape orientation (A4)
- Preview before printing
- Check that all columns fit on page

### Feature 4: Export to CSV

**What It Does:** Save CAR data to Excel file

**How to Use:**
1. View CAR details
2. Look for "Export" or "Download CSV" button
3. Click it
4. File downloads to computer
5. Open in Excel or Google Sheets

**Tips:**
- CSV format is compatible with Excel
- Can then create charts in Excel
- Can do further analysis

### Feature 5: Sample Data Tool

**What It Does:** Insert test data easily

**How to Use:**
1. Go to http://localhost/DEPEDEvaluationSystem/insert_sample_applicants.php
2. Click "Insert 4 Sample Applicants" button
3. Wait for success message
4. Sample data now available for testing

**Tips:**
- Run anytime to reset test data
- 4 applicants across 2 positions
- Auto-calculates realistic scores

---

## Best Practices

### Best Practice 1: Verify Data Before Submitting

**Do:**
- ✅ Review all entered data
- ✅ Check scores for accuracy
- ✅ Verify applicant name spelling
- ✅ Confirm position selection

**Don't:**
- ❌ Submit without reviewing
- ❌ Enter invalid codes
- ❌ Leave fields blank
- ❌ Enter wrong position

### Best Practice 2: Use Consistent Scoring

**Do:**
- ✅ Use same scoring standards for all applicants
- ✅ Reference scoring rubric
- ✅ Consider position requirements
- ✅ Be objective in scoring

**Don't:**
- ❌ Change scoring criteria mid-way
- ❌ Score based on personal preferences
- ❌ Make extreme score differences without reason
- ❌ Skip difficult decisions

### Best Practice 3: Review Rankings Regularly

**Do:**
- ✅ Check rankings after each submission
- ✅ Verify rankings make sense
- ✅ Look for ties (same scores)
- ✅ Validate against expectations

**Don't:**
- ❌ Ignore unusual rankings
- ❌ Assume rankings are automatic
- ❌ Skip verification
- ❌ Make decisions without reviewing CAR

### Best Practice 4: Maintain Data Integrity

**Do:**
- ✅ Keep application codes unique
- ✅ Save backup copies
- ✅ Document any corrections
- ✅ Export data regularly

**Don't:**
- ❌ Use duplicate application codes
- ❌ Delete data without backup
- ❌ Modify database directly
- ❌ Use system for unauthorized purposes

### Best Practice 5: Use Professional Format

**Do:**
- ✅ Print official CAR for decisions
- ✅ Use system-generated formats
- ✅ Include all required signatures
- ✅ Keep copies of final CAR

**Don't:**
- ❌ Modify CAR format manually
- ❌ Use unofficial templates
- ❌ Skip signature lines
- ❌ Lose track of CAR documents

---

## Common Scenarios

### Scenario 1: Multiple Applicants for One Position

**Situation:** 5 people apply for teacher position

**Process:**
1. Submit evaluation 1 → Rank 1 (Score 8.5)
2. Submit evaluation 2 → Rank 2 (Score 8.2)
3. Submit evaluation 3 → Rank 3 (Score 7.9)
4. Submit evaluation 4 → Rank 4 (Score 7.6)
5. Submit evaluation 5 → Rank 5 (Score 7.3)

**Result:** CAR shows all 5 with rankings 1-5

**Decision:** Typically hire rank 1 and 2

### Scenario 2: Comparing Positions

**Situation:** Need to compare applicants across positions

**Process:**
1. View position 1 results → See 3 applicants
2. View position 2 results → See 2 applicants
3. Compare top candidates across positions
4. Make decisions based on position needs

**Result:** Different rankings for same person in different positions

**Note:** Rankings are per position (not system-wide)

### Scenario 3: Retesting Applicants

**Situation:** Need to re-evaluate an applicant

**Process:**
1. Create new evaluation record with NEW application code
2. Submit evaluation with updated scores
3. System creates new ranking
4. Old evaluation remains in system (for history)
5. New evaluation shows in current rankings

**Important:** Don't modify existing evaluations; create new ones

### Scenario 4: Exporting for Analysis

**Situation:** Need to analyze all evaluation data

**Process:**
1. View all CAR results
2. Export to CSV
3. Open in Excel
4. Create charts and graphs
5. Generate summary report

**Result:** Analysis with charts for presentation

### Scenario 5: Archiving Completed Recruitment

**Situation:** Recruitment complete; need to save records

**Process:**
1. Print final CAR for each position
2. Export all data to CSV
3. Save files with recruitment name and date
4. Archive in document management system
5. Keep as historical record

**Result:** Complete documentation of recruitment process

---

## Tips and Tricks

### Tip 1: Quick Navigation

**Shortcut Links:**
- Main form: Bookmark http://localhost/DEPEDEvaluationSystem/index.php
- Results: Bookmark http://localhost/DEPEDEvaluationSystem/comparative_assessment_results.php
- Sample data: Bookmark http://localhost/DEPEDEvaluationSystem/insert_sample_applicants.php

### Tip 2: Keyboard Shortcuts

**Browser Shortcuts:**
- Refresh page: F5
- Full refresh: Ctrl+F5
- Developer console: F12
- Print dialog: Ctrl+P

### Tip 3: Checking Your Work

**After Submitting:**
1. Go to Results page immediately
2. Look for your applicant
3. Verify scores match what you entered
4. Check ranking is correct

### Tip 4: Using Sample Data

**For Training:**
1. Insert sample data
2. Practice with 4 applicants
3. Try different views
4. Test print and export
5. Build confidence before real data

### Tip 5: Dealing with Ties

**If Two Scores Are Same:**
- System keeps both scores
- Both get same rank
- Next rank skips (e.g., 1, 2, 2, 4)
- All tied applicants equally qualified

### Tip 6: Decimal Scores

**You Can Enter:**
- Whole numbers: 5, 8, 10
- One decimal: 5.5, 8.3, 9.7
- Two decimals: 5.25, 8.33, 9.75

**System Displays:**
- Always 2 decimals: 5.00, 8.30, 9.75

### Tip 7: Column Width Issues

**If Columns Are Narrow:**
1. Maximize browser window (F11)
2. Use landscape orientation
3. Zoom out (Ctrl+Minus)
4. Try different browser

### Tip 8: Exporting for Excel

**After Exporting CSV:**
1. Open Excel
2. File > Open > Select CSV file
3. Choose "Tab" or "Comma" delimiter
4. All columns should align correctly
5. Now can format, chart, analyze in Excel

---

## ⏱️ Time Estimates

| Task | Time |
|------|------|
| Submit one evaluation | 5-10 min |
| View and review CAR | 3-5 min |
| Print CAR | 2-3 min |
| Export to CSV | 1-2 min |
| Insert sample data | 1-2 min |
| Train on system | 30 min |

---

**Next: [CAR_FORMAT_GUIDE.md](./CAR_FORMAT_GUIDE.md) - Detailed CAR format documentation**
