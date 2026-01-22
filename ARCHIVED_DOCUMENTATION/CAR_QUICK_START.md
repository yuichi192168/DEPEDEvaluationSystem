# 🚀 CAR System - Quick Reference Guide

## 📍 Get Started in 3 Minutes

### 1️⃣ Run Database Migration
```
http://yoursite/setup/migrate_car.php
```
Click to create the database table. That's it!

### 2️⃣ View Comparative Assessment Results
```
http://yoursite/comparative_assessment_results.php
```
Select a position from the dropdown to see ranked results.

### 3️⃣ Save Results via API
```php
POST http://yoursite/api/save_comparative_assessment.php
Content-Type: application/json

{
    "position_id": 1,
    "applicant_id": 5,
    "scores": {
        "application_code": "ICT-2026-001",
        "education": 8.50,
        "training": 12.00,
        "experience": 15.00,
        "performance": 13.80,
        "outstanding_accomplishments": 0,
        "application_of_education": 0,
        "application_of_ld": 0,
        "potential": 13.80,
        "total_score": 63.10,
        "background_yes": true,
        "background_no": false,
        "for_appointment": true,
        "for_probation": false
    },
    "remarks": "Qualified candidate",
    "assessment_date": "2026-01-22"
}
```

---

## 📂 File Locations

| File | Purpose | Location |
|------|---------|----------|
| **Display Page** | View & manage results | `comparative_assessment_results.php` |
| **API Endpoint** | Save/retrieve results | `api/save_comparative_assessment.php` |
| **Core Class** | Business logic | `classes/ComparativeAssessmentReport.php` |
| **Migration Tool** | Setup database | `setup/migrate_car.php` |
| **Integration Guide** | Detailed instructions | `CAR_INTEGRATION_GUIDE.md` |
| **Full Summary** | Complete documentation | `CAR_SYSTEM_IMPLEMENTATION_SUMMARY.md` |

---

## ⚡ Quick Code Snippets

### Save Result in PHP
```php
require_once 'classes/ComparativeAssessmentReport.php';

$car = new ComparativeAssessmentReport();

$scores = [
    'application_code' => 'ICT-2026-001',
    'education' => 8.50,
    'training' => 12.00,
    'experience' => 15.00,
    'performance' => 13.80,
    'outstanding_accomplishments' => 0,
    'application_of_education' => 0,
    'application_of_ld' => 0,
    'potential' => 13.80,
    'total_score' => 63.10
];

$car->saveResult(1, 5, $scores, 'Qualified', '2026-01-22');
$car->generateRankings(1);
```

### Get Ranked Results
```php
$car = new ComparativeAssessmentReport();
$results = $car->getResultsByPosition(1);

while ($row = $results->fetch_assoc()) {
    echo "{$row['rank']}. {$row['name']} - Score: {$row['total_score']}\n";
}
```

### Save via jQuery/AJAX
```javascript
const data = {
    position_id: 1,
    applicant_id: 5,
    scores: {
        application_code: 'ICT-2026-001',
        education: 8.50,
        training: 12.00,
        experience: 15.00,
        performance: 13.80,
        outstanding_accomplishments: 0,
        application_of_education: 0,
        application_of_ld: 0,
        potential: 13.80,
        total_score: 63.10,
        background_yes: true,
        background_no: false,
        for_appointment: true,
        for_probation: false
    },
    remarks: 'Qualified',
    assessment_date: '2026-01-22'
};

$.ajax({
    url: 'api/save_comparative_assessment.php',
    type: 'POST',
    contentType: 'application/json',
    data: JSON.stringify(data),
    success: function(response) {
        console.log('Result saved:', response);
        alert('Results saved and ranked successfully!');
    },
    error: function(error) {
        console.error('Error:', error);
    }
});
```

---

## 🎯 Key Features

| Feature | How to Use |
|---------|-----------|
| **View Results** | Go to `comparative_assessment_results.php` → Select position |
| **Save Results** | POST to `api/save_comparative_assessment.php` |
| **Auto Ranking** | Rankings calculated on every save |
| **Print Results** | Click "Print Results" button on display page |
| **Export CSV** | Click "Export to CSV" button on display page |
| **Batch Save** | Use `"batch": true` in API request |
| **Load Sample Data** | Visit `setup/migrate_car.php?sample_data=1` |

---

## 🔑 Score Fields (Individual)

All individual scores should be calculated scores from your evaluation system:

- **education** - Education qualification score
- **training** - Training/professional development score
- **experience** - Work experience score
- **performance** - Job performance score
- **outstanding_accomplishments** - Special achievements score
- **application_of_education** - How well education is applied
- **application_of_ld** - How well learning & development is applied
- **potential** - Future potential score
- **total_score** - Sum of all above scores

---

## 🏆 Ranking Algorithm

- Applicants ranked by `total_score` (highest first)
- Ties automatically get the same rank
- Next rank after tie increments by 1
- Example:
  - Score 85.50 → Rank 1
  - Score 85.50 → Rank 1
  - Score 80.00 → Rank 2 (not 3!)

---

## ✅ Status Flags

| Flag | Meaning | Usage |
|------|---------|-------|
| **background_yes** | Background check passed | `true/false` |
| **background_no** | Background check failed | `true/false` |
| **for_appointment** | Recommended for appointment | `true/false` |
| **for_probation** | Recommended for probation | `true/false` |

---

## 🛠️ API Reference

### POST - Save Single Result
```
Endpoint: api/save_comparative_assessment.php
Method: POST
Content-Type: application/json

Request: {position_id, applicant_id, scores, remarks, assessment_date}
Response: {success, message}
```

### POST - Batch Save
```
Endpoint: api/save_comparative_assessment.php
Method: POST
Content-Type: application/json

Request: {batch: true, position_id, results: [{applicant_id, scores, ...}]}
Response: {success, message, saved_count}
```

### GET - Retrieve Results
```
Endpoint: api/save_comparative_assessment.php?position_id=1
Method: GET

Response: {success, results: []}
```

---

## 🐛 Common Issues & Fixes

| Issue | Fix |
|-------|-----|
| "Table doesn't exist" | Run migration: `setup/migrate_car.php` |
| "Position has no results" | Save some results first via API |
| "Invalid JSON" | Check JSON format; use online JSON validator |
| "Missing required fields" | Include position_id, applicant_id, and scores |
| Ranks seem wrong | Verify total_score values are set correctly |
| API not responding | Check server error logs; verify endpoint path |

---

## 📊 Sample Data

### Individual Scores Example
```json
{
    "application_code": "ICT-2026-001",
    "education": 8.50,
    "training": 12.00,
    "experience": 15.00,
    "performance": 13.80,
    "outstanding_accomplishments": 0.00,
    "application_of_education": 0.00,
    "application_of_ld": 0.00,
    "potential": 13.80,
    "total_score": 63.10,
    "background_yes": true,
    "background_no": false,
    "for_appointment": true,
    "for_probation": false
}
```

### Expected Ranking Output
```
Rank 1: Juan Dela Cruz - ICT-2026-001 - Score: 63.10 ✓
Rank 2: Maria Santos - ICT-2026-003 - Score: 30.83 ✓
Rank 3: Pedro Garcia - ICT-2026-002 - Score: 27.67 ✓
Rank 4: Rosa Lopez - ICT-2026-004 - Score: 23.58 ✗
```

---

## 📋 Integration with Evaluation Form

Add these to your evaluation form:

```html
<!-- Save to CAR Database -->
<input type="hidden" name="save_to_car" value="1">
<input type="hidden" name="assessment_date" value="<?php echo date('Y-m-d'); ?>">

<!-- Status Checkboxes -->
<label>
    <input type="checkbox" name="background_yes" value="1"> Background Passed
</label>
<label>
    <input type="checkbox" name="background_no" value="1"> Background Failed
</label>
<label>
    <input type="checkbox" name="for_appointment" value="1"> For Appointment
</label>
<label>
    <input type="checkbox" name="for_probation" value="1"> For Probation
</label>

<!-- Remarks -->
<textarea name="car_remarks" placeholder="Enter remarks..."></textarea>
```

The `process_evaluation.php` will automatically save to CAR!

---

## 🎓 Learning Path

1. **Beginner** - Just save results and view rankings
   - Use `comparative_assessment_results.php`
   - Use API to save results

2. **Intermediate** - Integrate with existing forms
   - Add hidden fields to evaluation form
   - Use `process_evaluation.php` CAR integration

3. **Advanced** - Use the class directly
   - Create custom interfaces
   - Build batch processing
   - Export to external systems

---

## 📞 Documentation

For more details, see:
- **Full Integration Guide:** `CAR_INTEGRATION_GUIDE.md`
- **Complete Summary:** `CAR_SYSTEM_IMPLEMENTATION_SUMMARY.md`
- **Code Comments:** Read inline comments in each class
- **API Examples:** See API section above

---

## ✨ Features at a Glance

✅ Automatic ranking by score  
✅ Tie handling  
✅ Professional display format  
✅ Print-friendly  
✅ CSV export  
✅ Position filtering  
✅ Status tracking  
✅ Remarks/notes  
✅ RESTful API  
✅ Batch operations  
✅ Database persistence  
✅ Responsive design  

---

## 🎯 Pro Tips

1. **Always set total_score** - Rankings depend on it
2. **Use batch API** for multiple results - More efficient
3. **Call generateRankings()** after each save
4. **Test with sample data** first - Visit `setup/migrate_car.php?sample_data=1`
5. **Use CSV export** for reporting and analysis
6. **Print the page** for official records
7. **Check error logs** if something doesn't work

---

**Ready to use! Start with step 1 above. 🚀**

---

Last Updated: January 22, 2026  
Version: 2.0 with CAR Module
