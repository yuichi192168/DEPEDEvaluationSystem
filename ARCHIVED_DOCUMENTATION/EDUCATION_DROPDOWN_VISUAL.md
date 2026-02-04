# Education Dropdown Implementation - Visual Guide

**Status:** ✅ IMPLEMENTED  
**File:** index.php (Lines 317-354)  
**Dropdown ID:** applicant_education_dropdown  
**Total Levels:** 31 (All from Table 2.a)

---

## DROPDOWN DISPLAY (As shown to users)

```
┌─────────────────────────────────────────────────────────────────────────┐
│ Actual Qualification (Table 2.a) *                                       │
├─────────────────────────────────────────────────────────────────────────┤
│ -- Select Education Level --                                             │
│ Level 1: Can Read and Write to Elementary Level Education                │
│ Level 2: Elementary Graduate to Junior High School Level Education       │
│ Level 3: Completed Junior High School to Senior High School              │
│ Level 4: Senior High School Graduate to Less than 2 years of College    │
│ Level 5: 2+ years College to Less than Bachelor's Degree                │
│ ─────────────────────────────────────────────────────────────────────    │
│ Level 6: Bachelor's Degree to Less than 6 units Master's                │ ← BASELINE
│ ─────────────────────────────────────────────────────────────────────    │
│ Level 7: 6-9 units Master's to Less than 9 units                         │
│ Level 8: 9-12 units Master's to Less than 12 units                       │
│ Level 9: 12-15 units Master's to Less than 15 units                      │
│ Level 10: 15-18 units Master's to Less than 18 units                     │
│ Level 11: 18-21 units Master's to Less than 21 units                     │ ← Test Case 1
│ Level 12: 21+ units Master's / Complete Master's Degree                  │
│ ─────────────────────────────────────────────────────────────────────    │
│ Level 13: 3-6 units Doctorate to Less than 6 units                       │
│ Level 14: 6-9 units Doctorate to Less than 9 units                       │
│ Level 15: 9-12 units Doctorate to Less than 12 units                     │ ← Test Case 2
│ Level 16: 12-15 units Doctorate to Less than 15 units                    │
│ Level 17: 15-18 units Doctorate to Less than 18 units                    │
│ Level 18: 18-21 units Doctorate to Less than 21 units                    │
│ Level 19: 21+ units Doctorate to Complete Academic Requirements          │
│ Level 20: Complete Academic Requirements towards Doctorate               │
│ Level 21: Doctorate Degree                                                │ ← Doctorate Complete
│ ─────────────────────────────────────────────────────────────────────    │
│ Level 22: 3-6 units earned towards 2nd Doctorate                         │
│ Level 23: 6-9 units earned towards 2nd Doctorate                         │
│ Level 24: 9-12 units earned towards 2nd Doctorate                        │
│ Level 25: 12-15 units earned towards 2nd Doctorate                       │
│ Level 26: 15-18 units earned towards 2nd Doctorate                       │
│ Level 27: 18-21 units earned towards 2nd Doctorate                       │
│ Level 28: 21-24 units earned towards 2nd Doctorate                       │
│ Level 29: 24+ units / Complete Academic Requirements 2nd Doctorate       │
│ Level 30: Complete Academic Requirements towards 2nd Doctorate           │
│ Level 31: 2nd Doctorate Degree                                            │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## GROUPINGS BY EDUCATION TYPE

### Basic Education (Levels 1-6)
```
┌─ Pre-Bachelor's Education ────────────────────────────────────┐
│  Level 1: Elementary School                                   │
│  Level 2: Junior High School (K-12)                           │
│  Level 3: Senior High School (K-12)                           │
│  Level 4: Senior High + <2 years College                      │
│  Level 5: 2+ years College (incomplete Bachelor's)            │
│  ─────────────────────────────────────────────────────────    │
│  Level 6: Bachelor's Degree ← BASELINE FOR TEACHER I ✓        │
└────────────────────────────────────────────────────────────────┘
```

### Master's Degree Progression (Levels 7-12)
```
┌─ Master's Degree Units ───────────────────────────────────────┐
│  Level 7:  6-9 units towards Master's                         │
│  Level 8:  9-12 units towards Master's                        │
│  Level 9:  12-15 units towards Master's                       │
│  Level 10: 15-18 units towards Master's                       │
│  Level 11: 18-21 units towards Master's ← Typical Level       │
│  Level 12: 21+ units / Complete Master's Degree              │
└────────────────────────────────────────────────────────────────┘
```

### First Doctorate Progression (Levels 13-21)
```
┌─ Doctorate Degree Units ──────────────────────────────────────┐
│  Level 13: 3-6 units towards Doctorate                        │
│  Level 14: 6-9 units towards Doctorate                        │
│  Level 15: 9-12 units towards Doctorate                       │
│  Level 16: 12-15 units towards Doctorate                      │
│  Level 17: 15-18 units towards Doctorate                      │
│  Level 18: 18-21 units towards Doctorate                      │
│  Level 19: 21+ units towards Doctorate                        │
│  Level 20: Complete coursework (not yet conferred)            │
│  Level 21: Doctorate Degree Conferred ✓                       │
└────────────────────────────────────────────────────────────────┘
```

### Second Doctorate Progression (Levels 22-31)
```
┌─ Second Doctorate Units ──────────────────────────────────────┐
│  Level 22: 3-6 units towards 2nd Doctorate                    │
│  Level 23: 6-9 units towards 2nd Doctorate                    │
│  Level 24: 9-12 units towards 2nd Doctorate                   │
│  Level 25: 12-15 units towards 2nd Doctorate                  │
│  Level 26: 15-18 units towards 2nd Doctorate                  │
│  Level 27: 18-21 units towards 2nd Doctorate                  │
│  Level 28: 21-24 units towards 2nd Doctorate                  │
│  Level 29: 24+ units towards 2nd Doctorate                    │
│  Level 30: Complete coursework (not yet conferred)            │
│  Level 31: 2nd Doctorate Degree Conferred ✓                   │
└────────────────────────────────────────────────────────────────┘
```

---

## EXAMPLE SELECTIONS

### Scenario 1: Teacher I Applicant (Bachelor's + 18 Units Master's)
```
Steps:
1. Open dropdown
2. Scroll to Master's Degree section
3. Find: "Level 11: 18-21 units Master's to Less than 21 units"
4. Click to select

Result:
- Education Level: 11
- Baseline: 6
- Increment: 11 - 6 = 5 increments
- Points: 4 (per 4-5 increment rubric) ✓
```

### Scenario 2: Administrator with Master's Degree
```
Steps:
1. Open dropdown
2. Scroll to Master's section
3. Find: "Level 12: 21+ units Master's / Complete Master's Degree"
4. Click to select

Result:
- Education Level: 12
- Baseline: 6
- Increment: 12 - 6 = 6 increments
- Points: 6 (per 6-7 increment rubric) ✓
```

### Scenario 3: Doctorate Holder
```
Steps:
1. Open dropdown
2. Scroll to Doctorate section
3. Find: "Level 21: Doctorate Degree"
4. Click to select

Result:
- Education Level: 21
- Baseline: 6
- Increment: 21 - 6 = 15 increments
- Points: 10 (maximum, 10+ increment rubric) ✓ MAXIMUM
```

---

## BACKEND PROCESSING

When a user selects an education level, the system:

```
┌─ User Selection ─────────────────────┐
│ Level 11: 18-21 units Master's       │
└────────────┬────────────────────────┘
             │
             ▼
┌─ JavaScript Processing ──────────────────────────┐
│ 1. Get selected value: "11"                      │
│ 2. Convert to integer: educationLevel = 11       │
│ 3. Map to underlying fields:                     │
│    - degree_field = "Master"                     │
│    - masters_units = 18 (calculated: 11-6)*3   │
│    - doctoral_units = 0                          │
│ 4. Store in hidden fields                        │
│ 5. Trigger calculation                           │
└────────────┬─────────────────────────────────────┘
             │
             ▼
┌─ Calculation Engine ─────────────────────────────┐
│ 1. Applicant Level = 11                          │
│ 2. Baseline Level = 6                            │
│ 3. Increment = 11 - 6 = 5                        │
│ 4. Points = 4 (per rubric for 4-5 increments)   │
│ 5. Display in real-time preview                  │
│ 6. Save to database on form submit               │
└────────────────────────────────────────────────────┘
```

---

## VALIDATION RULES

When selecting an education level:

```
✅ VALID SELECTIONS
- Any level from 1-31
- Clear representation in Table 2.a
- Appropriate for position group
- May be below/at/above baseline

❌ INVALID SELECTIONS
- No selection (--Select Education Level--)
- Does not meet position minimum education requirement
- Inconsistent with other fields

⚠️  WARNINGS (but allowed)
- Below minimum baseline (evaluated during ranking)
- Less than typical for position type
```

---

## REAL-TIME DISPLAY

After selection, the form shows:

```
┌─ Applicant Qualifications ────────────────────────┐
│                                                    │
│ Education (Dropdown A)                            │
│ Actual Qualification (Table 2.a) *                │
│ ┌─────────────────────────────────────────────┐   │
│ │ Level 11: 18-21 units Master's... ▼        │   │ ← Selected
│ └─────────────────────────────────────────────┘   │
│                                                    │
│ Education Level Display:                          │
│ Level: 11                                          │ ← Shows selected level
│                                                    │
│ [Real-time calculation preview]                   │
│ Increment: 5 (11 - 6)                            │
│ Points: 4                                          │
│                                                    │
└────────────────────────────────────────────────────┘
```

---

## COMPARING TO OLD DROPDOWN

### Before (Limited Options)
```
Dropdown Options: 3 only
  - Bachelor's Degree (Level 6)
  - Bachelor's + 18 units (Level 11)
  - Master's Degree (Level 21)

Limitations:
  ❌ No intermediate levels
  ❌ No doctorate options
  ❌ No 2nd doctorate options
  ❌ Limited to common cases only
```

### After (Complete Table 2.a)
```
Dropdown Options: 31 all levels
  - Level 1-6: Pre-Bachelor's and Bachelor's
  - Level 7-12: Master's progression
  - Level 13-21: Doctorate progression
  - Level 22-31: 2nd Doctorate progression

Improvements:
  ✅ All 31 levels available
  ✅ Covers all educational paths
  ✅ Matches Table 2.a exactly
  ✅ More accurate data entry
  ✅ Better compliance with DepEd requirements
```

---

## RELATED DOCUMENTATION

- **Full Reference:** [EDUCATION_LEVELS_REFERENCE.md](EDUCATION_LEVELS_REFERENCE.md)
- **Test Cases:** [TEST_CASES.md](TEST_CASES.md)
- **Quick Reference:** [QUICK_REFERENCE_ETE.md](QUICK_REFERENCE_ETE.md#level-assignment-decision-tree)
- **Source:** Table 2.a from DepEd Order No. 007, s. 2023

---

**Implementation Status:** ✅ COMPLETE  
**All 31 education levels now available in dropdown!**

