# Quick Reference: ETE Computation Guide
## For System Developers & DepEd Board Members

---

## CONVERSION TABLES AT A GLANCE

### Table 2.a: Education Levels
```
Level   Education Qualification
───────────────────────────────────────────────────
1       Can Read and Write to Elementary Level
2       Elementary to Junior High School (K-12)
3       Completed Junior High School to Senior High
4       Less than 2 years College
5       2+ years College to Less than Bachelor's
6       Bachelor's Degree ← BASELINE FOR TEACHER I
7       6-9 units Master's (Level 7)
8       9-12 units Master's
9       12-15 units Master's
10      15-18 units Master's
11      18-21 units Master's
12      21+ units / Master's Degree ✓
13      3-6 units Doctorate
...
20      Complete Academic Requirements towards Doctorate / Doctorate ✓
21      Doctorate ✓
```

### Table 2.b: Training Levels (Cumulative Hours)
```
Level   Hours Range              Increments from Baseline
───────────────────────────────────────────────────
1       0 to <8 hours           ← BASELINE FOR TEACHER I
2       8 to <16 hours
3       16 to <24 hours
4       24 to <32 hours         ← Test Case 4 example
5       32 to <40 hours         ← Test Case 1 example
6       40 to <48 hours
7       48 to <56 hours
8       56 to <64 hours
9       64 to <72 hours         ← Test Case 2 example
10      72 to <80 hours
...
13      96+ hours               ← Test Case 5 example
```

### Table 2.c: Experience Levels (Teaching)
```
Level   Experience Range        Examples
───────────────────────────────────────────────────
1       None / <6 months       ← BASELINE FOR TEACHER I
2       6 months to <1 year
3       1 year to <1.5 years
4       1.5 to <2 years
5       2 to <2.5 years
6       2.5 to <3 years
7       3 to <3.5 years        ← Teacher I min standard (3 yrs)
8       3.5 to <4 years
9       4 to <4.5 years        ← Test Case 1 example (4yr3m)
10      4.5 to <5 years
...
16      7.5 to <8 years        ← Test Case 2 example (8yr2m)
...
29      14.5 to <15 years      ← Test Case 5 example (15yrs)
31      15+ years
```

---

## INCREMENT CALCULATION FORMULA

```
Increment = Applicant's Level - Baseline Level
```

### Baseline Levels for Teacher I:
| Criterion | Baseline Level | Reason |
|-----------|---|---|
| Education | 6 | Bachelor's Degree minimum |
| Training | 1 | No training required |
| Experience | 1 | No experience required |

---

## POINTS CONVERSION RUBRIC

```
Increments from Baseline    →    Points Awarded
──────────────────────────────────────────────
0 or 1                      →    0 points
2 or 3                      →    2 points
4 or 5                      →    4 points
6 or 7                      →    6 points
8 or 9                      →    8 points
10 or more                  →    10 points (MAX)
```

**Maximum Points per Criterion:** 10 (ETE) or 10 (PBET) or 35 (PPST-COI) or 25 (PPST-NCOI)

---

## QUICK COMPUTATION EXAMPLES

### Example 1: Applicant A (from DepEd Document) ✅
```
Education:
  - Qualification: BSEd + 18 units Master's → Level 11
  - Baseline: Level 6
  - Increment: 11 - 6 = 5 increments
  - Points: 5 increments = 4 points ✓

Training:
  - Hours: 32 hours → Level 5
  - Baseline: Level 1
  - Increment: 5 - 1 = 4 increments
  - Points: 4 increments = 4 points ✓

Experience:
  - Duration: 4 years 3 months → Level 9
  - Baseline: Level 1
  - Increment: 9 - 1 = 8 increments
  - Points: 8 increments = 8 points ✓

TOTAL ETE: 4 + 4 + 8 = 16 POINTS
```

### Example 2: Applicant with 24 Hours Training (Edge Case)
```
Training:
  - Hours: 24 hours → Level 4 (NOT Level 5!)
  - Baseline: Level 1
  - Increment: 4 - 1 = 3 increments
  - Points: 3 increments = 2 points ✓
  
Note: Different from 32 hours, which gives 4 points
```

### Example 3: New Graduate (Baseline Only)
```
Education:
  - Qualification: BSEd only → Level 6
  - Baseline: Level 6
  - Increment: 6 - 6 = 0 increments
  - Points: 0 increments = 0 points ✓

Training:
  - Hours: None → Level 1
  - Baseline: Level 1
  - Increment: 1 - 1 = 0 increments
  - Points: 0 points ✓

Experience:
  - Duration: None → Level 1
  - Baseline: Level 1
  - Increment: 1 - 1 = 0 increments
  - Points: 0 points ✓

TOTAL ETE: 0 + 0 + 0 = 0 POINTS
```

---

## VERIFICATION CHECKLIST

**Before entering data into system, verify:**

- [ ] Education level is 1-31 ✓
- [ ] Training hours are 0-240+ hours ✓
- [ ] Experience is in months or years ✓
- [ ] Baseline levels: Edu=6, Train=1, Exp=1 ✓
- [ ] All increments are non-negative ✓
- [ ] Points follow rubric table ✓
- [ ] Total ETE = sum of three criteria ✓
- [ ] Total ETE is 0-30 points maximum ✓

---

## COMMON DATA ENTRY ERRORS

| Error | Example | Fix |
|-------|---------|-----|
| Wrong baseline | Using Level 5 for education baseline | Use Level 6 (Bachelor's minimum) |
| Hour to level mismatch | Entering 24 hours but assigning Level 5 | 24 hours = Level 4, 32 hours = Level 5 |
| Reverse calculation | Experience - Education instead of other way | Always use: Applicant - Baseline |
| Wrong rubric range | 4 increments gives 6 points | Use rubric: 4 increments = 4 points |
| Negative increments | Getting negative numbers | Verify applicant level ≥ baseline |
| Exceeding maximum | Education showing 15 points | Max is 10; use rubric cap at 10+ increments |

---

## LEVEL ASSIGNMENT DECISION TREE

### Education Level Assignment
```
START: What is the applicant's education qualification?

├─ Elementary only?
│  └─ Level 2
├─ High School only?
│  └─ Level 3
├─ Some College (1-2 years)?
│  └─ Level 4-5
├─ Bachelor's Degree (BS/BEd)?
│  └─ Level 6 ← BASELINE FOR TEACHER I
├─ Bachelor's + some Master's units (1-21 units)?
│  └─ Level 7-12
│     - 1-6 units: Level 7
│     - 6-9 units: Level 8
│     - 9-12 units: Level 9
│     - 12-15 units: Level 10
│     - 15-18 units: Level 11
│     - 18-21 units: Level 12 (or Master's Degree)
├─ Master's Degree Complete?
│  └─ Level 12
├─ Doctorate (partial or complete)?
│  └─ Level 13-21
└─ END: Assign level
```

### Training Level Assignment
```
START: How many training hours?

├─ 0 to <8 hours?
│  └─ Level 1 ← BASELINE FOR TEACHER I
├─ 8 to <16 hours?
│  └─ Level 2
├─ 16 to <24 hours?
│  └─ Level 3
├─ 24 to <32 hours?
│  └─ Level 4 ← Example: 24 hours
├─ 32 to <40 hours?
│  └─ Level 5 ← Example: 32 hours
├─ 40+ hours?
│  └─ Level 6+ (higher based on total)
└─ END: Assign level
```

### Experience Level Assignment
```
START: What is total teaching experience?

├─ None or <6 months?
│  └─ Level 1 ← BASELINE FOR TEACHER I
├─ 6 months to <1 year?
│  └─ Level 2
├─ 1 year to <1.5 years?
│  └─ Level 3
├─ 1.5 to <2 years?
│  └─ Level 4
├─ 2 to <3 years?
│  └─ Level 5-6
├─ 3 to <4 years?
│  └─ Level 7-8 ← Example: 3-4 years
├─ 4 to <5 years?
│  └─ Level 9-10 ← Example: 4.25 years (Test Case 1)
├─ 5+ years?
│  └─ Level 11+ (higher based on total)
└─ END: Assign level
```

---

## TEST DATA FOR QUICK VERIFICATION

### Easy Test Case (All Minimum):
```
Education: Bachelor's (Level 6) → 0 increments → 0 points
Training: None (Level 1) → 0 increments → 0 points
Experience: None (Level 1) → 0 increments → 0 points
Total: 0 points ← Meets minimum but no extra points
```

### Medium Test Case (Some Experience):
```
Education: Bachelor's + 12 units Master's (Level 9) → 3 increments → 2 points
Training: 24 hours (Level 4) → 3 increments → 2 points
Experience: 3.5 years (Level 8) → 7 increments → 6 points
Total: 10 points ← Moderate candidate
```

### Hard Test Case (High Achiever):
```
Education: Master's + 9 Doctorate units (Level 15) → 9 increments → 8 points
Training: 64 hours (Level 9) → 8 increments → 8 points
Experience: 8.2 years (Level 16) → 15 increments → 10 points (capped)
Total: 26 points ← Highly qualified
```

---

## SYSTEM IMPLEMENTATION CHECKLIST

### Database Fields
- [ ] education_level (INT, 1-31)
- [ ] education_baseline (INT, = 6 for Teacher I)
- [ ] education_increment (INT, calculated)
- [ ] education_points (INT, 0-10)
- [ ] training_hours (INT or DECIMAL, 0-240+)
- [ ] training_level (INT, 1-31)
- [ ] training_baseline (INT, = 1 for Teacher I)
- [ ] training_increment (INT, calculated)
- [ ] training_points (INT, 0-10)
- [ ] experience_months (INT or DATE range)
- [ ] experience_level (INT, 1-31)
- [ ] experience_baseline (INT, = 1 for Teacher I)
- [ ] experience_increment (INT, calculated)
- [ ] experience_points (INT, 0-10)
- [ ] total_ete_points (INT, 0-30)

### Validation Rules
- [ ] 0 ≤ education_level ≤ 31
- [ ] 0 ≤ training_hours ≤ 240 (or max expected)
- [ ] 0 ≤ experience_months ≤ max tenure
- [ ] increment = applicant_level - baseline_level
- [ ] points = rubric_lookup(increment)
- [ ] total_ete = education_points + training_points + experience_points
- [ ] 0 ≤ total_ete ≤ 30

---

## FORMULA SUMMARY FOR DEVELOPERS

```javascript
// Education
educationLevel = getEducationLevelFromQualification(qualification); // 1-31
educationIncrement = educationLevel - 6; // Baseline is 6
educationPoints = getPointsFromIncrement(educationIncrement); // 0-10

// Training
trainingLevel = getTrainingLevelFromHours(hours); // 1-31
trainingIncrement = trainingLevel - 1; // Baseline is 1
trainingPoints = getPointsFromIncrement(trainingIncrement); // 0-10

// Experience
experienceLevel = getExperienceLevelFromMonths(months); // 1-31
experienceIncrement = experienceLevel - 1; // Baseline is 1
experiencePoints = getPointsFromIncrement(experienceIncrement); // 0-10

// ETE Total
totalETEPoints = educationPoints + trainingPoints + experiencePoints; // 0-30

// Increment to Points Mapping
function getPointsFromIncrement(increment) {
  if (increment >= 10) return 10;
  if (increment >= 8) return 8;
  if (increment >= 6) return 6;
  if (increment >= 4) return 4;
  if (increment >= 2) return 2;
  return 0;
}
```

---

**Last Updated:** January 21, 2026  
**Version:** 1.0  
**Status:** Ready for Developer Implementation

