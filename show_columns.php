<?php
require 'config/database.php';

$conn = getDBConnection();
$result = mysqli_query($conn, 'SHOW COLUMNS FROM comparative_assessment_results');
echo "Columns in comparative_assessment_results:\n";
while($row = mysqli_fetch_assoc($result)) {
    echo "- " . $row['Field'] . "\n";
}

mysqli_close($conn);
