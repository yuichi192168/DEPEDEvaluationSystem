<?php
require_once __DIR__ . '/../classes/AssessmentProcessor.php';
header('Content-Type: application/json');

$position = $_GET['position'] ?? null;
if (!$position) {
    echo json_encode(['error'=>'position query parameter required. Example: ?position=Teacher%20I']);
    exit;
}

$qs = AssessmentProcessor::getQS($position);
echo json_encode($qs, JSON_PRETTY_PRINT);

?>
