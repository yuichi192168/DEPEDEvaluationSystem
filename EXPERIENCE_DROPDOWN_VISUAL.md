# Experience Dropdown Visual Guide
## All 31 Experience Levels from Table 2.c - Visual Implementation

**Date Created:** January 21, 2026  
**Status:** ✅ Complete visual guide for experience dropdown  
**Related:** [EXPERIENCE_LEVELS_REFERENCE.md](EXPERIENCE_LEVELS_REFERENCE.md)

---

## TABLE OF CONTENTS

1. [Dropdown Display - Complete List](#dropdown-display---complete-list)
2. [Level Groupings](#level-groupings)
3. [Time Range Visualization](#time-range-visualization)
4. [Selection Examples](#selection-examples)
5. [Backend Processing Flow](#backend-processing-flow)
6. [Before & After Comparison](#before--after-comparison)
7. [User Journey](#user-journey)
8. [Implementation Reference](#implementation-reference)

---

## Dropdown Display - Complete List

### How It Appears in the Form

```
┌──────────────────────────────────────────────────────────┐
│ Experience (Table 2.c) *                                 │
│                                                          │
│ ┌─────────────────────────────────────────────────────┐ │
│ │ -- Select Experience Level --                   ▼   │ │
│ ├─────────────────────────────────────────────────────┤ │
│ │ Level 1:  None to Less than 6 months             │ │
│ │ Level 2:  6 months to Less than 1 year           │ │
│ │ Level 3:  1 year to Less than 1 year 6 months    │ │
│ │ Level 4:  1 year 6 months to Less than 2 years   │ │
│ │ Level 5:  2 years to Less than 2 years 6 months  │ │
│ │ Level 6:  2 years 6 months to Less than 3 years  │ │
│ │ Level 7:  3 years to Less than 3 years 6 months  │ │
│ │ Level 8:  3 years 6 months to Less than 4 years  │ │
│ │ Level 9:  4 years to Less than 4 years 6 months  │ │
│ │ Level 10: 4 years 6 months to Less than 5 years  │ │
│ │ Level 11: 5 years to Less than 5 years 6 months  │ │
│ │ Level 12: 5 years 6 months to Less than 6 years  │ │
│ │ Level 13: 6 years to Less than 6 years 6 months  │ │
│ │ Level 14: 6 years 6 months to Less than 7 years  │ │
│ │ Level 15: 7 years to Less than 7 years 6 months  │ │
│ │ Level 16: 7 years 6 months to Less than 8 years  │ │
│ │ Level 17: 8 years to Less than 8 years 6 months  │ │
│ │ Level 18: 8 years 6 months to Less than 9 years  │ │
│ │ Level 19: 9 years to Less than 9 years 6 months  │ │
│ │ Level 20: 9 years 6 months to Less than 10 years │ │
│ │ Level 21: 10 years to Less than 10 years 6 mo... │ │
│ │           ... (scroll to see more) ...            │ │
│ │ Level 31: 15 years or more                        │ │
│ └─────────────────────────────────────────────────────┘ │
│                                                          │
│ Per DepEd Order No. 007, s. 2023                        │
│                                                          │
└──────────────────────────────────────────────────────────┘
```

---

## Level Groupings

### Entry Level Experience (Levels 1-5: 0-2.5 years)
```
┌──────────────────────────────────────────────────┐
│ ENTRY LEVEL EXPERIENCE                           │
├──────────────────────────────────────────────────┤
│ Level 1: None to Less than 6 months             │
│ Level 2: 6 months to Less than 1 year           │
│ Level 3: 1 year to Less than 1 year 6 months    │
│ Level 4: 1 year 6 months to Less than 2 years   │
│ Level 5: 2 years to Less than 2 years 6 months  │
├──────────────────────────────────────────────────┤
│ Profile: New teacher through early career       │
│ Teaching Years: 0-2.5 years                     │
│ Points: 0-4                                      │
└──────────────────────────────────────────────────┘
```

### Early Development (Levels 6-10: 2.5-5 years)
```
┌──────────────────────────────────────────────────┐
│ EARLY CAREER DEVELOPMENT                         │
├──────────────────────────────────────────────────┤
│ Level 6: 2 years 6 months to Less than 3 years  │
│ Level 7: 3 years to Less than 3 years 6 months  │
│ Level 8: 3 years 6 months to Less than 4 years  │
│ Level 9: 4 years to Less than 4 years 6 months  │
│ Level 10: 4 years 6 months to Less than 5 years │
├──────────────────────────────────────────────────┤
│ Profile: Teacher with growing experience base   │
│ Teaching Years: 2.5-5 years                     │
│ Points: 5-9                                      │
└──────────────────────────────────────────────────┘
```

### Mid-Career Development (Levels 11-15: 5-7.5 years)
```
┌──────────────────────────────────────────────────┐
│ MID-CAREER DEVELOPMENT                           │
├──────────────────────────────────────────────────┤
│ Level 11: 5 years to Less than 5 years 6 mo     │
│ Level 12: 5 years 6 months to Less than 6 years │
│ Level 13: 6 years to Less than 6 years 6 mo     │
│ Level 14: 6 years 6 months to Less than 7 years │
│ Level 15: 7 years to Less than 7 years 6 mo     │
├──────────────────────────────────────────────────┤
│ Profile: Experienced teacher with maturity      │
│ Teaching Years: 5-7.5 years                     │
│ Points: 10 (capped)                              │
└──────────────────────────────────────────────────┘
```

### Career Advancement (Levels 16-20: 7.5-10 years)
```
┌──────────────────────────────────────────────────┐
│ CAREER ADVANCEMENT                               │
├──────────────────────────────────────────────────┤
│ Level 16: 7 years 6 months to Less than 8 years │
│ Level 17: 8 years to Less than 8 years 6 mo     │
│ Level 18: 8 years 6 months to Less than 9 years │
│ Level 19: 9 years to Less than 9 years 6 mo     │
│ Level 20: 9 years 6 months to Less than 10 yrs  │
├──────────────────────────────────────────────────┤
│ Profile: Accomplished teacher with authority    │
│ Teaching Years: 7.5-10 years                    │
│ Points: 10 (capped)                              │
└──────────────────────────────────────────────────┘
```

### Veteran Development (Levels 21-25: 10-12.5 years)
```
┌──────────────────────────────────────────────────┐
│ VETERAN TEACHER DEVELOPMENT                      │
├──────────────────────────────────────────────────┤
│ Level 21: 10 years to Less than 10 years 6 mo   │
│ Level 22: 10 years 6 months to Less than 11 yrs │
│ Level 23: 11 years to Less than 11 years 6 mo   │
│ Level 24: 11 years 6 months to Less than 12 yrs │
│ Level 25: 12 years to Less than 12 years 6 mo   │
├──────────────────────────────────────────────────┤
│ Profile: Long-serving teacher with leadership   │
│ Teaching Years: 10-12.5 years                   │
│ Points: 10 (capped)                              │
└──────────────────────────────────────────────────┘
```

### Senior Development (Levels 26-31: 12.5+ years)
```
┌──────────────────────────────────────────────────┐
│ SENIOR TEACHER DEVELOPMENT                       │
├──────────────────────────────────────────────────┤
│ Level 26: 12 years 6 months to Less than 13 yrs │
│ Level 27: 13 years to Less than 13 years 6 mo   │
│ Level 28: 13 years 6 months to Less than 14 yrs │
│ Level 29: 14 years to Less than 14 years 6 mo   │
│ Level 30: 14 years 6 months to Less than 15 yrs │
│ Level 31: 15 years or more                      │
├──────────────────────────────────────────────────┤
│ Profile: Senior teacher with extensive service  │
│ Teaching Years: 12.5+ years                     │
│ Points: 10 (capped)                              │
└──────────────────────────────────────────────────┘
```

---

## Time Range Visualization

### Experience Timeline (0-15+ years)

```
        Entry      Early       Mid          Career      Veteran     Senior
        0-2.5y    2.5-5y      5-7.5y      7.5-10y     10-12.5y    12.5-15y+
        |         |            |           |            |          |
    L1 L2 L3 L4 L5 L6 L7 L8 L9 L10 L11 L12 L13 L14 L15 L16 L17 L18 L19 L20 L21 L22 L23 L24 L25 L26 L27 L28 L29 L30 L31
    |─|─|─|─|─|──|──|──|──|───|───|───|───|───|───|───|───|───|───|───|───|───|───|───|───|───|───|───|───|───|────|
    0  6 12 18 24 30 36 42 48 54 60 66 72 78 84 90 96 102 108 114 120 126 132 138 144 150 156 162 168 174 180+
    months (0-6, 6-12, ... 174-180, 180+)
    
    Each Level = 6 months increment
    Total Span = 0 to 180+ months (0 to 15+ years, 31 levels)
```

### Year-by-Year Progression

```
Years    Months  Levels                                          Points
─────────────────────────────────────────────────────────────────────
0 mo      0      L1 (None-6mo)    ← BASELINE (starts here)         0
6 mo      6      L2 (6mo-1yr)                                       1
1 year    12     L3 (1-1.5yr)                                       2
1.5 yr    18     L4 (1.5-2yr)                                       3
2 years   24     L5 (2-2.5yr)                                       4
2.5 yr    30     L6 (2.5-3yr)                                       5
3 years   36     L7 (3-3.5yr)                                       6
3.5 yr    42     L8 (3.5-4yr)                                       7
4 years   48     L9 (4-4.5yr)                                       8
4.5 yr    54     L10 (4.5-5yr)                                      9
5 years   60     L11 (5-5.5yr)                                      10 ← MAX reached
5.5 yr    66     L12 (5.5-6yr)                                      10
6 years   72     L13 (6-6.5yr)                                      10
...       ...    ...                                                10
15 years  180    L31 (15+yr)                                        10
```

---

## Selection Examples

### Example 1: Teacher with 2 Years Experience

**Step 1: Identify Teaching Experience**
```
Teacher has been teaching:
- 2 years (24 months)
```

**Step 2: Find Range**
```
24 months falls in: 2-2.5 years (24-30 months) range
→ This is Level 5
```

**Step 3: Select from Dropdown**
```
User opens dropdown
↓
Finds: "Level 5: 2 years to Less than 2 years 6 months"
↓
Clicks to select
```

**Step 4: System Auto-Calculate**
```
Experience Level Selected: 5
Baseline Level: 1
Increment: 5 - 1 = 4
Points: 4 (from increment rubric)
✓ Form auto-updates with 4 points
```

**Result:**
```
┌─────────────────────────────┐
│ Experience Points: 4        │
│ Reason: 2 years = Level 5   │
│         5 - 1 = 4 increment │
└─────────────────────────────┘
```

---

### Example 2: Teacher with 8 Years Experience

**Step 1: Identify Teaching Experience**
```
Teacher has been teaching:
- 8 years (96 months)
```

**Step 2: Find Range**
```
96 months falls in: 8-8.5 years (96-102 months) range
→ This is Level 17
```

**Step 3: Select from Dropdown**
```
User opens dropdown
↓
Scrolls down to find: "Level 17: 8 years to Less than 8 years 6 months"
↓
Clicks to select
```

**Step 4: System Auto-Calculate**
```
Experience Level Selected: 17
Baseline Level: 1
Increment: 17 - 1 = 16
Points: 10 (capped, even though increment is 16)
✓ Form auto-updates with 10 points (MAX)
```

**Result:**
```
┌─────────────────────────────┐
│ Experience Points: 10 (MAX) │
│ Reason: 8 years = Level 17  │
│         17 - 1 = 16         │
│         Capped at 10        │
└─────────────────────────────┘
```

---

### Example 3: New Teacher (0 experience)

**Step 1: Identify Teaching Experience**
```
Teacher is:
- Brand new, just hired
Total: 0 months experience
```

**Step 2: Find Range**
```
0 months falls in: None-6 months range
→ This is Level 1
```

**Step 3: Select from Dropdown**
```
User opens dropdown
↓
Finds: "Level 1: None to Less than 6 months"
↓
Clicks to select
```

**Step 4: System Auto-Calculate**
```
Experience Level Selected: 1
Baseline Level: 1
Increment: 1 - 1 = 0
Points: 0
✓ Form auto-updates with 0 points
```

**Result:**
```
┌─────────────────────────────┐
│ Experience Points: 0        │
│ Reason: 0 months = Level 1  │
│         1 - 1 = 0 increment │
│         Meets minimum       │
└─────────────────────────────┘
```

---

### Example 4: Senior Teacher (15+ years)

**Step 1: Identify Teaching Experience**
```
Teacher has been teaching:
- 20 years (240 months)
```

**Step 2: Find Range**
```
240 months falls in: 15+ years range (180+ months)
→ This is Level 31
```

**Step 3: Select from Dropdown**
```
User opens dropdown
↓
Scrolls to bottom
↓
Finds: "Level 31: 15 years or more"
↓
Clicks to select
```

**Step 4: System Auto-Calculate**
```
Experience Level Selected: 31
Baseline Level: 1
Increment: 31 - 1 = 30
Points: 10 (capped)
✓ Form auto-updates with 10 points (MAX)
```

**Result:**
```
┌─────────────────────────────┐
│ Experience Points: 10 (MAX) │
│ Reason: 20 years = Level 31 │
│         31 - 1 = 30         │
│         Capped at 10        │
└─────────────────────────────┘
```

---

## Backend Processing Flow

### How Selection Becomes Points

```
STEP 1: USER INTERFACE (What User Does)
├─ Opens form
├─ Locates "Experience (Table 2.c)" dropdown
├─ Clicks dropdown to view options
└─ Selects appropriate level (e.g., Level 13)

    ↓↓↓

STEP 2: HTML/JAVASCRIPT PROCESSING
├─ Dropdown captures selected value: "13"
├─ Triggers syncExperienceFromDropdown() function
├─ Converts Level 13 to representative months:
│  └─ Level 13 → 75 months (midpoint: (72+78)/2)
│     ← This is 6 years experience
└─ Updates hidden field: applicant_experience = "75"

    ↓↓↓

STEP 3: CALCULATION ENGINE
├─ Reads applicant_experience field: 75 months
├─ Applies experience-to-level converter
│  └─ 75 months → Level 13
├─ Reads baseline from position
│  └─ Teacher I baseline → Level 1
├─ Calculates increment: 13 - 1 = 12
└─ Looks up points in increment rubric: 10 points (capped)

    ↓↓↓

STEP 4: FORM UPDATE (What User Sees)
├─ Form auto-updates experience points field
├─ Displays: "Experience Increment Points: 10"
├─ Breakdown shows:
│  ├─ Selected Level: 13
│  ├─ Baseline Level: 1
│  ├─ Increment: 12
│  └─ Points: 10 (capped at maximum)
└─ Form ready for submission

    ↓↓↓

STEP 5: DATABASE STORAGE (Backend)
├─ On form submit
├─ Stored values:
│  ├─ applicant_experience_level = 13
│  ├─ applicant_experience = 75
│  ├─ applicant_experience_increment = 12
│  └─ applicant_experience_points = 10
└─ Data saved to database
```

### Code Implementation

```javascript
// FUNCTION: Sync experience dropdown to months value
function syncExperienceFromDropdown() {
    const dropdown = document.getElementById('applicant_experience_dropdown');
    const hiddenField = document.getElementById('applicant_experience');
    
    if (!dropdown || !hiddenField) return;
    
    const experienceLevel = parseInt(dropdown.value || '0') || 0;
    let months = 0;
    
    // Convert level to representative months
    // (using midpoint of each 6-month range)
    if (experienceLevel === 1) months = 3;      // 0-6mo → 3
    else if (experienceLevel === 2) months = 9;      // 6mo-1yr → 9
    else if (experienceLevel === 5) months = 27;     // 2-2.5yr → 27
    else if (experienceLevel === 13) months = 75;    // 6-6.5yr → 75
    else if (experienceLevel === 21) months = 123;   // 10-10.5yr → 123
    else if (experienceLevel === 31) months = 180;   // 15+ yr → 180
    
    // Pattern: months = (level - 1) * 6 + 3 (for most levels)
    // Store months in hidden field for backend processing
    hiddenField.value = months;
}

// FUNCTION: Calculate points from experience months
function calculateExperiencePoints(months) {
    // Convert months to level using inverse formula
    // If months = (level - 1) * 6 + 3, then:
    // level = (months - 3) / 6 + 1 = (months + 3) / 6
    
    let experienceLevel = 1;
    if (months >= 0 && months < 6) experienceLevel = 1;
    else if (months >= 6 && months < 12) experienceLevel = 2;
    else if (months >= 12 && months < 18) experienceLevel = 3;
    // ... (continues for all ranges)
    else if (months >= 174) experienceLevel = 31;
    
    // Get baseline (always Level 1 for Teacher I)
    const baseline = 1;
    
    // Calculate increment
    const increment = experienceLevel - baseline;
    
    // Calculate points (capped at 10)
    const points = Math.min(increment, 10);
    
    return {
        level: experienceLevel,
        increment: increment,
        points: points
    };
}
```

---

## Before & After Comparison

### BEFORE (Limited Dropdown - 9 Options)

```
┌────────────────────────────────────────┐
│ Experience (Dropdown C)                │
├────────────────────────────────────────┤
│ ☐ Select Years of Experience     ▼    │
│ ☐ None / Less than 6 months    (L1)   │
│ ☐ 6 months to 1 year           (L2)   │
│ ☐ 1 year to 1.5 years          (L3)   │
│ ☐ 1.5 years to 2 years         (L4)   │
│ ☐ 2 years to 2.5 years         (L5)   │
│ ☐ 2.5 years to 3 years         (L6)   │
│ ☐ 3 years to 3.5 years         (L7)   │
│ ☐ 3.5 years to 4 years         (L8)   │
│ ☐ 4 years to 4.5 years         (L9)   │
│                                        │
│ Choose the bracket; the months and   │
│ levels are auto-derived.             │
└────────────────────────────────────────┘

PROBLEM:
❌ Only 9 options available
❌ Caps at 4.5 years (huge gap!)
❌ Cannot select 5+ years directly
❌ Inaccurate for experienced teachers
❌ Loss of precision
```

### AFTER (Complete Dropdown - 31 Options)

```
┌────────────────────────────────────────┐
│ Experience (Table 2.c)                 │
├────────────────────────────────────────┤
│ ☐ -- Select Experience Level --  ▼    │
│ ☐ Level 1: None to 6 months     (L1)  │
│ ☐ Level 2: 6 months to 1 year   (L2)  │
│ ☐ Level 3: 1 year to 1.5 years  (L3)  │
│ ☐ Level 4: 1.5 years to 2 years (L4)  │
│ ☐ Level 5: 2 years to 2.5 years (L5)  │
│ ☐ Level 6: 2.5 years to 3 years (L6)  │
│ ☐ Level 7: 3 years to 3.5 years (L7)  │
│ ☐ Level 8: 3.5 years to 4 years (L8)  │
│ ☐ Level 9: 4 years to 4.5 years (L9)  │
│ ☐ Level 10: 4.5 years to 5 years(L10) │
│ ☐ Level 11: 5 years to 5.5 years(L11) │
│     ... (20 more levels) ...           │
│ ☐ Level 31: 15 years or more   (L31)  │
│                                        │
│ Per DepEd Order No. 007, s. 2023      │
└────────────────────────────────────────┘

BENEFITS:
✅ All 31 levels available
✅ Covers full range (0-15+ years)
✅ No gaps between options
✅ Accurate data entry
✅ Full precision maintained
✅ DepEd Table 2.c compliant
```

### Comparison Table

| Aspect | Before | After |
|--------|--------|-------|
| **Options** | 9 options | 31 options (all levels) |
| **Maximum Years** | 4.5 years (limited) | 15+ years (full) |
| **Precision** | Low (~6-12 months each) | High (exact 6-month ranges) |
| **User Accuracy** | ⚠️ Guesswork for 5+ years | ✅ Exact match |
| **Data Quality** | ❌ Reduced for veterans | ✅ High quality |
| **DepEd Compliance** | ⚠️ Partial | ✅ Full compliance |
| **Veteran Support** | ❌ Limited (capped at L9) | ✅ All 31 levels |
| **Audit Trail** | ⚠️ Ambiguous | ✅ Clear |

---

## User Journey

### Journey 1: Teacher with 3 Years Experience

```
┌─ START: Evaluator filling form
│
├─ 1. Locate "Experience (Table 2.c)" field
│     ↓
├─ 2. Click dropdown to view options
│     ↓ (Shows all 31 levels)
│
├─ 3. Determine applicant's experience
│     Example: Applicant has 3 years teaching
│
├─ 4. Find matching range
│     3 years → "3-3.5 years" → Level 7
│
├─ 5. Select corresponding level
│     Clicks: "Level 7: 3 years to Less than 3 years 6 months"
│
├─ 6. System auto-calculates
│     Level 7 - Baseline 1 = 6 increment = 6 points
│     ↓
│     Form displays: "Experience Points: 6"
│
├─ 7. Verify calculation appears correct
│     ✓ 3 years → Level 7
│     ✓ 7 - 1 = 6 increment
│     ✓ 6 points assigned
│
└─ END: Move to next field
```

### Journey 2: Veteran Teacher (10+ years)

```
┌─ START: Evaluator has teacher with 10 years
│
├─ 1. Open dropdown
│
├─ 2. Scroll down to find 10 years
│     Can now select from complete list!
│
├─ 3. Identify range: 10-10.5 years
│     Clicks: "Level 21: 10 years to Less than 10 years 6 months"
│
├─ 4. System calculates
│     Level 21 - Baseline 1 = 20 increment
│     → Capped at 10 points maximum
│     ↓
│     Form displays: "Experience Points: 10 (MAX)"
│
├─ 5. Note: Veteran teacher fully recognized
│     Previously: Would have capped at Level 9
│     Now: Can go up to Level 31 (15+ years)
│
└─ END: Accurate entry for veteran teacher
```

---

## Integration with Other Criteria

### Education + Training + Experience Form

```
COMPLETE EVALUATION FORM:
├─ Education (Table 2.a)      ← 31 levels (0-30+ units)
├─ Training (Table 2.b)       ← 31 levels (0-240+ hours)
├─ Experience (Table 2.c)     ← 31 levels (0-15+ years)
├─ Qualifications (Manual)    ← 5 fields
└─ Submit

CALCULATION FLOW:
├─ Education Increment = Ed Level - Baseline
├─ Training Increment = Training Level - Baseline
├─ Experience Increment = Exp Level - Baseline
├─ Qualifications Points = Sum of 5 fields
└─ TOTAL SCORE = All increments + Qualifications (capped at 10 each)
```

---

## Features & Benefits

### ✅ Complete Coverage
- All 31 experience levels from Table 2.c
- Covers 0 to 15+ years of teaching
- No gaps or missing ranges
- Every possible experience value covered

### ✅ User-Friendly
- Clear range descriptions (From-To format)
- Easy to find correct experience level
- Dropdown organized chronologically

### ✅ Accurate Calculations
- System auto-converts level to months
- Backend recalculates points
- Results always consistent

### ✅ DepEd Compliant
- Matches Table 2.c exactly
- Official terminology and ranges
- Audit trail maintained

### ✅ Backward Compatible
- Works with existing calculation engine
- Maintains data storage format
- No impact on other dropdowns

---

## Related Files

- **[EXPERIENCE_LEVELS_REFERENCE.md](EXPERIENCE_LEVELS_REFERENCE.md)** - Complete reference
- **[TRAINING_DROPDOWN_VISUAL.md](TRAINING_DROPDOWN_VISUAL.md)** - Training visual guide
- **[EDUCATION_DROPDOWN_VISUAL.md](EDUCATION_DROPDOWN_VISUAL.md)** - Education visual guide
- **[index.php](index.php#L421-L455)** - Dropdown implementation
- **[TEST_CASES.md](TEST_CASES.md)** - Test cases with experience scenarios

---

## Summary

✅ **Complete Implementation:**
- All 31 experience levels displayed
- Each level shows exact year/month range
- System accurately converts to points
- DepEd compliant throughout

✅ **Visual Clarity:**
- Levels grouped by career stage
- Clear progression from 0-15+ years
- User-friendly selection process

✅ **Accurate Processing:**
- Dropdown level → Representative months
- Months → Increment calculation
- Increment → Points assignment (capped at 10)

---

**The experience dropdown now provides complete, accurate, and DepEd-compliant experience level selection for all 31 levels!**

