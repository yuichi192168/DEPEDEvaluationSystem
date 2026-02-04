# Official Criteria System - Enforcement Verification

**Status**: ✅ FULLY IMPLEMENTED AND VERIFIED

---

## Source of Truth

**File**: `config/evaluation_criteria.php`

This file contains ALL evaluation criteria definitions. Every report, calculation, and comparison uses ONLY this source.

---

## Verified Criteria Specifications

### Teacher I Positions (TEACHING POSITIONS)
✅ Education: 10 pts
✅ Training: 10 pts
✅ Experience: 10 pts
✅ PBET, LET, or LEPT Rating: 10 pts
✅ PPST COIs (Classroom Observation or Demonstration Teaching): 35 pts
✅ PPST NCOIs (Teacher Reflection): 25 pts
✅ **Total: 100 points** (only 6 main criteria)

### School Administration Positions
✅ Education: 10 pts
✅ Training: 10 pts
✅ Experience: 10 pts
✅ Performance: 25 pts
✅ Outstanding Accomplishments: 10 pts
✅ Application of Education: 10 pts
✅ Application of L&D: 10 pts
✅ Potential (Written Exam, BEI): 15 pts
✅ **Total: 100 points**

### Related Teaching - SG 11-15
✅ Education: 10 pts
✅ Training: 10 pts
✅ Experience: 10 pts
✅ Performance: 20 pts
✅ Outstanding Accomplishments: 10 pts
✅ Application of Education: 10 pts
✅ Application of L&D: 10 pts
✅ Potential: 20 pts
✅ **Total: 100 points**

### Related Teaching - SG 16-23, 27
✅ Education: 10 pts
✅ Training: 10 pts
✅ Experience: 10 pts
✅ Performance: 20 pts
✅ Outstanding Accomplishments: 5 pts
✅ Application of Education: 15 pts
✅ Application of L&D: 10 pts
✅ Potential: 20 pts
✅ **Total: 100 points**

### Related Teaching - SG 24 (Chief)
✅ Education: 10 pts
✅ Training: 10 pts
✅ Experience: 10 pts
✅ Performance: 25 pts
✅ Outstanding Accomplishments: 10 pts
✅ Application of Education: 10 pts
✅ Application of L&D: 10 pts
✅ Potential: 15 pts
✅ **Total: 100 points**

### Non-Teaching - General Services
✅ Education: 5 pts
✅ Training: 5 pts
✅ Experience: 20 pts
✅ Performance: 10 pts
✅ Outstanding Accomplishments: 5 pts
✅ Application of Education: 0 pts
✅ Application of L&D: 0 pts
✅ Potential: 55 pts
✅ **Total: 100 points**

### Non-Teaching - SG 1-9
✅ Education: 5 pts
✅ Training: 5 pts
✅ Experience: 20 pts
✅ Performance: 20 pts
✅ Outstanding Accomplishments: 10 pts
✅ Application of Education: 10 pts
✅ Application of L&D: 10 pts
✅ Potential: 20 pts
✅ **Total: 100 points**

### Non-Teaching - SG 10-22, 27
✅ Education: 5 pts
✅ Training: 10 pts
✅ Experience: 15 pts
✅ Performance: 20 pts
✅ Outstanding Accomplishments: 10 pts
✅ Application of Education: 10 pts
✅ Application of L&D: 10 pts
✅ Potential: 20 pts
✅ **Total: 100 points**

### Non-Teaching - SG 24 (Chief)
✅ Education: 10 pts
✅ Training: 5 pts
✅ Experience: 15 pts
✅ Performance: 20 pts
✅ Outstanding Accomplishments: 10 pts
✅ Application of Education: 10 pts
✅ Application of L&D: 10 pts
✅ Potential: 20 pts
✅ **Total: 100 points**

---

## Enforcement Rules Implementation

### ✅ Rule 1: Position-Based Criteria Selection

**Function**: `getEvaluationCriteria($positionGroup, $salaryGrade, $category)`
**Location**: `config/evaluation_criteria.php` lines 227-270
**Implementation**:
- Detects position group from Position Applied For
- Determines salary grade from baseline library
- Selects correct criteria tier for position
- Returns error (null) if position group not found

**Applied In**:
- Live Calculation Preview (`index.php`)
- Comparative Assessment Results (`comparative_assessment_results.php`)
- CAR Report Generator (`classes/CARReportGenerator.php`)

### ✅ Rule 2: Complete Criteria Reset on Position Change

**Function**: `loadEvaluationCriteria()`
**Location**: `index.php` lines 1104-1147
**Implementation**:
```javascript
// Clear previous criteria before loading new ones
window.currentCriteria = {};
window.currentTotalPoints = 0;

// Then load new criteria based on selected position
```

**Behavior**:
- Clears all previous criteria immediately
- Reloads criteria from API based on new position
- Prevents mixing criteria from previous selections
- Shows error if new criteria cannot be loaded

### ✅ Rule 3: Reports Use Same Criteria and Scoring Logic

**Verified in all three modules**:

1. **Live Calculation Preview** (`index.php`)
   - Loads criteria: `api/get_evaluation_criteria.php`
   - Scoring: Increment/Weighted/Direct Points (lines 1292-1320)
   - Display: Dynamic table with position-specific names (lines 1327-1338)

2. **Comparative Assessment Results** (`comparative_assessment_results.php`)
   - Loads criteria: `getEvaluationCriteria()` from config
   - Displays: Position-specific criterion names and max points (lines 545-603)
   - Scoring: Same formulas used in evaluation database

3. **CAR Report Generator** (`classes/CARReportGenerator.php`)
   - Loads criteria: `getEvaluationCriteria()` from config
   - Displays: Position-specific names in table headers (lines 233-275)
   - Scoring: Same calculation methods applied

---

## No Fallback Defaults Policy

### Live Preview
```javascript
if (window.currentCriteria && Object.keys(window.currentCriteria).length > 0) {
    // Use dynamic criteria - REQUIRED
} else {
    // ERROR: No fallback allowed
    console.warn('Warning: Evaluation criteria not loaded');
    document.getElementById('previewTableBody').innerHTML = 
        '<tr><td colspan="6">ERROR: Evaluation criteria not available...</td></tr>';
    return;
}
```

### Comparative Assessment
```php
// ERROR: No fallback allowed
if (!$positionCriteria || empty($positionCriteria['criteria'])) {
    echo '<tr><td colspan="3">ERROR: Evaluation criteria not available...</td></tr>';
} else {
    // Display position-specific criteria
}
```

### CAR Report
```php
// ERROR: No fallback allowed
if (!$positionCriteria || empty($positionCriteria['criteria'])) {
    // Return error message - no report generated
    return $html . 'ERROR: Evaluation criteria not found...';
}
```

---

## Switching Positions - Full Reset Verification

When user changes position in `index.php`:

1. **Old criteria cleared**
   ```javascript
   window.currentCriteria = {};
   window.currentTotalPoints = 0;
   ```

2. **Position details updated**
   ```javascript
   setSelectedPosition(newPositionKey);
   document.getElementById('position_applied').value = pos.position_name;
   document.getElementById('job_group_sg_level').value = 'Group ' + pos.position_group + ...
   ```

3. **New criteria loaded**
   ```javascript
   await loadEvaluationCriteria();
   // Calls: api/get_evaluation_criteria.php?position_group=...&salary_grade=...
   ```

4. **Baseline values reset**
   ```javascript
   document.getElementById('baseline_education_degree').value = pos.education.degree;
   // ... all baseline fields reset to position defaults
   ```

5. **Preview recalculated**
   ```javascript
   calculatePreview();
   // Uses newly loaded window.currentCriteria
   ```

---

## Error Detection & Prevention

### What Prevents Mismatches

1. **Single Source of Truth**
   - All criteria defined in ONE file
   - No hardcoded defaults in code
   - All modules call same functions

2. **Validation Gates**
   - Each module verifies criteria loaded successfully
   - Error shown if criteria unavailable
   - Report not generated if criteria missing

3. **Position-Based Routing**
   - Criteria automatically selected by position group + salary grade
   - No manual selection possible
   - Salary grade checked for multi-tier positions

4. **Complete Reset on Change**
   - Switching positions clears old criteria completely
   - Prevents accidental use of previous position's criteria
   - New criteria loaded before any calculations

---

## System Audit Checklist

### Criteria Accuracy
- [x] All 9 position groups defined in config
- [x] All max points verified against official specs
- [x] All criterion names match official language
- [x] All totals equal exactly 100 points

### Three Module Compliance
- [x] Live Preview uses `config/evaluation_criteria.php`
- [x] Comparative Assessment uses `config/evaluation_criteria.php`
- [x] CAR Report uses `config/evaluation_criteria.php`
- [x] All three receive identical criteria for same position

### Enforcement Rules
- [x] Position-based selection implemented and tested
- [x] Switching positions clears all previous criteria
- [x] No default fallbacks permitted
- [x] Error states when criteria unavailable

### Mismatch Prevention
- [x] No hardcoded criterion names in code (use dynamic names)
- [x] No hardcoded max points in code (use from criteria)
- [x] Position group detection automatic (from baseline library)
- [x] Salary grade detection automatic (from position selection)

---

## Compliance Statement

This system fully complies with ALL enforcement rules:

✅ **"Criteria selection must be based strictly on Position Applied For and Salary Grade"**
- Implemented via `getEvaluationCriteria($positionGroup, $salaryGrade, $category)`
- Automatically detects from selected position
- No manual overrides possible

✅ **"Switching positions must fully reset and reload the criteria"**
- `loadEvaluationCriteria()` clears old and loads new criteria
- All baseline fields also reset
- Preview recalculated with new position's criteria

✅ **"Generated reports and comparative assessments must reflect the same criteria and scoring logic used during evaluation"**
- All three modules load from single authoritative source
- Same scoring methods applied (Increment/Weighted/Direct Points)
- Same max points displayed in reports

✅ **"Any mismatch between live evaluation and generated outputs is considered a system error"**
- Error detection gates in place
- Error messages shown if criteria unavailable
- Reports not generated without valid criteria

---

## System Status

🟢 **FULLY OPERATIONAL**
🟢 **ALL CRITERIA CORRECT**
🟢 **NO FALLBACK DEFAULTS**
🟢 **ENFORCEMENT RULES ACTIVE**
🟢 **READY FOR PRODUCTION**
