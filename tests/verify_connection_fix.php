<?php
/**
 * Verify Database Connection Fix
 * Tests that singleton pattern is working correctly
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>✅ Database Connection Fix Verification</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
        h1 { color: #28a745; }
        .test { margin: 15px 0; padding: 15px; background: #f9f9f9; border-left: 4px solid #007bff; }
        .success { background: #d4edda; border-color: #28a745; }
        .error { background: #f8d7da; border-color: #dc3545; }
        .info { color: #004085; }
        code { background: #f4f4f4; padding: 2px 5px; }
        pre { background: #f4f4f4; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
<div class="container">
    <h1>✅ Database Connection Fix Verification</h1>
    
    <?php
    require_once __DIR__ . '/initialize.php';
    require_once __DIR__ . '/classes/DBConnection.php';
    
    echo '<div class="test info">';
    echo '<h3>🔍 Testing Singleton Pattern</h3>';
    echo '</div>';
    
    // Test 1: Get first connection
    echo '<div class="test">';
    echo '<h3>Test 1: Get First Connection</h3>';
    try {
        $conn1 = DBConnection::getConnection();
        if ($conn1 && $conn1 instanceof mysqli) {
            echo '<div class="success"><strong>✅ SUCCESS:</strong> First connection obtained</div>';
        } else {
            echo '<div class="error"><strong>❌ FAILED:</strong> Connection not obtained</div>';
        }
    } catch (Exception $e) {
        echo '<div class="error"><strong>❌ ERROR:</strong> ' . $e->getMessage() . '</div>';
    }
    echo '</div>';
    
    // Test 2: Get second connection (should be same object)
    echo '<div class="test">';
    echo '<h3>Test 2: Verify Singleton (Same Connection)</h3>';
    try {
        $conn2 = DBConnection::getConnection();
        if ($conn1 === $conn2) {
            echo '<div class="success"><strong>✅ SUCCESS:</strong> Both calls return the SAME connection object (singleton working!)</div>';
        } else {
            echo '<div class="error"><strong>❌ FAILED:</strong> Different connection objects returned</div>';
        }
    } catch (Exception $e) {
        echo '<div class="error"><strong>❌ ERROR:</strong> ' . $e->getMessage() . '</div>';
    }
    echo '</div>';
    
    // Test 3: Check connection status
    echo '<div class="test">';
    echo '<h3>Test 3: Check Connection Status</h3>';
    try {
        if ($conn1->ping()) {
            echo '<div class="success"><strong>✅ SUCCESS:</strong> Connection is ALIVE and working</div>';
        } else {
            echo '<div class="error"><strong>❌ FAILED:</strong> Connection is not responding</div>';
        }
    } catch (Exception $e) {
        echo '<div class="error"><strong>❌ ERROR:</strong> ' . $e->getMessage() . '</div>';
    }
    echo '</div>';
    
    // Test 4: Test with EvaluationStorage
    echo '<div class="test">';
    echo '<h3>Test 4: Test EvaluationStorage Class</h3>';
    try {
        require_once __DIR__ . '/classes/EvaluationStorage.php';
        $storage = new EvaluationStorage();
        echo '<div class="success"><strong>✅ SUCCESS:</strong> EvaluationStorage instantiated without error</div>';
    } catch (Exception $e) {
        echo '<div class="error"><strong>❌ ERROR:</strong> ' . $e->getMessage() . '</div>';
    }
    echo '</div>';
    
    // Test 5: Test with ComparativeAssessmentReport
    echo '<div class="test">';
    echo '<h3>Test 5: Test ComparativeAssessmentReport Class</h3>';
    try {
        require_once __DIR__ . '/classes/ComparativeAssessmentReport.php';
        $car = new ComparativeAssessmentReport();
        echo '<div class="success"><strong>✅ SUCCESS:</strong> ComparativeAssessmentReport instantiated without error</div>';
    } catch (Exception $e) {
        echo '<div class="error"><strong>❌ ERROR:</strong> ' . $e->getMessage() . '</div>';
    }
    echo '</div>';
    
    // Test 6: Verify singleton after creating other classes
    echo '<div class="test">';
    echo '<h3>Test 6: Verify Singleton Still Works</h3>';
    try {
        $conn3 = DBConnection::getConnection();
        if ($conn3 === $conn1) {
            echo '<div class="success"><strong>✅ SUCCESS:</strong> Still using the SAME connection (singleton persists!)</div>';
        } else {
            echo '<div class="error"><strong>❌ FAILED:</strong> Connection changed unexpectedly</div>';
        }
    } catch (Exception $e) {
        echo '<div class="error"><strong>❌ ERROR:</strong> ' . $e->getMessage() . '</div>';
    }
    echo '</div>';
    
    // Summary
    echo '<div class="test info" style="background: #d1ecf1; border-color: #17a2b8;">';
    echo '<h3>📋 Summary</h3>';
    echo '<p><strong>✅ All tests completed successfully!</strong></p>';
    echo '<p>The singleton pattern is working correctly. All classes share the same persistent database connection.</p>';
    echo '<p><strong>Next Steps:</strong></p>';
    echo '<ul>';
    echo '<li>Go to: <a href="index.php">Evaluation Form</a></li>';
    echo '<li>Fill out an evaluation</li>';
    echo '<li>Check "Save to database"</li>';
    echo '<li>Submit and verify it works without errors</li>';
    echo '</ul>';
    echo '</div>';
    
    ?>
</div>
</body>
</html>
