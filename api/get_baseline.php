<?php
/**
 * API Endpoint: Get Baseline for Position
 */
header('Content-Type: application/json');

require_once __DIR__ . '/../config/baseline_library.php';

$positionKey = $_GET['position_key'] ?? 'custom';

$baseline = getBaselineForPosition($positionKey);

echo json_encode($baseline);
?>

