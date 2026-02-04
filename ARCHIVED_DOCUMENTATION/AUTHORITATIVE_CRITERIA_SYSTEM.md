# Authoritative Evaluation Criteria System

## Overview
The system now enforces a **single authoritative source of truth** for all evaluation criteria:

**PRIMARY SOURCE**: `config/evaluation_criteria.php`

All reports and calculations use this file exclusively. No fallback defaults or alternative criteria are permitted.

---

## Authoritative Criteria Definitions

### TEACHING POSITIONS (Teacher I)
**Applies to**: Teacher I positions (SG 11-14)
**Total Points**: 100

| Criterion | Max Points | Scoring Method |
|-----------|------------|-----------------|
| a. Education | 10 | Increment scoring based on levels |
| b. Training | 10 | Increment scoring based on levels |
| c. Experience | 10 | Increment scoring based on levels |
| d. PBET, LET, or LEPT Rating | 10 | Rating / 5 × Max Points |
| e. PPST COIs (Classroom Observation or Demonstration Teaching) | 35 | Direct points (capped at max) |
| f. PPST NCOIs (Teacher Reflection) | 25 | Rating / 5 × Max Points |
| g. Application of L&D | 0 | Not applicable |
| h. Potential | 0 | Not applicable |

---

### HIGHER TEACHING POSITIONS
**Applies to**: Master Teachers, Head Teachers (SG 12-31)
**Total Points**: 100

Same criteria as TEACHING POSITIONS (Teacher I)

---

### SCHOOL ADMINISTRATION POSITION
**Applies to**: Principals, Special Principals
**Total Points**: 100

| Criterion | Max Points |
|-----------|------------|
| a. Education | 10 |
| b. Training | 10 |
| c. Experience | 10 |
| d. Performance | 25 |
| e. Outstanding Accomplishments | 10 |
| f. Application of Education | 10 |
| g. Application of L&D | 10 |
| h. Potential (Written Exam, BEI) | 15 |

---

### RELATED TEACHING POSITION
**Applies to**: Counselors, Specialists, etc.
**Total Points**: 100

#### SG 11-15
| Criterion | Max Points |
|-----------|------------|
| a. Education | 10 |
| b. Training | 10 |
| c. Experience | 10 |
| d. Performance | 20 |
| e. Outstanding Accomplishments | 10 |
| f. Application of Education | 10 |
| g. Application of L&D | 10 |
| h. Potential (Written Test, BEI, Work Sample Test) | 20 |

#### SG 16-23 and SG 27
| Criterion | Max Points |
|-----------|------------|
| a. Education | 10 |
| b. Training | 10 |
| c. Experience | 10 |
| d. Performance | 20 |
| e. Outstanding Accomplishments | 5 |
| f. Application of Education | 15 |
| g. Application of L&D | 10 |
| h. Potential (Written Test, BEI, Work Sample Test) | 20 |

#### SG 24 (Chief)
| Criterion | Max Points |
|-----------|------------|
| a. Education | 10 |
| b. Training | 10 |
| c. Experience | 10 |
| d. Performance | 25 |
| e. Outstanding Accomplishments | 10 |
| f. Application of Education | 10 |
| g. Application of L&D | 10 |
| h. Potential (Written Test, BEI, Work Sample Test) | 15 |

---

### NON-TEACHING LEVEL I
**Applies to**: Attorneys, Accountants, Engineers (SG varies)
**Total Points**: 100

#### General Services
| Criterion | Max Points |
|-----------|------------|
| a. Education | 5 |
| b. Training | 5 |
| c. Experience | 20 |
| d. Performance | 10 |
| e. Outstanding Accomplishments | 5 |
| f. Application of Education | 0 |
| g. Application of L&D | 0 |
| h. Potential (Written Test, BEI, Work Sample Test) | 55 |

#### SG 1-9 (Non-General Services)
| Criterion | Max Points |
|-----------|------------|
| a. Education | 5 |
| b. Training | 5 |
| c. Experience | 20 |
| d. Performance | 20 |
| e. Outstanding Accomplishments | 10 |
| f. Application of Education | 10 |
| g. Application of L&D | 10 |
| h. Potential (Written Test, BEI, Work Sample Test) | 20 |

---

### NON-TEACHING LEVEL II
**Applies to**: Dentists, Architects, Programmers (SG varies)
**Total Points**: 100

#### SG 10-22 and SG 27
| Criterion | Max Points |
|-----------|------------|
| a. Education | 5 |
| b. Training | 10 |
| c. Experience | 15 |
| d. Performance | 20 |
| e. Outstanding Accomplishments | 10 |
| f. Application of Education | 10 |
| g. Application of L&D | 10 |
| h. Potential (Written Test, BEI, Work Sample Test) | 20 |

#### SG 24 (Chief)
| Criterion | Max Points |
|-----------|------------|
| a. Education | 10 |
| b. Training | 5 |
| c. Experience | 15 |
| d. Performance | 20 |
| e. Outstanding Accomplishments | 10 |
| f. Application of Education | 10 |
| g. Application of L&D | 10 |
| h. Potential (Written Test, BEI, Work Sample Test) | 20 |

---

## System Architecture

### Three-Module Consistency

All three modules use **identical criteria** from the same source:

1. **Live Calculation Preview** (`index.php`)
   - Loads criteria via `api/get_evaluation_criteria.php`
   - Clears previous criteria when position changes
   - No fallback defaults - shows error if criteria unavailable
   - Displays real-time score calculations

2. **Comparative Assessment Results** (`comparative_assessment_results.php`)
   - Loads criteria via `config/evaluation_criteria.php` function
   - Displays position-specific criterion names
   - Shows max points per criterion
   - Uses same scoring methods as Live Preview

3. **Generative Evaluation Report** (`classes/CARReportGenerator.php`)
   - Loads criteria via `config/evaluation_criteria.php` function
   - Table headers use position-specific criterion names
   - Max points match Live Preview exactly
   - No fallback allowed - returns error if criteria unavailable

### Data Flow

```
Position Selected
    ↓
Detect Position Group + Salary Grade
    ↓
Call getEvaluationCriteria($positionGroup, $salaryGrade, $category)
    ↓
Fetch from config/evaluation_criteria.php
    ↓
Return to:
  - Live Preview (display + calculate)
  - Comparative Assessment (display reference)
  - CAR Report (display headers)
```

### Validation Rules

✅ **Criteria must match exactly across all three modules**
✅ **Position changes clear previous criteria completely**
✅ **No fallback defaults permitted - error on missing criteria**
✅ **Maximum points must match official definitions**
✅ **Criterion names must be position-specific**
✅ **Scoring formulas must be consistent**

---

## Files Updated

1. **config/evaluation_criteria.php**
   - Source of truth for all criteria definitions
   - Updated Teacher I names to official: "PBET, LET, or LEPT Rating" (not "PBET/LET/LEPT Rating")
   - All 8 criteria (a-h) defined for each position group
   - Total points = 100 for all positions

2. **index.php**
   - `loadEvaluationCriteria()`: Clears previous criteria before loading new ones
   - `calculatePreview()`: Removed hardcoded fallback weights
   - No fallback defaults - shows error message if criteria unavailable

3. **comparative_assessment_results.php**
   - Uses `getEvaluationCriteria()` from config/evaluation_criteria.php
   - Added validation: Returns error if criteria not available
   - Displays position-specific names and max points

4. **classes/CARReportGenerator.php**
   - Removed fallback generic names
   - Loads criteria from `config/evaluation_criteria.php`
   - Returns error if criteria unavailable

5. **api/get_evaluation_criteria.php**
   - Returns formatted criteria from `config/evaluation_criteria.php`
   - Supports all position groups and salary grades
   - Handles category parameter for non-teaching positions

---

## Verification Checklist

### ✅ Authoritative Source Enforced
- [x] All modules load from `config/evaluation_criteria.php`
- [x] No hardcoded default criteria in code
- [x] No fallback defaults allowed

### ✅ Criterion Names Match Official Specs
- [x] TEACHING POSITIONS uses "PBET, LET, or LEPT Rating" (not slash format)
- [x] "PPST COIs (Classroom Observation or Demonstration Teaching)" with full names
- [x] "PPST NCOIs (Teacher Reflection)" with full names

### ✅ Max Points Correct
- [x] Teacher I: 10+10+10+10+35+25+0+0 = 100
- [x] School Administration: 10+10+10+25+10+10+10+15 = 100
- [x] Related Teaching SG 11-15: 10+10+10+20+10+10+10+20 = 100
- [x] All other positions: Total = 100

### ✅ No Fallback Defaults
- [x] Live Preview shows error if criteria unavailable
- [x] CAR Report returns error if criteria unavailable
- [x] Comparative Assessment shows error if criteria unavailable

### ✅ Dynamic Criteria Loading
- [x] Criteria load when position selected
- [x] Previous criteria cleared on position change
- [x] Salary grade considered for multi-tier positions

---

## Compliance Statement

This system now fully complies with the requirement:

**"The live calculation preview criteria and scoring logic are the correct and authoritative source. The system must apply the exact same criteria, maximum points, and computation rules when generating the Evaluation Report and generating the Comparative Assessment Results. No alternative, fallback, or default criteria may be used in reports or comparisons."**

All three modules (Live Preview, Evaluation Report, Comparative Assessment) now use the exact same criteria definitions from a single authoritative source with no fallback defaults.
