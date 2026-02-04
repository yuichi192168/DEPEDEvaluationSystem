<?php
/**
 * DepEd Human Resource Merit Promotion and Selection Board(HRMPSB) Evaluation System
 * Enhanced Version with Baseline Library and Level Pickers
 */
session_start();

require_once 'includes/banners.php';
require_once 'config/baseline_library.php';
require_once 'config/evaluation_criteria.php';

$positions = getAllPositions();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DepEd HRMPSB Evaluation System</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="manifest" href="images/site.webmanifest">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #E04040 0%, #E06060 100%);
        padding: 20px;
        min-height: 100vh;
    }
    
    .container {
        max-width: 1400px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 10px 40px rgba(224, 64, 64, 0.25);
        padding: 30px;
    }
    
    h1 {
        color: #333333;
        text-align: center;
        margin-bottom: 10px;
        font-size: 28px;
    }
    
    .subtitle {
        text-align: center;
        color: #666666;
        margin-bottom: 30px;
        font-size: 14px;
    }
    
    .form-section {
        margin-bottom: 30px;
        padding: 20px;
        background: #E0E0E0;
        border-radius: 8px;
        border-left: 4px solid #E04040;
    }
    
    .form-section h2 {
        color: #E04040;
        margin-bottom: 20px;
        font-size: 20px;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 15px;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-group.full-width {
        grid-column: 1 / -1;
    }
    
    label {
        display: block;
        margin-bottom: 5px;
        color: #333333;
        font-weight: 600;
        font-size: 14px;
    }
    
    input[type="text"],
    input[type="number"],
    select {
        width: 100%;
        padding: 10px;
        border: 2px solid #C0A0A0;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s;
    }
    
    input:focus,
    select:focus {
        outline: none;
        border-color: #E04040;
    }
    
    .help-text {
        font-size: 12px;
        color: #666666;
        margin-top: 5px;
        font-style: italic;
    }
    
    .level-display {
        display: inline-block;
        padding: 4px 8px;
        background: #E06060;
        color: #ffffff;
        border-radius: 4px;
        font-weight: bold;
        margin-left: 8px;
        font-size: 12px;
    }
    
    .btn-group {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 30px;
    }
    
    button {
        padding: 12px 30px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #E04040 0%, #E06060 100%);
        color: #ffffff;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(224, 64, 64, 0.4);
    }
    
    .btn-secondary {
        background: #C0A0A0;
        color: #ffffff;
    }
    
    .btn-secondary:hover {
        background: #A08080;
    }
    
    /* Enhanced styling for View CAR button */
    a.btn-primary {
        padding: 12px 30px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        background: linear-gradient(135deg, #E04040 0%, #E06060 100%);
        color: #ffffff;
        min-width: 150px;
    }
    
    a.btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(224, 64, 64, 0.4);
    }
    
    .info-box {
        background: #E0C0C0;
        border-left: 4px solid #E04040;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
    }
    
    .info-box strong {
        color: #E04040;
    }
    
    .live-preview {
        margin-top: 30px;
        padding: 20px;
        background: #ffffff;
        border: 2px solid #E04040;
        border-radius: 8px;
        display: none;
    }
    
    .live-preview.active {
        display: block;
    }
    
    .live-preview h3 {
        color: #E04040;
        margin-bottom: 15px;
    }
    
    .preview-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    
    .preview-table th,
    .preview-table td {
        padding: 8px;
        border: 1px solid #C0C0A0;
        text-align: left;
        font-size: 12px;
    }
    
    .preview-table th {
        background: #E0E0E0;
        font-weight: bold;
    }
    
    .baseline-info {
        background: #E0C0A0;
        border-left: 4px solid #E06060;
        padding: 10px;
        margin-top: 10px;
        border-radius: 4px;
        font-size: 12px;
    }
    
    .baseline-info strong {
        color: #7A2E2E;
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>
    <link rel="stylesheet" href="css/banners.css">
    <link rel="stylesheet" href="css/form-validation.css">
</head>
<body>
    <div class="container">
        <?php displayBannerFromSession(); ?>
        <h1>DepEd HRMPSB Evaluation System</h1>
        <p class="subtitle">Comparative Assessment Based on DepEd Order No. 007, s. 2023</p>
        
        <div class="info-box">
            <strong>System Information:</strong> This system automatically computes Comparative Assessment Scores using the Increment Method. Select a position to auto-load baseline QS, then use level pickers for applicant qualifications. Calculations update in real-time.
        </div>
        
        <form method="POST" action="process_evaluation.php" id="evaluationForm">
            
            <!-- Form Progress Indicator -->
            <div class="form-progress" id="form_progress">
                <label class="progress-label">Form Completion Status</label>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                <span class="progress-text">0 of 5 required fields completed</span>
            </div>
            
            <!-- Hidden fields to enable database and CAR saving -->
            <input type="hidden" name="save_to_database" value="1">
            <input type="hidden" name="save_to_car" value="1">
            
            <!-- Position Information -->
            <div class="form-section">
                <h2>Position Information</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="position_group_select">Select Position Group *</label>
                        <select id="position_group_select" name="position_group" required>
                            <option value="">Loading groups…</option>
                        </select>
                        <span class="help-text">Pick a position group to view available positions</span>
                    </div>
                    <div class="form-group">
                        <label for="position_key">Select Position (Auto-loads Baseline) *</label>
                        <select id="position_key" name="position_key" required>
                            <option value="custom">-- Custom Position (Manual Entry) --</option>
                        </select>
                        <span class="help-text">First select a Position Group, then choose a position from that group</span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="position_applied">Position Applied For <span class="required-indicator">*</span></label>
                        <div class="field-wrapper">
                            <input type="text" id="position_applied" name="position_applied" required 
                                   placeholder="Information and Communications Technology">
                            <span class="valid-indicator" id="position_applied_valid_indicator">✓</span>
                        </div>
                        <span class="helper-text">Auto-filled when you select a position</span>
                    </div>
                    <div class="form-group">
                        <label for="job_group_sg_level">Job Group / Salary Grade <span class="required-indicator">*</span></label>
                        <div class="field-wrapper">
                            <input type="text" id="job_group_sg_level" name="job_group_sg_level" readonly required>
                            <span class="valid-indicator" id="job_group_sg_level_valid_indicator">✓</span>
                        </div>
                        <span class="helper-text">Auto-filled from selected position</span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="applicant_name">Applicant Name <span class="required-indicator">*</span></label>
                        <div class="field-wrapper">
                            <input type="text" id="applicant_name" name="applicant_name" required>
                            <span class="valid-indicator" id="applicant_name_valid_indicator">✓</span>
                        </div>
                        <span class="helper-text">Enter full name of applicant</span>
                    </div>
                    <div class="form-group">
                        <label for="application_code">Application Code</label>
                        <input type="text" id="application_code" name="application_code">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="schools_division_office">Schools Division Office <span class="required-indicator">*</span></label>
                        <div class="field-wrapper">
                            <input type="text" id="schools_division_office" name="schools_division_office" 
                                   value="City Schools Division of Cabuyao" required>
                            <span class="valid-indicator" id="schools_division_office_valid_indicator">✓</span>
                        </div>
                        <span class="helper-text">Name of your schools division</span>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number <span class="required-indicator">*</span></label>
                        <div class="field-wrapper">
                            <input type="text" id="contact_number" name="contact_number" required>
                            <span class="valid-indicator" id="contact_number_valid_indicator">✓</span>
                        </div>
                        <span class="helper-text">Use 09XXXXXXXXX or +639XXXXXXXXX format</span>
                    </div>
                </div>
                <div id="baselineInfo" class="baseline-info" style="display: none;">
                    <strong>Baseline Loaded:</strong> <span id="baselineText"></span>
                </div>
            </div>
            
            <!-- Applicant Qualifications -->
            <div class="form-section">
                <h2>Applicant Qualifications (Use Level Pickers)</h2>
                
                <div class="form-group">
                    <label>Education (Dropdown A)</label>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="applicant_education_dropdown">Actual Qualification (Table 2.a) *</label>
                            <select id="applicant_education_dropdown" name="applicant_education_dropdown" required>
                                <option value="">-- Select Education Level --</option>
                                <option value="1">Level 1: Can Read and Write to Elementary Level Education</option>
                                <option value="2">Level 2: Elementary Graduate to Junior High School Level Education (K to 12)</option>
                                <option value="3">Level 3: Completed Junior High School to Senior High School Level Education</option>
                                <option value="4">Level 4: Senior High School Graduate to Less than 2 years of College</option>
                                <option value="5">Level 5: 2+ years College to Less than Bachelor's Degree</option>
                                <option value="6">Level 6: Bachelor's Degree to Less than 6 units Master's</option>
                                <option value="7">Level 7: 6-9 units Master's to Less than 9 units</option>
                                <option value="8">Level 8: 9-12 units Master's to Less than 12 units</option>
                                <option value="9">Level 9: 12-15 units Master's to Less than 15 units</option>
                                <option value="10">Level 10: 15-18 units Master's to Less than 18 units</option>
                                <option value="11">Level 11: 18-21 units Master's to Less than 21 units</option>
                                <option value="12">Level 12: 21+ units Master's / Complete Master's Degree</option>
                                <option value="13">Level 13: 3-6 units Doctorate to Less than 6 units</option>
                                <option value="14">Level 14: 6-9 units Doctorate to Less than 9 units</option>
                                <option value="15">Level 15: 9-12 units Doctorate to Less than 12 units</option>
                                <option value="16">Level 16: 12-15 units Doctorate to Less than 15 units</option>
                                <option value="17">Level 17: 15-18 units Doctorate to Less than 18 units</option>
                                <option value="18">Level 18: 18-21 units Doctorate to Less than 21 units</option>
                                <option value="19">Level 19: 21+ units Doctorate to Complete Academic Requirements</option>
                                <option value="20">Level 20: Complete Academic Requirements towards Doctorate</option>
                                <option value="21">Level 21: Doctorate Degree</option>
                                <option value="22">Level 22: 3-6 units earned towards 2nd Doctorate</option>
                                <option value="23">Level 23: 6-9 units earned towards 2nd Doctorate</option>
                                <option value="24">Level 24: 9-12 units earned towards 2nd Doctorate</option>
                                <option value="25">Level 25: 12-15 units earned towards 2nd Doctorate</option>
                                <option value="26">Level 26: 15-18 units earned towards 2nd Doctorate</option>
                                <option value="27">Level 27: 18-21 units earned towards 2nd Doctorate</option>
                                <option value="28">Level 28: 21-24 units earned towards 2nd Doctorate</option>
                                <option value="29">Level 29: 24+ units / Complete Academic Requirements 2nd Doctorate</option>
                                <option value="30">Level 30: Complete Academic Requirements towards 2nd Doctorate</option>
                                <option value="31">Level 31: 2nd Doctorate Degree</option>
                            </select>
                            <span class="help-text">Per DepEd Order No. 007, s. 2023 - Table 2.a. Select applicable education qualification.</span>
                            <span id="applicant_edu_level" class="level-display" style="display: none;">Level: 0</span>
                        </div>
                        <!-- Hidden original fields driven by the dropdown so backend logic stays intact -->
                        <div class="form-group" style="display:none;">
                            <label for="applicant_education_degree">Degree (auto-filled)</label>
                            <select id="applicant_education_degree" name="applicant_education_degree">
                                <option value="">Select Degree</option>
                                <option value="Bachelor">Bachelor's Degree (Level 6)</option>
                                <option value="Master">Master's Degree (Level 21)</option>
                                <option value="Doctorate">Doctorate Degree (Level 31)</option>
                            </select>
                        </div>
                        <div class="form-group" style="display:none;">
                            <label for="applicant_education_masters_units">Master's Units (auto-filled)</label>
                            <input type="number" id="applicant_education_masters_units" 
                                   name="applicant_education_masters_units" min="0" max="42" step="3" value="0">
                        </div>
                        <div class="form-group" style="display:none;">
                            <label for="applicant_education_doctoral_units">Doctoral Units (auto-filled)</label>
                            <input type="number" id="applicant_education_doctoral_units" 
                                   name="applicant_education_doctoral_units" min="0" max="27" step="3" value="0">
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="applicant_training_dropdown">Training (Table 2.b) *</label>
                        <select id="applicant_training_dropdown" name="applicant_training_dropdown" required>
                            <option value="">-- Select Training Level --</option>
                            <option value="1">Level 1: 0 hours to Less than 8 hours</option>
                            <option value="2">Level 2: 8 hours to Less than 16 hours</option>
                            <option value="3">Level 3: 16 hours to Less than 24 hours</option>
                            <option value="4">Level 4: 24 hours to Less than 32 hours</option>
                            <option value="5">Level 5: 32 hours to Less than 40 hours</option>
                            <option value="6">Level 6: 40 hours to Less than 48 hours</option>
                            <option value="7">Level 7: 48 hours to Less than 56 hours</option>
                            <option value="8">Level 8: 56 hours to Less than 64 hours</option>
                            <option value="9">Level 9: 64 hours to Less than 72 hours</option>
                            <option value="10">Level 10: 72 hours to Less than 80 hours</option>
                            <option value="11">Level 11: 80 hours to Less than 88 hours</option>
                            <option value="12">Level 12: 88 hours to Less than 96 hours</option>
                            <option value="13">Level 13: 96 hours to Less than 104 hours</option>
                            <option value="14">Level 14: 104 hours to Less than 112 hours</option>
                            <option value="15">Level 15: 112 hours to Less than 120 hours</option>
                            <option value="16">Level 16: 120 hours to Less than 128 hours</option>
                            <option value="17">Level 17: 128 hours to Less than 136 hours</option>
                            <option value="18">Level 18: 136 hours to Less than 144 hours</option>
                            <option value="19">Level 19: 144 hours to Less than 152 hours</option>
                            <option value="20">Level 20: 152 hours to Less than 160 hours</option>
                            <option value="21">Level 21: 160 hours to Less than 168 hours</option>
                            <option value="22">Level 22: 168 hours to Less than 176 hours</option>
                            <option value="23">Level 23: 176 hours to Less than 184 hours</option>
                            <option value="24">Level 24: 184 hours to Less than 192 hours</option>
                            <option value="25">Level 25: 192 hours to Less than 200 hours</option>
                            <option value="26">Level 26: 200 hours to Less than 208 hours</option>
                            <option value="27">Level 27: 208 hours to Less than 216 hours</option>
                            <option value="28">Level 28: 216 hours to Less than 224 hours</option>
                            <option value="29">Level 29: 224 hours to Less than 232 hours</option>
                            <option value="30">Level 30: 232 hours to Less than 240 hours</option>
                            <option value="31">Level 31: 240 hours or more</option>
                        </select>
                        <span class="help-text">Per DepEd Order No. 007, s. 2023</span>
                        <span id="applicant_training_level" class="level-display" style="display: none;">Level: 0</span>
                        <!-- Hidden numeric field used by backend and level converter -->
                        <input type="hidden" id="applicant_training" name="applicant_training" value="0">
                    </div>
                    <div class="form-group">
                        <label for="applicant_experience_dropdown">Experience (Table 2.c) *</label>
                        <select id="applicant_experience_dropdown" name="applicant_experience_dropdown" required>
                            <option value="">-- Select Experience Level --</option>
                            <option value="1">Level 1: None to Less than 6 months</option>
                            <option value="2">Level 2: 6 months to Less than 1 year</option>
                            <option value="3">Level 3: 1 year to Less than 1 year 6 months</option>
                            <option value="4">Level 4: 1 year 6 months to Less than 2 years</option>
                            <option value="5">Level 5: 2 years to Less than 2 years 6 months</option>
                            <option value="6">Level 6: 2 years 6 months to Less than 3 years</option>
                            <option value="7">Level 7: 3 years to Less than 3 years 6 months</option>
                            <option value="8">Level 8: 3 years 6 months to Less than 4 years</option>
                            <option value="9">Level 9: 4 years to Less than 4 years 6 months</option>
                            <option value="10">Level 10: 4 years 6 months to Less than 5 years</option>
                            <option value="11">Level 11: 5 years to Less than 5 years 6 months</option>
                            <option value="12">Level 12: 5 years 6 months to Less than 6 years</option>
                            <option value="13">Level 13: 6 years to Less than 6 years 6 months</option>
                            <option value="14">Level 14: 6 years 6 months to Less than 7 years</option>
                            <option value="15">Level 15: 7 years to Less than 7 years 6 months</option>
                            <option value="16">Level 16: 7 years 6 months to Less than 8 years</option>
                            <option value="17">Level 17: 8 years to Less than 8 years 6 months</option>
                            <option value="18">Level 18: 8 years 6 months to Less than 9 years</option>
                            <option value="19">Level 19: 9 years to Less than 9 years 6 months</option>
                            <option value="20">Level 20: 9 years 6 months to Less than 10 years</option>
                            <option value="21">Level 21: 10 years to Less than 10 years 6 months</option>
                            <option value="22">Level 22: 10 years 6 months to Less than 11 years</option>
                            <option value="23">Level 23: 11 years to Less than 11 years 6 months</option>
                            <option value="24">Level 24: 11 years 6 months to Less than 12 years</option>
                            <option value="25">Level 25: 12 years to Less than 12 years 6 months</option>
                            <option value="26">Level 26: 12 years 6 months to Less than 13 years</option>
                            <option value="27">Level 27: 13 years to Less than 13 years 6 months</option>
                            <option value="28">Level 28: 13 years 6 months to Less than 14 years</option>
                            <option value="29">Level 29: 14 years to Less than 14 years 6 months</option>
                            <option value="30">Level 30: 14 years 6 months to Less than 15 years</option>
                            <option value="31">Level 31: 15 years or more</option>
                        </select>
                        <span class="help-text">Per DepEd Order No. 007, s. 2023</span>
                        <span id="applicant_experience_level" class="level-display" style="display: none;">Level: 0</span>
                        <!-- Hidden numeric field used by backend and level converter (stored in months) -->
                        <input type="hidden" id="applicant_experience" name="applicant_experience" value="0">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="applicant_performance">Performance Rating (Level) *</label>
                        <input type="number" id="applicant_performance" name="applicant_performance" 
                               min="0" max="5" step="0.5" value="0">
                        <span class="help-text">Enter rating from 0 to 5 (0=Poor, 5=Outstanding). Scored by weighted computation: (rating/5) × weight</span>
                    </div>
                    <div class="form-group">
                        <label for="applicant_outstanding_accomplishments">Outstanding Accomplishments (Count)</label>
                        <input type="number" id="applicant_outstanding_accomplishments" 
                               name="applicant_outstanding_accomplishments" min="0" step="0.5" value="0">
                        <span class="help-text">Enter computed points from Enclosure 3 (will be capped by the criterion weight)</span>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="applicant_application_of_education">Application of Education (Level) *</label>
                        <input type="number" id="applicant_application_of_education" 
                               name="applicant_application_of_education" min="0" max="5" step="0.5" value="0">
                        <span class="help-text">Enter rating from 0 to 5 (0=Not Relevant, 5=Highly Relevant). Scored by weighted computation: (rating/5) × weight</span>
                    </div>
                    <div class="form-group">
                        <label for="applicant_application_of_ld">Application of L&amp;D (Level) *</label>
                        <input type="number" id="applicant_application_of_ld" 
                               name="applicant_application_of_ld" min="0" max="5" step="0.5" value="0">
                        <span class="help-text">Enter rating from 0 to 5 (0=Not Implemented, 5=Fully Implemented). Scored by weighted computation: (rating/5) × weight</span>
                    </div>
                    <div class="form-group">
                        <label for="applicant_potential">Potential (Level) *</label>
                        <input type="number" id="applicant_potential" 
                               name="applicant_potential" min="0" max="5" step="0.5" value="0">
                        <span class="help-text">Enter rating from 0 to 5 (0=Very Low Potential, 5=Excellent). Scored by weighted computation: (rating/5) × weight</span>
                    </div>
                </div>
            </div>
            
            <!-- Baseline (Auto-filled, editable) -->
            <div class="form-section">
                <h2>Minimum Qualification Standards (Baseline) - Auto-loaded</h2>
                <p style="color: #666; font-size: 12px; margin-bottom: 15px;">
                    These fields are automatically populated when you select a position. You can manually adjust if needed.
                </p>
                
                <div class="form-group">
                    <label>Education</label>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="baseline_education_degree">Degree *</label>
                            <select id="baseline_education_degree" name="baseline_education_degree" required>
                                <option value="">Select Degree</option>
                                <option value="Bachelor">Bachelor's Degree (Level 6)</option>
                                <option value="Master">Master's Degree (Level 21)</option>
                                <option value="Doctorate">Doctorate Degree (Level 31)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="baseline_education_masters_units">Master's Units</label>
                            <input type="number" id="baseline_education_masters_units" 
                                   name="baseline_education_masters_units" min="0" value="0">
                            <span id="baseline_edu_level" class="level-display" style="display: none;">Level: 0</span>
                        </div>
                        <div class="form-group">
                            <label for="baseline_education_doctoral_units">Doctoral Units</label>
                            <input type="number" id="baseline_education_doctoral_units" 
                                   name="baseline_education_doctoral_units" min="0" value="0">
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="baseline_training">Training (Hours)</label>
                        <input type="number" id="baseline_training" name="baseline_training" 
                               min="0" step="8" value="0">
                        <span id="baseline_training_level" class="level-display" style="display: none;">Level: 0</span>
                    </div>
                    <div class="form-group">
                        <label for="baseline_experience">Experience (Months)</label>
                        <input type="number" id="baseline_experience" name="baseline_experience" 
                               min="0" step="6" value="0">
                        <span id="baseline_experience_level" class="level-display" style="display: none;">Level: 0</span>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="baseline_performance">Performance Rating (Level)</label>
                        <input type="number" id="baseline_performance" name="baseline_performance" 
                               min="0" step="1" value="0">
                    </div>
                    <div class="form-group">
                        <label for="baseline_outstanding_accomplishments">Outstanding Accomplishments (Count)</label>
                        <input type="number" id="baseline_outstanding_accomplishments" 
                               name="baseline_outstanding_accomplishments" min="0" value="0">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="baseline_application_of_education">Application of Education (Level)</label>
                        <input type="number" id="baseline_application_of_education" 
                               name="baseline_application_of_education" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label for="baseline_application_of_ld">Application of L&D (Level)</label>
                        <input type="number" id="baseline_application_of_ld" 
                               name="baseline_application_of_ld" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label for="baseline_potential">Potential (Level)</label>
                        <input type="number" id="baseline_potential" name="baseline_potential" 
                               min="0" value="0">
                    </div>
                </div>
            </div>
            
            <!-- Live Preview -->
            <div id="livePreview" class="live-preview">
                <h3>Live Calculation Preview - Evaluation Criteria</h3>
                <p id="criteriaDescription" style="color: #666; font-size: 12px; margin-bottom: 10px;"></p>
                <table class="preview-table">
                    <thead>
                        <tr>
                            <th>Criteria</th>
                            <th>Applicant Level</th>
                            <th>Baseline Level</th>
                            <th>Increment</th>
                            <th>Max Points</th>
                            <th>Score</th>
                        </tr>
                    </thead>
                    <tbody id="previewTableBody">
                        <!-- Populated by JavaScript -->
                    </tbody>
                    <tfoot>
                        <tr style="font-weight: bold; background: #e8e8e8;">
                            <td colspan="5">TOTAL SCORE:</td>
                            <td id="totalScore">0.00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Additional Information -->
            <div class="form-section">
                <h2>Additional Information (for IES Report)</h2>
                <div class="form-group">
                    <label for="hrmpsb_chair">HRMPSB Chair Name</label>
                    <input type="text" id="hrmpsb_chair" name="hrmpsb_chair" 
                           value="RANDY D. PUNZALAN, CESO VI">
                    <span class="help-text">Name of the HRMPSB Chair for attestation</span>
                </div>
            </div>
            
            <!-- Output Options -->
            <div class="form-section">
                <h2>Output Options</h2>
                <div class="form-group">
                    <label for="output_format">Export Format *</label>
                    <select id="output_format" name="output_format" required>
                        <option value="html">HTML (View in Browser)</option>
                        <option value="word">Word Document (.docx)</option>
                        <option value="pdf">PDF Document (.pdf)</option>
                        <option value="excel">Excel Spreadsheet (.xlsx)</option>
                        <option value="text">Plain Text (.txt)</option>
                    </select>
                    <span class="help-text">Select the format you want to export the evaluation report</span>
                </div>
            </div>
            
            <!-- Submit Buttons (hidden - actions are available in sticky bar to avoid duplication) -->
            <div class="btn-group" style="display: none;">
                <button type="submit" id="generate_report_btn" class="btn-primary action-button" disabled>Generate Evaluation Report</button>
                <button type="button" id="generate_car_btn" class="btn-primary action-button disabled" disabled>Generate Comparative Assessment</button>
                <button type="button" id="save_draft_btn" class="btn-secondary">Save Draft</button>
                <button type="reset" class="btn-secondary" onclick="resetForm()">Reset Form</button>
                <a href="comparative_assessment_results.php?view=all" class="btn-primary">View All Results</a>
            </div>

            <!-- Confirmation Modal (reused for both actions) -->
            <div class="modal-overlay" id="confirmationModal" aria-hidden="true">
                <div class="modal" role="dialog" aria-modal="true" aria-labelledby="confirmationTitle">
                    <h3 id="confirmationTitle">Confirm Action</h3>
                    <div class="modal-body">
                        <p id="confirmationText">Please confirm this action.</p>
                        <ul class="checklist" id="confirmationChecklist"></ul>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn-cancel" id="confirmationCancel">Cancel</button>
                        <button type="button" class="btn-confirm" id="confirmationConfirm">Yes, Continue</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <script>
        // Position baseline data
        const positions = <?php echo json_encode($positions); ?>;

        // Populate Position Group select by calling backend API and wire cascading behavior
        async function loadPositionGroups() {
            try {
                const resp = await fetch('api/get_position_groups.php');
                const groups = await resp.json();
                const gsel = document.getElementById('position_group_select');
                if (!gsel) return;
                
                gsel.innerHTML = '<option value="">-- Select a Position Group --</option>';
                groups.forEach((g, idx) => {
                    const opt = document.createElement('option');
                    opt.value = idx;
                    opt.textContent = g.group;
                    gsel.appendChild(opt);
                });
                
                // expose for other handlers
                window.positionGroups = groups;
                
                // When group changes, populate position_key with positions from that group
                gsel.addEventListener('change', function() {
                    const groupIdx = parseInt(this.value);
                    const selectedGroup = groups[groupIdx];
                    const psel = document.getElementById('position_key');
                    if (!psel) return;
                    
                    // Clear and add custom option
                    psel.innerHTML = '<option value="custom">-- Custom Position (Manual Entry) --</option>';
                    
                    if (selectedGroup && selectedGroup.positions && selectedGroup.positions.length) {
                        selectedGroup.positions.forEach(posName => {
                            // Try to find the key for this position in the baseline library
                            let foundKey = null;
                            for (const k in positions) {
                                if (positions[k] && positions[k].position_name === posName) {
                                    foundKey = k;
                                    break;
                                }
                            }
                            
                            // Create option element
                            const o = document.createElement('option');
                            o.value = foundKey || posName;
                            o.textContent = posName;
                            psel.appendChild(o);
                        });
                        
                        // Auto-select first position in group (skip custom)
                        if (psel.options.length > 1) {
                            psel.selectedIndex = 1;
                            psel.dispatchEvent(new Event('change'));
                        }
                    }
                });
            } catch (e) {
                console.error('Failed to load position groups', e);
            }
        }

        // Load groups on startup
        loadPositionGroups();
        
        // Level conversion functions (client-side)
        function convertEducationToLevel(degree, mastersUnits, doctoralUnits) {
            let level = 0;
            if (degree === 'Doctorate' || degree === 'phd' || degree === 'ph.d') {
                level = 31;
            } else if (degree === 'Master') {
                level = 21;
                if (doctoralUnits > 0) {
                    level += Math.min(Math.floor(doctoralUnits / 3), 9);
                }
            } else if (degree === 'Bachelor') {
                level = 6;
                if (mastersUnits > 0) {
                    level += Math.min(Math.floor(mastersUnits / 3), 14);
                }
            }
            return level;
        }
        
        function convertTrainingToLevel(hours) {
            return hours < 8 ? 1 : Math.floor(hours / 8) + 1;
        }
        
        function convertExperienceToLevel(months) {
            return months < 6 ? 1 : Math.floor(months / 6) + 1;
        }
        
        function calculateIncrement(appLevel, baselineLevel) {
            return Math.max(0, appLevel - baselineLevel);
        }
        
        function convertIncrementToPoints(increment, weight) {
            let basePoints = 0;
            if (increment >= 10) basePoints = 10;
            else if (increment >= 8) basePoints = 8;
            else if (increment >= 6) basePoints = 6;
            else if (increment >= 4) basePoints = 4;
            else if (increment >= 2) basePoints = 2;
            else basePoints = 0;
            
            if (weight == 20) return basePoints * 2;
            else if (weight == 5) return basePoints / 2;
            else if (weight == 15) return basePoints * 1.5;
            else if (weight == 25) return basePoints * 2.5;
            return basePoints;
        }

        // Weighted scoring for non-increment criteria (rating is 0..5)
        function convertRatingToWeightedPoints(rating, weight, maxRating = 5) {
            const r = Math.max(0, Math.min(parseFloat(rating) || 0, maxRating));
            const w = parseFloat(weight) || 0;
            return maxRating > 0 ? (r / maxRating) * w : 0;
        }
        
        // Position group weights
        const weights = {
            'TEACHING POSITIONS': { education: 10, training: 10, experience: 10, performance: 10, outstanding_accomplishments: 35, application_of_education: 10, application_of_ld: 10, potential: 5 },
            'HIGHER TEACHING POSITIONS': { education: 5, training: 10, experience: 15, performance: 20, outstanding_accomplishments: 10, application_of_education: 10, application_of_ld: 10, potential: 20 },
            'NON-TEACHING LEVEL I': { education: 5, training: 5, experience: 20, performance: 20, outstanding_accomplishments: 10, application_of_education: 10, application_of_ld: 10, potential: 20 },
            'NON-TEACHING LEVEL II': { education: 5, training: 10, experience: 15, performance: 20, outstanding_accomplishments: 10, application_of_education: 10, application_of_ld: 10, potential: 20 },
            'RELATED TEACHING POSITION': { education: 10, training: 10, experience: 10, performance: 20, outstanding_accomplishments: 10, application_of_education: 10, application_of_ld: 10, potential: 20 },
            'SCHOOL ADMINISTRATION POSITION': { education: 10, training: 10, experience: 10, performance: 25, outstanding_accomplishments: 10, application_of_education: 10, application_of_ld: 10, potential: 15 }
        };
        
        // Helper: sync education dropdown to underlying degree/units fields
        function syncEducationFromDropdown() {
            const eduSelect = document.getElementById('applicant_education_dropdown');
            const value = eduSelect ? eduSelect.value : '';
            const degreeField = document.getElementById('applicant_education_degree');
            const mastersField = document.getElementById('applicant_education_masters_units');
            const doctoralField = document.getElementById('applicant_education_doctoral_units');

            if (!degreeField || !mastersField || !doctoralField) return;

            // Default reset
            degreeField.value = '';
            mastersField.value = 0;
            doctoralField.value = 0;

            // Map education levels (1-31) to underlying structure
            const educationLevel = parseInt(value) || 0;
            
            if (educationLevel === 0) {
                // No selection
                degreeField.value = '';
                mastersField.value = 0;
                doctoralField.value = 0;
            } else if (educationLevel >= 1 && educationLevel <= 6) {
                // Levels 1-6: Below or at Bachelor's
                degreeField.value = 'Bachelor';
                mastersField.value = 0;
                doctoralField.value = 0;
            } else if (educationLevel >= 7 && educationLevel <= 12) {
                // Levels 7-12: Master's degree units or completed
                degreeField.value = 'Master';
                // Calculate Master's units: Level 7=6 units, Level 8=9, Level 9=12, etc.
                const mastersUnits = (educationLevel - 6) * 3; // 6, 9, 12, 15, 18, 21
                mastersField.value = mastersUnits;
                doctoralField.value = 0;
            } else if (educationLevel >= 13 && educationLevel <= 20) {
                // Levels 13-20: Doctorate units or in progress
                degreeField.value = 'Doctorate';
                mastersField.value = 0;
                // Calculate Doctorate units: Level 13=3, Level 14=6, Level 15=9, etc.
                const doctoralUnits = (educationLevel - 12) * 3; // 3, 6, 9, 12, 15, 18, 21, 24
                doctoralField.value = doctoralUnits;
            } else if (educationLevel === 21) {
                // Level 21: Doctorate completed
                degreeField.value = 'Doctorate';
                mastersField.value = 0;
                doctoralField.value = 0;
            } else if (educationLevel >= 22 && educationLevel <= 31) {
                // Levels 22-31: Second Doctorate (treat as higher doctorate)
                degreeField.value = 'Doctorate';
                mastersField.value = 0;
                // Calculate 2nd Doctorate units: Level 22=3, Level 23=6, etc.
                const doctorate2Units = (educationLevel - 21) * 3; // 3, 6, 9, 12, 15, 18, 21, 24, 27, 30
                doctoralField.value = doctorate2Units;
            }
        }

        // Helper: sync training dropdown to hidden numeric hours
        function syncTrainingFromDropdown() {
            const dropdown = document.getElementById('applicant_training_dropdown');
            const hiddenField = document.getElementById('applicant_training');
            if (!dropdown || !hiddenField) return;
            
            const trainingLevel = parseInt(dropdown.value || '0') || 0;
            let hours = 0;
            
            // Convert training level to representative hours (using midpoint of range)
            if (trainingLevel === 1) hours = 4;      // 0-8 hours → 4
            else if (trainingLevel === 2) hours = 12;     // 8-16 hours → 12
            else if (trainingLevel === 3) hours = 20;     // 16-24 hours → 20
            else if (trainingLevel === 4) hours = 28;     // 24-32 hours → 28
            else if (trainingLevel === 5) hours = 36;     // 32-40 hours → 36
            else if (trainingLevel === 6) hours = 44;     // 40-48 hours → 44
            else if (trainingLevel === 7) hours = 52;     // 48-56 hours → 52
            else if (trainingLevel === 8) hours = 60;     // 56-64 hours → 60
            else if (trainingLevel === 9) hours = 68;     // 64-72 hours → 68
            else if (trainingLevel === 10) hours = 76;    // 72-80 hours → 76
            else if (trainingLevel === 11) hours = 84;    // 80-88 hours → 84
            else if (trainingLevel === 12) hours = 92;    // 88-96 hours → 92
            else if (trainingLevel === 13) hours = 100;   // 96-104 hours → 100
            else if (trainingLevel === 14) hours = 108;   // 104-112 hours → 108
            else if (trainingLevel === 15) hours = 116;   // 112-120 hours → 116
            else if (trainingLevel === 16) hours = 124;   // 120-128 hours → 124
            else if (trainingLevel === 17) hours = 132;   // 128-136 hours → 132
            else if (trainingLevel === 18) hours = 140;   // 136-144 hours → 140
            else if (trainingLevel === 19) hours = 148;   // 144-152 hours → 148
            else if (trainingLevel === 20) hours = 156;   // 152-160 hours → 156
            else if (trainingLevel === 21) hours = 164;   // 160-168 hours → 164
            else if (trainingLevel === 22) hours = 172;   // 168-176 hours → 172
            else if (trainingLevel === 23) hours = 180;   // 176-184 hours → 180
            else if (trainingLevel === 24) hours = 188;   // 184-192 hours → 188
            else if (trainingLevel === 25) hours = 196;   // 192-200 hours → 196
            else if (trainingLevel === 26) hours = 204;   // 200-208 hours → 204
            else if (trainingLevel === 27) hours = 212;   // 208-216 hours → 212
            else if (trainingLevel === 28) hours = 220;   // 216-224 hours → 220
            else if (trainingLevel === 29) hours = 228;   // 224-232 hours → 228
            else if (trainingLevel === 30) hours = 236;   // 232-240 hours → 236
            else if (trainingLevel === 31) hours = 240;   // 240+ hours → 240
            
            hiddenField.value = hours;
        }

        // Helper: sync experience dropdown to hidden numeric months
        function syncExperienceFromDropdown() {
            const dropdown = document.getElementById('applicant_experience_dropdown');
            const hiddenField = document.getElementById('applicant_experience');
            if (!dropdown || !hiddenField) return;
            
            const experienceLevel = parseInt(dropdown.value || '0') || 0;
            let months = 0;
            
            // Convert experience level to representative months (using midpoint of range)
            // Each level represents 6-month increments
            if (experienceLevel === 1) months = 3;      // 0-6 months → 3
            else if (experienceLevel === 2) months = 9;      // 6-12 months → 9
            else if (experienceLevel === 3) months = 15;     // 1-1.5 years → 15
            else if (experienceLevel === 4) months = 21;     // 1.5-2 years → 21
            else if (experienceLevel === 5) months = 27;     // 2-2.5 years → 27
            else if (experienceLevel === 6) months = 33;     // 2.5-3 years → 33
            else if (experienceLevel === 7) months = 39;     // 3-3.5 years → 39
            else if (experienceLevel === 8) months = 45;     // 3.5-4 years → 45
            else if (experienceLevel === 9) months = 51;     // 4-4.5 years → 51
            else if (experienceLevel === 10) months = 57;    // 4.5-5 years → 57
            else if (experienceLevel === 11) months = 63;    // 5-5.5 years → 63
            else if (experienceLevel === 12) months = 69;    // 5.5-6 years → 69
            else if (experienceLevel === 13) months = 75;    // 6-6.5 years → 75
            else if (experienceLevel === 14) months = 81;    // 6.5-7 years → 81
            else if (experienceLevel === 15) months = 87;    // 7-7.5 years → 87
            else if (experienceLevel === 16) months = 93;    // 7.5-8 years → 93
            else if (experienceLevel === 17) months = 99;    // 8-8.5 years → 99
            else if (experienceLevel === 18) months = 105;   // 8.5-9 years → 105
            else if (experienceLevel === 19) months = 111;   // 9-9.5 years → 111
            else if (experienceLevel === 20) months = 117;   // 9.5-10 years → 117
            else if (experienceLevel === 21) months = 123;   // 10-10.5 years → 123
            else if (experienceLevel === 22) months = 129;   // 10.5-11 years → 129
            else if (experienceLevel === 23) months = 135;   // 11-11.5 years → 135
            else if (experienceLevel === 24) months = 141;   // 11.5-12 years → 141
            else if (experienceLevel === 25) months = 147;   // 12-12.5 years → 147
            else if (experienceLevel === 26) months = 153;   // 12.5-13 years → 153
            else if (experienceLevel === 27) months = 159;   // 13-13.5 years → 159
            else if (experienceLevel === 28) months = 165;   // 13.5-14 years → 165
            else if (experienceLevel === 29) months = 171;   // 14-14.5 years → 171
            else if (experienceLevel === 30) months = 177;   // 14.5-15 years → 177
            else if (experienceLevel === 31) months = 180;   // 15+ years → 180
            
            hiddenField.value = months;
        }

        // Centralized setter for selected position (single source of truth)
        async function setSelectedPosition(keyOrName) {
            // Reset warning flags when changing position
            window.criteriaWarningShown = false;
            window.criteriaErrorShown = false;
            
            const selInput = document.getElementById('position_key');
            const appliedInput = document.getElementById('position_applied');

            // Handle custom
            if (!keyOrName || keyOrName === 'custom') {
                window.selectedPositionKey = null;
                if (selInput) selInput.value = 'custom';
                if (appliedInput) appliedInput.value = '';
                document.getElementById('job_group_sg_level').value = '';
                document.getElementById('baselineInfo').style.display = 'none';
                updateAllLevels();
                calculatePreview();
                return;
            }

            // Resolve by key first
            let pos = positions[keyOrName];
            let resolvedKey = keyOrName;
            if (!pos) {
                // Resolve by displayed name
                for (const k in positions) {
                    if (positions[k] && positions[k].position_name === keyOrName) {
                        pos = positions[k];
                        resolvedKey = k;
                        break;
                    }
                }
            }

            // If still not resolved, treat keyOrName as display text
            if (!pos) {
                window.selectedPositionKey = null;
                if (appliedInput) appliedInput.value = keyOrName || '';
                document.getElementById('job_group_sg_level').value = '';
                document.getElementById('baselineInfo').style.display = 'none';
                updateAllLevels();
                calculatePreview();
                return;
            }

            // Now we have a resolved pos and resolvedKey
            window.selectedPositionKey = resolvedKey;

            // Ensure the select uses the internal key
            if (selInput) {
                try { selInput.value = resolvedKey; } catch (e) { /* ignore */ }
            }

            // Update Position Applied For exactly as stored
            if (appliedInput) appliedInput.value = pos.position_name;
            
            // Trigger validation update for dynamically filled fields
            if (window.formValidator) {
                window.formValidator.handleFieldChange('position_applied');
                window.formValidator.handleFieldChange('job_group_sg_level');
            }

            // Update position group select to the group that contains this position (if known)
            const gsel = document.getElementById('position_group_select');
            if (gsel && window.positionGroups) {
                let foundIndex = null;
                window.positionGroups.forEach((g, idx) => {
                    if (g.positions && g.positions.indexOf(pos.position_name) !== -1) foundIndex = idx;
                });
                if (foundIndex !== null) {
                    gsel.value = foundIndex;
                }
            }

            // Auto-populate Job Group/SG-Level and baseline fields
            document.getElementById('job_group_sg_level').value = 'Group ' + pos.position_group + ' / Salary Grade ' + pos.salary_grade;
            document.getElementById('baseline_education_degree').value = pos.education.degree;
            document.getElementById('baseline_education_masters_units').value = pos.education.masters_units || 0;
            document.getElementById('baseline_education_doctoral_units').value = pos.education.doctoral_units || 0;
            document.getElementById('baseline_training').value = pos.training || 0;
            document.getElementById('baseline_experience').value = pos.experience || 0;
            document.getElementById('baseline_performance').value = pos.performance || 0;
            document.getElementById('baseline_outstanding_accomplishments').value = pos.outstanding_accomplishments || 0;
            document.getElementById('baseline_application_of_education').value = pos.application_of_education || 0;
            document.getElementById('baseline_application_of_ld').value = pos.application_of_ld || 0;
            document.getElementById('baseline_potential').value = pos.potential || 0;

            // Load dynamic evaluation criteria (non-blocking)
            await loadEvaluationCriteria();

            // Show baseline info and recalc
            const baselineInfo = document.getElementById('baselineInfo');
            const baselineText = document.getElementById('baselineText');
            baselineInfo.style.display = 'block';
            baselineText.textContent = `Education: ${pos.education.degree} (Level ${convertEducationToLevel(pos.education.degree, pos.education.masters_units || 0, pos.education.doctoral_units || 0)}), Training: ${pos.training || 0} hrs (Level ${convertTrainingToLevel(pos.training || 0)}), Experience: ${pos.experience || 0} mos (Level ${convertExperienceToLevel(pos.experience || 0)})`;

            updateAllLevels();
            
            // Only calculate preview if criteria loaded successfully
            if (window.currentCriteria && Object.keys(window.currentCriteria).length > 0) {
                calculatePreview();
            }
        }

        // Wire select change to centralized setter
        document.getElementById('position_key').addEventListener('change', function() {
            setSelectedPosition(this.value);
        });
        
        // Update level displays
        function updateAllLevels() {
            // Ensure dropdown-driven fields are in sync before computing
            syncEducationFromDropdown();
            syncTrainingFromDropdown();
            syncExperienceFromDropdown();

            // Applicant education level
            const appEduDegree = document.getElementById('applicant_education_degree').value;
            const appMastersUnits = parseInt(document.getElementById('applicant_education_masters_units').value) || 0;
            const appEduLevel = convertEducationToLevel(appEduDegree, appMastersUnits, parseInt(document.getElementById('applicant_education_doctoral_units').value) || 0);
            const appEduLevelSpan = document.getElementById('applicant_edu_level');
            if (appEduDegree) {
                appEduLevelSpan.textContent = `Level: ${appEduLevel}`;
                appEduLevelSpan.style.display = 'inline-block';
            } else {
                appEduLevelSpan.style.display = 'none';
            }
            
            // Applicant training level
            const appTraining = parseFloat(document.getElementById('applicant_training').value) || 0;
            const appTrainingLevel = convertTrainingToLevel(appTraining);
            const appTrainingLevelSpan = document.getElementById('applicant_training_level');
            appTrainingLevelSpan.textContent = `Level: ${appTrainingLevel}`;
            appTrainingLevelSpan.style.display = 'inline-block';
            
            // Applicant experience level
            const appExperience = parseFloat(document.getElementById('applicant_experience').value) || 0;
            const appExperienceLevel = convertExperienceToLevel(appExperience);
            const appExperienceLevelSpan = document.getElementById('applicant_experience_level');
            appExperienceLevelSpan.textContent = `Level: ${appExperienceLevel}`;
            appExperienceLevelSpan.style.display = 'inline-block';
            
            // Baseline education level
            const baseEduDegree = document.getElementById('baseline_education_degree').value;
            const baseMastersUnits = parseInt(document.getElementById('baseline_education_masters_units').value) || 0;
            const baseEduLevel = convertEducationToLevel(baseEduDegree, baseMastersUnits, parseInt(document.getElementById('baseline_education_doctoral_units').value) || 0);
            const baseEduLevelSpan = document.getElementById('baseline_edu_level');
            if (baseEduDegree) {
                baseEduLevelSpan.textContent = `Level: ${baseEduLevel}`;
                baseEduLevelSpan.style.display = 'inline-block';
            } else {
                baseEduLevelSpan.style.display = 'none';
            }
            
            // Baseline training level
            const baseTraining = parseFloat(document.getElementById('baseline_training').value) || 0;
            const baseTrainingLevel = convertTrainingToLevel(baseTraining);
            const baseTrainingLevelSpan = document.getElementById('baseline_training_level');
            baseTrainingLevelSpan.textContent = `Level: ${baseTrainingLevel}`;
            baseTrainingLevelSpan.style.display = 'inline-block';
            
            // Baseline experience level
            const baseExperience = parseFloat(document.getElementById('baseline_experience').value) || 0;
            const baseExperienceLevel = convertExperienceToLevel(baseExperience);
            const baseExperienceLevelSpan = document.getElementById('baseline_experience_level');
            baseExperienceLevelSpan.textContent = `Level: ${baseExperienceLevel}`;
            baseExperienceLevelSpan.style.display = 'inline-block';
        }
        
        // Calculate and display preview
        // Load evaluation criteria dynamically based on position group and salary grade
        async function loadEvaluationCriteria() {
            const selPosKey = document.getElementById('position_key').value;
            let positionGroup = null;
            let salaryGrade = null;
            
            // Clear previous criteria before loading new ones
            window.currentCriteria = {};
            window.currentTotalPoints = 0;
            
            if (selPosKey && positions[selPosKey]) {
                positionGroup = positions[selPosKey].position_group;
                salaryGrade = positions[selPosKey].salary_grade;
            } else {
                // Fallback: use selected group name directly
                const gsel = document.getElementById('position_group_select');
                if (gsel && window.positionGroups) {
                    const gidx = parseInt(gsel.value);
                    if (window.positionGroups[gidx]) {
                        positionGroup = window.positionGroups[gidx].group;
                    }
                }
            }
            
            if (!positionGroup) {
                return null;
            }
            
            try {
                // Add category parameter for NON-TEACHING positions
                let category = '';
                if (positionGroup === 'NON-TEACHING LEVEL I') {
                    category = '&category=non_general_services'; // Default to non_general_services
                }
                
                const url = `api/get_evaluation_criteria.php?position_group=${encodeURIComponent(positionGroup)}&salary_grade=${salaryGrade || ''}${category}`;
                const resp = await fetch(url);
                const data = await resp.json();
                
                // Load criteria from authoritative source
                if (data && data.criteria && !data.error) {
                    window.currentCriteria = data.criteria;
                    window.currentTotalPoints = data.total_points || 100;
                } else {
                    // Log error but don't show repeated warnings
                    if (!window.criteriaWarningShown) {
                        console.error('No evaluation criteria found for position group:', positionGroup, data.error || '');
                        window.criteriaWarningShown = true;
                    }
                    window.currentCriteria = {};
                    window.currentTotalPoints = 0;
                }
                
                return data;
            } catch (e) {
                if (!window.criteriaWarningShown) {
                    console.error('Failed to load evaluation criteria:', e);
                    window.criteriaWarningShown = true;
                }
                // Ensure criteria are cleared on error
                window.currentCriteria = {};
                window.currentTotalPoints = 0;
                return null;
            }
        }

        function calculatePreview() {
            // Ensure dropdown-driven fields are in sync before computing
            syncEducationFromDropdown();
            syncTrainingFromDropdown();
            syncExperienceFromDropdown();

            // Determine group for weight lookup
            let positionGroup = null;
            const selPosKey = document.getElementById('position_key').value;
            if (selPosKey && positions[selPosKey]) {
                positionGroup = positions[selPosKey].position_group;
            } else {
                // Fallback: use selected group name directly
                const gsel = document.getElementById('position_group_select');
                if (gsel && window.positionGroups) {
                    const gidx = parseInt(gsel.value);
                    if (window.positionGroups[gidx]) {
                        positionGroup = window.positionGroups[gidx].group;
                    }
                }
            }
            
            // If we have dynamically loaded criteria, use that; otherwise use hardcoded mapping
            let criteriaList = [];
            
            if (window.currentCriteria && Object.keys(window.currentCriteria).length > 0) {
                // Build criteria from dynamic data
                const appEduDegree = document.getElementById('applicant_education_degree').value;
                const appMastersUnits = parseInt(document.getElementById('applicant_education_masters_units').value) || 0;
                const appDoctoralUnits = parseInt(document.getElementById('applicant_education_doctoral_units').value) || 0;
                const appEduLevel = convertEducationToLevel(appEduDegree, appMastersUnits, appDoctoralUnits);
                
                const baseEduDegree = document.getElementById('baseline_education_degree').value;
                const baseMastersUnits = parseInt(document.getElementById('baseline_education_masters_units').value) || 0;
                const baseDoctoralUnits = parseInt(document.getElementById('baseline_education_doctoral_units').value) || 0;
                const baseEduLevel = convertEducationToLevel(baseEduDegree, baseMastersUnits, baseDoctoralUnits);
                
                const appTrainingLevel = convertTrainingToLevel(parseFloat(document.getElementById('applicant_training').value) || 0);
                const baseTrainingLevel = convertTrainingToLevel(parseFloat(document.getElementById('baseline_training').value) || 0);
                
                const appExperienceLevel = convertExperienceToLevel(parseFloat(document.getElementById('applicant_experience').value) || 0);
                const baseExperienceLevel = convertExperienceToLevel(parseFloat(document.getElementById('baseline_experience').value) || 0);
                
                const appPerformance = parseFloat(document.getElementById('applicant_performance').value) || 0;
                const basePerformance = parseFloat(document.getElementById('baseline_performance').value) || 0;
                
                const appOA = parseFloat(document.getElementById('applicant_outstanding_accomplishments').value) || 0;
                const baseOA = parseFloat(document.getElementById('baseline_outstanding_accomplishments').value) || 0;
                
                const appAOE = parseFloat(document.getElementById('applicant_application_of_education').value) || 0;
                const baseAOE = parseFloat(document.getElementById('baseline_application_of_education').value) || 0;
                
                const appAOLD = parseFloat(document.getElementById('applicant_application_of_ld').value) || 0;
                const baseAOLD = parseFloat(document.getElementById('baseline_application_of_ld').value) || 0;
                
                const appPotential = parseFloat(document.getElementById('applicant_potential').value) || 0;
                const basePotential = parseFloat(document.getElementById('baseline_potential').value) || 0;
                
                // Map criteria keys to data
                const criteriaData = {
                    'a': { appLevel: appEduLevel, baseLevel: baseEduLevel, scoring: 'increment' },
                    'b': { appLevel: appTrainingLevel, baseLevel: baseTrainingLevel, scoring: 'increment' },
                    'c': { appLevel: appExperienceLevel, baseLevel: baseExperienceLevel, scoring: 'increment' },
                    'd': { appLevel: appPerformance, baseLevel: basePerformance, scoring: 'weighted' },
                    'e': { appLevel: appOA, baseLevel: baseOA, scoring: 'direct_points' },
                    'f': { appLevel: appAOE, baseLevel: baseAOE, scoring: 'weighted' },
                    'g': { appLevel: appAOLD, baseLevel: baseAOLD, scoring: 'weighted' },
                    'h': { appLevel: appPotential, baseLevel: basePotential, scoring: 'weighted' }
                };
                
                // Build criteria list from loaded criteria
                Object.keys(window.currentCriteria).forEach(key => {
                    const criterionDef = window.currentCriteria[key];
                    const data = criteriaData[key] || { appLevel: 0, baseLevel: 0, scoring: 'weighted' };
                    
                    criteriaList.push({
                        key: key,
                        name: criterionDef.name,
                        max_points: criterionDef.max_points,
                        appLevel: data.appLevel,
                        baseLevel: data.baseLevel,
                        scoring: data.scoring
                    });
                });
            } else {
                // No fallback allowed - criteria must be loaded from evaluation_criteria.php
                // Log warning only once to avoid console spam
                if (!window.criteriaErrorShown) {
                    console.warn('Warning: Evaluation criteria not loaded for position group: ' + positionGroup);
                    window.criteriaErrorShown = true;
                }
                document.getElementById('previewTableBody').innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 20px; color: red;"><strong>Error: Evaluation criteria not available. Please select a valid position.</strong></td></tr>';
                document.getElementById('totalScore').textContent = '0.00';
                document.getElementById('livePreview').classList.add('active');
                return;
            }
            
            let totalScore = 0;
            const tbody = document.getElementById('previewTableBody');
            tbody.innerHTML = '';
            
            criteriaList.forEach(criterion => {
                let increment = '';
                let score = 0;

                if (criterion.scoring === 'increment') {
                    const inc = calculateIncrement(criterion.appLevel, criterion.baseLevel);
                    increment = `${criterion.appLevel} - ${criterion.baseLevel} = ${inc}`;
                    score = convertIncrementToPoints(inc, criterion.max_points);
                } else if (criterion.scoring === 'weighted') {
                    increment = `(${criterion.appLevel} / 5) × ${criterion.max_points}`;
                    score = convertRatingToWeightedPoints(criterion.appLevel, criterion.max_points, 5);
                } else if (criterion.scoring === 'direct_points') {
                    increment = `min(${criterion.appLevel}, ${criterion.max_points})`;
                    score = Math.min(Math.max(0, parseFloat(criterion.appLevel) || 0), parseFloat(criterion.max_points) || 0);
                }

                totalScore += score;
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${criterion.name}</td>
                    <td>${criterion.appLevel}</td>
                    <td>${criterion.baseLevel}</td>
                    <td>${increment}</td>
                    <td>${criterion.max_points}</td>
                    <td>${score.toFixed(2)}</td>
                `;
                tbody.appendChild(row);
            });
            
            document.getElementById('totalScore').textContent = totalScore.toFixed(2);
            
            // Update criteria description
            if (window.currentTotalPoints) {
                document.getElementById('criteriaDescription').textContent = `This position uses a point scale of 0-${window.currentTotalPoints}. Criteria and maximum points are dynamically loaded based on position type and salary grade.`;
            }
            
            document.getElementById('livePreview').classList.add('active');
        }
        
        // Add event listeners for real-time updates
        const inputsToWatch = [
            // Underlying fields plus the new dropdown "virtual" pickers
            'applicant_education_degree', 'applicant_education_masters_units', 'applicant_education_doctoral_units',
            'applicant_training', 'applicant_experience', 'applicant_performance', 'applicant_outstanding_accomplishments',
            'applicant_application_of_education', 'applicant_application_of_ld', 'applicant_potential',
            'baseline_education_degree', 'baseline_education_masters_units', 'baseline_education_doctoral_units',
            'baseline_training', 'baseline_experience', 'baseline_performance', 'baseline_outstanding_accomplishments',
            'baseline_application_of_education', 'baseline_application_of_ld', 'baseline_potential',
            'position_group_select',
            'applicant_education_dropdown', 'applicant_training_dropdown', 'applicant_experience_dropdown'
        ];
        
        inputsToWatch.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('input', function() {
                    updateAllLevels();
                    calculatePreview();
                });
                element.addEventListener('change', function() {
                    updateAllLevels();
                    calculatePreview();
                });
            }
        });
        
        // Initial update
        document.addEventListener('DOMContentLoaded', function() {
            updateAllLevels();
            calculatePreview();
        });
        
        function resetForm() {
            // Delegate to FormValidator with undo support if available
            try {
                if (window.formValidator && typeof window.formValidator.prepareUndoAndReset === 'function') {
                    window.formValidator.prepareUndoAndReset();
                    return;
                }
            } catch (e) { /* ignore */ }

            document.getElementById('baselineInfo').style.display = 'none';
            document.getElementById('livePreview').classList.remove('active');
            const frm = document.getElementById('evaluationForm');
            if (frm) frm.reset();
        }
    </script>

    <!-- Sticky Action Bar (keeps primary actions visible while scrolling) -->
    <div class="sticky-action-bar" aria-hidden="false">
        <div class="bar-inner">
            <button type="button" id="sticky_save_draft" class="btn-secondary">Save Draft</button>
            <button type="button" id="sticky_load_drafts" class="btn-secondary">Load Drafts</button>
            <button type="button" id="sticky_generate_report" class="btn-primary action-button disabled" disabled>Generate Report</button>
            <button type="button" id="sticky_generate_car" class="btn-primary action-button disabled" disabled>Generate CAR</button>
            <button type="button" id="sticky_reset" class="btn-secondary">Reset Form</button>
            <button type="button" id="sticky_view_results" class="btn-primary">View All Results</button>
            <button type="button" id="help_open" class="btn-secondary">Help</button>
        </div>
    </div>

    <!-- Drafts Modal -->
    <div id="draftsModal" class="modal" aria-hidden="true" style="display:none;">
        <div class="modal-inner" role="dialog" aria-modal="true" aria-labelledby="draftsTitle">
            <h3 id="draftsTitle">Saved Drafts</h3>
            <div id="draftsList">Loading…</div>
            <div style="margin-top:12px;text-align:right;"><button id="draftsClose" class="btn-secondary">Close</button></div>
        </div>
    </div>

    <!-- Help toggle and drawer -->
    <div id="helpToggle" class="help-toggle" title="Help" role="button" aria-pressed="false">?</div>
    <aside id="helpDrawer" class="help-drawer" aria-hidden="true">
        <h3>Form Help</h3>
        <div class="help-section">
            <h4>What this form is</h4>
            <p>This form collects applicant details and qualifications to compute an Individual Evaluation Sheet (IES) and Comparative Assessment scores based on DepEd Order No. 007, s. 2023.</p>
        </div>
        <div class="help-section">
            <h4>How evaluation works</h4>
            <p>The system compares applicant qualifications against the minimum qualification standards (baseline) and computes points using increment and weighted methods. Results are available as IES (individual) and CAR (comparative assessment).</p>
        </div>
        <div class="help-section">
            <h4>What happens after submission</h4>
            <p>When you generate the evaluation report the form is submitted to the server for processing and the generated IES will be shown or exported per selected format. You can also save drafts locally before final submission.</p>
        </div>
        <div>
            <button type="button" id="helpClose" class="btn-secondary">Close Help</button>
        </div>
    </aside>

    <script src="js/form-validation.js"></script>
    <script>
        // Register service worker (if available)
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('sw.js').then(reg => {
                console.info('Service Worker registered:', reg.scope);
            }).catch(err => console.warn('SW register failed', err));
        }
        // If server provided a loaded draft in session, attempt to prompt restore
        <?php if (isset($_SESSION['loaded_draft']) && !empty($_SESSION['loaded_draft'])): ?>
        (function(){
            try {
                const draft = <?php echo json_encode($_SESSION['loaded_draft']); ?>;
                // Store to localStorage so FormValidator.restoreDraftFromLocalStorage can pick it up
                localStorage.setItem('deped_eval_draft', JSON.stringify(draft));
                // Remove server copy to avoid reusing
            } catch(e) { console.warn(e); }
        })();
        <?php unset($_SESSION['loaded_draft']); endif; ?>
    </script>
</body>
</html>
