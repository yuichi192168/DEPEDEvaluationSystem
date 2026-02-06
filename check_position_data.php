<?php
require 'config/database.php';

$conn = getDBConnection();

// Check database record
$dbResult = mysqli_query($conn, "SELECT position_group FROM comparative_assessment_results WHERE application_code = 'NT-AOII-2026-003'");
$dbRow = mysqli_fetch_assoc($dbResult);

// Check draft 
$draftResult = mysqli_query($conn, "SELECT data FROM drafts WHERE application_code = 'NT-AOII-2026-003'");
$draftRow = mysqli_fetch_assoc($draftResult);
$draftData = json_decode($draftRow['data'], true);

echo "DATABASE position_group: '{$dbRow['position_group']}'\n";
echo "DRAFT position_group: '{$draftData['position_group']}'\n";
echo "DRAFT salary_grade: '{$draftData['salary_grade']}'\n";
echo "DRAFT category: '{$draftData['category']}'\n\n";

// Try extracting number from salary_grade
$salaryGrade = $draftData['salary_grade'];
if (preg_match('/(\d+)/', $salaryGrade, $matches)) {
    echo "Extracted SG number: " . $matches[1] . "\n";
}

mysqli_close($conn);
