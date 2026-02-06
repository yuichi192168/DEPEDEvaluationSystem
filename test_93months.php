<?php
$months = 93;

// Converting logic from index.php
if ($months < 6) $level = 0;
else if ($months < 12) $level = 1;
else if ($months < 24) $level = 2;
else if ($months < 36) $level = 3;
else if ($months < 48) $level = 4;
else if ($months < 60) $level = 5;
else if ($months < 72) $level = 6;
else if ($months < 84) $level = 7;
else if ($months < 96) $level = 8;
else if ($months < 108) $level = 9;
else $level = 10;

echo "93 months → Level: $level\n\n";

// With weight 15 (NON-TEACHING LEVEL II):
$weight = 15;
if ($weight == 15) $score = $level * 1.5;
elseif ($weight == 20) $score = $level * 2;
else $score = $level;

echo "Level $level × 1.5 (weight 15) = $score\n\n";

// But wait - this is using increment method
// Need to subtract baseline

$baseline = 0; // assuming baseline is 0
$increment = $level - $baseline;

echo "Increment = $level - $baseline = $increment\n\n";

// Now apply rubric
if ($increment >= 10) $basePoints = 10;
elseif ($increment >= 8) $basePoints = 8;
elseif ($increment >= 6) $basePoints = 6;
elseif ($increment >= 4) $basePoints = 4;
elseif ($increment >= 2) $basePoints = 2;
else $basePoints = 0;

echo "Increment $increment → Base Points: $basePoints\n";

// Scale by weight
if ($weight == 15) $finalScore = $basePoints * 1.5;
elseif ($weight == 20) $finalScore = $basePoints * 2;
else $finalScore = $basePoints;

echo "Base Points $basePoints × 1.5 = $finalScore\n";
