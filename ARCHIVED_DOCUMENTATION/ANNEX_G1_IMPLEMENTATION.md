# Annex G-1 Implementation Guide
## Comparative Assessment Results - Consolidated Master List

---

## Overview

**Annex G-1** is the consolidated ranking report that aggregates all individual evaluation sheets (Annex G) for a specific position and presents them in a ranked table format. It serves as the master list for all candidates competing for the same position.

**Key Purpose:**
- Consolidate individual assessments into one comprehensive ranking
- Automatically sort candidates by total score (highest to lowest)
- Apply tie-breaking rules per DepEd Order No. 007, s. 2023
- Generate official certification document for HRMPSB approval

---

## Data Flow: From Annex G to Annex G-1

```
┌─────────────────────────────────────────────────────────────┐
│  Individual Evaluation Sheet (Annex G)                       │
│  - Applicant A: Education (10), Training (8), ... Total: 85  │
│  - Applicant B: Education (9), Training (10), ... Total: 87  │
│  - Applicant C: Education (10), Training (8), ... Total: 85  │
└─────────────────────────────────────────────────────────────┘
                              ↓
                       [DATABASE STORAGE]
                    evaluations table
                              ↓
┌─────────────────────────────────────────────────────────────┐
│  Consolidated Assessment Results (Annex G-1)                │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ Rank │ Name      │ Edu │ Tra │ Exp │ ... │ TOTAL  │ R  │ │
│  ├────────────────────────────────────────────────────────┤ │
│  │  1   │ Applicant B│  9 │ 10  │  8  │ ... │  87.00 │    │ │
│  │  2*  │ Applicant A│ 10 │  8  │  7  │ ... │  85.00 │ T* │ │
│  │  2*  │ Applicant C│ 10 │  8  │  7  │ ... │  85.00 │ T* │ │
│  └────────────────────────────────────────────────────────┘ │
│  * Tied candidates - See tie-breaking protocol              │
└─────────────────────────────────────────────────────────────┘
```

---

## System Architecture

### 1. Database Tables Required

```sql
-- Evaluations table (stores individual evaluation results)
CREATE TABLE evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT NOT NULL,
    position_id INT,
    position_group ENUM('A', 'B', 'C') NOT NULL,
    total_score DECIMAL(10,2) DEFAULT 0,
    evaluation_date DATE NOT NULL,
    evaluator_name VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (applicant_id) REFERENCES applicants(id),
    FOREIGN KEY (position_id) REFERENCES positions(id),
    INDEX idx_position_id (position_id)
);

-- Evaluation details table (stores individual criterion scores)
CREATE TABLE evaluation_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluation_id INT NOT NULL,
    criterion VARCHAR(100) NOT NULL,
    applicant_qualification TEXT,
    applicant_level INT DEFAULT 0,
    baseline_level INT DEFAULT 0,
    increment INT DEFAULT 0,
    weight INT DEFAULT 0,
    points DECIMAL(10,2) DEFAULT 0,
    final_score DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (evaluation_id) REFERENCES evaluations(id)
);
```

### 2. Page Access

**URL:** `generate_car_g1.php`

**Steps:**
1. User selects a position from dropdown
2. System retrieves all evaluations for that position
3. User enters division office and optional certification text
4. User selects output format (HTML or PDF)
5. System generates and displays/downloads Annex G-1

---

## Report Structure

### Header Section
```
Republic of the Philippines
Department of Education
HUMAN RESOURCE MERIT PROMOTION AND SELECTION BOARD (HRMPSB)
                    COMPARATIVE ASSESSMENT RESULTS
                              Annex G-1
```

### Position Information
- **Position:** [Position Name]
- **Salary Grade:** [SG]
- **Item Number:** [Item #]
- **Schools Division Office:** [Division]
- **Position Group:** [A/B/C]

### Data Table

| Column | Description |
|--------|---|
| **Rank** | Numerical ranking (1, 2, 3...) |
| **Name of Applicant** | Full name of candidate |
| **Education (pts)** | Points earned for education increment |
| **Training (pts)** | Points earned for training increment |
| **Experience (pts)** | Points earned for experience increment |
| **Performance (pts)** | Points earned for performance rating |
| **Accomplishments (pts)** | Points earned for outstanding accomplishments |
| **App. of Ed. (pts)** | Points earned for application of education |
| **App. of L&D (pts)** | Points earned for application of L&D |
| **Potential (pts)** | Points earned for potential rating |
| **TOTAL SCORE** | Sum of all criterion scores |
| **Remarks** | Special notes (e.g., "TIED", tie-breaking results) |

### Color Coding
- **Yellow Background (Top 5):** Highlights top 5 ranked candidates
- **Red Background (Ties):** Highlights candidates involved in ties
- **Alert Box (Red Border):** Tie-breaking warning message

### Certification Section
```
We, the members of the HRMPSB, hereby certify that the abovementioned 
applicants were assessed based on the existing guidelines in accordance 
with DepEd Order No. 007, s. 2023...

[Signature Lines for HRMPSB Members - typically 4]
```

---

## Sorting and Ranking Logic

### Primary Sort: Total Score (Descending)
Candidates are first sorted by their total score from highest to lowest.

**Example:**
```
Applicant A: 87.50
Applicant B: 87.50  ← Same score = TIE
Applicant C: 85.00
Applicant D: 82.00
```

### Tie-Breaking Rule 1: Performance Rating
If two candidates have the same total score, the one with the higher **Performance Rating** gets the higher rank.

**Example (from above):**
```
Applicant A: Total 87.50, Performance 4.5
Applicant B: Total 87.50, Performance 4.0  ← Lower performance, ranks lower

Result:
Rank 1: Applicant A (Perf: 4.5)
Rank 2: Applicant B (Perf: 4.0)
```

### Tie-Breaking Rule 2: Potential Rating
If Performance Rating is also tied, use **Potential Rating** as the second tie-breaker.

**Example:**
```
Applicant A: Total 87.50, Performance 4.5, Potential 5.0
Applicant B: Total 87.50, Performance 4.5, Potential 4.5

Result:
Rank 1: Applicant A (Potential: 5.0)
Rank 2: Applicant B (Potential: 4.5)
```

### True Tie (When All Three Are Equal)
If all three criteria are identical, candidates receive the same rank number, and the board documents this in the **Remarks** column as "TIED - See tie-breaking protocol."

---

## Handling Tied Candidates

### Identification
The system automatically detects tied candidates by:
1. Comparing total scores (rounded to 2 decimal places)
2. Flagging rows with matching scores
3. Adding visual indicators (red background)

### Remarks Column
For tied candidates, the system records:
```
"TIED - See tie-breaking protocol"
```

### Tie-Breaking Alert Box
A prominent alert box appears when ties are detected:
```
⚠ TIE-BREAKING ALERT: 
The following candidates have identical total scores. Per DepEd Order No. 007, 
s. 2023, the tie-breaking protocol should be applied, considering Performance 
rating first, then Potential rating.
```

### Manual Board Resolution
While the system provides automatic tie-breaking recommendations, the HRMPSB Board has the authority to:
1. Review the detailed evaluation sheets (Annex G) for each tied candidate
2. Conduct additional assessment if necessary
3. Document the final decision in the Remarks column
4. Update the report with finalized rankings

---

## The 50% Rule

For certain positions or under specific regional memos, candidates must meet a **minimum cumulative score of 50 points** (or other threshold) to be considered for appointment.

### Implementation Steps
1. Define the minimum score threshold for each position group
2. The system should highlight or flag candidates below the threshold
3. Include a note in the Remarks column: "Does not meet minimum requirement"
4. These candidates may still appear in the ranking but are noted as ineligible

**Example:**
```
| Rank | Name | Total | Remarks |
|------|------|-------|---------|
| 1    | John | 88    | |
| 2    | Jane | 76    | |
| 3    | Pete | 52    | |
| 4    | Sara | 48    | Does not meet 50-point minimum |
```

---

## Position Group Weights Reference

### Position Group A (Non-Teaching Level 1)
- Education: 5%
- Training: 5%
- Experience: 20%
- Performance: 20%
- Outstanding Accomplishments: 10%
- Application of Education: 10%
- Application of L&D: 10%
- Potential: 20%

### Position Group B (Non-Teaching Level 2 & Teaching)
- Education: 5%
- Training: 10%
- Experience: 15%
- Performance: 20%
- Outstanding Accomplishments: 10%
- Application of Education: 10%
- Application of L&D: 10%
- Potential: 20%

### Position Group C (School Administration)
- Education: 10%
- Training: 10%
- Experience: 10%
- Performance: 25%
- Outstanding Accomplishments: 10%
- Application of Education: 10%
- Application of L&D: 10%
- Potential: 15%

---

## Usage Instructions

### Step 1: Access the Report Generator
Navigate to: `http://your-system.com/generate_car_g1.php`

### Step 2: Select Position
- Use dropdown to select the position
- System automatically displays salary grade and item number

### Step 3: Enter Report Details
- **Schools Division Office:** Name of the conducting division
- **Certification Text (Optional):** Custom message for the certification section

### Step 4: Choose Output Format
- **HTML:** View in browser and print from browser
- **PDF:** Download as PDF file (requires TCPDF integration)

### Step 5: Generate Report
- Click "Generate Annex G-1"
- Review the consolidated rankings
- Verify tie-breaking results
- Print or save for official records

### Step 6: HRMPSB Board Review
- Board members review the report
- Verify accuracy of evaluations
- Address any outstanding ties
- Approve and sign the certification

---

## Export and Sharing

### HTML Export
- View in any web browser
- Print to PDF using browser print function
- Share via email as HTML file

### PDF Export
- Direct PDF download (when TCPDF is integrated)
- Ready for official distribution
- Can be digitally signed

### Archiving
All generated reports should be stored in:
```
/documents/car_reports/[position_name]/CAR_G1_[date].html
```

---

## Compliance with DepEd Order No. 007, s. 2023

✅ **Automatic Ranking:** System sorts candidates by total score automatically

✅ **Tie-Breaking Protocol:** Implements Performance → Potential hierarchy

✅ **Top 5 Highlighting:** Visual indicator for top 5 candidates

✅ **Position Information:** Carries forward Position Title, Salary Grade, Item Number

✅ **Certification Section:** Includes official HRMPSB certification language

✅ **Signature Lines:** Multiple lines for board member signatures

✅ **Remarks Column:** Documents special notes including tie-breaking results

✅ **Consolidated View:** Single master list for all candidates per position

---

## Troubleshooting

### Issue: No evaluations found for position
**Solution:** Ensure individual evaluation sheets (Annex G) have been completed and saved for the selected position

### Issue: Incorrect total scores
**Solution:** Verify that each criterion score in evaluation_details table has been calculated and saved correctly

### Issue: Tie-breaking not working
**Solution:** Check that Performance and Potential ratings are populated in applicant_qualifications table

### Issue: Position group weights incorrect
**Solution:** Verify HRMPSBEvaluator class has correct weight definitions for positions A, B, C

---

## Future Enhancements

- [ ] Integration with TESDA and other external qualification databases
- [ ] Automatic email notifications to board members
- [ ] Digital signature capability
- [ ] Export to Excel format
- [ ] Integration with appointment system
- [ ] Audit trail logging
- [ ] Multi-language support (English/Filipino)

---

## Contact & Support

For technical support or questions about Annex G-1 generation:
- Refer to DepEd Order No. 007, s. 2023
- Contact the HRMPSB Secretariat
- Review system documentation in `/docs/` folder

---

**Last Updated:** January 2026  
**System Version:** 2.0  
**DepEd Order Reference:** DepEd Order No. 007, s. 2023
