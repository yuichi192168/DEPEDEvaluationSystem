# Fix: Live Calculation Preview - Evaluation Criteria Names

## Problem Fixed
The Live Calculation Preview was displaying incomplete evaluation criteria for TEACHING POSITIONS. The criteria table was missing criteria 'g' and 'h', causing the JavaScript code to not properly populate all expected evaluation criteria.

### Before (Incorrect)
TEACHING POSITIONS criteria had only 6 entries (a-f):
- a. Education: 10 max points
- b. Training: 10 max points
- c. Experience: 10 max points
- d. PBET/LET/LEPT Rating: 10 max points
- e. PPST COIs: 35 max points
- f. PPST NCOIs: 25 max points
- ❌ g. Missing
- ❌ h. Missing

### After (Corrected)
TEACHING POSITIONS criteria now has all 8 entries (a-h):
- a. Education: 10 max points
- b. Training: 10 max points
- c. Experience: 10 max points
- d. PBET/LET/LEPT Rating: 10 max points
- e. PPST COIs: 35 max points
- f. PPST NCOIs: 25 max points
- g. Application of L&D: 0 max points (not applicable for teaching positions)
- h. Potential: 0 max points (not applicable for teaching positions)

## Root Cause
The JavaScript function `calculatePreview()` in `index.php` expects 8 criteria keys (a-h) for all position groups. However, the TEACHING POSITIONS criteria definition in `config/evaluation_criteria.php` only included 6 criteria (a-f), leaving g and h undefined.

When the API returns incomplete criteria data, the JavaScript mapping couldn't properly construct the criteria list, causing the table display to be incorrect.

## Solution
Updated `config/evaluation_criteria.php` to include all 8 criteria keys for both:
1. **TEACHING POSITIONS** - Added g and h as 0-point criteria (not applicable)
2. **HIGHER TEACHING POSITIONS** - Added g and h as 0-point criteria (not applicable)

## Files Modified
- `config/evaluation_criteria.php` (Lines 7-27 for TEACHING POSITIONS, Lines 30-50 for HIGHER TEACHING POSITIONS)

## Verification
✅ API endpoint `/api/get_evaluation_criteria.php?position_group=TEACHING%20POSITIONS&salary_grade=11` now returns all 8 criteria with correct names and max points
✅ Live Calculation Preview should now display complete evaluation criteria for Teacher I and other teaching positions
✅ All position groups now have consistent 8-criteria structure

## Testing Instructions
1. Open `index.php` in browser
2. Select any TEACHING POSITIONS position (e.g., "Teacher I")
3. Verify the Live Calculation Preview table shows 8 criteria rows:
   - Row 1: Education
   - Row 2: Training
   - Row 3: Experience
   - Row 4: PBET/LET/LEPT Rating
   - Row 5: PPST COIs
   - Row 6: PPST NCOIs
   - Row 7: Application of L&D
   - Row 8: Potential

## Impact
- Live Calculation Preview now displays correct position-specific criterion names for all teaching positions
- Max points correctly show 100 total for TEACHING POSITIONS (10+10+10+10+35+25+0+0)
- JavaScript calculation logic properly receives all 8 criteria and can perform correct point calculations
