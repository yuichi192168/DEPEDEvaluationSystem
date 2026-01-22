# 📖 QUICK START - Detailed Setup Guide

Complete step-by-step guide with explanations for each action.

---

## 🎯 Overview

This guide will walk you through:
1. Inserting 4 sample applicants
2. Viewing comparative assessment results
3. Selecting and viewing by position
4. Understanding what you're seeing

**Estimated time: 5 minutes**

---

## 📋 Step 1: Insert Sample Data

### What This Does
Creates 4 test applicants with realistic HRMPSB evaluation scores for testing.

### How to Do It

**Open URL:**
```
http://localhost/DEPEDEvaluationSystem/insert_sample_applicants.php
```

**Click Button:**
Find and click the green button that says:
```
"➕ Insert 4 Sample Applicants"
```

### What You Should See

A success message appears:
```
✅ Sample Data Inserted Successfully

4 applicants have been inserted with sample evaluation data.

Applicants: SAMPLE APPLICANT 1 through 4
Application Codes: SAMPLE-001 through SAMPLE-004
Positions: Distributed across 2 positions
Rankings: Auto-calculated based on scores
```

### What Happens Behind the Scenes
- Old sample data is cleared (if any)
- 4 new applicant records are created
- Evaluation scores are calculated
- CAR (Comparative Assessment Results) records are generated
- Rankings are automatically calculated
- All data is saved to the database

---

## 📋 Step 2: View All Results

### What This Shows
All 4 applicants across all positions, grouped by position with rankings.

### How to Do It

**Open URL:**
```
http://localhost/DEPEDEvaluationSystem/comparative_assessment_results.php?view=all
```

### What You Should See

**Format:**
```
COMPARATIVE ASSESSMENT RESULT - Annex I

Position: Information and Communications Technology
│
├─ Rank 1: Maria Santos (Score: 9.38)
│   APPLICATION CODE: SAMPLE-001
│   
└─ Rank 2: Juan Dela Cruz (Score: 8.73)
    APPLICATION CODE: SAMPLE-002

Position: [Another Position Name]
│
├─ Rank 1: Ana Reyes (Score: 8.08)
│   APPLICATION CODE: SAMPLE-003
│   
└─ Rank 2: Carlos Mendoza (Score: 7.25)
    APPLICATION CODE: SAMPLE-004
```

### Understanding the Display

**Rank:** 
- Calculated automatically based on total score
- 1 = Highest score
- 2 = Second highest score
- Separate ranking per position

**Score:**
- Total weighted score from all criteria
- Range: 0.00 to 100.00
- Higher score = better ranking

**APPLICATION CODE:**
- Unique identifier for each applicant
- SAMPLE-001, SAMPLE-002, etc.

---

## 📋 Step 3: View by Position

### What This Shows
Detailed CAR format for a specific position (like your reference image).

### How to Do It

**Open URL:**
```
http://localhost/DEPEDEvaluationSystem/comparative_assessment_results.php
```

**Select Position:**
You'll see a dropdown menu that says:
```
"Select Position to View:"
```

Click it to see available positions:
```
┌─ Position Name 1 (2 applicants)
└─ Position Name 2 (2 applicants)
```

Choose one position.

### What You Should See

**Format:**
```
COMPARATIVE ASSESSMENT RESULT - Annex I

Position: Information and Communications Technology
Office/Bureau/Service/Unit: Information and Communications Technology Unit
Plantilla Item Number: n/a
Date of Final Deliberation: January 22, 2026

┌────────────────────────────────────────────────────────────────────┐
│ NAME │ CODE │ Education │ Training │ ... │ Total │ Rank │ Remarks
├────────────────────────────────────────────────────────────────────┤
│Maria Santos│SAMPLE-001│10.0│5.0│...│9.38│1│
│Juan Dela Cruz│SAMPLE-002│9.0│4.0│...│8.73│2│
└────────────────────────────────────────────────────────────────────┘

[Signature Section Below]
```

### Understanding Each Column

| Column | What It Shows | Example |
|--------|---------------|---------|
| NAME | Applicant's full name | Maria Santos |
| CODE | Application code | SAMPLE-001 |
| Education | Education score (0-10) | 10.0 |
| Training | Training hours (0-5) | 5.0 |
| Experience | Years of experience (0-20) | 15.0 |
| Performance | Performance rating (0-25) | 9.0 |
| Outstanding Accomplishments | Achievements (0-5) | 4.0 |
| Application of Education | Education applied (0-10) | 8.0 |
| Application of L&D | Learning & Dev (0-10) | 7.0 |
| Potential | Growth potential (0-10) | 9.0 |
| Total | Combined score (0-100) | 9.38 |
| Rank | Position ranking | 1 |

---

## 🔍 Sample Data Details

### What Data Will Be Inserted

**4 Applicants:**

```
Applicant 1:
- Name: Maria Santos
- Position: Position 1
- Application Code: SAMPLE-001
- Total Score: 9.38
- Rank: 1 (Highest)

Applicant 2:
- Name: Juan Dela Cruz
- Position: Position 1
- Application Code: SAMPLE-002
- Total Score: 8.73
- Rank: 2

Applicant 3:
- Name: Ana Reyes
- Position: Position 2
- Application Code: SAMPLE-003
- Total Score: 8.08
- Rank: 1 (Highest)

Applicant 4:
- Name: Carlos Mendoza
- Position: Position 2
- Application Code: SAMPLE-004
- Total Score: 7.25
- Rank: 2
```

### Score Breakdown for Maria Santos (Applicant 1)

```
Education: 10.0 × 0.15 = 1.50
Training: 5.0 × 0.05 = 0.25
Experience: 15.0 × 0.20 = 3.00
Performance: 9.0 × 0.25 = 2.25
App of Education: 8.0 × 0.10 = 0.80
App of L&D: 7.0 × 0.10 = 0.70
Potential: 9.0 × 0.10 = 0.90
Outstanding Accomplishments: 4.0 × 0.05 = 0.20
────────────────────────────────────────
TOTAL SCORE: 9.38
```

---

## ✅ Verification

After completing all steps, check:

- [ ] Sample data inserted (you see ✅ Success)
- [ ] All 4 applicants appear in results
- [ ] Applicants grouped by position
- [ ] Rankings show 1 and 2 per position
- [ ] Position dropdown populated
- [ ] Position selected shows detailed table
- [ ] All columns displayed correctly
- [ ] Format matches your reference image

---

## 🎨 Position Selector Behavior

### Why Some Positions Don't Show
The position dropdown only shows positions that have applicants.

**Before Fix:**
```
Select Position:
├─ Position 1 (0 applicants) ← Empty, shouldn't show
├─ Position 2 (2 applicants)
├─ Position 3 (0 applicants) ← Empty, shouldn't show
└─ Position 4 (1 applicant)
```

**After Fix (Now):**
```
Select Position:
├─ Position 2 (2 applicants) ← Only with data
└─ Position 4 (1 applicant) ← Only with data
```

This provides a cleaner interface showing only relevant positions.

---

## 🔗 Quick Links

| Action | URL |
|--------|-----|
| Insert Sample Data | http://localhost/DEPEDEvaluationSystem/insert_sample_applicants.php |
| View All Results | http://localhost/DEPEDEvaluationSystem/comparative_assessment_results.php?view=all |
| View by Position | http://localhost/DEPEDEvaluationSystem/comparative_assessment_results.php |
| Evaluation Form | http://localhost/DEPEDEvaluationSystem/index.php |

---

## 🎓 Key Features

✅ **Automatic Ranking**
- Calculated from total scores
- Highest score = Rank 1

✅ **Smart Position Selector**
- Only shows positions with applicants
- Displays applicant count

✅ **Professional Format**
- Matches DepEd HRMPSB template
- All required columns included

✅ **Print Ready**
- Professional layout
- Optimized for A4 landscape
- Print button available

✅ **Export Capability**
- Export to CSV for Excel
- Export button available

---

## 💡 Tips & Tricks

**Tip 1: Test Different Positions**
After inserting data, try selecting different positions to see how filtering works.

**Tip 2: Check Print Preview**
Use the Print button to see how the document looks formatted.

**Tip 3: Export Data**
Use Export CSV to get data in spreadsheet format for analysis.

**Tip 4: View All vs By Position**
- View All: See overview of all applicants
- View by Position: See detailed format for one position

---

## ⏱️ Time Breakdown

- Step 1 (Insert Data): 1 minute
- Step 2 (View All): 1 minute
- Step 3 (View by Position): 3 minutes
- **Total: 5 minutes**

---

## 🚀 Next Steps

After completing this setup:

1. **Explore features** - Try print and export buttons
2. **Add real data** - Use the Evaluation Form to submit real applicants
3. **Deep dive** - Read [../../REFERENCE/COMPLETE_GUIDE.md](../../REFERENCE/COMPLETE_GUIDE.md)
4. **Test thoroughly** - Follow [QUICK_START_TEST.md](./QUICK_START_TEST.md)

---

**Ready? Go to Step 1 →**

**http://localhost/DEPEDEvaluationSystem/insert_sample_applicants.php**
