# DepEd Evaluation System - Cascading Position Dropdown with Auto-Loading Baseline

## ✅ IMPLEMENTATION COMPLETE

The position selection system is now fully functional with:
1. **Position Group Dropdown** (First) - Select from 6 position groups
2. **Position Dropdown** (Second) - Filters to show ONLY positions in selected group
3. **Auto-Loading Baseline** - Automatically populates all baseline qualification standards

---

## How It Works

### Step 1: User Selects Position Group
```
Dropdown 1: Select Position Group
├─ TEACHING POSITIONS
├─ HIGHER TEACHING POSITIONS
├─ SCHOOL ADMINISTRATION POSITION
├─ RELATED TEACHING POSITION
├─ NON-TEACHING LEVEL I
└─ NON-TEACHING LEVEL II
```

### Step 2: Dropdown 2 Automatically Filters to Selected Group
```
Example: User selects "NON-TEACHING LEVEL I"
Dropdown 2 NOW SHOWS ONLY:
├─ Attorney I
├─ Attorney II
├─ Accountant II
├─ Clerk I
├─ Utility Worker I
└─ [30+ more positions in this group]
```

### Step 3: Automatic Baseline Loading
When user selects a specific position, the **"Minimum Qualification Standards (Baseline) - Auto-loaded"** section auto-populates with:

**Education**
- Degree (Bachelor/Master/Doctorate)
- Master's Units
- Doctoral Units
- **Calculated Level:** Displayed automatically

**Training & Experience**
- Training Hours → **Calculated Level:** Displayed
- Experience Months → **Calculated Level:** Displayed

**Performance Ratings**
- Performance Rating
- Outstanding Accomplishments
- Application of Education
- Application of L&D
- Potential

**Job Information**
- Job Group/SG-Level (auto-filled based on salary grade)
- Position Applied For (auto-filled with position name)

---

## Technical Implementation

### 1. Data Layer: `config/baseline_library.php`
**130+ positions** organized with:
```php
'position_key' => [
    'position_name' => 'Position Title',
    'position_group' => 'TEACHING POSITIONS',  // or other groups (ALL UPPERCASE)
    'salary_grade' => 11,
    'education' => ['degree' => 'Bachelor', 'masters_units' => 0, 'doctoral_units' => 0],
    'training' => 0,
    'experience' => 0,
    'performance' => 0,
    'outstanding_accomplishments' => 0,
    'application_of_education' => 0,
    'application_of_ld' => 0,
    'potential' => 0
]
```

### 2. Backend: `classes/AssessmentProcessor.php`
**Dynamic Position Group Builder**
- `buildPositionGroups()`: Reads baseline_library.php and groups positions
- `getPositionGroups()`: Returns organized groups with positions
- Automatically sorts positions alphabetically within each group
- Maintains defined order: TEACHING → HIGHER TEACHING → ADMIN → RELATED → NON-TEACHING LEVEL I → NON-TEACHING LEVEL II

### 3. API: `api/get_position_groups.php`
**Returns JSON structure:**
```json
[
  {
    "group": "TEACHING POSITIONS",
    "positions": ["Teacher I"]
  },
  {
    "group": "HIGHER TEACHING POSITIONS",
    "positions": ["Teacher II", "Master Teacher I", "Master Teacher II", ...]
  },
  {
    "group": "NON-TEACHING LEVEL I",
    "positions": ["Attorney I", "Attorney II", "Accountant II", ...]
  },
  ...
]
```

### 4. Frontend: `index.php`

**HTML Structure (Lines 275-310)**
```html
<!-- Position Group Dropdown (First) -->
<select id="position_group_select" name="position_group">
    <option value="">-- Select a Position Group --</option>
    <!-- Populated by JavaScript from API -->
</select>

<!-- Position Dropdown (Second) - Filters based on group selection -->
<select id="position_key" name="position_key">
    <option value="custom">-- Custom Position (Manual Entry) --</option>
    <!-- Auto-populated when group is selected -->
</select>

<!-- Baseline Section (Auto-loads when position is selected) -->
<h2>Minimum Qualification Standards (Baseline) - Auto-loaded</h2>
<!-- Fields for education, training, experience, etc. -->
```

**JavaScript (Lines 676-1072)**

**Function 1: loadPositionGroups() - Lines 676-720**
```javascript
// 1. Fetches position groups from API
// 2. Populates position_group_select dropdown
// 3. Adds change listener to position_group_select
// 4. On group selection:
//    - Filters position dropdown to show only positions in that group
//    - Auto-selects first position in group
//    - Triggers position change event to load baseline
```

**Function 2: Position Selection Handler - Lines 940-989**
```javascript
// Triggered when position is selected (manually or auto-selected)
// Populates all baseline fields:
// - position_applied (position name)
// - position_group_select (syncs to selected position's group)
// - job_group_sg_level (salary grade)
// - baseline_education_degree, baseline_education_masters_units, baseline_education_doctoral_units
// - baseline_training, baseline_experience
// - baseline_performance, baseline_outstanding_accomplishments
// - baseline_application_of_education, baseline_application_of_ld, baseline_potential
// Triggers updateAllLevels() and calculatePreview()
```

**Function 3: updateAllLevels() - Lines 991-1045**
```javascript
// Converts baseline and applicant values to levels:
// - Education → Level (6, 21, or 31 depending on degree + units)
// - Training → Level (1-31 based on hours)
// - Experience → Level (1-31 based on months)
// Displays calculated levels inline
```

**Function 4: calculatePreview() - Lines 1055-1119**
```javascript
// Uses position group to determine weights
// Calculates increments (applicant level - baseline level)
// Computes scores = increment × weight / 100
// Updates live preview table
// Displays total score
```

---

## Position Groups & Position Count

| Group | Count | Examples |
|-------|-------|----------|
| TEACHING POSITIONS | 1 | Teacher I |
| HIGHER TEACHING POSITIONS | 12 | Teacher II, Master Teachers I-V, Head Teachers I-VI |
| SCHOOL ADMINISTRATION POSITION | 10 | Principals I-IV, Special Principals, Assistant Principals |
| RELATED TEACHING POSITION | 30+ | Guidance Counselors, Education Program Specialists, School Farming Coordinators, Science Specialists, etc. |
| NON-TEACHING LEVEL I | 20+ | Attorneys, Accountants, Engineers, ICT Officers, Administrators, etc. |
| NON-TEACHING LEVEL II | 70+ | Dentists, Architects, Programmers, Nurses, Librarians, Statisticians, etc. |

**TOTAL: 130+ positions**

---

## Scoring Weights by Position Group

Each position group has custom weight distribution for 8 criteria:

| Criterion | TEACHING | HIGHER TEACHING | NON-TEACHING I | NON-TEACHING II | RELATED TEACHING | ADMIN |
|-----------|----------|-----------------|----------------|-----------------|------------------|-------|
| Education | 10 | 5 | 5 | 5 | 10 | 10 |
| Training | 10 | 10 | 5 | 10 | 10 | 10 |
| Experience | 10 | 15 | 20 | 15 | 10 | 10 |
| Performance | 10 | 20 | 20 | 20 | 20 | 25 |
| Outstanding Accomplishments | 35 | 10 | 10 | 10 | 10 | 10 |
| Application of Education | 10 | 10 | 10 | 10 | 10 | 10 |
| Application of L&D | 10 | 10 | 10 | 10 | 10 | 10 |
| Potential | 5 | 20 | 20 | 20 | 20 | 15 |
| **TOTAL** | **100** | **100** | **100** | **100** | **100** | **100** |

---

## User Experience Flow

### Scenario: Evaluating a Non-Teaching Level I Position

1. **Open Evaluation Form**
   - Form loads with empty dropdowns

2. **Select Position Group**
   - Click "Select Position Group" dropdown
   - Choose "NON-TEACHING LEVEL I"
   - Form automatically filters position dropdown

3. **Select Specific Position**
   - Click "Select Position" dropdown
   - See only NON-TEACHING LEVEL I positions
   - Select "Accountant III"
   - **BASELINE AUTO-LOADS:**
     - Education: Master's Degree
     - Training: 24 hours → Level 4
     - Experience: 36 months → Level 7
     - Job Group: "Group NON-TEACHING LEVEL I / Salary Grade 19"
     - Position Applied: "Accountant III"

4. **Enter Applicant Information**
   - Fill in applicant's actual education level
   - Fill in applicant's training hours
   - Fill in applicant's experience months
   - Fill in rating scores (0-5)

5. **View Automatic Calculation**
   - Live preview shows:
     - Applicant level vs. Baseline level
     - Increments (if any)
     - Weighted scores
     - Total score (0-100)

6. **Export Results**
   - Generate report with all data
   - Export as HTML, PDF, Word, Excel, or Text

---

## Key Features

✅ **Cascading Dropdowns** - Second dropdown filters based on first selection
✅ **Auto-Loading Baseline** - All 9 baseline fields auto-populate when position selected
✅ **130+ Positions** - All DepEd teaching and non-teaching positions included
✅ **Dynamic Grouping** - Position groups built from baseline_library.php
✅ **Smart Weight Assignment** - Correct weights applied based on position group
✅ **Live Calculation** - Preview updates in real-time as user changes values
✅ **Editable Baseline** - User can adjust baseline if needed
✅ **Custom Position Option** - Always available for positions not in the list
✅ **Level Display** - Calculated levels shown for education, training, and experience
✅ **Responsive Design** - Works on desktop, tablet, and mobile devices

---

## Testing Checklist

- [x] baseline_library.php contains 130+ positions (ALL groups in UPPERCASE)
- [x] Position groups API returns all 6 groups with positions
- [x] Cascading dropdown filters positions by group
- [x] Auto-selection of first position triggers baseline loading
- [x] All baseline fields populate correctly when position selected
- [x] Weights correctly assigned based on position group
- [x] Live preview calculates scores correctly
- [x] Level calculations (education, training, experience) work
- [x] Custom position option always available
- [x] Form validates required fields

---

## Status

🎉 **FULLY OPERATIONAL** 🎉

The system is ready for production use. All features are implemented, tested, and working correctly.

---

## Files Modified

1. **config/baseline_library.php** - 1167 lines with 130+ positions (ALL groups UPPERCASE)
2. **classes/AssessmentProcessor.php** - Dynamic group building
3. **api/get_position_groups.php** - Returns organized groups
4. **index.php** - Cascading dropdowns + auto-loading baseline + scoring system

---

## For Support

If positions need to be added or modified:
1. Edit `config/baseline_library.php`
2. Add/modify position entry with `position_group` (use exact group name in UPPERCASE)
3. System automatically groups and displays position

No code changes needed for adding new positions!
