# Database Score Fix Guide - Multiple Solutions

## Problems

The database contains scores calculated with old formulas that no longer match current calculations:

### 1. Application of Education
- **Old:** `(rating/5) × 10` → Example: rating 4 becomes score 8.00
- **New:** `rating` directly → Example: rating 4 becomes score 4.00
- **Impact:** ~4 points inflation per record

### 2. Experience (NON-TEACHING LEVEL II)
- **Old:** Baseline=24 months, Weight=possibly 20
- **New:** Baseline=0 months, Weight=15
- **Example:** 93 months was 16.00, now should be 15.00
- **Impact:** 1-5 points inflation per record

**Result:** Reports show incorrect totals that don't match live preview.

## Solutions Available

Choose based on which scores need fixing:

---

## Fix Application of Education Only

### Option 1A: PHP Web Script (Most User-Friendly)
**File:** `fix_application_of_education_scores.php`

**How to use:**
1. Open in browser: `http://localhost/DEPEDEvaluationSystemV2/fix_application_of_education_scores.php`
2. View the visual report of all changes
3. Records are automatically updated

**Advantages:**
- Visual interface
- Shows before/after comparison
- Easy to understand
- No SQL knowledge needed

---

### Option 1B: SQL Script (Most Accurate)
**File:** `fix_application_of_education_scores.sql`

**How to use:**
1. Open phpMyAdmin
2. Select your database (e.g., `deped_evaluation`)
3. Click "SQL" tab
4. Copy and paste the entire script
5. Click "Go"

**What it does:**
- Shows current state
- Creates automatic backup table
- Updates scores using evaluation data
- Shows updated state
- Provides comparison summary

**Advantages:**
- Most accurate (uses original rating from evaluations table)
- Creates automatic backup
- Shows detailed before/after comparison

---

### Option 1C: Alternative SQL Script (Fallback)
**File:** `fix_application_of_education_scores_alternative.sql`

**When to use:**
- If Option 2 fails
- If evaluations table doesn't have complete data
- If you only have CAR table data

**How it works:**
- Reverse-engineers rating from old score
- Applies new calculation
- Simpler but less precise

---

## Fix Experience Only

### Option 2: Experience SQL Script
**File:** `fix_experience_scores.sql`

**How to use:**
1. Open phpMyAdmin
2. Select your database
3. Click "SQL" tab
4. Copy and paste the entire script
5. Click "Go"

**What it does:**
- Recalculates experience scores using new baseline (0 months) and weight (15)
- Creates backup: `comparative_assessment_results_backup_exp_fix`
- Updates `experience_score` and `total_score`

**For:** NON-TEACHING LEVEL II positions

---
any fix, verify results:

1. **Check CAR Report:**
   ```
   http://localhost/DEPEDEvaluationSystemV2/comparative_assessment_results.php?view=all
   ```

2. **Expected Results:**
   - Application of Education: 0-5 range (not 0-10)
   - Experience: Recalculated (typically 0-15 for NON-TEACHING LEVEL II)
   - Total scores: Lower than before

3. **Compare with Live Preview:**
   - Fill out evaluation form
   - Live Preview total should NOW match generated report total!

4. **Query Database:**
   ```sql
Each fix creates a backup table. To undo:

**Backup Tables:**
- Option 1A (Web): `comparative_assessment_results_backup_[timestamp]`
- Option 1B (AOE SQL): `comparative_assessment_results_backup_pre_fix`
- Option 1C (AOE Alternative): `car_backup_before_aoe_fix`
- Option 2 (Experience): `comparative_assessment_results_backup_exp_fix`
- Option 3 (Combined): `comparative_assessment_results_backup_combined_fix`

**Rollback Combined Fix:**
```sql
UPDATE comparative_assessment_results car
INNER JOIN comparative_assessment_results_backup_combined_fix backup ON car.id = backup.id
SET car.application_of_education_score = backup.application_of_education_score,
   Important Notes

### Baseline Auto-Load Issue

If live preview still shows wrong scores, the form is loading old baseline values:

**Symptoms:**
- Baseline Experience shows 24 or 42 (should be 0)
- Baseline Training shows 16 (should be 0 for Admin Officer II)

**Solution:**
1. Hard refresh browser: `Ctrl + Shift + R`
2. Don't load old drafts
3. Verify baseline fields show: Training=0, Experience=0

### Position-Specific Weights

Experience fix uses weight=15 (NON-TEACHING LEVEL II). NON-TEACHING LEVEL I uses weight=20.

---

## Files Included

- `fix_application_of_education_scores.php` - Web GUI fix
- `fix_application_of_education_scores.sql` - AOE primary SQL
- `fix_application_of_education_scores_alternative.sql` - AOE fallback SQL
- `fix_experience_scores.sql` - Experience SQL fix
- `fix_combined_scores.sql` - Combined fix (AOE + Experience)
- `EXPERIENCE_ISSUE_ANALYSIS.md` - Detailed analysis
- `README_FIX_SCORES.md` - This guidlace table name as needed)
```sql
-- For Application of Education only
UPDATE comparative_assessment_results car
INNER JOIN comparative_assessment_results_backup_pre_fix backup ON car.id = backup.id
SET car.application_of_education_score = backup.application_of_education_score,
    car.total_score = backup.total_score,
    car.updated_at = backup.updated_at;

-- For Experience only
UPDATE comparative_assessment_results car
INNER JOIN comparative_assessment_results_backup_exp_fix backup ON car.id = backup.id
SET car.experience_score = backup.experience_score,
    car.total_score = backup.total_score,
    car.updated_at = backup.updated_at
- Single backup to manage

**This is the recommended solution for most users!**

---

## Quick Decisionissues:
1. Ensure Apache and MySQL are running
2. Verify database connection
3. Check phpMyAdmin error messages
4. Review backup tables before proceeding
5. See `EXPERIENCE_ISSUE_ANALYSIS.md` for detailed breakdown

---

**Last Updated:** February 6, 2026n 1A or 1B |
| Only Experience wrong | Option 2 |
| **Both wrong (MOST COMMON)** | **Option 3** ✅ |
| Not sure | **Option 3** (fixes both) |
| Prefer GUI | Option 1A for AOE only |
| Comfortable with SQL | **Option 3** ✅ |

## Verification

After running either script, check your results:

1. **Go to CAR Report:**
   ```
   http://localhost/DEPEDEvaluationSystemV2/comparative_assessment_results.php?view=all
   ```

2. **Expected Results:**
   - Application of Education score should match the rating (max 5)
   - Total scores should be lower than before
   - Example: Rating 4 → Score 4.00 (not 8.00)

3. **Compare with Live Preview:**
   - Fill out evaluation form
   - Live Preview total should match CAR report total

## Rollback (If Needed)

If you need to undo the changes:

**From PHP script backup:**
- The backup table is: `comparative_assessment_results_backup_pre_fix`

**Rollback SQL:**
```sql
UPDATE comparative_assessment_results car
INNER JOIN comparative_assessment_results_backup_pre_fix backup ON car.id = backup.id
SET car.application_of_education_score = backup.application_of_education_score,
    car.total_score = backup.total_score,
    car.updated_at = NOW();
```

## Files Created

- `fix_application_of_education_scores.php` - Web-based fix script
- `fix_application_of_education_scores.sql` - Primary SQL script
- `fix_application_of_education_scores_alternative.sql` - Fallback SQL script
- `README_FIX_SCORES.md` - This file

## Support

If you encounter any issues:
1. Check that you have backup
2. Verify database connection is working
3. Check phpMyAdmin for any error messages
4. Review the backup table before dropping it
