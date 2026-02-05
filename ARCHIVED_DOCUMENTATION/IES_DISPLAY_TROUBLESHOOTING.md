# IES Display Issue - Troubleshooting Guide

**Issue:** Individual Evaluation Sheet (IES) is not displaying for active applicants, only for archived applicants.

## Quick Diagnostics

### Step 1: Run Diagnostic Script
Access: `http://localhost/DEPEDEvaluationSystemV2/diagnostic_ies_status.php`

This will show:
- Count of active applicants with evaluations
- Count of archived applicants with evaluations
- Sample data for both types

### Step 2: Run API Test
Access: `http://localhost/DEPEDEvaluationSystemV2/test_ies_api.php`

This will allow you to:
- Test the API with specific applicant IDs
- View raw JSON responses
- Check browser console for detailed logs

## Recent Changes Made

The following changes were made to support archived applicant filtering in CAR:

### Modified Files:
1. **classes/ComparativeAssessmentReport.php** - Added `archive_status = 'active'` filters
2. **classes/EvaluationStorage.php** - Added `archive_status = 'active'` filters  
3. **view_car.php** - Added `archive_status` filter to position query

### Important: API Not Modified
The `api/get_applicant_evaluation.php` file was NOT modified and does NOT have archive_status filters. This endpoint should work for ALL applicants (active and archived).

## Likely Root Cause

If IES is showing for archived but NOT for active applicants:

### Scenario A: Active Applicants Don't Have Evaluations
**Symptoms:**
- Diagnostic script shows "0" active applicants with evaluations
- Test API returns `evaluation: null` for active applicants
- Test API returns `evaluation: {...}` for archived applicants

**Solution:**
This means you need to create evaluations for the active applicants. The system is working correctly - there's just no IES data to display.

### Scenario B: Data Exists But Not Displaying
**Symptoms:**
- Diagnostic script shows active applicants WITH evaluations
- Test API returns evaluation data for both active and archived
- But modal still shows "No Data Available" for active applicants

**Possible Causes:**
1. Modal not opening properly (fixed - moved openModal before fetch)
2. JavaScript console error preventing rendering
3. Evaluation data format mismatch

**Solution:**
1. Check browser console (F12) for errors
2. Look for "Fetching applicant details" log message
3. Look for "API Response Data:" log message in console

### Scenario C: Archive Operation Removed Data
**Symptoms:**
- Applicants show different data after being archived then restored

**Possible Causes:**
- Archive/restore process shouldn't affect evaluation data
- Check `archived_applicants_audit` table for logs

**Solution:**
Contact support if data was lost during archiving

## Manual Testing Steps

1. **Open Admin Dashboard**
   - Go to `/admin/applicants.php`
   - Make sure you're on the "Active Applicants" tab

2. **Locate an Active Applicant**
   - Look for any applicant in the table

3. **Click View Button**
   - Modal should appear with loading spinner
   - Wait for data to load

4. **Check Browser Console** (Press F12)
   - Look for "Fetching applicant details for ID: X" message
   - Look for "API Response Data:" and examine the JSON
   - Check for any error messages in red

5. **Repeat for Archived Applicant**
   - Switch to "Archived Applicants" tab
   - Click View on an archived applicant
   - Compare the API responses

## Code Changes Summary

### admin/applicants.php - viewDetails Function
**Changes:**
1. Added `console.log` statements for debugging
2. Moved `openModal()` call to execute immediately
3. Modal now opens while data is loading (shows spinner)

**Before:**
```javascript
openModal called AFTER fetch completed
```

**After:**
```javascript
openModal called IMMEDIATELY with loading spinner
Fetch runs in background and updates modal content
```

This ensures the modal opens promptly even if the API is slow.

## Database Structure Check

### Verify These Relationships Exist:
```sql
-- Check evaluations for active applicants
SELECT COUNT(*) FROM evaluations e
WHERE e.applicant_id IN (
  SELECT id FROM applicants WHERE archive_status = 'active'
);

-- Check evaluations for archived applicants
SELECT COUNT(*) FROM evaluations e
WHERE e.applicant_id IN (
  SELECT id FROM applicants WHERE archive_status = 'archived'
);

-- Check evaluation_details exist
SELECT COUNT(*) FROM evaluation_details;

-- Check qualifications exist
SELECT COUNT(*) FROM applicant_qualifications;
```

## Next Steps

1. **Run the diagnostic script** to identify which scenario applies
2. **Check browser console** for JavaScript errors
3. **Verify evaluation data exists** in the database
4. **Test with specific applicant IDs** using the test API script

## Files Created for Debugging

- `/test_ies_api.php` - Interactive API tester
- `/diagnostic_ies_status.php` - Database diagnostics
- `/ARCHIVED_APPLICANTS_FILTER_FIX.md` - Archive filter documentation

## Technical Notes

### API Endpoint: get_applicant_evaluation.php
- **Access:** `/api/get_applicant_evaluation.php?id=<applicant_id>`
- **Auth:** Requires admin login
- **Response Fields:**
  - `success` - Boolean
  - `applicant` - Full applicant record
  - `evaluation` - Latest evaluation (or null)
  - `details` - Evaluation details array (or empty)
  - `qualifications` - Applicant qualifications (or null)
  - `history` - Archive history array (or empty)

### Filter Consistency
Archive status filters were added to:
- ComparativeAssessmentReport.getResultsByPosition()
- ComparativeAssessmentReport.getPositionsWithResults()
- ComparativeAssessmentReport.getAllResults()
- ComparativeAssessmentReport.getResultById()
- EvaluationStorage.getComparativeAssessmentResults()
- EvaluationStorage.getEvaluationsByPosition()
- view_car.php position query

These filters should NOT affect the get_applicant_evaluation.php API since that file was not modified.

## Support

If the issue persists after running diagnostics:
1. Share the diagnostic output
2. Share the Test API response (JSON)
3. Share browser console errors (F12 → Console tab)
4. Specify applicant IDs that show/don't show IES

---

**Last Updated:** February 4, 2026
**Status:** Troubleshooting Guide Created
