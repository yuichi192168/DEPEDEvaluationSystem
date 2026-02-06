# Live Preview Calculation Functions Applied to Database

## Overview

This script applies the **exact same calculation logic** used in the working live preview to all database records in `comparative_assessment_results`.

## What It Does

### 1. Extracts Live Preview Functions
All calculation functions are copied directly from `index.php` (the working live preview):

```php
// These functions match the JavaScript in index.php exactly
- calculateIncrement()              // appLevel - baselineLevel
- convertExperienceToLevel()        // months → level
- convertTrainingToLevel()          // hours → level  
- convertEducationToLevel()         // degree + units → level
- convertIncrementToPoints()        // increment + weight → points (CRITICAL)
- convertRatingToWeightedPoints()   // (rating/5) × weight
- calculateCriterionScore()         // universal scoring dispatcher
```

### 2. Key Calculation: convertIncrementToPoints()

This is the critical function that uses the official scoring rubric:

```php
function convertIncrementToPoints($increment, $weight) {
    // Map increment to base points (0-10 range)
    if ($increment >= 10) $basePoints = 10;
    else if ($increment >= 8) $basePoints = 8;
    else if ($increment >= 6) $basePoints = 6;
    else if ($increment >= 4) $basePoints = 4;
    else if ($increment >= 2) $basePoints = 2;
    else $basePoints = 0;
    
    // Scale by weight (max points for criterion)
    // Formula: basePoints × (weight / 10)
    if ($weight == 20) return $basePoints * 2;      // 10 base → 20 max
    else if ($weight == 15) return $basePoints * 1.5;  // 10 base → 15 max ← NON-TEACHING LEVEL II
    else if ($weight == 5) return $basePoints / 2;   // 10 base → 5 max
    else if ($weight == 25) return $basePoints * 2.5;  // 10 base → 25 max
}
```

### 3. Scoring by Criterion Type

- **Increment-based** (Education, Training, Experience):
  - Uses `convertIncrementToPoints()` with criterion weight
  - For Experience with weight 15: basePoints × 1.5
  
- **Weighted Rating** (Performance, Application of L&D, Potential):
  - Formula: `(rating / 5) × weight`
  
- **Direct Points** (Outstanding Accomplishments):
  - Formula: `min(appLevel, maxPoints)`
  
- **Direct Rating** (Application of Education):
  - **NO CALCULATION** - uses raw value directly
  - Example: Input 8.5 → Score 8.5 (no capping)

### 4. Database Updates

Updates all 8 criteria scores for each applicant:
1. education_score
2. training_score
3. experience_score (with correct weight 15)
4. performance_score
5. outstanding_accomplishments_score
6. application_of_education_score (raw value)
7. application_of_ld_score
8. potential_score
9. total_score (recalculated)

## How to Run

### Option 1: Windows Command Prompt
```cmd
cd C:\xampp\htdocs\DEPEDEvaluationSystemV2
sync_database_with_live_preview.bat
```

### Option 2: PowerShell
```powershell
cd C:\xampp\htdocs\DEPEDEvaluationSystemV2
php apply_live_preview_to_database.php
```

### Option 3: Web Browser
Navigate to:
```
http://localhost/DEPEDEvaluationSystemV2/apply_live_preview_to_database.php
```

## Expected Output

```
╔══════════════════════════════════════════════════════════════╗
║   Live Preview Calculation → Database Sync                  ║
║   Using exact functions from working live preview           ║
╚══════════════════════════════════════════════════════════════╝

📊 Found X records to process.
🔍 Using live preview calculation functions...

Processing [1] Sofia Rica Lapidario              (NT-AOII-2026-003)... ✅ UPDATED (16.50 → 14.50, -2.00)
```

## Verification

Compare results across systems:
- ✅ Live preview: 14.50
- ✅ IES report: 14.50 (after running recalculation)
- ✅ Annex I (CAR): 14.50
- ✅ Database: 14.50

All four should now show **exactly the same scores**.

## Critical Fixes Applied

1. **Experience Weight**: Now uses 15 for NON-TEACHING LEVEL II (was 20)
   - Example: increment 4 × weight 15 = 4 × 1.5 = **6.00** ✓

2. **Application of Education**: Uses raw value (no capping)
   - Example: Input 8.5 → Score **8.5** (not capped at 5) ✓

3. **Salary Grade Awareness**: Dynamic weights from evaluation criteria
   - SG 10-22, 27: Experience weight 15
   - SG 24: Experience weight 15
   - Different for other position groups

## Files Modified

- `apply_live_preview_to_database.php` - Main recalculation script
- `sync_database_with_live_preview.bat` - Windows batch runner
- `process_evaluation.php` - IES generation now uses dynamic weights
- `index.php` - Form fields added for salary_grade and category
- `comparative_assessment_results.php` - Already uses HRMPSBEvaluator for recalc

## After Running

1. All database scores will match live preview
2. Future IES generations will use correct weights (from our earlier fix)
3. All reports (IES, Annex I, CAR) will show consistent results
4. No manual SQL edits needed
