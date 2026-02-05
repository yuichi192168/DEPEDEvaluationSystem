<?php
/**
 * Complete Position Groups Update
 * Maps all positions to the correct category based on position names
 */

require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();

echo "<h2>Complete Position Groups Update</h2>";
echo "<p>Mapping all positions to correct categories: TEACHING, NON-TEACHING LEVEL I, NON-TEACHING LEVEL II, RELATED TEACHING, HIGHER TEACHING, SCHOOL ADMINISTRATION</p>";

// Get all unique position names from the database
$result = $conn->query("SELECT DISTINCT position_name FROM positions WHERE position_name IS NOT NULL AND position_name != '' ORDER BY position_name");

echo "<h3>Analyzing Position Names</h3>";
$positionMap = [];
$categoryKeywords = [
    'SCHOOL ADMINISTRATION' => ['PRINCIPAL', 'HEAD', 'DIRECTOR', 'ADMINISTRATOR', 'ADMIN'],
    'HIGHER TEACHING' => ['MASTER TEACHER', 'SENIOR TEACHER', 'HIGHER'],
    'RELATED TEACHING' => ['RELATED TEACHING', 'GUIDANCE', 'LIBRARIAN', 'NURSE'],
    'TEACHING' => ['TEACHER', 'INSTRUCTOR'],
    'NON-TEACHING LEVEL II' => ['SENIOR', 'LEVEL II', 'SG 16', 'SG 17', 'SG 18', 'SG 19', 'SG 20', 'SG 21', 'SG 22'],
    'NON-TEACHING LEVEL I' => ['ICT', 'ACCOUNTANT', 'AUDITOR', 'ATTORNEY', 'ENGINEER', 'SURVEYOR', 'ARCHITECT']
];

$updates = [];

while ($row = $result->fetch_assoc()) {
    $posName = strtoupper($row['position_name']);
    $category = 'NON-TEACHING LEVEL I'; // Default
    
    // Try to match position name to a category
    foreach ($categoryKeywords as $cat => $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($posName, $keyword) !== false) {
                $category = $cat;
                break 2;
            }
        }
    }
    
    $positionMap[$row['position_name']] = $category;
    echo "<p>" . htmlspecialchars($row['position_name']) . " → <strong>$category</strong></p>";
    
    $updates[] = [
        'position_name' => $row['position_name'],
        'category' => $category
    ];
}

echo "<h3>Applying Updates</h3>";

$totalUpdated = 0;
foreach ($updates as $update) {
    $posName = $update['position_name'];
    $category = $update['category'];
    
    // Update positions table
    $stmt = $conn->prepare("UPDATE positions SET position_group = ? WHERE position_name = ?");
    $stmt->bind_param("ss", $category, $posName);
    if ($stmt->execute()) {
        $affected = $stmt->affected_rows;
        if ($affected > 0) {
            echo "<p>✓ Updated $affected position record(s): " . htmlspecialchars($posName) . " → $category</p>";
            $totalUpdated += $affected;
        }
    } else {
        echo "<p style='color: red;'>❌ Error updating positions: " . $stmt->error . "</p>";
    }
    $stmt->close();
    
    // Update applicants table based on position_name
    // First get position IDs for this position name
    $stmt = $conn->prepare("SELECT id FROM positions WHERE position_name = ?");
    $stmt->bind_param("s", $posName);
    $stmt->execute();
    $posResult = $stmt->get_result();
    
    while ($posRow = $posResult->fetch_assoc()) {
        $posId = $posRow['id'];
        $stmt2 = $conn->prepare("UPDATE applicants SET position_group = ? WHERE position_applied_id = ?");
        $stmt2->bind_param("si", $category, $posId);
        if ($stmt2->execute()) {
            $affected = $stmt2->affected_rows;
            if ($affected > 0) {
                echo "<p>✓ Updated $affected applicant record(s) for position ID $posId → $category</p>";
                $totalUpdated += $affected;
            }
        }
        $stmt2->close();
        
        // Update evaluations table
        $stmt2 = $conn->prepare("UPDATE evaluations SET position_group = ? WHERE position_id = ?");
        $stmt2->bind_param("si", $category, $posId);
        if ($stmt2->execute()) {
            $affected = $stmt2->affected_rows;
            if ($affected > 0) {
                echo "<p>✓ Updated $affected evaluation record(s) for position ID $posId → $category</p>";
                $totalUpdated += $affected;
            }
        }
        $stmt2->close();
    }
    $stmt->close();
}

echo "<h3 style='color: green;'>✓ Update Complete</h3>";
echo "<p><strong>Total records updated: $totalUpdated</strong></p>";

// Verify final state
echo "<h3>Final Verification</h3>";
echo "<p><strong>All position_group values in database:</strong></p>";

$result = $conn->query("SELECT DISTINCT position_group FROM positions ORDER BY position_group");
echo "<h4>Positions Table:</h4>";
echo "<ul>";
while ($row = $result->fetch_assoc()) {
    $val = htmlspecialchars($row['position_group']);
    echo "<li>$val</li>";
}
echo "</ul>";

$result = $conn->query("SELECT DISTINCT position_group FROM applicants ORDER BY position_group");
echo "<h4>Applicants Table:</h4>";
echo "<ul>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $val = htmlspecialchars($row['position_group']);
        echo "<li>$val</li>";
    }
} else {
    echo "<li>No records</li>";
}
echo "</ul>";

$result = $conn->query("SELECT DISTINCT position_group FROM evaluations ORDER BY position_group");
echo "<h4>Evaluations Table:</h4>";
echo "<ul>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $val = htmlspecialchars($row['position_group']);
        echo "<li>$val</li>";
    }
} else {
    echo "<li>No records</li>";
}
echo "</ul>";

echo "<hr>";
echo "<p><a href='index.php'>Return to Home</a></p>";
$conn->close();
?>
