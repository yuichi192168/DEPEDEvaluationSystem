<?php
/**
 * Script to Initialize Database and Load DEPED Authorized Positions
 */

require_once 'initialize.php';

// Connect without specifying database first
$conn = @new mysqli(
    DB_SERVER,
    DB_USERNAME,
    DB_PASSWORD,
    "",
    intval(DB_PORT)
);

// Fallback to port 3306 if 3307 fails
if (!$conn || $conn->connect_errno) {
    $conn = @new mysqli(
        DB_SERVER,
        DB_USERNAME,
        DB_PASSWORD,
        "",
        3306
    );
}

if (!$conn || $conn->connect_errno) {
    echo "❌ Database connection failed: " . $conn->connect_error . "\n";
    exit(1);
}

echo "📥 Loading DEPED Authorized Positions...\n";
echo str_repeat("=", 60) . "\n";

// First, create the database if it doesn't exist
echo "Creating database if needed...\n";
$conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);

// Select the database
if (!$conn->select_db(DB_NAME)) {
    echo "❌ Failed to select database: " . $conn->error . "\n";
    exit(1);
}

// Create positions table if it doesn't exist
$createTableSQL = "
CREATE TABLE IF NOT EXISTS positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position_name VARCHAR(255) NOT NULL,
    position_group ENUM('A', 'B', 'C') NOT NULL,
    salary_grade VARCHAR(50),
    item_number VARCHAR(100),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_position_group (position_group)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

if (!$conn->query($createTableSQL)) {
    echo "❌ Failed to create positions table: " . $conn->error . "\n";
    exit(1);
}

echo "✓ Positions table ready\n";

// Read and execute the SQL file
$sqlFile = __DIR__ . '/database/insert_deped_positions.sql';

if (!file_exists($sqlFile)) {
    echo "❌ SQL file not found: $sqlFile\n";
    exit(1);
}

$sql = file_get_contents($sqlFile);

// Remove USE statement and clean up
$sql = str_replace('USE deped_evaluation;', '', $sql);
$sql = str_replace('DELETE FROM positions WHERE position_group IN (\'A\', \'B\', \'C\');', '', $sql);

// Split by semicolons and execute
$statements = array_filter(array_map('trim', explode(';', $sql)));

$successCount = 0;
$errorCount = 0;
$insertCount = 0;

foreach ($statements as $statement) {
    if (empty($statement) || strpos(trim($statement), '--') === 0) {
        continue;
    }

    if ($conn->query($statement)) {
        if (stripos($statement, 'INSERT') === 0) {
            $insertCount += $conn->affected_rows;
        }
        $successCount++;
    } else {
        echo "⚠️  Error: " . $conn->error . "\n";
        echo "   Statement: " . substr($statement, 0, 100) . "...\n";
        $errorCount++;
    }
}

// Get statistics
$result = $conn->query("SELECT COUNT(*) as total FROM positions");
$row = $result->fetch_assoc();
$totalPositions = $row['total'];

$result = $conn->query("SELECT position_group, COUNT(*) as count FROM positions GROUP BY position_group ORDER BY position_group");
$groupCounts = [];
while ($row = $result->fetch_assoc()) {
    $groupNames = ['A' => 'Teaching/Non-Teaching', 'B' => 'Related Teaching', 'C' => 'School Administration'];
    $groupCounts[$groupNames[$row['position_group']]] = $row['count'];
}

echo "\n✅ DEPED Positions Loaded Successfully!\n";
echo str_repeat("=", 60) . "\n";
echo "Total Positions in Database: $totalPositions\n";
echo "Positions Inserted: $insertCount\n";
echo "\nBreakdown by Group:\n";
foreach ($groupCounts as $group => $count) {
    echo "  - $group: $count positions\n";
}

if ($errorCount > 0) {
    echo "\n⚠️  Errors encountered: $errorCount\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "✨ Position loading complete!\n";

$conn->close();
?>
