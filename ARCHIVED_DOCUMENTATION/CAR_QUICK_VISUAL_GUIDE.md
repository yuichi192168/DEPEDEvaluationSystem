# Comparative Assessment Results - Quick Visual Guide

## 🎯 System Overview

```
┌────────────────────────────────────────────────────────────────┐
│                  DepEd HRMPSB Evaluation System                │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│  ┌──────────────────────┐         ┌──────────────────────┐   │
│  │  Evaluation Form     │         │  Comparative Results │   │
│  │  (index.php)         │◄────────│  (comparative_ass...) │   │
│  │                      │  Button │                      │   │
│  │  • Position Select   │         │  • Position Select  │   │
│  │  • Applicant Details │         │  • Ranked Table     │   │
│  │  • Scores Entry      │         │  • Signatures       │   │
│  │                      │         │  • Print/Export     │   │
│  │  [Generate Report]   │         │                      │   │
│  │  [Reset Form]        │         │  [Back to Form]     │   │
│  │  [View CAR Results]◄─┼─────────┘                      │   │
│  │                      │                                │   │
│  └──────────────────────┘                                │   │
│                                                                │
└────────────────────────────────────────────────────────────────┘
```

---

## 📄 Official Template Layout

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃                  COMPARATIVE ASSESSMENT RESULT               ┃
┃                          Annex I                             ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛

Position Information:                    Plantilla Item Number: n/a
─────────────────────────────────        Date of Final Deliberation:
Position: [Position Name]               [Current Date]
 under Contract of Service

Office/Bureau/Service/Unit where 
the vacancy exists: [Unit Name]


┌─────────────────────────────────────────────────────────────────┐
│                    ASSESSMENT RESULTS                           │
├──┬──────────────────┬────────┬────┬─────┬────┬──────┬─────┬────┤
│Rk│ NAME             │App.Code│Edu │Trng │Exp │Perf  │...  │Total
├──┼──────────────────┼────────┼────┼─────┼────┼──────┼─────┼────┤
│1 │ Applicant Name 1 │CODE001 │8.0 │5.0  │3.0 │15.98 │...  │38.78
├──┼──────────────────┼────────┼────┼─────┼────┼──────┼─────┼────┤
│2 │ Applicant Name 2 │CODE002 │0.0 │5.0  │8.0 │0.00  │...  │30.83
├──┼──────────────────┼────────┼────┼─────┼────┼──────┼─────┼────┤
│3 │ Applicant Name 3 │CODE003 │0.0 │0.0  │8.0 │0.00  │...  │27.67
└──┴──────────────────┴────────┴────┴─────┴────┴──────┴─────┴────┘


Prepared by the HRMPSB


_________________________          _________________________
RANDY D. PUNZALAN, CESO VI        CHRISTOPHER R. DIAZ, CESO V
Assistant Schools Division         Schools Division
Superintendent                     Superintendent
Chairperson                        Appointing Authority


_________________________          _________________________
JOSE CHARLIE S. ALOQUIN, PhD      ATTY. JERICA CLARA S.
In-Charge, SGOD Chief              MACHADO - DELA PEÑA
Member                             Attorney III
                                   Member


_________________________          _________________________
AUBREY ANNE A. TABLAN              NOEL G. SEQUITO, EdD
Administrative Assistant III        Administrative Officer V
Rep., National Employees' Union    Member
(1st Level)


_________________________
JHOANNA M. MANZANERO
Administrative Officer IV
Member
```

---

## 🎨 Design & Color Scheme

### Primary Colors
```
┌────────────────────────────────────┐
│ DepEd Red:      █ #E04040          │  Used for: Buttons, Headers, Accents
│ Text Gray:      █ #333333          │  Used for: Main text content
│ Light Gray:     █ #e8e8e8          │  Used for: Table headers
│ Border Gray:    █ #999999          │  Used for: Grid borders
│ Background:     █ Gradient 40→60   │  Used for: Page background
└────────────────────────────────────┘
```

### Font Stack
```
Primary:    Calibri (modern, professional)
Fallback 1: Segoe UI (Windows native)
Fallback 2: sans-serif (system default)
```

### Responsive Design
```
Desktop/Print: 1800px max-width
Tablet:        Adjust for 768px-1024px
Mobile:        Stack vertically
Print Media:   A4 Landscape, 10mm margins
```

---

## 🚀 Navigation Flow

### From Evaluation Form
```
1. Fill evaluation details
   ↓
2. Click "View Comparative Assessment Results"
   ↓
3. Navigates to comparative_assessment_results.php
   ↓
4. Select position from dropdown
   ↓
5. View ranked applicants with scores
```

### From CAR Page
```
Current Position: Position A (5 applicants)
   ├─ View Results [Dropdown ↓]
   │  ├─ Position A (5 applicants) ← Current
   │  ├─ Position B (3 applicants)
   │  └─ Position C (0 applicants)
   │
   └─ Action Buttons
      ├─ 🔄 Refresh (reload data)
      ├─ 🖨️ Print (open print dialog)
      └─ 📊 Export CSV (download file)
```

---

## 📊 Table Structure

### Column Details

| # | Column | Format | Example | Notes |
|---|--------|--------|---------|-------|
| 1 | Rank | Integer | 1, 2, 3 | Based on total score |
| 2 | NAME | Text | John Doe | Applicant name |
| 3 | APPLICATION CODE | Text | CoS-ICT-2026-001 | Unique identifier |
| 4 | Education | Decimal | 8.00 | Score from education level |
| 5 | Training | Decimal | 5.00 | Score from training hours |
| 6 | Experience | Decimal | 3.00 | Score from experience months |
| 7 | Performance | Decimal | 15.98 | Rating-based score |
| 8 | Outstanding Accomplishments | Decimal | 0.00 | Direct points |
| 9 | Application of Education | Decimal | 0.00 | Rating-based score |
| 10 | Application of L&D | Decimal | 0.00 | Rating-based score |
| 11 | Potential | Decimal | 0.00 | Rating-based score |
| 12 | Total | Decimal | 38.78 | Sum of all scores (highlighted) |
| 13 | Remarks | Text | (optional) | Admin notes |
| 14 | For Background Yes | Checkbox | ✓ or - | Background check result |
| 15 | For Background No | Checkbox | ✓ or - | Background check result |
| 16 | For Appointment | Checkbox | ✓ or - | Recommended for appointment |
| 17 | For Probation | Checkbox | ✓ or - | Probation period applicable |

---

## 🖨️ Print Output

### Print Dialog Settings
```
Paper Size:     A4
Orientation:    Landscape
Margins:        10mm all sides
Header/Footer:  Off (or custom)
Background:     On (for grid visibility)
```

### What Prints
✅ Title and Annex I
✅ Position header with details
✅ Ranked assessment table
✅ Signature section (blank lines for signing)
✅ All 17 data columns

### What Doesn't Print
❌ Navigation buttons
❌ Position selector
❌ Action buttons
❌ Page borders/shadows

---

## 📥 CSV Export Format

### File Details
```
Filename:    CAR-YYYY-MM-DD.csv
Format:      Comma-separated values
Encoding:    UTF-8 with BOM
Quote:       " (escaped as "")
Delimiter:   , (comma)
```

### Sample Content
```csv
"Rank","NAME","APPLICATION CODE","Education","Training","Experience",...
"1","John Doe","CoS-ICT-2026-001","8.00","5.00","3.00",...
"2","Jane Smith","CoS-ICT-2026-002","0.00","5.00","8.00",...
```

---

## 🔧 Technical Details

### Database Connection
```php
// ComparativeAssessmentReport.php
- Constructor: Creates DBConnection
- getConnection(): Returns fresh mysqli reference
- All methods: Use $conn = $this->getConnection()
```

### Data Sources
```
positions table
    ↓
applicants table
    ↓
comparative_assessment_results table
```

### Key Methods
```php
$car = new ComparativeAssessmentReport();

// Get list of positions with result counts
$positions = $car->getPositionsWithResults();

// Get all results for a specific position
$results = $car->getResultsByPosition($positionId);

// Save a single result
$car->saveResult($data);

// Generate rankings
$car->generateRankings($positionId);
```

---

## 🐛 Troubleshooting

### Issue: Page doesn't load
**Solution:** Check database connection in ComparativeAssessmentReport.php
```
Error: "mysqli object is already closed"
→ Verify getConnection() method is called
```

### Issue: No positions shown
**Solution:** Ensure comparative_assessment_results table has data
```
Run: SELECT * FROM comparative_assessment_results;
Check: Records exist in database
```

### Issue: Print looks wrong
**Solution:** Check print CSS media rules
```
@media print {
    .navigation,
    .selector-section,
    .action-buttons { display: none; }
}
```

### Issue: CSV export empty
**Solution:** Verify table has results
```
- Select a position first
- Wait for table to load
- Then click Export CSV button
```

---

## 📈 Performance Tips

### For Large Datasets
1. Use pagination (if adding >100 results)
2. Optimize database indexes on:
   - position_id
   - applicant_id
   - total_score

### For Print Quality
1. Print to PDF (not directly to printer)
2. Use 100% zoom in print dialog
3. Enable background graphics

### For CSV Export
1. Maximum rows: ~10,000 (Excel limitation)
2. If larger: Split by position
3. Use column filtering in Excel

---

## ✅ Verification Checklist

Before using in production:

- [ ] Navigation button visible in index.php
- [ ] CAR page loads successfully
- [ ] Position dropdown populates
- [ ] Assessment table displays with data
- [ ] Signatures section renders properly
- [ ] Print preview shows correct layout
- [ ] CSV export creates valid file
- [ ] All fonts display consistently
- [ ] Colors match DepEd theme
- [ ] Back button returns to form

---

## 📞 Quick Reference

### File Locations
```
/index.php                              - Main evaluation form
/comparative_assessment_results.php    - CAR display page
/classes/ComparativeAssessmentReport.php - Database class
```

### Key Functions
```
changePosition()  - Switch selected position
refreshResults()  - Reload data
exportToCSV()     - Download table as CSV
window.print()    - Open print dialog
```

### CSS Classes
```
.results-table       - Main data table
.car-title           - Page title section
.position-header     - Position info section
.signatures          - Signature section
.nav-btn             - Navigation buttons
.btn-action          - Action buttons
```

---

**System Ready for Production Use** ✅
