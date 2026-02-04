# Dynamic Evaluation Criteria in Reports

## Overview
The evaluation criteria and point system now dynamically changes based on the selected position and its classification in both the Live Calculation Preview and the Comparative Assessment Result (CAR).

## 1. Live Calculation Preview - Evaluation Criteria

**Location:** `index.php` (lines 606-630)

**Features:**
- Displays "Live Calculation Preview - Evaluation Criteria" section with dynamic criteria table
- Table shows: Criteria | Applicant Level | Baseline Level | Increment | Max Points | Score
- Updates in real-time as applicant and baseline data is entered
- Criteria and max points change automatically when a different position is selected
- Shows total score at the bottom

**How it works:**
1. When user selects a position via the "Select Position" dropdown
2. `setSelectedPosition()` is called (see `index.php` lines 947-1006)
3. `await loadEvaluationCriteria()` is executed (line 1025)
4. Fetches criteria from `api/get_evaluation_criteria.php` based on position group and salary grade
5. Populates `window.currentCriteria` with criteria definitions
6. `calculatePreview()` runs and builds the table using `window.currentCriteria`
7. Each criterion row shows:
   - **Criteria Name** (Education, Training, Experience, Performance, etc.)
   - **Applicant Level** (calculated from input values)
   - **Baseline Level** (auto-loaded from position standards)
   - **Increment** (difference or formula)
   - **Max Points** (position-specific maximum)
   - **Score** (calculated based on scoring method)

**Criteria Scoring Methods:**
- **Increment Scoring** (Education, Training, Experience): `(appLevel - baseLevel) × (maxPoints / maxLevel)`
- **Weighted Rating** (Performance, AOE, AOLD, Potential): `(rating / 5) × maxPoints`
- **Direct Points** (Outstanding Accomplishments): `min(value, maxpoints)`

**Example:**
For **Teacher I** (TEACHING POSITIONS):
- Education: max 10 points
- Training: max 10 points
- Experience: max 10 points
- Performance: max 10 points
- Outstanding Accomplishments: max 35 points
- Application of Education: max 0 points
- Application of L&D: max 0 points
- Potential: max 25 points
- **Total: 100 points**

---

## 2. Comparative Assessment Result (CAR) - Evaluation Criteria Reference

**Location:** `comparative_assessment_results.php` (after position header, before results table)

**Features:**
- New "Evaluation Criteria and Maximum Points" reference section
- Displayed just before the ranked applicants table
- Shows all 8 criteria with:
  - Criteria name
  - Maximum points for this position
  - Scoring method description
  - Total maximum points
- Changes based on position group classification

**How it works:**
1. When viewing CAR for a specific position
2. System determines position group from baseline library
3. Looks up default criteria points for that position group
4. Displays criteria reference table with:
   - All 8 evaluation criteria (a-h)
   - Maximum points per criterion (varies by position group)
   - Scoring method for each criterion
   - Total maximum points achieved by adding all max points

**Criteria Reference by Position Group:**

### TEACHING POSITIONS
| Criteria | Max Points | Method |
|----------|-----------|--------|
| a. Education | 10 | Increment |
| b. Training | 10 | Increment |
| c. Experience | 10 | Increment |
| d. Performance | 10 | Rating/5 × 10 |
| e. Outstanding Accomplishments | 35 | Direct (capped) |
| f. Application of Education | 0 | N/A |
| g. Application of L&D | 0 | N/A |
| h. Potential | 25 | Rating/5 × 25 |
| **TOTAL** | **100** | |

### HIGHER TEACHING POSITIONS
| Criteria | Max Points | Method |
|----------|-----------|--------|
| a. Education | 5 | Increment |
| b. Training | 10 | Increment |
| c. Experience | 15 | Increment |
| d. Performance | 20 | Rating/5 × 20 |
| e. Outstanding Accomplishments | 15 | Direct (capped) |
| f. Application of Education | 10 | Rating/5 × 10 |
| g. Application of L&D | 10 | Rating/5 × 10 |
| h. Potential | 15 | Rating/5 × 15 |
| **TOTAL** | **100** | |

### SCHOOL ADMINISTRATION POSITION
| Criteria | Max Points | Method |
|----------|-----------|--------|
| a. Education | 10 | Increment |
| b. Training | 10 | Increment |
| c. Experience | 10 | Increment |
| d. Performance | 25 | Rating/5 × 25 |
| e. Outstanding Accomplishments | 10 | Direct (capped) |
| f. Application of Education | 10 | Rating/5 × 10 |
| g. Application of L&D | 10 | Rating/5 × 10 |
| h. Potential | 15 | Rating/5 × 15 |
| **TOTAL** | **100** | |

### RELATED TEACHING POSITION
| Criteria | Max Points | Method |
|----------|-----------|--------|
| a. Education | 10 | Increment |
| b. Training | 10 | Increment |
| c. Experience | 10 | Increment |
| d. Performance | 20 | Rating/5 × 20 |
| e. Outstanding Accomplishments | 10 | Direct (capped) |
| f. Application of Education | 10 | Rating/5 × 10 |
| g. Application of L&D | 10 | Rating/5 × 10 |
| h. Potential | 20 | Rating/5 × 20 |
| **TOTAL** | **100** | |

### NON-TEACHING LEVEL I
| Criteria | Max Points | Method |
|----------|-----------|--------|
| a. Education | 5 | Increment |
| b. Training | 5 | Increment |
| c. Experience | 20 | Increment |
| d. Performance | 20 | Rating/5 × 20 |
| e. Outstanding Accomplishments | 10 | Direct (capped) |
| f. Application of Education | 10 | Rating/5 × 10 |
| g. Application of L&D | 10 | Rating/5 × 10 |
| h. Potential | 20 | Rating/5 × 20 |
| **TOTAL** | **100** | |

### NON-TEACHING LEVEL II
| Criteria | Max Points | Method |
|----------|-----------|--------|
| a. Education | 5 | Increment |
| b. Training | 10 | Increment |
| c. Experience | 15 | Increment |
| d. Performance | 20 | Rating/5 × 20 |
| e. Outstanding Accomplishments | 10 | Direct (capped) |
| f. Application of Education | 10 | Rating/5 × 10 |
| g. Application of L&D | 10 | Rating/5 × 10 |
| h. Potential | 20 | Rating/5 × 20 |
| **TOTAL** | **100** | |

---

## 3. Files Modified

### `index.php`
- **Lines 606-630:** Live Calculation Preview HTML with dynamic table
- **Lines 947-1006:** `setSelectedPosition()` function with criteria loading
- **Lines 1104-1136:** `loadEvaluationCriteria()` async function
- **Lines 1140-1310:** `calculatePreview()` function with dynamic criteria support
- **Lines 1303-1304:** Criteria description text update

### `comparative_assessment_results.php`
- **Lines 533-603:** New "Evaluation Criteria and Maximum Points" reference section
- Displays before CAR table when viewing position results
- Automatically detects position group and displays appropriate criteria

### `api/get_evaluation_criteria.php`
- Existing API endpoint (no changes needed)
- Returns position-specific criteria with max points
- Called by `loadEvaluationCriteria()`

### `config/baseline_library.php`
- Existing position database (no changes)
- Provides position group classification for criteria lookup

---

## 4. Testing Workflow

### Test Live Calculation Preview
1. Open form: `http://localhost/DEPEDEvaluationSystemV2/index.php`
2. Select Position Group (e.g., "TEACHING POSITIONS")
3. Select a Position (e.g., "Teacher I")
4. Scroll to "Live Calculation Preview - Evaluation Criteria"
5. **Expected:** 
   - Criteria table shows 8 rows (one per criterion)
   - Max Points column shows position-specific values (e.g., 10, 10, 10, 10, 35, 0, 0, 25)
   - Total shown at bottom (100)
6. Enter applicant and baseline values
7. **Expected:** Score column updates in real-time
8. Change position
9. **Expected:** Criteria and max points change accordingly

### Test CAR Criteria Reference
1. Submit evaluation form
2. View CAR: `http://localhost/DEPEDEvaluationSystemV2/comparative_assessment_results.php`
3. **Expected:**
   - "Evaluation Criteria and Maximum Points" reference table appears
   - Shows criteria for the position being evaluated
   - Total maximum points displayed (should be 100)
4. Change position via dropdown
5. **Expected:** Criteria reference updates to match new position

---

## 5. Real-Time Updates

Both sections update automatically when:
- Position is selected/changed
- Applicant education, training, experience values are entered
- Baseline values are auto-populated
- Input field values change (for preview scoring)

**No manual refresh required** - all updates are JavaScript-driven for instant feedback.

---

## 6. Fallback Behavior

If dynamic criteria API fails:
- Live Preview falls back to hardcoded weights array (still shows criteria)
- CAR uses default criteria array by position group
- System remains functional even if API is unavailable

---

## 7. Next Steps

- [ ] Create database table to store custom criteria per position
- [ ] Add admin interface to edit criteria per position
- [ ] Export CAR with criteria reference to PDF/Word
- [ ] Add criteria tooltips explaining scoring methodology
- [ ] Implement salary grade-specific criteria variations for non-teaching positions

---

## 8. Support

For issues or questions:
- Check browser console (F12) for JavaScript errors
- Verify position is selected before criteria loads
- Ensure `api/get_evaluation_criteria.php` is accessible
- Confirm `config/baseline_library.php` contains position data
