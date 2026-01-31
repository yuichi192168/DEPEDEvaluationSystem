# Form Validation - Quick Reference Guide

## What's New?

The evaluation form now has **smart validation** that helps you fill it out correctly and prevents errors.

---

## ✅ Required Fields (Must Be Filled)

1. **Name of Applicant** ✓
   - Enter the full name
   - Helper text: "Enter full name of applicant"

2. **Contact Number** ✓
   - Use Philippine format only
   - Helper text: "Use 09XXXXXXXXX or +639XXXXXXXXX format"
   - **Valid examples:**
     - `09123456789`
     - `+63912345678`
   - **Invalid examples:**
     - `9123456789` (missing 0 or 63)
     - `09XXXXXXX` (too short)

3. **Position Applied For** ✓
   - Auto-fills when you select a position
   - Helper text: "Auto-filled when you select a position"

4. **Schools Division Office** ✓
   - Name of your schools division
   - Helper text: "Name of your schools division"

5. **Job Group / Salary Grade** ✓
   - Auto-fills when you select a position
   - Helper text: "Auto-filled from selected position"

---

## 🟢 Green Checkmark - What Does It Mean?

When you see a **✓** next to a field, it means:
- ✅ The field has content
- ✅ The content is in the correct format
- ✅ That field is ready

**Example:** When you enter "09123456789" in Contact Number, a green ✓ appears.

---

## 🔴 Red Errors - What Went Wrong?

If a field shows red:
- ❌ The field is empty OR
- ❌ The content is not in the correct format

**Error messages tell you exactly what to fix:**
- "Applicant name is required"
- "Use format 09XXXXXXXXX or +639XXXXXXXXX"

---

## 📊 Progress Indicator

At the top of the form, you'll see:
```
Form Completion Status
[████████░░] 4 of 5 required fields completed
```

This shows:
- How many fields you've correctly filled
- The green bar fills as you complete fields
- You can submit when it shows "5 of 5"

---

## 🚀 Generating the Report

The **"Generate Evaluation Report"** button:
- 🔴 **RED (Disabled)** = Form incomplete
  - Can't click it yet
  - One or more fields have errors
  - Check for red highlighted fields

- 🟢 **GREEN (Enabled)** = Form complete
  - Click to generate your report
  - All fields are valid and complete

---

## 📝 How to Fill Out the Form

### Step 1: Enter Your Name
```
Applicant Name: Juan Dela Cruz
                ✓ (green checkmark appears)
```

### Step 2: Enter Contact Number
```
Contact Number: 09123456789
                ✓ (green checkmark appears)
```
**Tip:** Copy and paste from another device if you're unsure about format.

### Step 3: Select Position
```
Position: Select from dropdown
→ Position Applied For auto-fills
→ Job Group auto-fills
→ Two more green checkmarks appear
```

### Step 4: Enter Schools Division
```
Schools Division: City Schools Division of Cabuyao
                  ✓ (green checkmark appears)
```

### Step 5: Generate Report
```
Once all fields show ✓ and progress shows "5 of 5":
Click "Generate Evaluation Report" button
```

---

## ⚠️ Common Issues & Solutions

### Issue: "Contact Number field shows red"
**Solution:** Use one of these formats:
- `09XXXXXXXXX` (example: 09123456789)
- `+639XXXXXXXXX` (example: +63912345678)

### Issue: "Generate button is still disabled"
**Solution:** Check the progress indicator:
- Look for any fields without a ✓
- Fix any red highlighted fields
- Wait for all to show ✓

### Issue: "Position not showing in dropdown"
**Solution:**
1. Select a Position Group first
2. Wait for positions to load
3. Then select the specific position

### Issue: "Got error message on submit"
**Solution:**
- Check for red highlighted fields
- Fix each error
- All fields must show ✓

---

## 💡 Tips & Tricks

1. **Use helper text:** Read the gray text below each field for guidance
2. **Watch the progress bar:** Motivates you to complete the form
3. **Submit when ready:** Button only enables when form is valid
4. **Use copy-paste:** For contact number, copy from verified source
5. **Check once:** Don't submit twice - the system prevents duplicates

---

## ✨ What Validation Does

1. **Checks you filled everything:** No empty required fields
2. **Validates contact number format:** Only Philippine formats accepted
3. **Prevents invalid submission:** Can't proceed with errors
4. **Guides you to errors:** Highlights what needs fixing
5. **Auto-fills where possible:** Position info auto-populates

---

## 🎯 Summary

- **Green ✓** = Field is correct
- **Red** = Field has an error (read error message)
- **Progress bar** = Shows how many fields you've completed
- **Generate button** = Activates when all fields valid
- **Helper text** = Tells you what format is expected

**Fill out all 5 fields → Get 5 green checkmarks → Click Generate → Done! ✅**

---

## 📞 Need Help?

If you're stuck:
1. Read the error message in red below the field
2. Read the helper text in gray below the field
3. Check the Contact Number format requirements
4. Make sure Position is selected (it auto-fills other fields)

For more details, see the Form Validation Implementation guide.
