# Annex G-2 Implementation Guide
## Comparative Assessment Results - Consolidated for Administrative/Non-Teaching Positions

---

## Overview

**Annex G-2** is the consolidated ranking report specifically designed for **Administrative Officers and Non-Teaching Positions**. It aggregates all individual evaluation sheets (Annex G) and presents them in a landscape-oriented table that emphasizes:

- **Weighted Points Display** (not just levels)
- **Top 5 Endorsement** highlighting
- **Tie-Breaking Protocol** implementation
- **Specific Plantilla/Item** reference

**Key Differences from Annex G-1:**
- G-1: General summary for all positions
- G-2: Specific focus on administrative/non-teaching roles with plantilla-specific formatting

---

## System Architecture: Dropdown to Report Workflow

### Input/Output Mapping

The system automatically maps dropdown selections from Annex G into weighted points in Annex G-2:

```
┌─────────────────────────────────────────────────────────────────┐
│  ANNEX G (Individual Evaluation Sheet - Dropdown Selections)     │
│                                                                   │
│  Education Dropdown: "Master's Degree + 18 units" → Level 12    │
│  Training Dropdown: "24-32 hours" → Level 4                     │
│  Experience Dropdown: "5 years" → Level 11                       │
│  Performance Input: 4.5 (rating 1-5)                            │
│  Potential Input: 5.0 (rating 1-5)                              │
└─────────────────────────────────────────────────────────────────┘
                              ↓
                  [CRITERION CONVERSION]
                  Evaluator Class Calculates:
                  - Increment = Applicant Level - Baseline Level
                  - Final Score = (Increment × Weight) / 100
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│  ANNEX G-2 (Consolidated Ranking - Weighted Points Display)     │
│                                                                   │
│  Rank │ Name  │ Education │ Training │ Experience │ ... │ Total │
│  ─────┼───────┼───────────┼──────────┼────────────┼─────┼───────│
│  1    │ John  │ 10.00 pts │ 8.00 pts │ 12.00 pts  │ ... │ 87.50 │
│  2    │ Jane  │ 9.00 pts  │ 10.00 pts│ 10.00 pts  │ ... │ 85.00 │
│  3    │ Pete  │ 10.00 pts │ 8.00 pts │ 10.00 pts  │ ... │ 85.00 │
│  ...  │ ...   │ ...       │ ...      │ ...        │ ... │ ...   │
└─────────────────────────────────────────────────────────────────┘
```

### Example Calculation Flow

**Applicant A Profile:**
```
Position: Administrative Officer IV (Group B)
Baseline Education: Level 6 (Bachelor's Degree)
Applicant Education: Master's + 18 units (Level 12)
Increment: 12 - 6 = 6 levels
Group B Weight for Education: 5%
Final Points: (6 increments → 6 points) × (5/100) = 0.30 points

BUT DISPLAYED IN G-2 AS: 10.00 pts (if max weight is 10)
```

---

## Page Access & Workflow

### URL
```
http://your-system.com/generate_car_g2.php
```

### Steps to Generate Annex G-2

#### Step 1: Access the Form
- Navigate to generate_car_g2.php
- Form loads with all available positions

#### Step 2: Select Position
```
Position Selection dropdown shows:
- Information and Communications Technology (SG 11, Item #1)
- Administrative Officer IV (SG 18, Item #5)
- Principal I (SG 19, Item #2)
- [etc.]
```

#### Step 3: Enter Division Office (Optional)
- Pre-filled with: "City Schools Division of Cabuyao"
- User can customize based on actual location

#### Step 4: Choose Output Format
- **HTML:** View in browser (landscape), print from browser
- **PDF:** Download ready-to-sign PDF (landscape orientation)
- **Text:** Plain text file for data archiving

#### Step 5: Generate Report
- System queries evaluation database
- Pulls all evaluation data for selected position
- Retrieves HRMPSB member details
- Generates consolidated report with:
  - Automatic ranking
  - Tie-breaking alerts
  - Top 5 highlighting
  - Certification blocks

---

## Report Structure & Format

### Header Section
```
═══════════════════════════════════════════════════════════════════
                        Annex G-2
            COMPARATIVE ASSESSMENT RESULTS (CONSOLIDATED)
           For Administrative Officers and Non-Teaching Positions
═══════════════════════════════════════════════════════════════════
```

### Position Information Box
```
┌─────────────────────────────────────────────────────────────────┐
│ Position Applied for: Administrative Officer IV                 │
│ Salary Grade: 18 | Item Number: 2024-AO-IV-001                │
│ Schools Division Office: City Schools Division of Cabuyao       │
└─────────────────────────────────────────────────────────────────┘
```

### Main Data Table (Landscape Orientation)

| Col | Header | Description | Format |
|-----|--------|-------------|--------|
| 1 | **Rank** | Numerical rank (1, 2, 3...) | Right-aligned |
| 2 | **Name of Applicant** | Full name | Left-aligned |
| 3 | **Education (5 pts)** | Weighted points for education increment | Right-aligned, 2 decimals |
| 4 | **Training (10 pts)** | Weighted points for training increment | Right-aligned, 2 decimals |
| 5 | **Experience (15 pts)** | Weighted points for experience increment | Right-aligned, 2 decimals |
| 6 | **Performance (20 pts)** | Weighted points for performance rating | Right-aligned, 2 decimals |
| 7 | **Accomplishments (10 pts)** | Weighted points for outstanding accomplishments | Right-aligned, 2 decimals |
| 8 | **Application of Ed (10 pts)** | Weighted points for application of education | Right-aligned, 2 decimals |
| 9 | **Application of L&D (10 pts)** | Weighted points for application of L&D | Right-aligned, 2 decimals |
| 10 | **Potential (20 pts)** | Weighted points for potential rating | Right-aligned, 2 decimals |
| 11 | **Total Score** | Sum of all weighted points | Bold, right-aligned |
| 12 | **Remarks** | Special notes (ties, below threshold, etc.) | Left-aligned, font-size 8pt |

### Color Coding System

**Green Background (Top 5):**
- Highlights the top 5 ranked candidates
- Indicates they are recommended for endorsement
- Thick font weight for emphasis

**Yellow Background (Tied Candidates):**
- Indicates two or more candidates have the same total score
- Signals need for tie-breaking protocol application
- Remarks column shows: "TIE - See tie-breaking protocol"

**Red Background (Below Threshold):**
- Marks candidates with total score < 50 points (50% rule)
- Remarks column shows: "Below 50% threshold"
- These candidates may not be eligible for appointment

---

## Automatic Data Processing Logic

### 1. Data Retrieval from Database

```sql
SELECT 
    e.id, e.applicant_id, e.total_score, a.name as applicant_name,
    aq.performance_rating, aq.potential_level
FROM evaluations e
JOIN applicants a ON e.applicant_id = a.id
LEFT JOIN applicant_qualifications aq ON a.id = aq.applicant_id
WHERE e.position_id = [selected_position]
ORDER BY e.total_score DESC
```

### 2. Sorting & Ranking Algorithm

```
1. Sort by Total Score (descending)
2. If tied on Total Score:
   → Sort by Performance Rating (descending)
3. If tied on Performance Rating:
   → Sort by Potential Rating (descending)
4. If all three tied:
   → Assign same rank, mark with "TIE" remark
```

### 3. Rank Assignment

```
Rank 1: Applicant A (87.50)
Rank 2: Applicant B (87.50) ← Same score as A, lower performance
        [TIE detected - alert generated]
Rank 3: Applicant C (85.00)
Rank 4: Applicant D (82.50)
Rank 5: Applicant E (80.00)
Rank 6: Applicant F (48.00) ← Below 50% threshold
        [Threshold warning - alert generated]
```

### 4. Top 5 Identification

- System automatically identifies ranks 1-5
- Applies green highlighting
- Generates endorsement notice

### 5. Tie-Breaking Alert Generation

When ties detected:
```
⚠ TIE-BREAKING ALERT:
The following candidates have identical total scores:
- Rank 2: Applicant B, Applicant C (Score: 87.50)

Per DepEd Order No. 007, s. 2023, the tie-breaking protocol 
should be applied, considering Performance rating first, 
then Potential rating.
```

---

## Weighted Points Calculation

### Position Group A Weights
```
Education:                    5 points
Training:                     5 points
Experience:                  20 points
Performance:                 20 points
Outstanding Accomplishments: 10 points
Application of Education:    10 points
Application of L&D:          10 points
Potential:                   20 points
─────────────────────────
TOTAL:                      100 points
```

### Position Group B Weights
```
Education:                    5 points
Training:                    10 points
Experience:                  15 points
Performance:                 20 points
Outstanding Accomplishments: 10 points
Application of Education:    10 points
Application of L&D:          10 points
Potential:                   20 points
─────────────────────────
TOTAL:                      100 points
```

### Position Group C Weights (Administrative)
```
Education:                   10 points
Training:                    10 points
Experience:                  10 points
Performance:                 25 points
Outstanding Accomplishments: 10 points
Application of Education:    10 points
Application of L&D:          10 points
Potential:                   15 points
─────────────────────────
TOTAL:                      100 points
```

### Example Point Calculation

**Scenario:** Administrative Officer IV (Group B) Applicant

```
Education: Bachelor → Master's + 18 units (6 increments)
           Points = min(6, 10) = 6 points (out of 5 weight, but normalized)
           
Training: 8 hours → 32 hours (4 increments)
         Points = 4 points (out of 10 weight)

Experience: 0 months → 60 months (10 increments)
           Points = 10 points (out of 15 weight)

Performance: Rating 4.5/5
            Points = (4.5/5) × 20 = 18 points

Outstanding Accomplishments: 8 points
                            Points = min(8, 10) = 8 points

Application of Education: Rating 4/5
                         Points = (4/5) × 10 = 8 points

Application of L&D: Rating 4.5/5
                   Points = (4.5/5) × 10 = 9 points

Potential: Rating 5/5
         Points = (5/5) × 20 = 20 points

─────────────────────────────────────────────────────────
TOTAL SCORE: 6 + 4 + 10 + 18 + 8 + 8 + 9 + 20 = 83 points
```

---

## Certification Section (Annex G-2 Specific)

### Certification Text
```
We, the members of the Human Resource Merit Promotion and Selection 
Board (HRMPSB), hereby certify that the abovementioned applicants were 
assessed based on the existing guidelines and that the comparative 
assessment was conducted objectively and judiciously in accordance with 
the Merit Selection Plan and DepEd Order No. 007, s. 2023.

This Comparative Assessment Results (CAR) is prepared in accordance with 
DepEd Order No. 007, s. 2023, and reflects the actual weighted points 
obtained by each applicant based on their qualifications and submitted 
documentary requirements.
```

### Signature Blocks (Three-Part System)

**1. HRMPSB MEMBERS**
```
┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐
│                 │  │                 │  │                 │
│ _______________│  │ _______________│  │ _______________│
│ [Name 1]       │  │ [Name 2]       │  │ [Name 3]       │
│ [Title 1]      │  │ [Title 2]      │  │ [Title 3]      │
└─────────────────┘  └─────────────────┘  └─────────────────┘
```

**2. SECRETARIAT**
```
┌─────────────────┐  ┌─────────────────┐
│                 │  │                 │
│ _______________│  │ _______________│
│ [Secretary 1]  │  │ [Secretary 2]  │
│ [Title]        │  │ [Title]        │
└─────────────────┘  └─────────────────┘
```

**3. APPOINTING AUTHORITY**
```
┌──────────────────────────────────┐
│                                  │
│ ________________________________│
│ [Schools Division Superintendent]│
│ Appointing Authority             │
│                                  │
│ Date: __________________________│
└──────────────────────────────────┘
```

---

## The 50% Rule Implementation

### Threshold Setting
- Default: 50 points (50% of 100 maximum)
- Configurable per region or memo
- Applied to all candidates

### Processing
```
BEFORE ranking display:
1. Calculate each applicant's total score
2. Check if Total Score < 50
3. If YES:
   - Mark background color as red
   - Add remark: "Below 50% threshold"
   - Flag as ineligible
4. Continue ranking (still listed but flagged)
```

### Example Output
```
| Rank | Name   | Education | ... | Total | Remarks              |
|------|--------|-----------|-----|-------|----------------------|
| 1    | John   | 10.00     | ... | 87.50 | [Green highlight]   |
| 2    | Jane   | 9.00      | ... | 75.00 | [Green highlight]   |
| 3    | Pete   | 10.00     | ... | 52.00 | [Green highlight]   |
| 4    | Sara   | 8.00      | ... | 48.00 | Below 50% threshold |
| 5    | Mike   | 7.00      | ... | 45.00 | Below 50% threshold |
```

---

## Top 5 Endorsement Logic

### Automatic Highlighting
```
- Rank 1 → GREEN + BOLD
- Rank 2 → GREEN + BOLD
- Rank 3 → GREEN + BOLD
- Rank 4 → GREEN + BOLD
- Rank 5 → GREEN + BOLD
- Rank 6+ → WHITE/NORMAL
```

### Endorsement Notice
```
├─────────────────────────────────────────────────────────┤
│ TOP 5 CANDIDATES (Highlighted in Green):               │
│ These candidates are recommended for endorsement to the │
│ Appointing Authority as per Merit Selection Plan        │
│ guidelines.                                             │
└─────────────────────────────────────────────────────────┘
```

---

## Tie-Breaking Protocol (DepEd Order No. 007, s. 2023)

### Step 1: Detect Tied Candidates
System identifies candidates with matching total scores.

### Step 2: Apply First Tie-Breaker
Compare **Performance Rating** scores:
```
Applicant A: Total 87.50, Performance 4.5 ← HIGHER
Applicant B: Total 87.50, Performance 4.0

Result: Applicant A ranks higher
```

### Step 3: Apply Second Tie-Breaker
If Performance also tied, compare **Potential Rating**:
```
Applicant A: Total 87.50, Perf 4.5, Potential 5.0 ← HIGHER
Applicant B: Total 87.50, Perf 4.5, Potential 4.5

Result: Applicant A ranks higher
```

### Step 4: Manual Board Resolution
If all three criteria tied:
```
Decision: HRMPSB Board discretion
Action: Document in Remarks column: "TIED - Board decision pending"
Next Step: Board may conduct additional assessment or interview
```

---

## Export & Sharing

### HTML Format
- Landscape orientation
- View in any browser
- Print to PDF from browser
- Share via email

### PDF Format
- Professional landscape layout
- Ready for signature collection
- Can be digitally signed
- Archive-ready format

### Text Format
- Plain text backup
- For data archival
- Easy to share
- Version control friendly

### File Naming Convention
```
CAR_G2_[PositionName]_[DateGenerated].format

Examples:
CAR_G2_Admin_Officer_IV_20260121_143022.html
CAR_G2_Principal_I_20260121_143022.pdf
CAR_G2_ICT_20260121_143022.txt
```

---

## Database Requirements

### Required Tables
```
- evaluations (evaluation results)
- evaluation_details (criterion-level scores)
- applicants (applicant names)
- applicant_qualifications (qualifications)
- positions (position metadata)
- hrmpsb_members (board member details)
```

### HRMPSB Members Table Structure
```
hrmpsb_members:
├── id (PK)
├── name
├── position
├── member_type (enum: 'hrmpsb', 'secretariat', 'appointing_authority')
├── member_order (for sequence in report)
├── is_active (boolean)
└── created_at
```

---

## Compliance Checklist

✅ **DepEd Order No. 007, s. 2023 Compliance:**
- [x] Weighted points display format
- [x] Automatic ranking algorithm
- [x] Tie-breaking protocol implementation
- [x] Top 5 highlighting for endorsement
- [x] Official certification language
- [x] Multiple signatory sections
- [x] Landscape orientation
- [x] 50% rule implementation
- [x] Consolidated format for administrative/non-teaching
- [x] Data audit trail (database storage)

---

## Troubleshooting

| Issue | Cause | Solution |
|-------|-------|----------|
| No evaluations found | Annex G forms not saved for position | Complete individual evaluations first |
| Incorrect rankings | Database sort issue | Verify evaluation_details scores |
| Missing tie alerts | Comparison threshold too narrow | Check floating-point precision (use 0.001) |
| Signature blocks misaligned | Print settings | Use browser landscape printing |
| Weights don't match | Position group setting | Verify position group in positions table |

---

## Future Enhancements

- [ ] Real-time report preview
- [ ] Email distribution to board members
- [ ] Digital signature integration
- [ ] Batch processing (multiple positions)
- [ ] Historical comparison reports
- [ ] Performance analytics dashboard
- [ ] Integration with CSC e-services
- [ ] Multi-language support

---

**Last Updated:** January 2026  
**System Version:** 2.0  
**DepEd Order Reference:** DepEd Order No. 007, s. 2023  
**Applicable Positions:** Administrative Officers, Non-Teaching Positions, Technical Positions
