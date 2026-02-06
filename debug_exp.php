<?php
require_once 'config/evaluation_criteria.php';
require 'config/database.php';

$conn = getDBConnection();

// Get draft data
$result = mysqli_query($conn, "SELECT data FROM drafts WHERE application_code = 'NT-AOII-2026-003'");
$row = mysqli_fetch_assoc($result);
$data = json_decode($row['data'], true);

echo "Input Data:\n";
echo "Experience: {$data['applicant_experience']} months\n";
echo "Position: {$data['position_group']}\n";
echo "Salary: {$data['salary_grade']}\n";
echo "Category: {$data['category']}\n\n";

// Get criteria
$criteria = getEvaluationCriteria($data['position_group'], $data['salary_grade'], $data['category']);

foreach ($criteria as $c) {
    if ($c['criterion'] == 'Experience') {
        echo "Experience Weight: {$c['weight']}\n";
        
        $months = intval($data['applicant_experience']);
        
        // Convert to level (93 months)
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
        
        echo "Months: $months → Level: $level\n";
        
        $weight = intval($c['weight']);
        
        if ($weight == 15) $score = $level * 1.5;
        elseif ($weight == 20) $score = $level * 2;
        elseif ($weight == 10) $score = $level;
        elseif ($weight == 5) $score = $level / 2;
        else $score = $level;
        
        echo "Expected: $level × " . ($weight == 15 ? "1.5" : ($weight == 20 ? "2" : "1")) . " = $score\n";
    }
}

mysqli_close($conn);
