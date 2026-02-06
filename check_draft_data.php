<?php
require_once 'initialize.php';

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
$conn->set_charset("utf8mb4");

$code = 'NT-AOII-2026-003';
$stmt = $conn->prepare("SELECT data FROM drafts WHERE application_code = ? LIMIT 1");
$stmt->bind_param("s", $code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $data = json_decode($row['data'], true);
    
    echo "╔════════════════════════════════════════════════╗\n";
    echo "║   Draft Data for NT-AOII-2026-003            ║\n";
    echo "╚════════════════════════════════════════════════╝\n\n";
    
    echo "Applicant Inputs:\n";
    echo "- Experience: " . ($data['applicant_experience'] ?? 'N/A') . " months\n";
    echo "- Training: " . ($data['applicant_training'] ?? 'N/A') . " hours\n";
    echo "- Education: " . ($data['applicant_education_degree'] ?? 'N/A') . "\n";
    echo "- Performance: " . ($data['applicant_performance'] ?? 'N/A') . "\n";
    echo "- Outstanding Accomplish: " . ($data['applicant_outstanding_accomplishments'] ?? 'N/A') . "\n";
    echo "- Application of Education: " . ($data['applicant_application_of_education'] ?? 'N/A') . "\n";
    echo "- Application of L&D: " . ($data['applicant_application_of_ld'] ?? 'N/A') . "\n";
    echo "- Potential: " . ($data['applicant_potential'] ?? 'N/A') . "\n";
    
    echo "\nBaseline Inputs:\n";
    echo "- Experience: " . ($data['baseline_experience'] ?? 'N/A') . " months\n";
    echo "- Training: " . ($data['baseline_training'] ?? 'N/A') . " hours\n";
    echo "- Education: " . ($data['baseline_education_degree'] ?? 'N/A') . "\n";
    
} else {
    echo "No draft found for NT-AOII-2026-003\n";
}

$conn->close();
?>
