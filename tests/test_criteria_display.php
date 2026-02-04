<?php
require_once 'config/evaluation_criteria.php';

echo "=== TEACHER I POSITIONS ===\n";
$criteria = getEvaluationCriteria('TEACHING POSITIONS');
echo "Total Points: " . $criteria['total_points'] . "\n";
foreach($criteria['criteria'] as $key => $c) {
    echo $key . ". " . $c['name'] . ": " . $c['max_points'] . " points\n";
}

echo "\n=== SCHOOL ADMINISTRATION ===\n";
$criteria = getEvaluationCriteria('SCHOOL ADMINISTRATION POSITION');
echo "Total Points: " . $criteria['total_points'] . "\n";
foreach($criteria['criteria'] as $key => $c) {
    echo $key . ". " . $c['name'] . ": " . $c['max_points'] . " points\n";
}

echo "\n=== RELATED TEACHING (SG 11-15) ===\n";
$criteria = getEvaluationCriteria('RELATED TEACHING POSITION', 11);
echo "Total Points: " . $criteria['total_points'] . "\n";
foreach($criteria['criteria'] as $key => $c) {
    echo $key . ". " . $c['name'] . ": " . $c['max_points'] . " points\n";
}

echo "\n=== NON-TEACHING LEVEL I (General Services) ===\n";
$criteria = getEvaluationCriteria('NON-TEACHING LEVEL I', null, 'general_services');
echo "Total Points: " . $criteria['total_points'] . "\n";
foreach($criteria['criteria'] as $key => $c) {
    echo $key . ". " . $c['name'] . ": " . $c['max_points'] . " points\n";
}

echo "\n=== NON-TEACHING LEVEL II (SG 24 Chief) ===\n";
$criteria = getEvaluationCriteria('NON-TEACHING LEVEL II', 24);
echo "Total Points: " . $criteria['total_points'] . "\n";
foreach($criteria['criteria'] as $key => $c) {
    echo $key . ". " . $c['name'] . ": " . $c['max_points'] . " points\n";
}
?>
