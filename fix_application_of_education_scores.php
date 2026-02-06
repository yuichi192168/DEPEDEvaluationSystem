<?php
/**
 * Fix Application of Education Scores
 * Updates all CAR records to use direct rating instead of weighted calculation
 * 
 * Old formula: (rating/5) × 10 = score
 * New formula: rating = score (direct, max 5)
 * 
 * Run this once to update all existing records
 */

require_once 'classes/DBConnection.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Fix Application of Education Scores</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        table { border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Fix Application of Education Scores</h1>
    <p class='info'><strong>Purpose:</strong> Recalculate Application of Education scores using direct rating (not weighted)</p>
";

try {
    $conn = DBConnection::getConnection();
    
    // Get all applicants with their Application of Education level
    $query = "SELECT 
        car.id,
        car.application_code,
        car.application_of_education_score as old_score,
        car.total_score as old_total,
        a.name as applicant_name,
        e.applicant_application_of_education as rating
    FROM comparative_assessment_results car
    LEFT JOIN applicants a ON car.applicant_id = a.id
    LEFT JOIN evaluations e ON car.applicant_id = e.applicant_id
    WHERE car.application_of_education_score IS NOT NULL
    ORDER BY car.id";
    
    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    echo "<p class='info'>Found " . $result->num_rows . " records to process.</p>";
    
    echo "<table>
        <tr>
            <th>ID</th>
            <th>Application Code</th>
            <th>Applicant</th>
            <th>Rating (0-5)</th>
            <th>Old Score</th>
            <th>New Score</th>
            <th>Old Total</th>
            <th>New Total</th>
            <th>Difference</th>
            <th>Status</th>
        </tr>";
    
    $updated = 0;
    $skipped = 0;
    
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $applicationCode = $row['application_code'];
        $applicantName = $row['applicant_name'] ?? 'Unknown';
        $rating = floatval($row['rating'] ?? 0);
        $oldScore = floatval($row['old_score']);
        $oldTotal = floatval($row['old_total']);
        
        // New calculation: Direct rating (0-5, no weighted calculation)
        $newScore = max(0, min($rating, 5));
        
        // Calculate score difference
        $scoreDiff = $newScore - $oldScore;
        
        // Calculate new total
        $newTotal = $oldTotal + $scoreDiff;
        
        if (abs($scoreDiff) < 0.01) {
            // No change needed
            echo "<tr style='background-color: #f9f9f9;'>
                <td>$id</td>
                <td>$applicationCode</td>
                <td>$applicantName</td>
                <td>$rating</td>
                <td>" . number_format($oldScore, 2) . "</td>
                <td>" . number_format($newScore, 2) . "</td>
                <td>" . number_format($oldTotal, 2) . "</td>
                <td>" . number_format($newTotal, 2) . "</td>
                <td>0.00</td>
                <td>No change</td>
            </tr>";
            $skipped++;
        } else {
            // Update the record
            $updateQuery = "UPDATE comparative_assessment_results 
                SET application_of_education_score = ?, 
                    total_score = ?,
                    updated_at = NOW()
                WHERE id = ?";
            
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("ddi", $newScore, $newTotal, $id);
            
            if ($stmt->execute()) {
                echo "<tr style='background-color: #e8f5e9;'>
                    <td>$id</td>
                    <td>$applicationCode</td>
                    <td>$applicantName</td>
                    <td>$rating</td>
                    <td>" . number_format($oldScore, 2) . "</td>
                    <td><strong>" . number_format($newScore, 2) . "</strong></td>
                    <td>" . number_format($oldTotal, 2) . "</td>
                    <td><strong>" . number_format($newTotal, 2) . "</strong></td>
                    <td>" . number_format($scoreDiff, 2) . "</td>
                    <td class='success'>✓ Updated</td>
                </tr>";
                $updated++;
            } else {
                echo "<tr style='background-color: #ffebee;'>
                    <td>$id</td>
                    <td>$applicationCode</td>
                    <td>$applicantName</td>
                    <td>$rating</td>
                    <td>" . number_format($oldScore, 2) . "</td>
                    <td>" . number_format($newScore, 2) . "</td>
                    <td>" . number_format($oldTotal, 2) . "</td>
                    <td>" . number_format($newTotal, 2) . "</td>
                    <td>" . number_format($scoreDiff, 2) . "</td>
                    <td class='error'>✗ Failed</td>
                </tr>";
            }
        }
    }
    
    echo "</table>";
    
    echo "<h2>Summary</h2>";
    echo "<p class='success'>✓ <strong>$updated</strong> records updated successfully</p>";
    echo "<p class='info'>→ <strong>$skipped</strong> records already correct (no change needed)</p>";
    echo "<p><strong>Total processed:</strong> " . ($updated + $skipped) . "</p>";
    
    echo "<p><a href='comparative_assessment_results.php?view=all'>View All Results</a> | 
          <a href='index.php'>Back to Evaluation Form</a></p>";
    
} catch (Exception $e) {
    echo "<p class='error'><strong>Error:</strong> " . $e->getMessage() . "</p>";
}

echo "</body></html>";
?>
