# Form Validation - Visual Guide

## What You'll See on the Form

### Initial State (Page Loads)
```
┌─────────────────────────────────────────────────────┐
│  DepEd HRMPSB Evaluation System                      │
└─────────────────────────────────────────────────────┘

┌─ Form Completion Status ─────────────────────────────┐
│  [░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░]  │
│  0 of 5 required fields completed                    │
└──────────────────────────────────────────────────────┘

Position Information
├─ Select Position Group *
│  [────────────────────────────────]
│  Pick a position group to view available positions
│
├─ Position Applied For *
│  [────────────────────────────────]
│  Auto-filled when you select a position
│
└─ Job Group / Salary Grade *
   [────────────────────────────────] (readonly)
   Auto-filled from selected position

Applicant Information
├─ Applicant Name *
│  [────────────────────────────────]
│  Enter full name of applicant
│
├─ Schools Division Office *
│  [City Schools Division of Cabuyao──────]
│  Name of your schools division
│
└─ Contact Number *
   [────────────────────────────────]
   Use 09XXXXXXXXX or +639XXXXXXXXX format

Generate Evaluation Report ⦰ (disabled - grayed out)
Reset Form
View All Results
```

---

### After User Enters Name
```
┌─ Form Completion Status ─────────────────────────────┐
│  [█░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░]  │
│  1 of 5 required fields completed                    │
└──────────────────────────────────────────────────────┘

Applicant Information
├─ Applicant Name *
│  [Juan Dela Cruz───────────────────] ✓
│  ✓ Green checkmark appears
│  Enter full name of applicant
│
├─ Contact Number *
│  [────────────────────────────────]
│  Use 09XXXXXXXXX or +639XXXXXXXXX format
│
└─ Schools Division Office *
   [City Schools Division of Cabuyao──────]
   Name of your schools division

Generate Evaluation Report ⦰ (still disabled)
```

---

### After User Enters Valid Contact Number
```
┌─ Form Completion Status ─────────────────────────────┐
│  [██░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░]  │
│  2 of 5 required fields completed                    │
└──────────────────────────────────────────────────────┘

Contact Number
├─ Contact Number *
│  [09123456789──────────────────────] ✓
│  ✓ Green checkmark appears
│  Use 09XXXXXXXXX or +639XXXXXXXXX format
│
└─ Position still needed
   [────────────────────────────────]
```

---

### If User Enters Invalid Contact Number
```
Contact Number
├─ Contact Number *
│  [9123456789───────────────────────] ✗
│  ┌─────────────────────────────────────────────┐
│  │ Use format 09XXXXXXXXX or +639XXXXXXXXX   │
│  └─────────────────────────────────────────────┘
│  Red error message appears
│  Use 09XXXXXXXXX or +639XXXXXXXXX format
```

---

### After User Selects Position (Auto-fills fields)
```
┌─ Form Completion Status ─────────────────────────────┐
│  [█████████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░]  │
│  5 of 5 required fields completed ✓ COMPLETE!      │
└──────────────────────────────────────────────────────┘

Form Section 1
├─ Applicant Name *
│  [Juan Dela Cruz───────────────────] ✓
│
├─ Contact Number *
│  [09123456789──────────────────────] ✓
│
├─ Position Applied For *
│  [Teacher I─────────────────────────] ✓ (auto-filled)
│
├─ Schools Division Office *
│  [City Schools Division of Cabuyao──] ✓
│
└─ Job Group / Salary Grade *
   [Group TEACHING POSITIONS / SG 11──] ✓ (auto-filled)

Generate Evaluation Report ✓ (ENABLED - bright green)
Reset Form
View All Results
```

---

### If User Clicks Submit with Invalid Form
```
┌─ Form Completion Status ─────────────────────────────┐
│  [██░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░]  │
│  2 of 5 required fields completed                    │
└──────────────────────────────────────────────────────┘

Applicant Name
├─ Applicant Name *
│  [Juan Dela Cruz───────────────────] ✓
│
Contact Number
├─ Contact Number *
│  [9123456789───────────────────────] ✗
│  ┌──────────────────────────────────────────────────┐
│  │ Use format 09XXXXXXXXX or +639XXXXXXXXX        │
│  └──────────────────────────────────────────────────┘
│
Position Applied For
├─ Position Applied For *
│  [────────────────────────────────] ✗
│  ┌──────────────────────────────────────────────────┐
│  │ Position is required                            │
│  └──────────────────────────────────────────────────┘
│
└─ Page scrolls to first error (Contact Number)
   and focuses it automatically

Generate Evaluation Report ⦰ (still disabled)
```

---

### Progress Bar States

#### Empty (0%)
```
[░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░]
0 of 5 required fields completed
```

#### Partially Filled (40%)
```
[████████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░]
2 of 5 required fields completed
```

#### Mostly Filled (80%)
```
[██████████████████████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░]
4 of 5 required fields completed
```

#### Complete (100%)
```
[████████████████████████████████████████████████████] ✓
5 of 5 required fields completed ✓ ALL FIELDS COMPLETE!
```
(Progress bar turns green)

---

## Field States

### ✓ Valid State (Green)
```
Contact Number *
┌────────────────────────────────┬──┐
│ 09123456789                    │ ✓│
└────────────────────────────────┴──┘
 ▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔  Light green background
Use 09XXXXXXXXX or +639XXXXXXXXX format
```

### ✗ Invalid State (Red)
```
Contact Number *
┌────────────────────────────────┐
│ 9123456789                     │ ✗
└────────────────────────────────┘
 ▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔  Light red background
┌──────────────────────────────────────────────────┐
│ Use format 09XXXXXXXXX or +639XXXXXXXXX        │
└──────────────────────────────────────────────────┘
 Red error message below
```

### ○ Empty/Untouched State (Gray)
```
Contact Number *
┌────────────────────────────────┐
│                                │
└────────────────────────────────┘
 ▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔  Neutral gray
Use 09XXXXXXXXX or +639XXXXXXXXX format
```

---

## Button States

### Disabled (Submit Blocked)
```
┌──────────────────────────────────────────┐
│  Generate Evaluation Report              │ ⦰
│  (Grayed out, reduced opacity)           │
│  (Cannot click, cursor: not-allowed)     │
└──────────────────────────────────────────┘
```

### Enabled (Ready to Submit)
```
┌──────────────────────────────────────────┐
│  Generate Evaluation Report              │ ✓
│  (Bright green, fully opaque)            │
│  (Clickable, cursor: pointer)            │
└──────────────────────────────────────────┘
```

---

## Error Display Examples

### Contact Number Format Error
```
Contact Number *
[09XXXXXXX─────────]
┌─────────────────────────────────────────────────┐
│ Use format 09XXXXXXXXX or +639XXXXXXXXX       │
│ Appears in red after user leaves field        │
│ Disappears when corrected                     │
└─────────────────────────────────────────────────┘
```

### Required Field Empty Error
```
Applicant Name *
[──────────────────]
┌─────────────────────────────────────────────────┐
│ Applicant name is required                     │
│ Appears on submit attempt if field empty      │
│ Disappears when user enters text              │
└─────────────────────────────────────────────────┘
```

---

## Mobile View Example

```
┌──────────────────────────┐
│ DepEd HRMPSB Evaluation  │
│ System                   │
└──────────────────────────┘

Form Completion Status
┌──────────────────────────┐
│[█████░░░░░░░░░░░░░░░░░░]│
│2 of 5 fields completed   │
└──────────────────────────┘

Position Information
├─ Position Group *
│ [Select─────────────] v
│
├─ Position *
│ [Select─────────────] v
│
└─ Job Group *
  [────────────────────]

Applicant Name *
[Juan Dela Cruz─────] ✓

Contact Number *
[09123456789───────] ✓
Use 09XXXXXXXXX
or +639XXXXXXXXX

Division *
[City Schools──────] ✓

┌──────────────────────────┐
│ Generate Report    ✓     │
│ Reset Form               │
│ View All Results         │
└──────────────────────────┘
```

---

## Animation Examples

### ✓ Checkmark Appearance
```
Frame 1: Field value entered
Contact Number *
[09123456789──────────]  ○ (no indicator)

Frame 2 (0.1s): Checkmark starting to scale
[09123456789──────────]  ◐ (scales in)

Frame 3 (0.2s): Checkmark scaling
[09123456789──────────]  ◑ (grows)

Frame 4 (0.3s): Checkmark fully visible
[09123456789──────────] ✓ (complete)
```

### Error Message Appearance
```
Frame 1: Submit clicked with invalid field
Contact Number *
[9123456789───────────]

Frame 2 (0.1s): Error starting to slide
[9123456789───────────]
┌─────────────────────────────┐
│ Use format... (slides up)   │

Frame 3 (0.2s): Error fully visible
[9123456789───────────]
┌─────────────────────────────┐
│ Use format 09XXXXXXXXX...   │
└─────────────────────────────┘
```

---

## Keyboard Navigation

```
TAB → Move to next field
      Validation runs on blur
      Error shown if invalid
      ✓ Shown if valid

SHIFT+TAB → Move to previous field

ENTER → Submit form
        If invalid: show errors, focus first error
        If valid: submit normally

ESC → (No specific action, standard browser)

Arrow Keys → In select dropdowns
            Close/open options
```

---

## Colors Used

### Valid (Green)
- Background: `#f0fdf4` (very light green)
- Border: `#28a745` (medium green)
- Icon: `#28a745` (medium green)
- Text: `#155724` (dark green)

### Invalid (Red)
- Background: `#fef2f2` (very light red)
- Border: `#dc3545` (medium red)
- Icon: `#dc3545` (medium red)
- Text: `#721c24` (dark red)

### Progress Bar
- Empty: `#e9ecef` (light gray)
- Filled: `#E04040` to `#E06060` (DepEd red gradient)

### Text
- Helper: `#666` (medium gray) italic
- Error: `#721c24` (dark red) bold
- Label: `#333` (dark gray) bold

---

## Summary

**The user sees:**
1. ✅ Clean, organized form
2. ✅ Progress indicator showing completion
3. ✅ Green checkmarks for valid fields
4. ✅ Red errors with clear messages for invalid fields
5. ✅ Helper text guiding input format
6. ✅ Submit button that lights up when form is ready
7. ✅ Smooth animations and transitions
8. ✅ Real-time feedback as they type

**Result:** Users stay motivated, errors are prevented, and the form feels professional and modern.
