# Comparative Assessment Results (CAR) System

## Overview

The Comparative Assessment Results (CAR) system has been fully implemented to manage, rank, and display applicants' assessment scores in a professional format matching DepEd HRMPSB standards.

## What's New

### 1. **Database Schema Updates**

A new table `comparative_assessment_results` has been added to store all CAR data:

```sql
CREATE TABLE comparative_assessment_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position_id INT NOT NULL,
    applicant_id INT NOT NULL,
    application_code VARCHAR(100),
    education_score DECIMAL(10,2),
    training_score DECIMAL(10,2),
    experience_score DECIMAL(10,2),
    performance_score DECIMAL(10,2),
    outstanding_accomplishments_score DECIMAL(10,2),
    application_of_education_score DECIMAL(10,2),
    application_of_ld_score DECIMAL(10,2),
    potential_score DECIMAL(10,2),
    total_score DECIMAL(10,2),
    rank INT,
    remarks TEXT,
    background_yes BOOLEAN,
    background_no BOOLEAN,
    for_appointment BOOLEAN,
    for_probation BOOLEAN,
    assessment_date DATE,
    ...
);
```

**To update your database, run the schema.sql migration or execute the new table creation query.**

### 2. **New Files Created**

#### A. `classes/ComparativeAssessmentReport.php`
Core class for managing CAR data with these key methods:

- `saveResult($positionId, $applicantId, $scores, $remarks, $assessmentDate)` - Save individual assessment results
- `generateRankings($positionId)` - Calculate and update rankings based on total scores
- `getResultsByPosition($positionId)` - Retrieve all results for a position with rankings
- `getResultById($resultId)` - Get specific result details
- `exportResults($positionId)` - Export results as array for external use
- `deleteResult($resultId)` - Remove a result
- `getPositionsWithResults()` - List all positions with their result counts

#### B. `api/save_comparative_assessment.php`
RESTful API endpoint for saving CAR results:

**POST - Single Result:**
```json
{
    "position_id": 1,
    "applicant_id": 5,
    "scores": {
        "application_code": "POS-2026-001",
        "education": 8.50,
        "training": 12.00,
        "experience": 15.00,
        "performance": 13.80,
        "outstanding_accomplishments": 0.00,
        "application_of_education": 0.00,
        "application_of_ld": 0.00,
        "potential": 13.80,
        "total_score": 63.10,
        "background_yes": false,
        "background_no": true,
        "for_appointment": true,
        "for_probation": false
    },
    "remarks": "Qualified candidate",
    "assessment_date": "2026-01-22"
}
```

**POST - Batch Results:**
```json
{
    "batch": true,
    "position_id": 1,
    "results": [
        {
            "applicant_id": 5,
            "scores": {...},
            "remarks": "...",
            "assessment_date": "2026-01-22"
        },
        {
            "applicant_id": 6,
            "scores": {...},
            "remarks": "...",
            "assessment_date": "2026-01-22"
        }
    ]
}
```

**GET - Retrieve Results:**
```
/api/save_comparative_assessment.php?position_id=1
```

#### C. `comparative_assessment_results.php`
Professional display page showing:

- Position selector dropdown
- Position details (name, salary grade, item number)
- Formatted comparison table with:
  - Rank
  - Applicant Name
  - Application Code
  - Individual scores (Education, Training, Experience, Performance, etc.)
  - Total Score
  - Remarks
  - Background check status
  - Appointment recommendation
  - Probation status
- Print functionality
- CSV export option

## Integration Guide

### Step 1: Update Database

Run the updated schema file:
```bash
mysql deped_evaluation < database/schema.sql
```

Or execute the new table creation query in your database manager.

### Step 2: Modify Your Evaluation Form

Add these hidden fields or inputs to your evaluation form to enable CAR saving:

```html
<!-- Add to your form -->
<input type="hidden" name="save_to_car" value="1">
<input type="hidden" name="position_id" value="<?php echo $positionId; ?>">
<input type="hidden" name="applicant_id" value="<?php echo $applicantId; ?>">

<!-- Optional CAR fields -->
<input type="hidden" name="car_remarks" value="">
<input type="hidden" name="assessment_date" value="<?php echo date('Y-m-d'); ?>">
<input type="hidden" name="background_yes" value="0">
<input type="hidden" name="background_no" value="1">
<input type="hidden" name="for_appointment" value="1">
<input type="hidden" name="for_probation" value="0">
```

### Step 3: Use the CAR Display Page

Navigate to: `http://yoursite/comparative_assessment_results.php`

Or create a link in your navigation:
```html
<a href="comparative_assessment_results.php">View Comparative Assessment Results</a>
```

## Usage Examples

### Example 1: Save Single Result via API

```php
$data = [
    'position_id' => 1,
    'applicant_id' => 5,
    'scores' => [
        'application_code' => 'ICT-2026-001',
        'education' => 8.50,
        'training' => 12.00,
        'experience' => 15.00,
        'performance' => 13.80,
        'outstanding_accomplishments' => 0,
        'application_of_education' => 0,
        'application_of_ld' => 0,
        'potential' => 13.80,
        'total_score' => 63.10,
        'background_yes' => false,
        'background_no' => true,
        'for_appointment' => true,
        'for_probation' => false
    ],
    'remarks' => 'Highly qualified',
    'assessment_date' => '2026-01-22'
];

$ch = curl_init('http://yoursite/api/save_comparative_assessment.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
if ($result['success']) {
    echo "Results saved and ranked successfully!";
}
```

### Example 2: Generate Rankings

```php
require_once 'classes/ComparativeAssessmentReport.php';

$car = new ComparativeAssessmentReport();

// Generate rankings for position ID 1
$car->generateRankings(1);

// Retrieve ranked results
$results = $car->getResultsByPosition(1);
while ($row = $results->fetch_assoc()) {
    echo "Rank {$row['rank']}: {$row['name']} - Score: {$row['total_score']}\n";
}
```

### Example 3: Batch Save Multiple Results

```php
$batchData = [
    'batch' => true,
    'position_id' => 1,
    'results' => []
];

// Assuming you have evaluation data for multiple applicants
foreach ($evaluations as $evaluation) {
    $batchData['results'][] = [
        'applicant_id' => $evaluation['applicant_id'],
        'scores' => $evaluation['scores'],
        'remarks' => $evaluation['remarks'],
        'assessment_date' => date('Y-m-d')
    ];
}

// Send to API
$ch = curl_init('http://yoursite/api/save_comparative_assessment.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($batchData));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
curl_close($ch);
```

## Features

✅ **Automatic Ranking** - Applicants ranked by total score with tie-handling
✅ **Flexible Storage** - Save individual or batch results
✅ **Professional Display** - Official DepEd HRMPSB format
✅ **Print Support** - Print-friendly layout
✅ **CSV Export** - Easy data export for reporting
✅ **Position Management** - Filter results by position
✅ **Detailed Records** - Track background checks, appointment recommendations, probation status
✅ **RESTful API** - Easy integration with external systems

## Ranking Algorithm

The system uses a "dense ranking" approach:
- Applicants with the same total_score receive the same rank
- The next rank after ties increments by 1 (not by number of ties)
- Example: If two applicants are tied for 1st, the next is 2nd (not 3rd)

## Display Format

The CAR display page matches the official DepEd HRMPSB format with:
- Position information at the top
- Comprehensive ranking table
- All evaluation criteria scores
- Status indicators (Background, For Appointment, For Probation)
- Remarks column for notes
- Print and export options

## API Response Examples

**Successful Save:**
```json
{
    "success": true,
    "message": "Result saved successfully"
}
```

**Batch Save Response:**
```json
{
    "success": true,
    "message": "Saved 4 results successfully",
    "saved_count": 4
}
```

**Get Results Response:**
```json
{
    "success": true,
    "results": [
        {
            "id": 1,
            "applicant_id": 5,
            "name": "Juan Dela Cruz",
            "application_code": "ICT-2026-001",
            "education_score": "8.50",
            "training_score": "12.00",
            "total_score": "63.10",
            "rank": 1,
            ...
        }
    ]
}
```

## Error Handling

All operations include error handling:
- Database connection errors are logged
- Invalid input is validated before processing
- File-not-found issues are gracefully handled
- JSON parse errors return clear error messages

## Next Steps

1. ✅ Update your database with the new schema
2. ✅ Visit `comparative_assessment_results.php` to view the new interface
3. ✅ Update your evaluation form to include CAR fields
4. ✅ Test saving a few results
5. ✅ Generate rankings and verify the display
6. ✅ Use CSV export for reporting

## Support

For issues or questions:
1. Check the error logs in your server's error_log
2. Verify database permissions and table creation
3. Ensure all class files are properly included
4. Test the API endpoint with sample data

---

**Last Updated:** January 22, 2026
**System Version:** v2.0 with CAR Module
