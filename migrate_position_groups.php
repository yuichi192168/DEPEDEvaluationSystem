<?php
/**
 * Database Migration: Update Position Groups from A/B/C to Full Names
 * This script updates all existing position_group values in the database
 */

require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();

echo "<h2>Position Group Migration</h2>";
echo "<p>Updating position_group values from A/B/C to full names...</p>";

// Mapping of old values to new values
$mappings = [
    'A' => 'NON-TEACHING LEVEL I',
    'B' => 'NON-TEACHING LEVEL II',
    'C' => 'SCHOOL ADMINISTRATION'
];

$tables = ['positions', 'applicants', 'evaluations'];
$totalUpdated = 0;

foreach ($tables as $table) {
    echo "<h3>Processing table: $table</h3>";
    
    foreach ($mappings as $oldValue => $newValue) {
        $query = "UPDATE $table SET position_group = ? WHERE position_group = ?";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            echo "<p style='color: red;'>❌ Error preparing statement for $table: " . $conn->error . "</p>";
            continue;
        }
        
        $stmt->bind_param("ss", $newValue, $oldValue);
        
        if (!$stmt->execute()) {
            echo "<p style='color: red;'>❌ Error executing update for $table: " . $stmt->error . "</p>";
            $stmt->close();
            continue;
        }
        
        $affectedRows = $stmt->affected_rows;
        $totalUpdated += $affectedRows;
        
        echo "<p>✓ Updated $affectedRows rows in $table: '$oldValue' → '$newValue'</p>";
        $stmt->close();
    }
}

// Also modify the ENUM column to include new values
echo "<h3>Updating database schema...</h3>";

$alterSQL = "ALTER TABLE positions MODIFY position_group ENUM('TEACHING', 'NON-TEACHING LEVEL I', 'NON-TEACHING LEVEL II', 'RELATED TEACHING', 'HIGHER TEACHING', 'SCHOOL ADMINISTRATION') NOT NULL";
if ($conn->query($alterSQL)) {
    echo "<p>✓ Updated positions table schema</p>";
} else {
    echo "<p style='color: orange;'>⚠ Could not modify positions table (may already be updated): " . $conn->error . "</p>";
}

$alterSQL = "ALTER TABLE applicants MODIFY position_group VARCHAR(50) NOT NULL DEFAULT 'NON-TEACHING LEVEL I'";
if ($conn->query($alterSQL)) {
    echo "<p>✓ Updated applicants table schema</p>";
} else {
    echo "<p style='color: orange;'>⚠ Could not modify applicants table: " . $conn->error . "</p>";
}

$alterSQL = "ALTER TABLE evaluations MODIFY position_group VARCHAR(50) NOT NULL DEFAULT 'NON-TEACHING LEVEL I'";
if ($conn->query($alterSQL)) {
    echo "<p>✓ Updated evaluations table schema</p>";
} else {
    echo "<p style='color: orange;'>⚠ Could not modify evaluations table: " . $conn->error . "</p>";
}

echo "<h3 style='color: green;'>✓ Migration Complete</h3>";
echo "<p>Total rows updated: <strong>$totalUpdated</strong></p>";
echo "<p><a href='index.php'>Return to Home</a></p>";
?>
