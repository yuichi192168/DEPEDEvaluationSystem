# Dynamic Template Modification - Schedule-Based DTR Generation

## Overview
The DTR Generator now automatically detects employee schedules (7-4pm vs 8-5pm) and dynamically modifies the DTR template accordingly.

---

## Features Implemented

### 1. **Automatic Schedule Detection**
- **How it works**: Analyzes employee arrival times from the source data
- **Detection logic**:
  - Arrival times between 6:00-7:59 AM → **7-4 Schedule**
  - Arrival times between 8:00-9:00 AM → **8-5 Schedule**
  - Uses majority voting (whichever time range has more entries)
  - Default to 8-5 if no data available

### 2. **Dynamic Template Modification**
- **Cells Updated**: A14 and I14 in the DTR template
- **7-4pm Employees**: "Official hours for arrival and departure: 7:00 a.m. to 4:00 p.m."
- **8-5pm Employees**: "Official hours for arrival and departure: 8:00 a.m. to 5:00 p.m."
- **Applied automatically** before injecting time logs

### 3. **Schedule-Based File Naming**
- **Format**: `DTR_{SCHEDULE}_{EMPLOYEE_NAME}.xlsx`
- **Examples**:
  - `DTR_7-4_BUENA_G_VILLAN.xlsx`
  - `DTR_8-5_JOSE_M_SANTOS.xlsx`
- Makes it easy to identify employee schedules at a glance

### 4. **Schedule Badges in UI**
Each generated file displays a color-coded schedule badge:
- **7-4pm**: Blue badge 🔵
- **8-5pm**: Purple badge 🟣
- Displayed in the "Generated Files" table

### 5. **Batch Download by Schedule**
Download all DTR files for a specific schedule in one ZIP file:
- **"Download All 7-4pm Staff"** button - Downloads all 7-4 schedule files as a ZIP
- **"Download All 8-5pm Staff"** button - Downloads all 8-5 schedule files as a ZIP
- **ZIP Filename Format**: `DTR_{SCHEDULE}_Batch_YYYY-MM-DD_HHMMSS.zip`
- **Example**: `DTR_7-4_Batch_2026-03-05_143022.zip`

---

## Technical Implementation

### Backend Changes (generate_dtr.php)

#### 1. **Added Schedule Detection Method**
```php
private function detectSchedules()
{
    foreach ($this->logData as $name => &$data) {
        // Analyzes arrival_times array
        // Counts 7am vs 8am arrivals
        // Determines schedule based on majority
    }
}
```

#### 2. **Modified Template Population**
```php
private function generateSingleDTR($employeeName, $employeeData)
{
    // Detect schedule
    $schedule = $employeeData['schedule'] ?? '8-5';
    
    // Set official hours text
    if ($schedule === '7-4') {
        $officialHoursText = '...7:00 a.m. to 4:00 p.m.';
    } else {
        $officialHoursText = '...8:00 a.m. to 5:00 p.m.';
    }
    
    // Update cells A14 and I14
    $sheet->setCellValue('A14', $officialHoursText);
    $sheet->setCellValue('I14', $officialHoursText);
}
```

#### 3. **Enhanced Employee Data Structure**
```php
$this->logData[$name] = [
    'department' => $department,
    'dates' => [],
    'schedule' => null,        // NEW: Detected schedule
    'arrival_times' => []      // NEW: Track for detection
];
```

### Frontend Changes (dtr_generator_redesigned.php)

#### 1. **Schedule Detection in File List**
```php
// Detect schedule from filename
$schedule = 'Unknown';
if (preg_match('/^DTR_(7-4|8-5)_/', $file, $matches)) {
    $schedule = $matches[1];
}
```

#### 2. **Schedule Count Statistics**
```php
$schedule74Count = 0;
$schedule85Count = 0;
foreach ($outputFiles as $file) {
    if ($file['schedule'] === '7-4') $schedule74Count++;
    elseif ($file['schedule'] === '8-5') $schedule85Count++;
}
```

#### 3. **Batch Download Handler**
```php
if (isset($_GET['download_schedule'])) {
    // Create ZIP file
    // Find all files matching schedule
    // Add to ZIP
    // Download ZIP
}
```

---

## User Guide

### Step 1: Upload Excel Files
- Upload your OSDS attendance files as usual
- Files are automatically scanned

### Step 2: Generate DTRs
- Select files and click "Generate DTRs"
- System automatically detects each employee's schedule
- DTR templates are modified accordingly
- Files are named with schedule prefix

### Step 3: View Generated Files
- **Schedule Badges**: Each file shows a color-coded badge (7-4 or 8-5)
- **Filename**: Clearly indicates schedule (e.g., DTR_7-4_Name.xlsx)
- **Organized Display**: Easy to filter and identify schedules

### Step 4: Download Files
**Option A - Individual Download**:
- Click "Download" next to any file

**Option B - Batch Download by Schedule**:
- Click "Download All 7-4pm Staff" for all 7-4 schedule files
- Click "Download All 8-5pm Staff" for all 8-5 schedule files
- Receives a ZIP file with all matching DTRs

---

## Schedule Detection Examples

### Example 1: 7-4 Schedule Employee
**Source Data**:
- Jan 1: Morning arrival 07:05
- Jan 2: Morning arrival 06:55
- Jan 3: Morning arrival 07:10
- Jan 4: Morning arrival 07:00

**Result**: Detected as **7-4 Schedule**
- Most arrivals are in 6:00-7:59 range
- Template updated with "7:00 a.m. to 4:00 p.m."
- File named: `DTR_7-4_EMPLOYEE_NAME.xlsx`

### Example 2: 8-5 Schedule Employee
**Source Data**:
- Jan 1: Morning arrival 08:10
- Jan 2: Morning arrival 08:05
- Jan 3: Morning arrival 08:15
- Jan 4: Morning arrival 08:00

**Result**: Detected as **8-5 Schedule**
- Most arrivals are in 8:00-9:00 range
- Template updated with "8:00 a.m. to 5:00 p.m."
- File named: `DTR_8-5_EMPLOYEE_NAME.xlsx`

### Example 3: Mixed Schedule Employee
**Source Data**:
- Jan 1: Morning arrival 07:05
- Jan 2: Morning arrival 08:10
- Jan 3: Morning arrival 08:05
- Jan 4: Morning arrival 08:15

**Result**: Detected as **8-5 Schedule**
- 3 arrivals at 8am vs 1 arrival at 7am
- Majority wins: 8-5 schedule
- Template updated with "8:00 a.m. to 5:00 p.m."

---

## Benefits

### For Administrators
- ✅ **Automated Schedule Detection** - No manual classification needed
- ✅ **Accurate Templates** - Official hours match actual schedules
- ✅ **Easy Organization** - Files clearly labeled by schedule
- ✅ **Batch Operations** - Download all files for a schedule at once
- ✅ **Time Savings** - No need to manually separate or modify files

### For HR Personnel
- ✅ **Clear Identification** - Schedule badges make it obvious
- ✅ **Quick Filtering** - Easy to find specific schedule groups
- ✅ **Bulk Downloads** - Get all 7-4 or 8-5 staff files together
- ✅ **Accurate Records** - Official hours correctly match schedules

### For Auditors
- ✅ **Compliance** - Official hours match employee schedules
- ✅ **Traceability** - Filename indicates schedule clearly
- ✅ **Organized Files** - Easy to review by schedule type

---

## Troubleshooting

### Q: What if an employee has inconsistent arrival times?
**A**: The system uses majority voting. If an employee arrives at 7am most days but occasionally at 8am, they'll be classified as 7-4. For truly split schedules, the most frequent arrival time determines the classification.

### Q: Can I manually override the detected schedule?
**A**: Currently, schedule detection is automatic based on arrival times. If you need to change a schedule, you can manually edit the Excel template after generation.

### Q: What happens if there's no arrival data for an employee?
**A**: The system defaults to **8-5 schedule** if no arrival times are recorded.

### Q: The schedule detection seems wrong for one employee. Why?
**A**: Check the source data for that employee:
1. Verify arrival times are in the "Clock In" column
2. Ensure times are in the "Morning" timetable entries
3. Look for data entry errors (e.g., 18:00 instead of 08:00)

### Q: Can I download files from both schedules together?
**A**: Yes! Use the checkbox selection and bulk download, or download each schedule batch separately and combine them.

---

## File Naming Convention

### Standard Format
```
DTR_{SCHEDULE}_{EMPLOYEE_NAME}.xlsx
```

### Components
- **DTR**: File type identifier
- **{SCHEDULE}**: Either "7-4" or "8-5"
- **{EMPLOYEE_NAME}**: Sanitized employee name (spaces→underscores)
- **.xlsx**: File extension

### Examples
- `DTR_7-4_BUENA_G_VILLAN.xlsx`
- `DTR_8-5_JOSE_M_SANTOS.xlsx`
- `DTR_7-4_MARIA_CRUZ.xlsx`
- `DTR_8-5_JUAN_DELA_CRUZ.xlsx`

---

## Version History

**v3.1** - March 5, 2026
- ✨ Added automatic schedule detection
- ✨ Dynamic template modification (cells A14, I14)
- ✨ Schedule-based file naming
- ✨ Schedule badges in UI
- ✨ Batch download by schedule
- ✨ Schedule statistics

**v3.0** - March 2026
- Multiple file upload
- Bulk file deletion
- DepEd logo integration
- User-friendly interface

---

## Support

For issues or questions about the schedule detection feature:
1. Check the source data format
2. Verify arrival times are correctly recorded
3. Review the troubleshooting section above
4. Check generated files for accuracy

---

**DTR Generator v3.1** | Department of Education Evaluation System | Schedule-Aware Edition
