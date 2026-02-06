<?php
require_once 'classes/HRMPSBEvaluator.php';
require 'config/database.php';

$conn = getDBConnection();

// Get draft data
$result = mysqli_query($conn, "SELECT data FROM drafts WHERE application_code = 'NT-AOII-2026-003'");
$row = mysqli_fetch_assoc($result);
$data = json_decode($row['data'], true);

echo "📋 Input Data:\n";
echo "────────────────────────────────\n";
echo "Experience: {$data['applicant_experience']} months\n";
echo "Position Group: {$data['position_group']}\n";
echo "Salary Grade: {$data['salary_grade']}\n";
echo "Category: {$data['category']}\n\n";

// Get criteria
$criteria = getEvaluationCriteria($data['position_group'], $data['salary_grade'], $data['category']);

echo "📊 Criteria:\n";
echo "────────────────────────────────\n";
foreach ($criteria as $c) {
    if ($c['criterion'] == 'Experience') {
        echo "Experience Weight: {$c['weight']}\n";
        echo "Scoring Type: {$c['scoring_type']}\n\n";
        
        // Calculate experience level
        $months = intval($data['applicant_experience']);
        
        // Convert to level
        if ($months < 6) $level = 0;
        else if ($months < 12) $level = 1;
        else if ($months < 24) $level = 2;
        else if ($months < 36) $level = 3;
        else if ($months < 48) $level = 4;
        else if ($months < 60) $level = 5;
        else if ($months < 72) $level = 6;
        else if ($months < 84) $level = 7;
        else if ($months < 96) $level = 8;
        else if ($months < 108) $level = 9;
        else $level = 10;
        
        echo "🧮 Calculation:\n";
        echo "────────────────────────────────\n";
        echo "Months: $months\n";
        echo "Level: $level\n";
        echo "Weight: {$c['weight']}\n\n";
        
        // Apply weight scaling
        $basePoints = $level;
        $weight = intval($c['weight']);
        
        if ($weight == 15) {
            $score = $basePoints * 1.5;
        } elseif ($weight == 20) {
            $score = $basePoints * 2;
        } elseif ($weight == 10) {
            $score = $basePoints;
        } elseif ($weight == 5) {
            $score = $basePoints / 2;
        } else {
            $score = $basePoints;
        }
        
        echo "Expected Score: $score\n";
        echo "(Level $level × ");
        if ($weight == 15) echo "1.5";
        elseif ($weight == 20) echo "2";
        elseif ($weight == 5) echo "0.5";
        else echo "1";
        echo ")\n";
    }
}

mysqli_close($conn);
?>
