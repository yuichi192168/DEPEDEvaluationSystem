# Quick Start - Testing Admin Dashboard Fixes

## How to Test the Fixes

### 1. Test View Details (IES Display)

**Steps:**
1. Navigate to `http://localhost/DEPEDEvaluationSystemV2/admin/login.php`
2. Login with: **admin** / **admin123**
3. Click "Applicants Management" from dashboard
4. Find any applicant in the table
5. Click the **"View"** button (eye icon)

**Expected Result:**
- ✅ Wide modal appears (900px)
- ✅ Shows applicant name with icon
- ✅ Displays position group and status badges
- ✅ If evaluated: Shows complete IES table with:
  - All evaluation criteria
  - Applicant qualification vs baseline
  - Levels, increments, weights, and points
  - Total score prominently displayed
  - Evaluator name and date
- ✅ If not evaluated: Shows qualifications summary with note
- ✅ Archive history at bottom (if applicable)
- ✅ Professional formatting with icons and colors

**What You'll See:**
```
┌────────────────────────────────────────────────────┐
│  ✕                  Applicant Details              │
├────────────────────────────────────────────────────┤
│                                                    │
│  👤 John Doe                                       │
│                                                    │
│  ┌────────────┐  ┌────────────┐                   │
│  │ Group A    │  │ Active     │                   │
│  └────────────┘  └────────────┘                   │
│                                                    │
│  📋 Individual Evaluation Sheet (IES)             │
│  ┌──────────────────────────────────────────────┐ │
│  │ Criterion     │ Applicant │ Baseline │ Score │ │
│  ├──────────────────────────────────────────────┤ │
│  │ Education     │ Master's  │ Bachelor │ 20.00 │ │
│  │ Training      │ 120 hrs   │ 0 hrs    │ 15.50 │ │
│  │ ...           │ ...       │ ...      │ ...   │ │
│  └──────────────────────────────────────────────┘ │
│                                                    │
│  ┌────────────────────────────────────────────┐   │
│  │        TOTAL EVALUATION SCORE              │   │
│  │              87.50 / 100                   │   │
│  └────────────────────────────────────────────┘   │
│                                                    │
└────────────────────────────────────────────────────┘
```

---

### 2. Test Dropdown Readability

**Steps:**
1. On the Applicants Management page
2. Look at the "All Groups" dropdown in the search bar
3. Click on the dropdown to expand options

**Expected Result:**
- ✅ Dropdown text is dark (#333) on white background
- ✅ High contrast, easy to read
- ✅ Options are clearly visible when expanded
- ✅ Blue border appears on focus (#E04040)
- ✅ Font weight is medium (500) for better readability

**What You'll See:**
```
Search: [________________]  [All Groups ▼]  [🔍 Search]
                             ├─────────────┐
                             │ All Groups  │  ← Dark text, white bg
                             │ Group A     │
                             │ Group B     │
                             │ Group C     │
                             └─────────────┘
```

---

### 3. Verify CSS Override

**Steps:**
1. Inspect the page layout
2. Check that flexbox and grid layouts work correctly
3. Verify no layout disruptions

**Expected Result:**
- ✅ Statistics cards display in grid (3-4 columns on desktop)
- ✅ Search bar and buttons align properly
- ✅ Table displays without layout issues
- ✅ Modal content flows naturally
- ✅ No unexpected display: block overrides

---

### 4. Test Different Scenarios

#### Scenario A: Applicant with Full Evaluation
**Test Applicant:** Any applicant that has been evaluated
**Expected:** Full IES table with all criteria and scores

#### Scenario B: Applicant without Evaluation
**Test Applicant:** Newly added applicant without evaluation
**Expected:** Shows qualifications summary + yellow alert saying "No Evaluation"

#### Scenario C: Archived Applicant
**Steps:**
1. Switch to "Archived Applicants" tab
2. Click View on any archived applicant
**Expected:** Shows archive history with dates, reasons, and who archived

---

## Browser Console Testing

### Check API Response:

1. Open browser DevTools (F12)
2. Go to Network tab
3. Click "View" button on any applicant
4. Find the request to `get_applicant_evaluation.php`
5. Check the response

**Expected Response:**
```json
{
  "success": true,
  "applicant": { ... },
  "evaluation": { ... },
  "details": [ ... ],
  "qualifications": { ... },
  "history": [ ... ]
}
```

---

## Color Contrast Testing

### Verify Readability:

**Dropdown:**
- Text: #333 on #ffffff
- Ratio: 12.63:1 ✅ (Exceeds WCAG AAA)

**Badges:**
- Group A: #155724 on #d4edda ✅
- Group B: #0c5460 on #d1ecf1 ✅
- Group C: #856404 on #fff3cd ✅

**IES Table:**
- Headers: #333 on #f8f9fa ✅
- Scores: #E04040 on #ffffff ✅

---

## Common Issues & Solutions

### Issue: Modal doesn't show IES data

**Check:**
1. Does applicant have evaluation? (Check `evaluations` table)
2. Does evaluation have details? (Check `evaluation_details` table)
3. Check browser console for API errors
4. Verify API file exists: `api/get_applicant_evaluation.php`

**Solution:**
- If no evaluation: Expected behavior (shows qualifications instead)
- If API error: Check database connection
- If 403 error: Verify admin login

---

### Issue: Dropdown still hard to read

**Check:**
1. Browser cache cleared?
2. CSS loaded from applicants.php?
3. Other global styles overriding?

**Solution:**
- Hard refresh (Ctrl+F5)
- Inspect element to verify styles applied
- Check for conflicting stylesheets

---

### Issue: Layout looks wrong

**Check:**
1. Browser zoom at 100%?
2. Screen size/resolution?
3. Browser compatibility?

**Solution:**
- Reset zoom to 100%
- Test on standard resolution (1920x1080)
- Use modern browser (Chrome/Firefox/Edge latest)

---

## Performance Testing

### Speed Benchmarks:

**API Response Time:**
- Target: < 200ms
- Acceptable: < 500ms
- Slow: > 1000ms (check database indexes)

**Modal Display:**
- Should appear instantly on click
- Content should load within 300ms
- No lag or freezing

---

## Database Verification

### Check if evaluation data exists:

```sql
-- Count applicants with evaluations
SELECT COUNT(*) FROM evaluations;

-- Get sample evaluation with details
SELECT e.*, ed.criterion, ed.final_score
FROM evaluations e
LEFT JOIN evaluation_details ed ON e.id = ed.evaluation_id
WHERE e.id = 1;

-- Check applicant with complete data
SELECT a.name, e.total_score, COUNT(ed.id) as criteria_count
FROM applicants a
LEFT JOIN evaluations e ON a.id = e.applicant_id
LEFT JOIN evaluation_details ed ON e.id = ed.evaluation_id
WHERE a.id = 1
GROUP BY a.id, e.id;
```

---

## Screenshots to Verify

### 1. Dropdown Before/After
**Before:** Hard to read, low contrast
**After:** ✅ Dark text, white background, clear focus state

### 2. Modal Before/After
**Before:** Narrow (500px), basic info only
**After:** ✅ Wide (900px), full IES table, professional layout

### 3. IES Table Display
**After:** ✅ Complete criteria table, scores, totals, color coding

---

## Mobile Testing

### Responsive Checks:

1. Open in mobile view (F12 → Toggle Device Toolbar)
2. Test at 375px width (iPhone SE)
3. Test at 768px width (iPad)

**Expected:**
- ✅ Modal adjusts to screen width
- ✅ IES table scrolls horizontally if needed
- ✅ Info grid stacks to single column
- ✅ Buttons remain accessible
- ✅ Text remains readable

---

## Success Criteria

### All Tests Pass When:

- ✅ View Details shows complete IES with all evaluation criteria
- ✅ Dropdown text is clearly readable with high contrast
- ✅ Modal width is 900px and displays content properly
- ✅ No layout disruptions from global CSS
- ✅ Loading spinner appears during data fetch
- ✅ Error messages display gracefully
- ✅ Archive history shows when applicable
- ✅ Color coding is consistent with badges
- ✅ Icons display correctly
- ✅ Responsive on mobile devices
- ✅ API responds within 300ms
- ✅ No console errors

---

## Next Steps After Testing

### If Issues Found:
1. Document the issue with screenshot
2. Check browser console for errors
3. Verify database has evaluation data
4. Review ADMIN_DASHBOARD_FIXES_COMPLETE.md for troubleshooting

### If All Tests Pass:
1. ✅ Fixes are working correctly
2. ✅ System is production-ready
3. ✅ Train admin users on new IES display
4. ✅ Monitor performance in production

---

**Testing Guide Version:** 1.0
**Last Updated:** February 4, 2026
