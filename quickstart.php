<?php
/**
 * Verification and Quick Start Guide
 * Check system status and provide instructions
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CAR System - Quick Start & Verification</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Calibri', 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #E04040 0%, #E06060 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            padding: 40px;
        }
        h1 {
            color: #E04040;
            margin-bottom: 10px;
            border-bottom: 3px solid #E04040;
            padding-bottom: 10px;
        }
        h2 {
            color: #333;
            margin-top: 30px;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-left: 4px solid #E04040;
            border-radius: 4px;
        }
        .step {
            margin: 15px 0;
            padding: 15px;
            background: white;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .step-number {
            display: inline-block;
            background: #E04040;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            text-align: center;
            line-height: 32px;
            margin-right: 10px;
            font-weight: bold;
        }
        .step-content {
            display: inline-block;
            vertical-align: top;
            width: calc(100% - 50px);
        }
        .code {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
            word-break: break-all;
        }
        .button-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 10px;
            margin: 20px 0;
        }
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #E04040;
            color: white;
        }
        .btn-primary:hover {
            background: #c73030;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-success:hover {
            background: #218838;
        }
        .btn-info {
            background: #2196F3;
            color: white;
        }
        .btn-info:hover {
            background: #0b7dda;
        }
        .checklist {
            list-style: none;
        }
        .checklist li {
            padding: 10px;
            margin: 5px 0;
            background: white;
            border-left: 4px solid #ddd;
            border-radius: 2px;
        }
        .checklist li:before {
            content: "[OK] ";
            color: #28a745;
            font-weight: bold;
            margin-right: 10px;
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .success-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
            color: #155724;
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
            color: #856404;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>CAR System - Quick Start Guide</h1>
        <p style="color: #666; font-size: 14px;">
            Complete guide to insert sample data and view comparative assessment results
        </p>

        <div class="section">
            <h2>Overview</h2>
            <p>This guide will help you:</p>
            <ul class="checklist">
                <li>Insert 4 sample applicants into the database</li>
                <li>View results in Comparative Assessment Result (CAR) format</li>
                <li>See rankings generated based on scores</li>
                <li>Select applicants by position</li>
            </ul>
        </div>

        <div class="section">
            <h2>Quick Start (3 Steps)</h2>

            <div class="step">
                <span class="step-number">1</span>
                <div class="step-content">
                    <strong>Insert Sample Data</strong>
                    <p style="margin-top: 8px; color: #666;">Click the button below or go to the URL to insert 4 test applicants:</p>
                    <div class="button-group">
                        <a href="insert_sample_applicants.php" class="btn btn-success">
                            Insert Sample Applicants
                        </a>
                    </div>
                </div>
            </div>

            <div class="step">
                <span class="step-number">2</span>
                <div class="step-content">
                    <strong>View All Results</strong>
                    <p style="margin-top: 8px; color: #666;">View all 4 applicants across positions:</p>
                    <div class="button-group">
                        <a href="comparative_assessment_results.php?view=all" class="btn btn-primary">
                            View All Results
                        </a>
                    </div>
                </div>
            </div>

            <div class="step">
                <span class="step-number">3</span>
                <div class="step-content">
                    <strong>View by Position</strong>
                    <p style="margin-top: 8px; color: #666;">Select a position from dropdown to see applicants:</p>
                    <div class="button-group">
                        <a href="comparative_assessment_results.php" class="btn btn-info">
                            View by Position
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Sample Data</h2>
            <p>The following 4 applicants will be inserted:</p>
            <table>
                <tr>
                    <th>Applicant Name</th>
                    <th>Position</th>
                    <th>Code</th>
                    <th>Score</th>
                    <th>Rank</th>
                </tr>
                <tr>
                    <td>Maria Santos</td>
                    <td>Position 1</td>
                    <td>SAMPLE-001</td>
                    <td>9.38</td>
                    <td>1</td>
                </tr>
                <tr>
                    <td>Juan Dela Cruz</td>
                    <td>Position 1</td>
                    <td>SAMPLE-002</td>
                    <td>8.73</td>
                    <td>2</td>
                </tr>
                <tr>
                    <td>Ana Reyes</td>
                    <td>Position 2</td>
                    <td>SAMPLE-003</td>
                    <td>8.08</td>
                    <td>1</td>
                </tr>
                <tr>
                    <td>Carlos Mendoza</td>
                    <td>Position 2</td>
                    <td>SAMPLE-004</td>
                    <td>7.25</td>
                    <td>2</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2>Features</h2>
            <ul class="checklist">
                <li>Automatic ranking based on scores</li>
                <li>Position-based filtering</li>
                <li>Professional CAR format with all required columns</li>
                <li>Print and export capabilities</li>
                <li>Real-time score calculations</li>
                <li>Easy sample data management</li>
            </ul>
        </div>

        <div class="section">
            <h2>What You'll See</h2>
            <p>The CAR display includes the following columns:</p>
            <ul style="padding-left: 20px;">
                <li><strong>NAME</strong> - Applicant's full name</li>
                <li><strong>APPLICATION CODE</strong> - Unique identifier (e.g., CoS-ICT-2026-001)</li>
                <li><strong>Education</strong> - Education score</li>
                <li><strong>Training</strong> - Training hours score</li>
                <li><strong>Experience</strong> - Years of experience score</li>
                <li><strong>Performance</strong> - Performance rating score</li>
                <li><strong>Outstanding Accomplishments</strong> - Achievements score</li>
                <li><strong>Application of Education</strong> - Education application score</li>
                <li><strong>Application of L&D</strong> - Learning & Development score</li>
                <li><strong>Potential</strong> - Growth potential score</li>
                <li><strong>Total</strong> - Combined weighted score</li>
                <li><strong>Remarks</strong> - Additional notes</li>
                <li><strong>Background</strong> - Background check status (Yes/No)</li>
                <li><strong>For Appointment</strong> - Appointment recommendation</li>
                <li><strong>For Probation</strong> - Probation status</li>
            </ul>
        </div>

        <div class="info-box">
            <strong>Note:</strong> The position selector will only show positions that have applicants saved in the database. This provides a cleaner, more focused interface.
        </div>

        <div class="section">
            <h2>Direct Links</h2>
            <div class="button-group" style="margin-top: 15px;">
                <a href="insert_sample_applicants.php" class="btn btn-success">Insert Sample Data</a>
                <a href="comparative_assessment_results.php?view=all" class="btn btn-primary">View All Results</a>
                <a href="comparative_assessment_results.php" class="btn btn-info">View by Position</a>
                <a href="index.php" class="btn" style="background: #6c757d; color: white;">Back to Evaluation Form</a>
            </div>
        </div>

        <div class="success-box">
            <strong>System Status: READY</strong>
            <p>All components are configured and ready to use. Start by inserting sample data!</p>
        </div>

        <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; color: #999; font-size: 12px;">
            <p>DepEd HRMPSB Evaluation System | CAR Display Module | January 22, 2026</p>
        </div>
    </div>
</body>
</html>
