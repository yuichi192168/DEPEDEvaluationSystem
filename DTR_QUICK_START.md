# DTR Generator - Quick Start Guide

## 🚀 5-Minute Setup

### Option 1: Web Interface (Easiest)

1. **Open in Browser:**
   ```
   http://localhost/DEPEDEvaluationSystem/dtr_generator_ui.php
   ```

2. **Generate Sample Data (First Time Only):**
   - Click "📊 Generate Sample Data" button
   - Creates `OSDS-January-2026-SAMPLE.xlsx`

3. **Generate DTRs:**
   - Select source file: `OSDS-January-2026.xlsx`
   - Select template: `dtr-jan-2026.xlsx`
   - Click "🚀 Generate DTR Files"
   - Check `output/` folder for generated files

### Option 2: Command Line (For Automation)

1. **Generate Sample Data:**
   ```bash
   cd C:\xampp\htdocs\DEPEDEvaluationSystem
   php generate_dtr.php --sample
   ```

2. **Generate DTRs:**
   ```bash
   php generate_dtr.php
   ```

3. **Check Results:**
   ```bash
   dir output\
   ```

---

## 📁 File Requirements

### Source File (OSDS-January-2026.xlsx)

Must have these columns (in order A-F):

| A | B | C | D | E | F |
|---|---|---|---|---|---|
| Name | Date | Timetable | Clock In | Clock Out | Department |

**Example Data:**
```
BUENA G. VILLAN | 1/1/2026 | Morning | 08:00 | 12:00 | CSD
BUENA G. VILLAN | 1/1/2026 | Afternoon | 13:00 | 17:00 | CSD
JOSE M. SANTOS | 1/2/2026 | Morning | 07:56 | 12:05 | CSD
```

### Template File (dtr-jan-2026.xlsx)

Already included! Has DepEd-standard format with:
- Employee name cell at A13
- Date rows from 19 to 49 (days 1-31)
- Time columns: Morning Arrival/Departure, Afternoon Arrival/Departure

---

## ✅ Expected Output

After running the generator:

```
output/
├── DTR_Generated_BUENA_G._VILLAN.xlsx
├── DTR_Generated_JOSE_M._SANTOS.xlsx
└── (one file per employee)
```

Each file contains:
- Employee name
- All days of the month (1-31)
- Clock in/out times properly mapped
- All original template formatting preserved

---

## 🔧 Troubleshooting

| Problem | Solution |
|---------|----------|
| "Source file not found" | Generate sample with `--sample` flag or check file name |
| No data in generated DTRs | Verify column headers match: Name, Date, Timetable, Clock In, Clock Out, Department |
| Times not showing as times | Ensure times are in HH:mm format (08:00, not 8:00) |
| Script runs slow | Normal for large files; be patient or increase PHP memory in php.ini |

---

## 📊 Supported Formats

### Dates
- `1/5/2026` (recommended)
- `05/01/2026`
- `2026-01-05`
- Excel serial numbers

### Times
- `08:00` (recommended)
- `8:00:00`
- `8:00 AM`
- Excel decimal times

---

## 💡 Tips

1. **Test First:** Use "Generate Sample Data" before using real data
2. **Check Template:** Verify dtr-jan-2026.xlsx looks correct
3. **Column Order:** Source file MUST have columns in order: Name, Date, Timetable, Clock In, Clock Out, Department
4. **Employee Names:** Use FULL NAMES; script groups by exact name match
5. **Backup:** Save your original OSDS file before generating (you'll need it again next month)

---

## 📞 Need Help?

1. Check **DTR_GENERATOR_DOCUMENTATION.md** for detailed information
2. Run `php generate_dtr.php --sample` to test with sample data
3. Verify all file formats match specifications above
4. Check generated files in `output/` directory

---

**✨ That's it! You're ready to generate DTRs!**
