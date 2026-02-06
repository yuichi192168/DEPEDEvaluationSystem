# Experience Calculation Issue Analysis

## Problem Report
- **Live Preview**: 12.00 points
- **Generated Report (Database)**: 16.00 points
- **Difference**: 4.00 points

## Root Cause Analysis

### Experience Calculation Formula
For Administrative Officer II (NON-TEACHING LEVEL II, SG 11):
- Uses criteria: `sg_10_22_27`
- Experience max_points: **15** (not 20)
- Scoring method: **increment-based**

### Calculation Breakdown

**For applicant with 93 months experience:**
- Applicant Level = floor(93/6) + 1 = **16**

**Scenario 1: Database (shows 16.00)**
- OLD baseline_experience was likely: **42 months** (when weight was 20)
- Baseline Level = floor(42/6) + 1 = **8**
- Increment = 16 - 8 = **8**
- For weight=20: basePoints = 8 → 8 × 2 = **16.00** ✓

**Scenario 2: Live Preview (shows 12.00)**
- CURRENT baseline_experience in form: **42 months** (still old value!)
- Baseline Level = **8**
- Increment = 16 - 8 = **8**
- For weight=15: basePoints = 8 → 8 × 1.5 = **12.00** ✓

**Scenario 3: EXPECTED with new baseline (should show 15.00)**
- NEW baseline_experience from baseline_library.php: **0 months**
- Baseline Level = 1 (since 0 < 6)
- Increment = 16 - 1 = **15**
- For weight=15: basePoints = 10 → 10 × 1.5 = **15.00**

## The Real Issues

### Issue #1: Baseline Not Loading from Updated Library
The baseline auto-load is NOT using the updated `baseline_library.php` values.

**Evidence:**
- baseline_library.php shows: `'admin_officer_ii' => ['experience' => 0]`
- Form is loading: `baseline_experience = 42` (old value)
- This suggests browser caching or draft data restoration

**Possible Causes:**
1. **Browser cache** - Page needs hard refresh (Ctrl+Shift+R or Ctrl+F5)
2. **Draft loaded** - User loaded an old draft that has old baseline values
3. **Session data** - Old session data still in memory
4. **Cached JavaScript** - Browser cached the old positions object

### Issue #2: Database Has Old Scores
Database contains records calculated with:
- Old experience weight: 20
- Old baseline_experience: 42 months

These need to be recalculated with:
- New experience weight: 15
- New baseline_experience: 0 months

## Solutions

### Solution 1: Fix Form Baseline Loading (IMMEDIATE)
**For the User:**
1. **Clear browser cache** and do a hard refresh:
   - Chrome/Edge: Ctrl+Shift+R or Ctrl+F5
   - Firefox: Ctrl+Shift+R

2. **Don't load old drafts** - Start fresh evaluation

3. **Verify baseline fields** before submitting:
   - Training should show: 0
   - Experience should show: 0
   - NOT 16 and 24

### Solution 2: Fix Database Records (COMPLETE UPDATE)
Similar to the Application of Education fix, we need SQL scripts to recalculate experience scores.

**Calculation Update:**
```sql
-- Old: weight=20, baseline=42 months (level 8)
-- New: weight=15, baseline=0 months (level 1)

UPDATE comparative_assessment_results car
INNER JOIN evaluations e ON car.application_code = e.application_code
SET
    -- Calculate new experience score
    car.experience_score = CASE
        WHEN (FLOOR(COALESCE(e.applicant_experience, 0) / 6) + 1) - 1 >= 10 THEN 10 * 1.5
        WHEN (FLOOR(COALESCE(e.applicant_experience, 0) / 6) + 1) - 1 >= 8 THEN 8 * 1.5
        WHEN (FLOOR(COALESCE(e.applicant_experience, 0) / 6) + 1) - 1 >= 6 THEN 6 * 1.5
        WHEN (FLOOR(COALESCE(e.applicant_experience, 0) / 6) + 1) - 1 >= 4 THEN 4 * 1.5
        WHEN (FLOOR(COALESCE(e.applicant_experience, 0) / 6) + 1) - 1 >= 2 THEN 2 * 1.5
        ELSE 0
    END,
    -- Update total score
    car.total_score = car.total_score - car.experience_score + [new_score],
    car.updated_at = NOW()
WHERE car.experience_score IS NOT NULL;
```

### Solution 3: Combined Database Fix
Update BOTH Application of Education AND Experience in one script.

## Expected Score Changes

**For applicant with 93 months experience:**

| Scenario | Baseline Experience | Weight | Calculation | Old Score | New Score | Difference |
|----------|---------------------|--------|-------------|-----------|-----------|------------|
| OLD (DB) | 42 months | 20 | (16-8=8) × 2 | 16.00 | - | - |
| NEW (Expected) | 0 months | 15 | (16-1=15) → 10 × 1.5 | - | 15.00 | - |
| **Impact** | | | | **16.00** | **15.00** | **-1.00** |

**Combined with Application of Education fix:**
- Application of Education: -4.00 points
- Experience: -1.00 point
- **Total reduction: -5.00 points**
- Old total: 31.98
- New total: **26.98**

## Next Steps

1. **IMMEDIATE**: User should hard refresh browser and verify baseline shows 0 for both training and experience
2. **SHORT TERM**: Create SQL script to fix experience scores in database
3. **MEDIUM TERM**: Create combined fix for both Application of Education AND Experience
4. **VERIFY**: After fixes, confirm reports match live preview
