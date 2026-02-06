<?php
require 'config/database.php';

$conn = getDBConnection();

$result = mysqli_query($conn, "SELECT data FROM drafts WHERE application_code = 'NT-AOII-2026-003'");
$row = mysqli_fetch_assoc($result);
$data = json_decode($row['data'], true);

echo "FULL DRAFT DATA:\n";
echo "================\n";
print_r($data);

mysqli_close($conn);
