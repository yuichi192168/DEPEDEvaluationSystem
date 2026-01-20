<?php
/**
 * DepEd Human Resource Merit Promotion and Selection Board(HRMPSB) Evaluation System
 * Enhanced Version with Baseline Library and Level Pickers
 */
require_once 'config/baseline_library.php';

$positions = getAllPositions();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DepEd HRMPSB Evaluation System</title>
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

</head>
<body>
    <div class="container">
        <h1>DepEd HRMPSB Evaluation System</h1>
        <p class="subtitle">Comparative Assessment Based on DepEd Order No. 007, s. 2023</p>
        
        <div class="info-box">
            <strong>System Information:</strong> This system automatically computes Comparative Assessment Scores using the Increment Method. Select a position to auto-load baseline QS, then use level pickers for applicant qualifications. Calculations update in real-time.
        </div>
        
        <form method="POST" action="process_evaluation.php" id="evaluationForm">
            
            <!-- Position Information -->
            <div class="form-section">
                <h2>Position Information</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="position_key">Select Position (Auto-loads Baseline) *</label>
                        <select id="position_key" name="position_key" required>
                            <option value="custom">-- Custom Position (Manual Entry) --</option>
                            <?php foreach ($positions as $key => $pos): ?>
                                <?php if ($key !== 'custom'): ?>
                                    <option value="<?php echo htmlspecialchars($key); ?>">
                                        <?php echo htmlspecialchars($pos['position_name']); ?> (Group <?php echo $pos['position_group']; ?>)
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <span class="help-text">Selecting a position automatically loads baseline qualification standards</span>
                    </div>
                    <div class="form-group">
                        <label for="position_applied">Position Applied For *</label>
                        <input type="text" id="position_applied" name="position_applied" required 
                               placeholder="Information and Communications Technology">
                        <span class="help-text">This field auto-fills when you select a position above</span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="position_group">Position Group *</label>
                        <select id="position_group" name="position_group" required>
                            <option value="A">Group A: Non-Teaching Level 1 (General)</option>
                            <option value="B">Group B: Non-Teaching Level 2</option>
                            <option value="C">Group C: School Administration</option>
                        </select>
                        <span class="help-text">Auto-updates when position is selected</span>
                    </div>
                    <div class="form-group">
                        <label for="job_group_sg_level">Job Group/SG-Level</label>
                        <input type="text" id="job_group_sg_level" name="job_group_sg_level" 
                               placeholder="Non-Teaching Position / Contract of Service">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="applicant_name">Applicant Name *</label>
                        <input type="text" id="applicant_name" name="applicant_name" required>
                    </div>
                    <div class="form-group">
                        <label for="application_code">Application Code</label>
                        <input type="text" id="application_code" name="application_code">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="schools_division_office">Schools Division Office</label>
                        <input type="text" id="schools_division_office" name="schools_division_office" 
                               placeholder="City Schools Division of Cabuyao">
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="text" id="contact_number" name="contact_number">
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
                            <label for="applicant_education_dropdown">Actual Qualification *</label>
                            <select id="applicant_education_dropdown" name="applicant_education_dropdown" required>
                                <option value="">Select Education</option>
                                <option value="bachelor">Bachelor's Degree (Level 6)</option>
                                <option value="bachelor_18">Bachelor's Degree + 18 units (Approx. Level 11)</option>
                                <option value="master">Master's Degree (Level 21)</option>
                            </select>
                            <span class="help-text">Select the actual qualification; levels and increments are auto-computed.</span>
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
                        <label for="applicant_training_dropdown">Training (Dropdown B) *</label>
                        <select id="applicant_training_dropdown" name="applicant_training_dropdown" required>
                            <option value="">Select Training Hours</option>
                            <option value="0">None / Less than 8 hours (Level 1)</option>
                            <option value="8">8 to 16 hours (Level 2)</option>
                            <option value="16">16 to 24 hours (Level 3)</option>
                            <option value="24">24 to 32 hours (Level 4)</option>
                            <option value="32">32 to 40 hours (Level 5)</option>
                            <option value="40">40+ hours (Level 6)</option>
                        </select>
                        <span class="help-text">Choose the bracket; the exact level is calculated automatically.</span>
                        <span id="applicant_training_level" class="level-display" style="display: none;">Level: 0</span>
                        <!-- Hidden numeric field used by backend and level converter -->
                        <input type="hidden" id="applicant_training" name="applicant_training" value="0">
                    </div>
                    <div class="form-group">
                        <label for="applicant_experience_dropdown">Experience (Dropdown C) *</label>
                        <select id="applicant_experience_dropdown" name="applicant_experience_dropdown" required>
                            <option value="">Select Years of Experience</option>
                            <option value="0">None / Less than 6 months (Level 1)</option>
                            <option value="12">1 year to 1.5 years (Level 3)</option>
                            <option value="24">2 years to 2.5 years (Level 5)</option>
                            <option value="36">3 years to 3.5 years (Level 7)</option>
                            <option value="48">4 years to 4.5 years (Level 9)</option>
                        </select>
                        <span class="help-text">Choose the bracket; the months and levels are auto-derived.</span>
                        <span id="applicant_experience_level" class="level-display" style="display: none;">Level: 0</span>
                        <!-- Hidden numeric field used by backend and level converter (stored in months) -->
                        <input type="hidden" id="applicant_experience" name="applicant_experience" value="0">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="applicant_performance">Performance (Dropdown) *</label>
                        <select id="applicant_performance" name="applicant_performance" required>
                            <option value="">Select Performance</option>
                            <option value="5">Outstanding (4.500 - 5.000) (Rating 5/5)</option>
                            <option value="4">Very Satisfactory (3.500 - 4.499) (Rating 4/5)</option>
                            <option value="3">Satisfactory (2.500 - 3.499) (Rating 3/5)</option>
                            <option value="2">Unsatisfactory (1.500 - 2.499) (Rating 2/5)</option>
                            <option value="1">Poor (1.000 - 1.499) (Rating 1/5)</option>
                        </select>
                        <span class="help-text">Scored by weighted computation: (rating/5) × weight</span>
                    </div>
                    <div class="form-group">
                        <label for="applicant_outstanding_accomplishments">Outstanding Accomplishments (Points)</label>
                        <input type="number" id="applicant_outstanding_accomplishments" 
                               name="applicant_outstanding_accomplishments" min="0" step="0.5" value="0">
                        <span class="help-text">Enter computed points from Enclosure 3 (will be capped by the criterion weight)</span>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="applicant_application_of_education">Application of Education (Dropdown) *</label>
                        <select id="applicant_application_of_education" name="applicant_application_of_education" required>
                            <option value="">Select Rating</option>
                            <option value="5">Highly Relevant / High Impact (5/5)</option>
                            <option value="4">Relevant / Good Impact (4/5)</option>
                            <option value="3">Moderately Relevant (3/5)</option>
                            <option value="2">Low Relevance (2/5)</option>
                            <option value="1">Not Relevant (1/5)</option>
                        </select>
                        <span class="help-text">Scored by weighted computation: (rating/5) × weight</span>
                    </div>
                    <div class="form-group">
                        <label for="applicant_application_of_ld">Application of L&amp;D (Dropdown) *</label>
                        <select id="applicant_application_of_ld" name="applicant_application_of_ld" required>
                            <option value="">Select Rating</option>
                            <option value="5">Fully implemented L&amp;D action plan (5/5)</option>
                            <option value="4">Implemented with clear outcomes (4/5)</option>
                            <option value="3">Partially implemented (3/5)</option>
                            <option value="2">Minimally implemented (2/5)</option>
                            <option value="1">Not implemented (1/5)</option>
                        </select>
                        <span class="help-text">Scored by weighted computation: (rating/5) × weight</span>
                    </div>
                    <div class="form-group">
                        <label for="applicant_potential">Potential (Dropdown) *</label>
                        <select id="applicant_potential" name="applicant_potential" required>
                            <option value="">Select Potential Rating</option>
                            <option value="5">Excellent / High Potential (5/5)</option>
                            <option value="4">Above Average Potential (4/5)</option>
                            <option value="3">Moderate Potential (3/5)</option>
                            <option value="2">Low Potential (2/5)</option>
                            <option value="1">Very Low Potential (1/5)</option>
                        </select>
                        <span class="help-text">Scored by weighted computation: (rating/5) × weight</span>
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
                <h3>Live Calculation Preview</h3>
                <table class="preview-table">
                    <thead>
                        <tr>
                            <th>Criteria</th>
                            <th>Applicant Level</th>
                            <th>Baseline Level</th>
                            <th>Increment</th>
                            <th>Weight</th>
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
                           placeholder="RANDY D. PUNZALAN, CESO VI">
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
            
            <!-- Submit Buttons -->
            <div class="btn-group">
                <button type="submit" class="btn-primary">Generate Evaluation Report</button>
                <button type="reset" class="btn-secondary" onclick="resetForm()">Reset Form</button>
            </div>
        </form>
    </div>
    
    <script>
        // Position baseline data
        const positions = <?php echo json_encode($positions); ?>;
        
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
            'A': { education: 5, training: 5, experience: 20, performance: 20, outstanding_accomplishments: 10, application_of_education: 10, application_of_ld: 10, potential: 20 },
            'B': { education: 5, training: 10, experience: 15, performance: 20, outstanding_accomplishments: 10, application_of_education: 10, application_of_ld: 10, potential: 20 },
            'C': { education: 10, training: 10, experience: 10, performance: 25, outstanding_accomplishments: 10, application_of_education: 10, application_of_ld: 10, potential: 15 }
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

            // Map dropdown to underlying structure understood by the evaluator
            if (value === 'bachelor') {
                degreeField.value = 'Bachelor';
                mastersField.value = 0;
                doctoralField.value = 0;
            } else if (value === 'bachelor_18') {
                degreeField.value = 'Bachelor';
                // Use 18 units as specified; backend will convert this to a corresponding level
                mastersField.value = 18;
                doctoralField.value = 0;
            } else if (value === 'master') {
                degreeField.value = 'Master';
                mastersField.value = 0;
                doctoralField.value = 0;
            }
        }

        // Helper: sync training dropdown to hidden numeric hours
        function syncTrainingFromDropdown() {
            const dropdown = document.getElementById('applicant_training_dropdown');
            const hiddenField = document.getElementById('applicant_training');
            if (!dropdown || !hiddenField) return;
            const hours = parseFloat(dropdown.value || '0') || 0;
            hiddenField.value = hours;
        }

        // Helper: sync experience dropdown to hidden numeric months
        function syncExperienceFromDropdown() {
            const dropdown = document.getElementById('applicant_experience_dropdown');
            const hiddenField = document.getElementById('applicant_experience');
            if (!dropdown || !hiddenField) return;
            const months = parseFloat(dropdown.value || '0') || 0;
            hiddenField.value = months;
        }

        // Load baseline when position is selected
        document.getElementById('position_key').addEventListener('change', function() {
            const positionKey = this.value;
            if (positionKey !== 'custom' && positions[positionKey]) {
                const pos = positions[positionKey];
                
                // Update position name
                document.getElementById('position_applied').value = pos.position_name;
                
                // Update position group
                document.getElementById('position_group').value = pos.position_group;
                
                // Load baseline education
                document.getElementById('baseline_education_degree').value = pos.education.degree;
                document.getElementById('baseline_education_masters_units').value = pos.education.masters_units || 0;
                document.getElementById('baseline_education_doctoral_units').value = pos.education.doctoral_units || 0;
                
                // Load baseline training and experience
                document.getElementById('baseline_training').value = pos.training || 0;
                document.getElementById('baseline_experience').value = pos.experience || 0;
                
                // Load other baselines
                document.getElementById('baseline_performance').value = pos.performance || 0;
                document.getElementById('baseline_outstanding_accomplishments').value = pos.outstanding_accomplishments || 0;
                document.getElementById('baseline_application_of_education').value = pos.application_of_education || 0;
                document.getElementById('baseline_application_of_ld').value = pos.application_of_ld || 0;
                document.getElementById('baseline_potential').value = pos.potential || 0;
                
                // Show baseline info
                const baselineInfo = document.getElementById('baselineInfo');
                const baselineText = document.getElementById('baselineText');
                baselineInfo.style.display = 'block';
                baselineText.textContent = `Education: ${pos.education.degree} (Level ${convertEducationToLevel(pos.education.degree, pos.education.masters_units || 0, pos.education.doctoral_units || 0)}), Training: ${pos.training || 0} hrs (Level ${convertTrainingToLevel(pos.training || 0)}), Experience: ${pos.experience || 0} mos (Level ${convertExperienceToLevel(pos.experience || 0)})`;
                
                // Update levels and recalculate
                updateAllLevels();
                calculatePreview();
            } else {
                document.getElementById('baselineInfo').style.display = 'none';
            }
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
        function calculatePreview() {
            // Ensure dropdown-driven fields are in sync before computing
            syncEducationFromDropdown();
            syncTrainingFromDropdown();
            syncExperienceFromDropdown();

            const positionGroup = document.getElementById('position_group').value;
            const groupWeights = weights[positionGroup];
            
            // Get applicant levels
            const appEduDegree = document.getElementById('applicant_education_degree').value;
            const appMastersUnits = parseInt(document.getElementById('applicant_education_masters_units').value) || 0;
            const appDoctoralUnits = parseInt(document.getElementById('applicant_education_doctoral_units').value) || 0;
            const appEduLevel = convertEducationToLevel(appEduDegree, appMastersUnits, appDoctoralUnits);
            
            const appTrainingLevel = convertTrainingToLevel(parseFloat(document.getElementById('applicant_training').value) || 0);
            const appExperienceLevel = convertExperienceToLevel(parseFloat(document.getElementById('applicant_experience').value) || 0);
            const appPerformance = parseFloat(document.getElementById('applicant_performance').value) || 0; // rating 1..5
            const appOA = parseFloat(document.getElementById('applicant_outstanding_accomplishments').value) || 0; // direct points
            const appAOE = parseFloat(document.getElementById('applicant_application_of_education').value) || 0; // rating 1..5
            const appAOLD = parseFloat(document.getElementById('applicant_application_of_ld').value) || 0; // rating 1..5
            const appPotential = parseFloat(document.getElementById('applicant_potential').value) || 0; // rating 1..5
            
            // Get baseline levels
            const baseEduDegree = document.getElementById('baseline_education_degree').value;
            const baseMastersUnits = parseInt(document.getElementById('baseline_education_masters_units').value) || 0;
            const baseDoctoralUnits = parseInt(document.getElementById('baseline_education_doctoral_units').value) || 0;
            const baseEduLevel = convertEducationToLevel(baseEduDegree, baseMastersUnits, baseDoctoralUnits);
            
            const baseTrainingLevel = convertTrainingToLevel(parseFloat(document.getElementById('baseline_training').value) || 0);
            const baseExperienceLevel = convertExperienceToLevel(parseFloat(document.getElementById('baseline_experience').value) || 0);
            const basePerformance = parseFloat(document.getElementById('baseline_performance').value) || 0;
            const baseOA = parseFloat(document.getElementById('baseline_outstanding_accomplishments').value) || 0;
            const baseAOE = parseFloat(document.getElementById('baseline_application_of_education').value) || 0;
            const baseAOLD = parseFloat(document.getElementById('baseline_application_of_ld').value) || 0;
            const basePotential = parseFloat(document.getElementById('baseline_potential').value) || 0;
            
            // Calculate increments and scores
            const criteria = [
                { name: 'Education', appLevel: appEduLevel, baseLevel: baseEduLevel, weight: groupWeights.education, scoring: 'increment' },
                { name: 'Training', appLevel: appTrainingLevel, baseLevel: baseTrainingLevel, weight: groupWeights.training, scoring: 'increment' },
                { name: 'Experience', appLevel: appExperienceLevel, baseLevel: baseExperienceLevel, weight: groupWeights.experience, scoring: 'increment' },
                { name: 'Performance', appLevel: appPerformance, baseLevel: basePerformance, weight: groupWeights.performance, scoring: 'weighted' },
                { name: 'Outstanding Accomplishments', appLevel: appOA, baseLevel: baseOA, weight: groupWeights.outstanding_accomplishments, scoring: 'direct_points' },
                { name: 'Application of Education', appLevel: appAOE, baseLevel: baseAOE, weight: groupWeights.application_of_education, scoring: 'weighted' },
                { name: 'Application of L&D', appLevel: appAOLD, baseLevel: baseAOLD, weight: groupWeights.application_of_ld, scoring: 'weighted' },
                { name: 'Potential', appLevel: appPotential, baseLevel: basePotential, weight: groupWeights.potential, scoring: 'weighted' }
            ];
            
            let totalScore = 0;
            const tbody = document.getElementById('previewTableBody');
            tbody.innerHTML = '';
            
            criteria.forEach(criterion => {
                let increment = '';
                let score = 0;

                if (criterion.scoring === 'increment') {
                    const inc = calculateIncrement(criterion.appLevel, criterion.baseLevel);
                    increment = `${criterion.appLevel} - ${criterion.baseLevel} = ${inc}`;
                    score = convertIncrementToPoints(inc, criterion.weight);
                } else if (criterion.scoring === 'weighted') {
                    increment = `(${criterion.appLevel} / 5) × ${criterion.weight}`;
                    score = convertRatingToWeightedPoints(criterion.appLevel, criterion.weight, 5);
                } else if (criterion.scoring === 'direct_points') {
                    increment = `min(${criterion.appLevel}, ${criterion.weight})`;
                    score = Math.min(Math.max(0, parseFloat(criterion.appLevel) || 0), parseFloat(criterion.weight) || 0);
                }

                totalScore += score;
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${criterion.name}</td>
                    <td>${criterion.appLevel}</td>
                    <td>${criterion.baseLevel}</td>
                    <td>${increment}</td>
                    <td>${criterion.weight}%</td>
                    <td>${score.toFixed(2)}</td>
                `;
                tbody.appendChild(row);
            });
            
            document.getElementById('totalScore').textContent = totalScore.toFixed(2);
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
            'position_group',
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
            document.getElementById('baselineInfo').style.display = 'none';
            document.getElementById('livePreview').classList.remove('active');
        }
    </script>
</body>
</html>
