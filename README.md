# DepEd HRMPSB Evaluation System

A comprehensive Human Resource Merit Promotion and Selection Board (HRMPSB) Evaluation System for the Department of Education (DepEd). This system streamlines the evaluation and ranking of applicants for various DepEd positions.

## 📋 Features

- **Comprehensive Position Library**: 242 DepEd authorized positions across all salary grades (SG 1-25)
- **Multi-Group Evaluation**: Support for three position groups:
  - Group A: Non-Teaching Positions
  - Group B: Supervisory Positions  
  - Group C: Administrative Positions

- **Intelligent Evaluation System**:
  - Individual Evaluation Sheets (IES) - Annex G Format
  - Comparative Assessment Results (CAR) - Annex G-1 Format
  - Consolidated Ranking Reports - Annex G-2 Format
  - Automated scoring based on HRMPSB criteria

- **User-Friendly Interface**:
  - Auto-population of position qualifications
  - Default organization name (City Schools Division of Cabuyao)
  - HRMPSB Chair signature field
  - Quick access to navigation buttons in reports

- **Data Management**:
  - Real-time applicant evaluation
  - Database-backed position and evaluation storage
  - Export-ready HTML reports
  - Applicant ranking and qualification tracking

- **Professional Reporting**:
  - Official DepEd format compliance
  - Print-ready evaluation sheets
  - Navigation buttons for easy report management
  - Favicon branding across all pages

## 🚀 Quick Start

### System Requirements

- PHP 7.4 or higher
- MySQL/MariaDB 5.7 or higher
- Apache Web Server with mod_rewrite
- XAMPP (recommended for local development)

### Installation

1. **Clone or extract the project**:
   ```
   c:\xampp\htdocs\DEPEDEvaluationSystem\
   ```

2. **Configure Database Connection**:
   - Edit `config/database.php`
   - Update database credentials:
     ```php
     const DB_HOST = 'localhost';
     const DB_USER = 'root';
     const DB_PASSWORD = '';
     const DB_NAME = 'deped_evaluation';
     ```

3. **Initialize Database**:
   ```bash
   php database_setup.php
   php sync_baseline_positions.php
   php insert_sample_data.php
   ```

4. **Access the System**:
   ```
   http://localhost/DEPEDEvaluationSystem/
   ```

## 📖 Usage Guide

### Main Evaluation Form (index.php)

1. **Select Position**: Choose from 242 available DepEd positions
2. **Auto-Population**: Job Group and Salary Grade automatically populate
3. **Enter Applicant Information**:
   - Application code
   - Applicant name
   - Contact information
   - Educational qualifications
   - Training/seminars attended
   - Work experience

4. **Evaluation Criteria**: Rate applicants based on HRMPSB criteria:
   - Education (0-100)
   - Training/Seminars (0-100)
   - Work Experience (0-100)
   - Performance Rating (0-100)
   - Potential Level (0-100)

5. **Generate Report**: Choose output format:
   - HTML (view in browser)
   - PDF (requires export feature)

### Viewing Comparative Assessment Results

1. **Navigate to CAR Page** (`comparative_assessment_results.php`)
2. **View All Applicants**: See rankings by position
3. **Filter by Position**: Select specific position for detailed analysis
4. **Export Reports**: Download consolidated rankings

### Generate Consolidated Reports

- **Annex G-1** (`generate_car_g1.php`): Teaching positions ranking
- **Annex G-2** (`generate_car_g2.php`): Administrative positions ranking
- **View CAR** (`view_car.php`): Review and manage saved reports

## 📁 Project Structure

```
DEPEDEvaluationSystem/
├── index.php                          # Main evaluation form
├── comparative_assessment_results.php  # CAR display page
├── generate_car_g1.php                # Generate Annex G-1 reports
├── generate_car_g2.php                # Generate Annex G-2 reports
├── view_car.php                       # View CAR reports
├── process_evaluation.php             # Process evaluation submissions
│
├── api/                               # API endpoints
│   ├── calculate_levels.php
│   ├── get_baseline.php
│   └── save_comparative_assessment.php
│
├── classes/                           # PHP Classes
│   ├── DBConnection.php               # Database connection handler
│   ├── HRMPSBEvaluator.php           # Evaluation scoring logic
│   ├── CARReportGenerator.php         # Annex G-1 report generator
│   ├── CARReportGeneratorG2.php       # Annex G-2 report generator
│   ├── IESReportGenerator.php         # Individual evaluation sheet generator
│   ├── ComparativeAssessmentReport.php
│   └── IESExport.php
│
├── config/                            # Configuration files
│   ├── database.php                   # Database connection settings
│   ├── baseline_library.php           # 242 position definitions & qualifications
│   └── constants.php                  # System constants
│
├── database/                          # Database setup
│   ├── schema.sql                     # Database schema
│   └── sample_data.sql                # Sample data for testing
│
├── images/                            # Assets & Favicon
│   ├── favicon.ico
│   ├── favicon-16x16.png
│   ├── favicon-32x32.png
│   ├── apple-touch-icon.png
│   └── site.webmanifest
│
├── DOCUMENTATION/                     # Technical documentation
└── README.md                          # This file
```

## ⚙️ Configuration

### Default Settings

**Schools Division Office**: City Schools Division of Cabuyao

**HRMPSB Chair**: RANDY D. PUNZALAN, CESO VI

### Position Groups

- **Group A** (Non-Teaching): Accountants, Bookkeepers, IT Specialists, etc.
- **Group B** (Supervisory): Head Teachers, Master Teachers, Lead Teachers
- **Group C** (Administration): School Principals, Directors, Supervisors

### Salary Grades

Positions span from SG 1 (lowest) to SG 25 (highest) with appropriate distribution:
- **SG 1-5**: Non-teaching support positions
- **SG 6-11**: Teaching and supervisory roles
- **SG 12-25**: Leadership and administrative positions

## 🔐 Security Notes

- Always use HTTPS in production
- Restrict database access to localhost only
- Change default admin credentials
- Regularly backup evaluation data
- Validate all user inputs on both client and server side

## 🐛 Troubleshooting

### Favicon Not Displaying
- Favicon paths use absolute URLs: `/DEPEDEvaluationSystem/images/favicon.ico`
- Clear browser cache (Ctrl+Shift+Delete)
- Verify images folder contains favicon files

### Database Connection Errors
- Check `config/database.php` credentials
- Ensure MySQL service is running
- Verify database exists: `deped_evaluation`

### Position Data Not Loading
- Run `php sync_baseline_positions.php` to sync positions
- Verify `config/baseline_library.php` contains position data
- Check database positions table is populated

### Reports Not Generating
- Verify evaluation data exists for the position
- Check PHP error logs for database query errors
- Ensure output buffering is enabled in php.ini

## 📊 Evaluation Weights

Position Group A (Non-Teaching):
- Education: 30%
- Training/Seminars: 20%
- Work Experience: 40%
- Performance: 5%
- Potential: 5%

Position Groups B & C (Teaching/Administrative):
- Education: 25%
- Training/Seminars: 15%
- Work Experience: 35%
- Performance: 15%
- Potential: 10%

## 🔄 Recent Enhancements

### Version 2.1 (Current)
- ✅ Added complete DepEd position library (242 positions)
- ✅ Auto-population of Job Group and Salary Grade fields
- ✅ Default Schools Division Office configuration
- ✅ Back and Home navigation buttons on all reports
- ✅ Favicon branding across all pages and reports
- ✅ Baseline qualification system for all positions

## 📝 Database Schema

### Key Tables

**positions** - Master position list
- id, position_name, position_group, salary_grade, item_number

**applicants** - Applicant information
- id, name, application_code, contact_number, email

**evaluations** - Evaluation records
- id, applicant_id, position_id, total_score, created_date

**evaluation_details** - Detailed scoring
- id, evaluation_id, criterion, score, final_score

**applicant_qualifications** - Qualification data
- id, applicant_id, education_level, performance_rating, potential_level

## 🤝 Contributing

For bug reports or feature requests, please document:
1. Current behavior
2. Expected behavior
3. Steps to reproduce
4. System environment details

## 📞 Support

For technical support or questions:
- Check DOCUMENTATION folder for detailed guides
- Review database schema in `database/schema.sql`
- Consult `ARCHIVED_DOCUMENTATION/` for implementation details

## 📜 License & Usage

This system is developed for the Department of Education's HRMPSB evaluation processes. Usage is restricted to authorized DepEd personnel and evaluation boards.

## 🎯 Key Performance Metrics

- **242 Positions**: Complete DepEd position library
- **25 Salary Grades**: SG 1-25 coverage
- **3 Position Groups**: Non-Teaching, Supervisory, Administrative
- **100% Web-Based**: No installation required beyond XAMPP
- **Real-Time Scoring**: Instant evaluation calculations

## 📅 Last Updated

January 28, 2026

---

**Version 2.1** | DepEd HRMPSB Evaluation System | All Rights Reserved
