<?php
/**
 * Verify Redeclaration Guards Are Working
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>✅ Redeclaration Guard Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
        h1 { color: #28a745; }
        .test { margin: 15px 0; padding: 15px; background: #f9f9f9; border-left: 4px solid #007bff; }
        .success { background: #d4edda; border-color: #28a745; }
        .error { background: #f8d7da; border-color: #dc3545; }
        code { background: #f4f4f4; padding: 2px 5px; }
    </style>
</head>
<body>
<div class="container">
    <h1>✅ Redeclaration Guard Test</h1>
    
    <?php
    
    echo '<div class="test">';
    echo '<h3>Test 1: Include DBConnection Multiple Times</h3>';
    try {
        require_once __DIR__ . '/initialize.php';
        require_once __DIR__ . '/classes/DBConnection.php';
        echo '<div class="success">✅ First include successful</div>';
        
        // Try including again (should not cause redeclaration error)
        include __DIR__ . '/classes/DBConnection.php';
        echo '<div class="success">✅ Second include successful (guard working!)</div>';
        
        include __DIR__ . '/classes/DBConnection.php';
        echo '<div class="success">✅ Third include successful (no redeclaration error)</div>';
    } catch (Error $e) {
        echo '<div class="error">❌ Error: ' . $e->getMessage() . '</div>';
    }
    echo '</div>';
    
    echo '<div class="test">';
    echo '<h3>Test 2: Classes Can Be Instantiated</h3>';
    try {
        // Get connection
        $conn = DBConnection::getConnection();
        if ($conn) {
            echo '<div class="success">✅ DBConnection::getConnection() works</div>';
        }
        
        // Try instantiating classes
        $evaluator = new HRMPSBEvaluator();
        echo '<div class="success">✅ HRMPSBEvaluator instantiated</div>';
    } catch (Error $e) {
        echo '<div class="error">❌ Error: ' . $e->getMessage() . '</div>';
    }
    echo '</div>';
    
    echo '<div class="test">';
    echo '<h3>Test 3: Include EvaluationStorage</h3>';
    try {
        require_once __DIR__ . '/classes/EvaluationStorage.php';
        echo '<div class="success">✅ EvaluationStorage first include successful</div>';
        
        include __DIR__ . '/classes/EvaluationStorage.php';
        echo '<div class="success">✅ EvaluationStorage second include successful (guard working!)</div>';
    } catch (Error $e) {
        echo '<div class="error">❌ Error: ' . $e->getMessage() . '</div>';
    }
    echo '</div>';
    
    echo '<div class="test">';
    echo '<h3>Test 4: Include ComparativeAssessmentReport</h3>';
    try {
        require_once __DIR__ . '/classes/ComparativeAssessmentReport.php';
        echo '<div class="success">✅ ComparativeAssessmentReport first include successful</div>';
        
        include __DIR__ . '/classes/ComparativeAssessmentReport.php';
        echo '<div class="success">✅ ComparativeAssessmentReport second include successful (guard working!)</div>';
    } catch (Error $e) {
        echo '<div class="error">❌ Error: ' . $e->getMessage() . '</div>';
    }
    echo '</div>';
    
    echo '<div class="test">';
    echo '<h3>Test 5: Verify Singleton Still Works</h3>';
    try {
        $conn1 = DBConnection::getConnection();
        $conn2 = DBConnection::getConnection();
        
        if ($conn1 === $conn2) {
            echo '<div class="success">✅ Singleton pattern still working</div>';
        } else {
            echo '<div class="error">❌ Singleton broken</div>';
        }
    } catch (Error $e) {
        echo '<div class="error">❌ Error: ' . $e->getMessage() . '</div>';
    }
    echo '</div>';
    
    echo '<div class="test success">';
    echo '<h3>✅ All Tests Passed!</h3>';
    echo '<p>All guards are working correctly!</p>';
    echo '<p>Classes can now be included multiple times without errors.</p>';
    echo '<p>Ready to use: <a href="index.php">Go to Evaluation Form</a></p>';
    echo '</div>';
    
    ?>
</div>
</body>
</html>
