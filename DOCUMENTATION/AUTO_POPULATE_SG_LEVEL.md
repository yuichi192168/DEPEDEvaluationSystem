# Auto-Population of Job Group and Salary Grade Feature

## Summary
Added automatic population of Job Group and Salary Grade (SG-Level) fields across all evaluation forms when a position is selected from the dropdown.

## Changes Made

### 1. **index.php** (Main Evaluation Form)
- Updated the position selection event listener to auto-populate the `Job Group/SG-Level` field
- Format: `Group {A/B/C} / Salary Grade {SG}`
- Example: `Group B / Salary Grade 18`
- Clears the field when "Custom Position" is selected

### 2. **generate_car_g1.php** (CAR Report - Teaching Positions)
- Added `data-salary-grade` and `data-position-group` attributes to dropdown options
- Added two new read-only fields:
  - **Salary Grade** - displays SG value
  - **Position Group** - displays position group (A/B/C)
- Added JavaScript listener to populate fields on selection
- Format: `SG {number}` and `Group {A/B/C}`

### 3. **generate_car_g2.php** (CAR Report - Administrative Positions)
- Added `data-salary-grade` and `data-position-group` attributes to dropdown options
- Added two new read-only fields:
  - **Salary Grade** - displays SG value
  - **Position Group** - displays position group (A/B/C)
- Added JavaScript listener to populate fields on selection
- Format: `SG {number}` and `Group {A/B/C}`

## How It Works

1. **User selects a position** from the dropdown
2. **JavaScript event listener triggers**
3. **Data attributes** (salary grade and position group) from the selected option are extracted
4. **Read-only fields** are automatically populated with the relevant information
5. **User sees immediate feedback** about the selected position's classification

## Benefits

✅ **Improved User Experience** - Users immediately see the Job Group and Salary Grade classification  
✅ **Reduced Manual Entry** - No need to manually look up and type this information  
✅ **Prevents Errors** - Read-only fields ensure correct data is used  
✅ **Consistent Across All Forms** - Same behavior on all three main evaluation forms  
✅ **Fast Reference** - Users can quickly verify they selected the correct position

## Technical Details

- **JavaScript:** Vanilla JavaScript (no external libraries required)
- **Data Storage:** HTML5 `data-*` attributes on dropdown options
- **Field Type:** Read-only input fields (cannot be manually modified)
- **Browser Compatible:** Works in all modern browsers supporting HTML5
