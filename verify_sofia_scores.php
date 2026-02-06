<?php
require 'config/database.php';

$conn = getDBConnection();

$result = mysqli_query($conn, "
SELECT 
    id, 
    application_code,
    education_score,
    training_score,
    experience_score,
    performance_score,
    outstanding_accomplishments_score,
    application_of_education_score,
    application_of_ld_score,
    potential_score,
    total_score
FROM comparative_assessment_results 
WHERE application_code = 'NT-AOII-2026-003'
");

if ($row = mysqli_fetch_assoc($result)) {
    echo "📋 Sofia's Complete Score Breakdown:\n";
    echo "────────────────────────────────────────\n";
    printf("Education Score:               %.2f\n", $row['education_score']);
    printf("Training Score:                %.2f\n", $row['training_score']);
    printf("Experience Score:              %.2f\n", $row['experience_score']);
    printf("Performance Score:             %.2f\n", $row['performance_score']);
    printf("Outstanding Accomplishments:   %.2f\n", $row['outstanding_accomplishments_score']);
    printf("Application of Education:      %.2f\n", $row['application_of_education_score']);
    printf("Application of L&D:            %.2f\n", $row['application_of_ld_score']);
    printf("Potential Score:               %.2f\n", $row['potential_score']);
    echo "────────────────────────────────────────\n";
    printf("TOTAL SCORE:                   %.2f\n", $row['total_score']);
} else {
    echo "No record found\n";
}

mysqli_close($conn);
?>
