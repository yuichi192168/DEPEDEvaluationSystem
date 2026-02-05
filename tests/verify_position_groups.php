<?php
/**
 * Verification Script: Check and Update Position Groups in Database
 */

require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();

echo "<h2>Position Groups Verification & Update</h2>";

// Check current values in positions table
echo "<h3>Positions Table</h3>";
$result = $conn->query("SELECT DISTINCT position_group FROM positions ORDER BY position_group");
echo "<p><strong>Current position_group values in positions table:</strong></p>";
echo "<ul>";
while ($row = $result->fetch_assoc()) {
    echo "<li>" . htmlspecialchars($row['position_group']) . "</li>";
}
echo "</ul>";

// Check applicants table
echo "<h3>Applicants Table</h3>";
$result = $conn->query("SELECT DISTINCT position_group FROM applicants ORDER BY position_group");
echo "<p><strong>Current position_group values in applicants table:</strong></p>";
echo "<ul>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row['position_group']) . "</li>";
    }
} else {
    echo "<li>No records found</li>";
}
echo "</ul>";

// Check evaluations table
echo "<h3>Evaluations Table</h3>";
$result = $conn->query("SELECT DISTINCT position_group FROM evaluations ORDER BY position_group");
echo "<p><strong>Current position_group values in evaluations table:</strong></p>";
echo "<ul>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row['position_group']) . "</li>";
    }
} else {
    echo "<li>No records found</li>";
}
echo "</ul>";

// Count records with old values
echo "<h3>Migration Status</h3>";
$tables = ['positions', 'applicants', 'evaluations'];
$oldValues = ['A', 'B', 'C'];
$needsUpdate = false;
$totalOld = 0;

foreach ($tables as $table) {
    foreach ($oldValues as $val) {
        $result = $conn->query("SELECT COUNT(*) as cnt FROM $table WHERE position_group = '$val'");
        $row = $result->fetch_assoc();
        if ($row['cnt'] > 0) {
            echo "<p style='color: orange;'>⚠ Found $row[cnt] records with '$val' in $table table</p>";
            $needsUpdate = true;
            $totalOld += $row['cnt'];
        }
    }
}

if ($totalOld > 0) {
    echo "<p style='color: red;'><strong>❌ Found $totalOld records still using old A/B/C values</strong></p>";
    echo "<h3>Updating Database...</h3>";
    
    // Perform the updates
    $updates = [
        "UPDATE positions SET position_group = 'NON-TEACHING LEVEL I' WHERE position_group = 'A'",
        "UPDATE positions SET position_group = 'NON-TEACHING LEVEL II' WHERE position_group = 'B'",
        "UPDATE positions SET position_group = 'SCHOOL ADMINISTRATION' WHERE position_group = 'C'",
        "UPDATE applicants SET position_group = 'NON-TEACHING LEVEL I' WHERE position_group = 'A'",
        "UPDATE applicants SET position_group = 'NON-TEACHING LEVEL II' WHERE position_group = 'B'",
        "UPDATE applicants SET position_group = 'SCHOOL ADMINISTRATION' WHERE position_group = 'C'",
        "UPDATE evaluations SET position_group = 'NON-TEACHING LEVEL I' WHERE position_group = 'A'",
        "UPDATE evaluations SET position_group = 'NON-TEACHING LEVEL II' WHERE position_group = 'B'",
        "UPDATE evaluations SET position_group = 'SCHOOL ADMINISTRATION' WHERE position_group = 'C'"
    ];
    
    $totalUpdated = 0;
    foreach ($updates as $sql) {
        if ($conn->query($sql)) {
            $affected = $conn->affected_rows;
            if ($affected > 0) {
                echo "<p>✓ Updated $affected record(s): " . htmlspecialchars(substr($sql, 0, 70)) . "...</p>";
                $totalUpdated += $affected;
            }
        } else {
            echo "<p style='color: red;'>❌ Error: " . $conn->error . "</p>";
        }
    }
    
    echo "<p style='color: green;'><strong>✓ Completed: $totalUpdated records updated</strong></p>";
    
    // Verify updates
    echo "<h3>Verification After Update</h3>";
    echo "<p><strong>Positions table:</strong></p>";
    $result = $conn->query("SELECT DISTINCT position_group FROM positions ORDER BY position_group");
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row['position_group']) . "</li>";
    }
    echo "</ul>";
    
    echo "<p><strong>Applicants table:</strong></p>";
    $result = $conn->query("SELECT DISTINCT position_group FROM applicants ORDER BY position_group");
    echo "<ul>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($row['position_group']) . "</li>";
        }
    } else {
        echo "<li>No records found</li>";
    }
    echo "</ul>";
    
    echo "<p><strong>Evaluations table:</strong></p>";
    $result = $conn->query("SELECT DISTINCT position_group FROM evaluations ORDER BY position_group");
    echo "<ul>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($row['position_group']) . "</li>";
        }
    } else {
        echo "<li>No records found</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: green;'><strong>✓ All records are already updated! No A/B/C values found.</strong></p>";
}

echo "<hr>";
echo "<p><a href='index.php'>Return to Home</a></p>";
$conn->close();
?>
