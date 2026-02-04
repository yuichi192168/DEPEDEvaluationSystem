<?php
require_once 'classes/DBConnection.php';

$conn = DBConnection::getConnection();

echo "Checking applicants and their qualification data:\n\n";

// Check applicants
$result = $conn->query("
    SELECT 
        a.id, 
        a.name,
        (SELECT COUNT(*) FROM applicant_qualifications q WHERE q.applicant_id = a.id) as has_qual,
        (SELECT COUNT(*) FROM evaluations e WHERE e.applicant_id = a.id) as has_eval
    FROM applicants a
    WHERE a.archive_status != 'archived'
    ORDER BY a.id
    LIMIT 20
");

echo "ID | Name | Qualifications | Evaluations\n";
echo "------------------------------------------------------------\n";

while ($row = $result->fetch_assoc()) {
    printf("%d | %s | %d | %d\n", 
        $row['id'], 
        $row['name'], 
        $row['has_qual'],
        $row['has_eval']
    );
}

echo "\n\nChecking applicant_qualifications table structure:\n";
$result = $conn->query("DESCRIBE applicant_qualifications");
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
?>
