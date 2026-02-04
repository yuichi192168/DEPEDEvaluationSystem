# Dynamic Evaluation Criteria System Documentation

## Overview

The DepEd Evaluation System now implements a **dynamic evaluation criteria and point system** that automatically changes based on the selected position's type and salary grade. Instead of using hardcoded weights, the system loads the appropriate criteria from a centralized database.

## Key Features

✅ **Dynamic Criteria Loading** - Evaluation criteria load automatically when a position is selected
✅ **Position-Based Point Distribution** - Different positions have different max points per criterion
✅ **Salary Grade Variations** - Related Teaching and Non-Teaching positions vary by salary grade
✅ **Real-Time Display** - Criteria and max points update immediately in the preview table
✅ **Clear Point Allocation** - Shows exactly which criteria are used and their maximum points

## Implemented Criteria

### 1. TEACHING POSITIONS (Teacher I)

| Criteria | Max Points | Scoring Method |
|----------|-----------|-----------------|
| a. Education | 10 | Increment (Level-based) |
| b. Training | 10 | Increment (Hours-based) |
| c. Experience | 10 | Increment (Months-based) |
| d. PBET/LET/LEPT Rating | 10 | Weighted (0-5 rating) |
| e. PPST COIs | 35 | Weighted (0-5 rating) |
| f. PPST NCOIs | 25 | Weighted (0-5 rating) |
| **TOTAL** | **100** | |

**Used by:** Teacher I positions

---

### 2. HIGHER TEACHING POSITIONS

| Criteria | Max Points | Scoring Method |
|----------|-----------|-----------------|
| a. Education | 10 | Increment (Level-based) |
| b. Training | 10 | Increment (Hours-based) |
| c. Experience | 10 | Increment (Months-based) |
| d. PBET/LET/LEPT Rating | 10 | Weighted (0-5 rating) |
| e. PPST COIs | 35 | Weighted (0-5 rating) |
| f. PPST NCOIs | 25 | Weighted (0-5 rating) |
| **TOTAL** | **100** | |

**Used by:** Teacher II, Master Teacher I-V, Head Teacher I-VI

---

### 3. SCHOOL ADMINISTRATION POSITION

| Criteria | Max Points | Scoring Method |
|----------|-----------|-----------------|
| a. Education | 10 | Increment (Level-based) |
| b. Training | 10 | Increment (Hours-based) |
| c. Experience | 10 | Increment (Months-based) |
| d. Performance | 25 | Weighted (0-5 rating) |
| e. Outstanding Accomplishments | 10 | Direct Points (capped) |
| f. Application of Education | 10 | Weighted (0-5 rating) |
| g. Application of L&D | 10 | Weighted (0-5 rating) |
| h. Potential | 15 | Weighted (0-5 rating) |
| **TOTAL** | **100** | |

**Used by:** Principal I-IV, Special Principal, Assistant Principal

---

### 4. RELATED TEACHING POSITION

#### SG 11-15

| Criteria | Max Points | Scoring Method |
|----------|-----------|-----------------|
| a. Education | 10 | Increment (Level-based) |
| b. Training | 10 | Increment (Hours-based) |
| c. Experience | 10 | Increment (Months-based) |
| d. Performance | 20 | Weighted (0-5 rating) |
| e. Outstanding Accomplishments | 10 | Direct Points (capped) |
| f. Application of Education | 10 | Weighted (0-5 rating) |
| g. Application of L&D | 10 | Weighted (0-5 rating) |
| h. Potential | 20 | Weighted (0-5 rating) |
| **TOTAL** | **100** | |

#### SG 16-23 and SG 27

| Criteria | Max Points | Scoring Method |
|----------|-----------|-----------------|
| a. Education | 10 | Increment (Level-based) |
| b. Training | 10 | Increment (Hours-based) |
| c. Experience | 10 | Increment (Months-based) |
| d. Performance | 20 | Weighted (0-5 rating) |
| e. Outstanding Accomplishments | 5 | Direct Points (capped) |
| f. Application of Education | 15 | Weighted (0-5 rating) |
| g. Application of L&D | 10 | Weighted (0-5 rating) |
| h. Potential | 20 | Weighted (0-5 rating) |
| **TOTAL** | **100** | |

#### SG 24 (Chief)

| Criteria | Max Points | Scoring Method |
|----------|-----------|-----------------|
| a. Education | 10 | Increment (Level-based) |
| b. Training | 10 | Increment (Hours-based) |
| c. Experience | 10 | Increment (Months-based) |
| d. Performance | 25 | Weighted (0-5 rating) |
| e. Outstanding Accomplishments | 10 | Direct Points (capped) |
| f. Application of Education | 10 | Weighted (0-5 rating) |
| g. Application of L&D | 10 | Weighted (0-5 rating) |
| h. Potential | 15 | Weighted (0-5 rating) |
| **TOTAL** | **100** | |

---

### 5. NON-TEACHING LEVEL I

#### General Services

| Criteria | Max Points | Scoring Method |
|----------|-----------|-----------------|
| a. Education | 5 | Increment (Level-based) |
| b. Training | 5 | Increment (Hours-based) |
| c. Experience | 20 | Increment (Months-based) |
| d. Performance | 10 | Weighted (0-5 rating) |
| e. Outstanding Accomplishments | 5 | Direct Points (capped) |
| f. Application of Education | 0 | N/A |
| g. Application of L&D | 0 | N/A |
| h. Potential | 55 | Weighted (0-5 rating) |
| **TOTAL** | **100** | |

#### Non-General Services (SG 1-9)

| Criteria | Max Points | Scoring Method |
|----------|-----------|-----------------|
| a. Education | 5 | Increment (Level-based) |
| b. Training | 5 | Increment (Hours-based) |
| c. Experience | 20 | Increment (Months-based) |
| d. Performance | 20 | Weighted (0-5 rating) |
| e. Outstanding Accomplishments | 10 | Direct Points (capped) |
| f. Application of Education | 10 | Weighted (0-5 rating) |
| g. Application of L&D | 10 | Weighted (0-5 rating) |
| h. Potential | 20 | Weighted (0-5 rating) |
| **TOTAL** | **100** | |

---

### 6. NON-TEACHING LEVEL II

#### SG 10-22 and SG 27

| Criteria | Max Points | Scoring Method |
|----------|-----------|-----------------|
| a. Education | 5 | Increment (Level-based) |
| b. Training | 10 | Increment (Hours-based) |
| c. Experience | 15 | Increment (Months-based) |
| d. Performance | 20 | Weighted (0-5 rating) |
| e. Outstanding Accomplishments | 10 | Direct Points (capped) |
| f. Application of Education | 10 | Weighted (0-5 rating) |
| g. Application of L&D | 10 | Weighted (0-5 rating) |
| h. Potential | 20 | Weighted (0-5 rating) |
| **TOTAL** | **100** | |

#### SG 24 (Chief)

| Criteria | Max Points | Scoring Method |
|----------|-----------|-----------------|
| a. Education | 10 | Increment (Level-based) |
| b. Training | 5 | Increment (Hours-based) |
| c. Experience | 15 | Increment (Months-based) |
| d. Performance | 20 | Weighted (0-5 rating) |
| e. Outstanding Accomplishments | 10 | Direct Points (capped) |
| f. Application of Education | 10 | Weighted (0-5 rating) |
| g. Application of L&D | 10 | Weighted (0-5 rating) |
| h. Potential | 20 | Weighted (0-5 rating) |
| **TOTAL** | **100** | |

---

## System Architecture

### 1. Criteria Database
**File:** `config/evaluation_criteria.php`

Contains all evaluation criteria organized by:
- Position Group (e.g., TEACHING POSITIONS, SCHOOL ADMINISTRATION POSITION)
- Salary Grade (for positions with varying criteria)
- Category (for non-teaching general vs. non-general services)

Each criterion includes:
- Name (e.g., "Education", "Performance")
- Maximum points possible
- Description of the criterion

### 2. Criteria API Endpoint
**File:** `api/get_evaluation_criteria.php`

REST API endpoint that returns the appropriate criteria based on:
- `position_group` (required)
- `salary_grade` (optional)
- `category` (optional)

**Example Request:**
```
GET api/get_evaluation_criteria.php?position_group=TEACHING%20POSITIONS&salary_grade=11
```

**Example Response:**
```json
{
  "criteria": {
    "a": {
      "name": "Education",
      "max_points": 10,
      "description": "Educational attainment and qualifications"
    },
    "b": {
      "name": "Training",
      "max_points": 10,
      "description": "Professional development training hours"
    },
    ...
  },
  "total_points": 100,
  "description": "Teaching Positions Evaluation Criteria"
}
```

### 3. Frontend Integration
**File:** `index.php`

**Key JavaScript Functions:**

#### `loadEvaluationCriteria()`
- Fetches criteria from API based on selected position
- Stores criteria in `window.currentCriteria`
- Stores total points in `window.currentTotalPoints`
- Called automatically when position is selected

#### `calculatePreview()`
- Updated to use dynamic criteria instead of hardcoded weights
- Loads data from `window.currentCriteria` if available
- Falls back to hardcoded criteria if dynamic load fails
- Updates preview table with correct max points per criterion
- Displays total possible points

## User Experience Flow

```
User selects Position Group
    ↓
Position dropdown filters by selected group
    ↓
User selects specific Position
    ↓
loadEvaluationCriteria() is called
    ↓
API returns criteria specific to that position/salary grade
    ↓
Preview table updates with:
    - Correct criteria names
    - Correct maximum points
    - Dynamic scoring calculations
    ↓
User sees position-specific evaluation criteria
```

## Scoring Methods

### 1. Increment (Level-based)
Used for: Education, Training, Experience

**Formula:** `(Applicant Level - Baseline Level) × (Max Points / Max Possible Increment)`

**Example:** If max points = 10 and applicant is Level 8, baseline is Level 6:
- Increment = 8 - 6 = 2
- Score = 2 × (10 / ~30) ≈ 0.67 points

### 2. Weighted (Rating-based)
Used for: Performance, Application of Education, Application of L&D, Potential

**Formula:** `(Applicant Rating / 5) × Max Points`

**Example:** If max points = 25 and applicant rating = 4:
- Score = (4 / 5) × 25 = 20 points

### 3. Direct Points (Capped)
Used for: Outstanding Accomplishments

**Formula:** `min(Applicant Points, Max Points)`

**Example:** If max points = 10 and applicant has 15 points:
- Score = min(15, 10) = 10 points

## Example Scenarios

### Scenario 1: Teacher I Position
1. User selects Position Group: "TEACHING POSITIONS"
2. User selects Position: "Teacher I"
3. System loads criteria with 6 criteria (a-f), total 100 points
4. Preview shows Education (10), Training (10), Experience (10), PBET (10), PPST COIs (35), PPST NCOIs (25)

### Scenario 2: School Principal (Admin Position)
1. User selects Position Group: "SCHOOL ADMINISTRATION POSITION"
2. User selects Position: "Principal I" (SG 25)
3. System loads criteria with 8 criteria (a-h), total 100 points
4. Preview shows Education (10), Training (10), Experience (10), Performance (25), etc.

### Scenario 3: Related Teaching Specialist (SG 18)
1. User selects Position Group: "RELATED TEACHING POSITION"
2. User selects Position: "Guidance Counselor" (SG 18)
3. System detects SG 16-23 variant
4. Preview shows 8 criteria with: Education (10), Training (10), Experience (10), Performance (20), Outstanding Accomplishments (5), Application of Education (15), etc.

### Scenario 4: Non-Teaching Chief (SG 24)
1. User selects Position Group: "NON-TEACHING LEVEL II"
2. User selects Position: "Chief Accountant" (SG 24)
3. System detects SG 24 chief variant
4. Preview shows 8 criteria with specific chief-level distribution: Education (10), Training (5), Experience (15), etc.

## Implementation Details

### Position-Criteria Mapping

**config/evaluation_criteria.php** maintains a global `$evaluationCriteria` array:

```php
$evaluationCriteria = [
    'TEACHING POSITIONS' => [
        'default' => [ ... ]  // All Teacher I positions use same criteria
    ],
    'SCHOOL ADMINISTRATION POSITION' => [
        'default' => [ ... ]  // All admin positions use same criteria
    ],
    'RELATED TEACHING POSITION' => [
        'sg_11_15' => [ ... ],      // For SG 11, 12, 13, 14, 15
        'sg_16_23_27' => [ ... ],   // For SG 16-23, 27
        'sg_24_chief' => [ ... ]    // For SG 24 (Chief)
    ],
    'NON-TEACHING LEVEL I' => [
        'general_services' => [ ... ],      // General services positions
        'non_general_services' => [ ... ]   // Non-general services (SG 1-9)
    ],
    'NON-TEACHING LEVEL II' => [
        'sg_10_22_27' => [ ... ],   // For SG 10-22, 27
        'sg_24_chief' => [ ... ]    // For SG 24 (Chief)
    ]
];
```

### Helper Function

`getEvaluationCriteria($positionGroup, $salaryGrade, $category)`

Automatically selects the correct criteria variant based on:
1. Position group
2. Salary grade (if applicable)
3. Category (for non-teaching positions)

## Testing the System

### Test 1: Load Teacher I Position
1. Open the form
2. Select "TEACHING POSITIONS" group
3. Select "Teacher I" position
4. Verify preview table shows 6 criteria with 100 total points
5. Verify criteria: Education (10), Training (10), Experience (10), PBET (10), PPST COIs (35), PPST NCOIs (25)

### Test 2: Load Related Teaching Position (SG 18)
1. Select "RELATED TEACHING POSITION" group
2. Select position with SG 18 (e.g., "Guidance Counselor")
3. Verify preview shows 8 criteria
4. Verify Outstanding Accomplishments = 5, Application of Education = 15

### Test 3: Load Non-Teaching Chief (SG 24)
1. Select "NON-TEACHING LEVEL II" group
2. Select position with SG 24 (e.g., "Chief Accountant")
3. Verify preview shows 8 criteria
4. Verify Education = 10, Training = 5, Potential = 20

### Test 4: Switch Between Positions
1. Start with Teacher I
2. Switch to School Principal
3. Verify criteria change
4. Verify max points update correctly

## Future Enhancements

1. **Criteria Customization** - Allow admins to adjust criteria per position
2. **Category Management** - Add UI to manage position categories
3. **Audit Trail** - Log which criteria were used for each evaluation
4. **Export Criteria** - Include selected criteria in evaluation reports
5. **Criteria Versioning** - Track changes to criteria over time

## Files Modified/Created

| File | Type | Changes |
|------|------|---------|
| `config/evaluation_criteria.php` | NEW | Centralized criteria database |
| `api/get_evaluation_criteria.php` | NEW | API endpoint for criteria |
| `index.php` | MODIFIED | Added dynamic criteria loading and display |
| `classes/AssessmentProcessor.php` | EXISTING | No changes needed |
| `config/baseline_library.php` | EXISTING | No changes needed |

## Status

✅ **IMPLEMENTATION COMPLETE**

The dynamic evaluation criteria system is fully implemented and ready for:
- User acceptance testing
- Position-specific testing (try different position groups and salary grades)
- Scoring verification
- Production deployment

---

**Last Updated:** 2024
**Status:** Production Ready
