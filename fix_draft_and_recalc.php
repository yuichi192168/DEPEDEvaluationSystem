<?php
require 'config/database.php';

$conn = getDBConnection();

// Get existing draft
$result = mysqli_query($conn, "SELECT data FROM drafts WHERE application_code = 'NT-AOII-2026-003'");
$row = mysqli_fetch_assoc($result);
$data = json_decode($row['data'], true);

// Add missing fields
$data['salary_grade'] = 'SG 2';
$data['category'] = 'NON-TEACHING LEVEL II';

// Update draft
$jsonData = json_encode($data);
$updateQuery = "UPDATE drafts SET data = ? WHERE application_code = 'NT-AOII-2026-003'";
$stmt = mysqli_prepare($conn, $updateQuery);
mysqli_stmt_bind_param($stmt, 's', $jsonData);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo "✅ Draft updated with salary_grade and category\n\n";
    
    // Now recalculate
    require_once 'classes/HRMPSBEvaluator.php';
    
    $evaluator = new HRMPSBEvaluator($data['position_group'], $data['salary_grade'], $data['category']);
    
    $educationScore = $evaluator->calculateEducationScore(
        $data['applicant_education_degree'],
        $data['applicant_education_masters_units'],
        $data['applicant_education_doctoral_units']
    );
    
    $trainingScore = $evaluator->calculateTrainingScore($data['applicant_training']);
    $experienceScore = $evaluator->calculateExperienceScore($data['applicant_experience']);
    $performanceScore = $evaluator->calculatePerformanceScore($data['applicant_performance']);
    $outstandingScore = $evaluator->calculateOutstandingAccomplishmentsScore($data['applicant_outstanding_accomplishments']);
    $aoeScore = $evaluator->calculateApplicationOfEducationScore($data['applicant_application_of_education']);
    $aoldScore = $evaluator->calculateApplicationOfLDScore($data['applicant_application_of_ld']);
    $potentialScore = $evaluator->calculatePotentialScore($data['applicant_potential']);
    
    $totalScore = $educationScore + $trainingScore + $experienceScore + $performanceScore + 
                  $outstandingScore + $aoeScore + $aoldScore + $potentialScore;
    
    echo "📊 Recalculated Scores:\n";
    echo "────────────────────────────────\n";
    printf("Education:      %.2f\n", $educationScore);
    printf("Training:       %.2f\n", $trainingScore);
    printf("Experience:     %.2f\n", $experienceScore);
    printf("Performance:    %.2f\n", $performanceScore);
    printf("Outstanding:    %.2f\n", $outstandingScore);
    printf("App of Edu:     %.2f\n", $aoeScore);
    printf("App of L&D:     %.2f\n", $aoldScore);
    printf("Potential:      %.2f\n", $potentialScore);
    echo "────────────────────────────────\n";
    printf("TOTAL:          %.2f\n\n", $totalScore);
    
    // Update database
    $updateDB = "UPDATE comparative_assessment_results SET 
        education_score = ?, training_score = ?, experience_score = ?,
        performance_score = ?, outstanding_accomplishments_score = ?,
        application_of_education_score = ?, application_of_ld_score = ?,
        potential_score = ?, total_score = ?
        WHERE application_code = 'NT-AOII-2026-003'";
    
    $stmt2 = mysqli_prepare($conn, $updateDB);
    mysqli_stmt_bind_param($stmt2, 'ddddddddd', 
        $educationScore, $trainingScore, $experienceScore,
        $performanceScore, $outstandingScore, $aoeScore,
        $aoldScore, $potentialScore, $totalScore
    );
    mysqli_stmt_execute($stmt2);
    
    if (mysqli_stmt_affected_rows($stmt2) > 0) {
        echo "✅ Database updated!\n";
    } else {
        echo "ℹ️  Database unchanged (values same)\n";
    }
    
} else {
    echo "❌ Failed to update draft\n";
}

mysqli_close($conn);
