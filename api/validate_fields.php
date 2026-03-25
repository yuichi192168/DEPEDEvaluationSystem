<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__ . '/../classes/DBConnection.php');

$post = $_POST ?: json_decode(file_get_contents('php://input'), true);
if (!$post) $post = [];

$errors = [];

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

// Duplicate validation: block only when applicant name + application code already exists
if (!isset($errors['applicant_name']) && !empty($post['applicant_name']) && !empty($post['application_code'])) {
    $conn = DBConnection::getConnection();
    if ($conn) {
        $applicantName = trim($post['applicant_name']);
        $applicationCode = trim($post['application_code']);

        $stmt = $conn->prepare(
            "SELECT car.id
             FROM comparative_assessment_results car
             INNER JOIN applicants a ON a.id = car.applicant_id
             WHERE car.application_code = ?
               AND LOWER(TRIM(a.name)) = LOWER(TRIM(?))
             LIMIT 1"
        );
        $stmt->bind_param('ss', $applicationCode, $applicantName);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows > 0) {
            $errors['applicant_name'] = 'Duplicate applicant name and application code found';
            $errors['application_code'] = 'Duplicate applicant name and application code found';
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
