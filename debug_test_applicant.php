<?php
require 'config/database.php';

$conn = getDBConnection();

// Get the test applicant from drafts
$result = mysqli_query($conn, "
SELECT data FROM drafts 
WHERE application_code LIKE 'NT-AOII-2026-000%'
ORDER BY id DESC LIMIT 1
");

if ($result && $row = mysqli_fetch_assoc($result)) {
    $data = json_decode($row['data'], true);
    
    echo "TEST APPLICANT DRAFT DATA:\n";
    echo "========================================\n\n";
    
    echo "TRAINING VALUES:\n";
    printf("  applicant_training_dropdown: %s\n", $data['applicant_training_dropdown'] ?? 'MISSING');
    printf("  applicant_training (numeric): %s\n", $data['applicant_training'] ?? 'MISSING');
    printf("  baseline_training: %s\n", $data['baseline_training'] ?? 'MISSING');
    
    echo "\nEXPERIENCE VALUES:\n";
    printf("  applicant_experience_dropdown: %s\n", $data['applicant_experience_dropdown'] ?? 'MISSING');
    printf("  applicant_experience (months): %s\n", $data['applicant_experience'] ?? 'MISSING');
    printf("  baseline_experience: %s\n", $data['baseline_experience'] ?? 'MISSING');
    
    echo "\nEDUCATION VALUES:\n";
    printf("  applicant_education_degree: %s\n", $data['applicant_education_degree'] ?? 'MISSING');
    printf("  applicant_education_masters_units: %s\n", $data['applicant_education_masters_units'] ?? 'MISSING');
    printf("  baseline_education_degree: %s\n", $data['baseline_education_degree'] ?? 'MISSING');
    
} else {
    echo "No test applicant found\n";
}

mysqli_close($conn);
