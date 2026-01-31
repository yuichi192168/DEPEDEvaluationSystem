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

// NEW: Check if applicant name already exists (duplicate applicant check)
if (!isset($errors['applicant_name']) && !empty($post['applicant_name'])) {
    $conn = DBConnection::getConnection();
    if ($conn) {
        $name = $conn->real_escape_string(trim($post['applicant_name']));
        $sql = "SELECT id FROM applicants WHERE LOWER(TRIM(name)) = LOWER('$name') LIMIT 1";
        $res = $conn->query($sql);
        if ($res && $res->num_rows > 0) {
            $errors['applicant_name'] = 'An applicant with this name already exists in the system';
        }
    }
}

if (!empty($errors)) {
    echo json_encode(['valid'=>false,'errors'=>$errors]);
} else {
    echo json_encode(['valid'=>true,'errors'=>[]]);
}

?>
