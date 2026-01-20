# DepEd HRMPSB Evaluation System

**Comparative Assessment System Based on DepEd Order No. 007, s. 2023**

## Overview

This system automatically computes the Comparative Assessment Score of applicants using the **Increment Method** as defined in DepEd Order No. 007, s. 2023. It generates Individual Evaluation Sheets (IES) in Annex G format.

## Features

- ✅ Automatic Level Conversion (Education, Training, Experience)
- ✅ Increment Calculation (Applicant Level - Baseline Level)
- ✅ Range Rubric Point Conversion (Non-linear scoring)
- ✅ Position Group Weight Allocation (Groups A, B, C)
- ✅ Individual Evaluation Sheet (IES) Generation (Annex G Format)
- ✅ Baseline Requirements Validation

## System Requirements

- PHP 7.4 or higher
- Web server (Apache/Nginx) or PHP built-in server
- MySQL/MariaDB (optional, for database storage)

## Installation

1. Clone or download this repository to your web server directory (e.g., `htdocs` or `www`)

2. Ensure PHP is installed and configured

3. (Optional) Configure database settings in `config/database.php`

4. Access the system via web browser:
   ```
   http://localhost/DEPEDEvaluationSystem/
   ```

## Usage

### Web Interface

1. Open `index.php` in your web browser
2. Fill in the required information:
   - Position Information (Position Applied For, Position Group, Applicant Name)
   - Applicant Qualifications
   - Minimum Qualification Standards (Baseline)
3. Click "Generate Evaluation Report"
4. View the generated IES (Annex G format)

### Programmatic Usage

```php
require_once 'classes/HRMPSBEvaluator.php';
require_once 'classes/IESReportGenerator.php';

// Initialize evaluator with position group (A, B, or C)
$evaluator = new HRMPSBEvaluator('A');

// Prepare applicant data
$applicantData = [
    'name' => 'Juan Dela Cruz',
    'position' => 'Information and Communications Technology',
    'education' => [
        'degree' => 'Bachelor',
        'masters_units' => 18,
        'doctoral_units' => 0
    ],
    'training' => 40, // hours
    'experience' => 48, // months
    // ... other criteria
];

// Prepare baseline data
$baselineData = [
    'education' => [
        'degree' => 'Bachelor',
        'masters_units' => 0,
        'doctoral_units' => 0
    ],
    'training' => 0,
    'experience' => 0,
    // ... other criteria
];

// Perform evaluation
$evaluation = $evaluator->evaluateApplicant($applicantData, $baselineData);

// Generate IES Report
$reportGenerator = new IESReportGenerator();
$iesHTML = $reportGenerator->generateIES($evaluation);
$iesText = $reportGenerator->generateTextIES($evaluation);
```

## Testing

Run the sample test case:

```bash
php test_sample.php
```

This will generate a sample evaluation based on the example from DepEd Order No. 007, s. 2023.

## Position Groups

### Group A: Non-Teaching Level 1 (General)
- Example: ICT
- Education: 5%, Training: 5%, Experience: 20%, Performance: 20%, Outstanding Accomplishments: 10%, Application of Education: 10%, Application of L&D: 10%, Potential: 20%

### Group B: Non-Teaching Level 2
- Example: Admin Officer IV, SEPS
- Education: 5%, Training: 10%, Experience: 15%, Performance: 20%, Outstanding Accomplishments: 10%, Application of Education: 10%, Application of L&D: 10%, Potential: 20%

### Group C: School Administration
- Example: Principal
- Education: 10%, Training: 10%, Experience: 10%, Performance: 25%, Outstanding Accomplishments: 10%, Application of Education: 10%, Application of L&D: 10%, Potential: 15%

## Level Conversion Rules

### Education Levels
- Level 6: Bachelor's Degree
- Levels 7-20: Bachelor's with Master's units (increase by 1 per 3 units)
- Level 21: Master's Degree
- Levels 22-30: Master's with Doctoral units (increase by 1 per 3 units)
- Level 31: Doctorate Degree

### Training Levels
- Level 1: 0 to less than 8 hours
- Level 2: 8 to less than 16 hours
- Level 3: 16 to less than 24 hours
- Level 4: 24 to less than 32 hours
- Increase by 1 for every additional 8 hours

### Experience Levels
- Level 1: 0 to less than 6 months
- Level 2: 6 months to less than 1 year
- Level 3: 1 year to less than 1.5 years
- Level 4: 1.5 years to less than 2 years
- Level 5: 2 years to less than 2.5 years
- Increase by 1 for every additional 6 months

## Range Rubric System

The system uses a non-linear range rubric for converting increments to points:

**For 10-point weight:**
- 10+ increments = 10 points
- 8-9 increments = 8 points
- 6-7 increments = 6 points
- 4-5 increments = 4 points
- 2-3 increments = 2 points
- 0-1 increments = 0 points

**Scaling:**
- 20-point weight: multiply by 2
- 5-point weight: divide by 2
- 15-point weight: multiply by 1.5
- 25-point weight: multiply by 2.5

## File Structure

```
DEPEDEvaluationSystem/
├── classes/
│   ├── HRMPSBEvaluator.php      # Core evaluation logic
│   └── IESReportGenerator.php    # IES report generation
├── config/
│   └── database.php              # Database configuration
├── index.php                     # Main input form
├── process_evaluation.php        # Evaluation processor
├── test_sample.php              # Sample test case
└── README.md                    # This file
```

## Important Notes

- The system strictly follows DepEd Order No. 007, s. 2023
- Increments are calculated as: Applicant Level - Baseline Level
- Negative increments are not allowed (minimum 0)
- The Range Rubric System is non-linear (5 increments ≠ 5 points)
- All calculations are based on increments, not raw credentials

## Compliance

This system is designed to comply with:
- **DepEd Order No. 007, s. 2023**
- Increment Method for Comparative Assessment
- Official Range Rubric System
- Annex G Individual Evaluation Sheet Format

## Support

For issues or questions regarding DepEd Order No. 007, s. 2023, please refer to the official DepEd documentation.

## License

This system is developed for use by the Department of Education, Philippines.

