# IES Display - Quick Start Guide

## ⚡ Quick Diagnosis (15 minutes max)

### Step 1: Check Database (2 min)
Open in browser: **`http://localhost/xampp/htdocs/DEPEDEvaluationSystemV2/check_evaluations.php`**

Look for this line:
```
Active Applicants WITH Evaluations: X
```

- **If X = 0**: No evaluation data! Create evaluations first, then continue.
- **If X > 0**: Great! Continue to Step 2.

---

### Step 2: Test API (3 min)
Open in browser: **`http://localhost/xampp/htdocs/DEPEDEvaluationSystemV2/test_api_endpoint.php`**

1. Select an applicant from the dropdown (with evaluation)
2. Click "Test API"
3. Look for:
   - ✅ Status Code: **200**
   - ✅ Success: **YES**
   - ✅ Has Evaluation: **YES**
   - ✅ Evaluation Details Count: **6+**

- **If all green**: API works! Continue to Step 3.
- **If any red**: API broken! Note the error and stop.

---

### Step 3: Test View Button (10 min)

1. Go to: **Admin Dashboard → Applicants** (Active tab)
2. Press **F12** on keyboard (opens browser developer tools)
3. Click **Console** tab at the top
4. Click **View** button on any applicant
5. **Watch the console**

#### Expected Console Output:
```
=== START viewDetails Function ===
Button clicked for applicant ID: 1
Step 1: Getting modal body element
...
Step 18: Verifying HTML was inserted
=== viewDetails Function SUCCESS ===
```

#### If you see this: ✅ SUCCESS!
The IES should display in the modal. If not, there's a CSS issue. Try:
- Refresh page (Ctrl+F5)
- Check if modal has dark background (overlay)
- Check if you need to scroll in modal

#### If you see an ❌ RED ERROR:
Write down the exact error message and what step number it shows, then:
1. Share error with developer
2. Developer will add more logging at that step
3. Run again to get more details

---

## 🔧 Common Issues & Quick Fixes

| Problem | Solution |
|---------|----------|
| **Check Evaluations shows 0** | Create evaluations through the system first |
| **API Test shows error** | Check if admin is logged in, restart browser |
| **Console shows red error** | Note the error, share with developer |
| **Console shows nothing** | Refresh page, try again, check if JavaScript enabled |
| **Modal opens but blank** | Check browser console for errors (Step 3) |
| **Modal shows "Loading..." forever** | API not responding - check test_api_endpoint.php |

---

## 📋 Information to Share If Issue Persists

1. **From check_evaluations.php**:
   - "Active Applicants WITH Evaluations: X"

2. **From test_api_endpoint.php**:
   - Status Code
   - Success: YES/NO
   - Any error messages

3. **From Browser Console** (F12):
   - Screenshot or copy-paste of any red errors
   - Last step number where it stopped
   - Full text of error message if any

---

## 🎯 Success Checklist

- [ ] check_evaluations.php shows "Active Applicants WITH Evaluations: > 0"
- [ ] test_api_endpoint.php shows "Success: YES" and "Has Evaluation: YES"
- [ ] Browser console shows "=== viewDetails Function SUCCESS ===" (no red errors)
- [ ] Modal opens and shows evaluation data
- [ ] Close button (×) works to close modal

---

## 📞 If You're Stuck

**Don't guess! Provide specific information:**

1. Run the diagnostic tools (check_evaluations.php and test_api_endpoint.php)
2. Open browser console (F12) and click View button
3. Copy the console output (or take screenshot)
4. Share:
   - What you saw in check_evaluations.php
   - What you saw in test_api_endpoint.php
   - The console output from Step 3
   - The exact error message (if any)

This will help identify the exact issue!

---

**Created for**: Phase 10 - IES Display Troubleshooting
