<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__ . '/../classes/DBConnection.php');

$post = $_POST ?: json_decode(file_get_contents('php://input'), true);
if (!$post) $post = [];

$errors = [];
$evaluationDate = !empty($post['evaluation_date']) ? trim($post['evaluation_date']) : date('Y-m-d');
$evaluationPeriodStart = date('Y-m-01', strtotime($evaluationDate));
$evaluationPeriodEnd = date('Y-m-t', strtotime($evaluationDate));

// Required fields
$required = ['applicant_name','contact_number','position_applied','schools_division_office','job_group_sg_level'];
foreach ($required as $f) {
    if (empty($post[$f]) || trim($post[$f]) === '') {
        $errors[$f] = 'This field is required';
    }
}

// Contact number format
if (!isset($errors['contact_number']) && isset($post['contact_number'])) {
    $val = preg_replace('/[\s\-]/','', $post['contact_number']);
    if (!preg_match('/^09\d{9}$/', $val) && !preg_match('/^\+639\d{9}$/', $val)) {
        $errors['contact_number'] = 'Use format 09XXXXXXXXX or +639XXXXXXXXX';
    }
}

// Duplicate validation: block only when the same applicant and position already exist in the same evaluation period
if (!isset($errors['applicant_name']) && !empty($post['applicant_name']) && !empty($post['position_applied'])) {
    $conn = DBConnection::getConnection();
    if ($conn) {
        $applicantName = trim($post['applicant_name']);
        $positionApplied = trim($post['position_applied']);

        $stmt = $conn->prepare(
            "SELECT car.id
             FROM comparative_assessment_results car
             INNER JOIN applicants a ON a.id = car.applicant_id
             INNER JOIN positions p ON p.id = car.position_id
             WHERE LOWER(TRIM(a.name)) = LOWER(TRIM(?))
               AND LOWER(TRIM(p.position_name)) = LOWER(TRIM(?))
               AND car.assessment_date BETWEEN ? AND ?
             LIMIT 1"
        );
        $stmt->bind_param('ssss', $applicantName, $positionApplied, $evaluationPeriodStart, $evaluationPeriodEnd);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows > 0) {
            $errors['applicant_name'] = 'Duplicate applicant evaluation found for this period';
            $errors['position_applied'] = 'Duplicate applicant evaluation found for this period';
            $errors['application_code'] = 'Duplicate applicant evaluation found for this period';
        }
        $stmt->close();
    }
}

if (!empty($errors)) {
    echo json_encode(['valid'=>false,'errors'=>$errors]);
} else {
    echo json_encode(['valid'=>true,'errors'=>[]]);
}

?>
