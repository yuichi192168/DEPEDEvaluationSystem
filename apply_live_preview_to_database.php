<?php
/**
 * LIVE PREVIEW CALCULATION FUNCTIONS (Extracted from index.php)
 * This script applies the EXACT live preview calculation logic to all database records
 * 
 * All calculation functions are copied directly from the working live preview
 * to ensure 100% consistency between preview and database
 */

require_once __DIR__ . '/initialize.php';
require_once __DIR__ . '/config/baseline_library.php';
require_once __DIR__ . '/config/evaluation_criteria.php';

// ============================================================================
// LIVE PREVIEW CALCULATION FUNCTIONS (from index.php - JavaScript converted to PHP)
// ============================================================================

/**
 * Calculate increment (same as live preview)
 * Formula: appLevel - baselineLevel (min 0)
 */
function calculateIncrement($appLevel, $baselineLevel) {
    return max(0, $appLevel - $baselineLevel);
}

/**
 * Convert experience months to level (same as live preview)
 * Formula: months < 6 ? 1 : floor(months/6) + 1
 */
function convertExperienceToLevel($months) {
    return $months < 6 ? 1 : floor($months / 6) + 1;
}

/**
 * Convert training hours to level (same as live preview)
 * Formula: hours < 8 ? 1 : floor(hours/8) + 1
 */
function convertTrainingToLevel($hours) {
    return $hours < 8 ? 1 : floor($hours / 8) + 1;
}

/**
 * Convert education degree & units to level (same as live preview)
 */
function convertEducationToLevel($degree, $mastersUnits = 0, $doctoralUnits = 0) {
    $degree = strtolower(trim($degree));
    
    if ($degree === 'doctorate' || $degree === 'phd' || $degree === 'ph.d') {
        $level = 31;
    } elseif ($degree === 'master') {
        $level = 21;
        if ($doctoralUnits > 0) {
            $level += min(floor($doctoralUnits / 3), 9);
        }
    } elseif ($degree === 'bachelor') {
        $level = 6;
        if ($mastersUnits > 0) {
            $level += min(floor($mastersUnits / 3), 14);
        }
    } else {
        $level = 0;
    }
    
    return $level;
}

/**
 * Convert increment to points using weight (same as live preview)
 * This is the KEY function that uses the scoring rubric
 */
function convertIncrementToPoints($increment, $weight) {
    // Determine base points from increment level
    $basePoints = 0;
    if ($increment >= 10) {
        $basePoints = 10;
    } elseif ($increment >= 8) {
        $basePoints = 8;
    } elseif ($increment >= 6) {
        $basePoints = 6;
    } elseif ($increment >= 4) {
        $basePoints = 4;
    } elseif ($increment >= 2) {
        $basePoints = 2;
    } else {
        $basePoints = 0;
    }
    
    // Scale points based on weight
    // This matches the rubric: weight is the max points for this criterion
    if ($weight == 20) {
        return $basePoints * 2;           // 10 base → 20 max
    } elseif ($weight == 5) {
        return $basePoints / 2;           // 10 base → 5 max
    } elseif ($weight == 15) {
        return $basePoints * 1.5;         // 10 base → 15 max
    } elseif ($weight == 25) {
        return $basePoints * 2.5;         // 10 base → 25 max
    } else {
        return $basePoints;               // Default 10 base
    }
}

/**
 * Convert rating to weighted points (same as live preview)
 * Formula: (rating / 5) × weight
 * Used for: Performance, Application of L&D, Potential
 */
function convertRatingToWeightedPoints($rating, $weight, $maxRating = 5) {
    $rating = max(0, min(floatval($rating), $maxRating));
    $weight = floatval($weight);
    
    return $maxRating > 0 ? ($rating / $maxRating) * $weight : 0;
}

/**
 * Calculate criterion score based on scoring type (same as live preview)
 */
function calculateCriterionScore($appLevel, $baseLevel, $scoringType, $maxPoints) {
    $score = 0;
    
    if ($scoringType === 'increment') {
        // Education, Training, Experience
        $increment = calculateIncrement($appLevel, $baseLevel);
        $score = convertIncrementToPoints($increment, $maxPoints);
    } elseif ($scoringType === 'weighted') {
        // Performance, Application of L&D, Potential
        $score = convertRatingToWeightedPoints($appLevel, $maxPoints, 5);
    } elseif ($scoringType === 'direct_points') {
        // Outstanding Accomplishments
        $score = min(max(0, floatval($appLevel)), floatval($maxPoints));
    } elseif ($scoringType === 'direct_rating') {
        // Application of Education (raw value, no calculation)
        $score = floatval($appLevel);
    }
    
    return floatval($score);
}

// ============================================================================
// DATABASE RECALCULATION
// ============================================================================

// Database connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error . "\n");
}
$conn->set_charset("utf8mb4");

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║   Live Preview Calculation → Database Sync                  ║\n";
echo "║   Using exact functions from working live preview           ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Get evaluation criteria for scoring type mapping
$criteria = getEvaluationCriteria('NON-TEACHING LEVEL II', null, '');

// Map criteria keys to score types
$scoringTypes = [
    'a' => 'increment',              // Education
    'b' => 'increment',              // Training
    'c' => 'increment',              // Experience
    'd' => 'weighted',               // Performance
    'e' => 'direct_points',          // Outstanding Accomplishments
    'f' => 'direct_rating',          // Application of Education (NO CALCULATION - RAW VALUE)
    'g' => 'weighted',               // Application of L&D
    'h' => 'weighted'                // Potential
];

// Fetch all records
$query = "SELECT * FROM comparative_assessment_results ORDER BY id ASC";
$result = $conn->query($query);

if (!$result) {
    die("❌ Error fetching records: " . $conn->error . "\n");
}

$totalRecords = $result->num_rows;
$updated = 0;
$skipped = 0;
$errors = 0;
$changes = [];

echo "📊 Found {$totalRecords} records to process.\n";
echo "🔍 Using live preview calculation functions...\n\n";

while ($row = $result->fetch_assoc()) {
    $oldTotal = floatval($row['total_score']);
    $applicationCode = $row['application_code'] ?? '';
    $applicantName = $row['name'] ?? 'Unknown';
    
    printf("Processing [%d] %-35s (%s)... ", $row['id'], substr($applicantName, 0, 34), $applicationCode);
    
    try {
        // Fetch draft data containing original form inputs
        $stmt = $conn->prepare("SELECT data FROM drafts WHERE application_code = ? LIMIT 1");
        $stmt->bind_param("s", $applicationCode);
        $stmt->execute();
        $draftResult = $stmt->get_result();
        
        if ($draftResult->num_rows === 0) {
            echo "⚠️  No draft\n";
            $skipped++;
            $stmt->close();
            continue;
        }
        
        $draft = $draftResult->fetch_assoc();
        $draftData = json_decode($draft['data'], true);
        
        if (!$draftData) {
            echo "⚠️  Invalid data\n";
            $skipped++;
            $stmt->close();
            continue;
        }
        
        // Get baseline (minimum qualification standards)
        $positionKey = $draftData['position_key'] ?? 'custom';
        $baseline = getBaselineForPosition($positionKey);
        
        // ====== APPLICANT SCORES (using live preview functions) ======
        
        // Education
        $appEduLevel = convertEducationToLevel(
            $draftData['applicant_education_degree'] ?? '',
            intval($draftData['applicant_education_masters_units'] ?? 0),
            intval($draftData['applicant_education_doctoral_units'] ?? 0)
        );
        $baseEduLevel = convertEducationToLevel(
            $baseline['education']['degree'] ?? '',
            intval($baseline['education']['masters_units'] ?? 0),
            intval($baseline['education']['doctoral_units'] ?? 0)
        );
        $educationScore = calculateCriterionScore(
            $appEduLevel,
            $baseEduLevel,
            'increment',
            floatval($criteria['criteria']['a']['max_points'] ?? 5)
        );
        
        // Training
        $appTrainingLevel = convertTrainingToLevel(floatval($draftData['applicant_training'] ?? 0));
        $baseTrainingLevel = convertTrainingToLevel(floatval($baseline['training'] ?? 0));
        $trainingScore = calculateCriterionScore(
            $appTrainingLevel,
            $baseTrainingLevel,
            'increment',
            floatval($criteria['criteria']['b']['max_points'] ?? 10)
        );
        
        // Experience (THE CRITICAL ONE - uses weight 15 for NON-TEACHING LEVEL II)
        $appExperienceLevel = convertExperienceToLevel(floatval($draftData['applicant_experience'] ?? 0));
        $baseExperienceLevel = convertExperienceToLevel(floatval($baseline['experience'] ?? 0));
        $experienceScore = calculateCriterionScore(
            $appExperienceLevel,
            $baseExperienceLevel,
            'increment',
            floatval($criteria['criteria']['c']['max_points'] ?? 15)  // NON-TEACHING LEVEL II uses 15
        );
        
        // Performance
        $performanceScore = calculateCriterionScore(
            floatval($draftData['applicant_performance'] ?? 0),
            floatval($baseline['performance'] ?? 0),
            'weighted',
            floatval($criteria['criteria']['d']['max_points'] ?? 20)
        );
        
        // Outstanding Accomplishments
        $outstandingScore = calculateCriterionScore(
            floatval($draftData['applicant_outstanding_accomplishments'] ?? 0),
            floatval($baseline['outstanding_accomplishments'] ?? 0),
            'direct_points',
            floatval($criteria['criteria']['e']['max_points'] ?? 10)
        );
        
        // Application of Education (RAW VALUE - NO CAPPING)
        $appEducationScore = calculateCriterionScore(
            floatval($draftData['applicant_application_of_education'] ?? 0),
            floatval($baseline['application_of_education'] ?? 0),
            'direct_rating',  // Uses direct_rating type = raw value
            floatval($criteria['criteria']['f']['max_points'] ?? 10)
        );
        
        // Application of L&D
        $appLdScore = calculateCriterionScore(
            floatval($draftData['applicant_application_of_ld'] ?? 0),
            floatval($baseline['application_of_ld'] ?? 0),
            'weighted',
            floatval($criteria['criteria']['g']['max_points'] ?? 10)
        );
        
        // Potential
        $potentialScore = calculateCriterionScore(
            floatval($draftData['applicant_potential'] ?? 0),
            floatval($baseline['potential'] ?? 0),
            'weighted',
            floatval($criteria['criteria']['h']['max_points'] ?? 20)
        );
        
        // Calculate new total
        $newTotal = $educationScore + $trainingScore + $experienceScore + $performanceScore +
                    $outstandingScore + $appEducationScore + $appLdScore + $potentialScore;
        
        $difference = $newTotal - $oldTotal;
        
        // Update database with new scores
        $updateStmt = $conn->prepare("
            UPDATE comparative_assessment_results 
            SET education_score = ?,
                training_score = ?,
                experience_score = ?,
                performance_score = ?,
                outstanding_accomplishments_score = ?,
                application_of_education_score = ?,
                application_of_ld_score = ?,
                potential_score = ?,
                total_score = ?
            WHERE id = ?
        ");
        
        $updateStmt->bind_param(
            "dddddddddi",
            $educationScore,
            $trainingScore,
            $experienceScore,
            $performanceScore,
            $outstandingScore,
            $appEducationScore,
            $appLdScore,
            $potentialScore,
            $newTotal,
            $row['id']
        );
        
        if ($updateStmt->execute()) {
            if (abs($difference) > 0.01) {
                printf("✅ UPDATED (%.2f → %.2f, %+.2f)\n", $oldTotal, $newTotal, $difference);
                $changes[] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'old' => $oldTotal,
                    'new' => $newTotal,
                    'diff' => $difference,
                    'exp' => $experienceScore,
                    'aoe' => $appEducationScore
                ];
            } else {
                echo "✓ No change needed\n";
            }
            $updated++;
        } else {
            printf("❌ Update failed: %s\n", $updateStmt->error);
            $errors++;
        }
        
        $updateStmt->close();
        $stmt->close();
        
    } catch (Exception $e) {
        printf("❌ Error: %s\n", $e->getMessage());
        $errors++;
    }
}

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║                        RESULTS                               ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

echo "📊 Total records:           {$totalRecords}\n";
echo "✅ Successfully updated:    {$updated}\n";
echo "⚠️  Skipped (no draft):     {$skipped}\n";
echo "❌ Errors:                  {$errors}\n";

if (!empty($changes)) {
    echo "\n╔══════════════════════════════════════════════════════════════╗\n";
    echo "║               CHANGES MADE TO DATABASE                       ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n\n";
    
    echo sprintf(
        "%-4s | %-30s | %-8s | %-8s | %-8s | %-5s | %-5s\n",
        "ID",
        "Name",
        "Old",
        "New",
        "Change",
        "Exp",
        "AoE"
    );
    echo str_repeat("-", 85) . "\n";
    
    foreach ($changes as $change) {
        echo sprintf(
            "%-4d | %-30s | %8.2f | %8.2f | %+8.2f | %5.2f | %5.2f\n",
            $change['id'],
            substr($change['name'], 0, 29),
            $change['old'],
            $change['new'],
            $change['diff'],
            $change['exp'],
            $change['aoe']
        );
    }
}

echo "\n✅ Database sync with live preview complete!\n\n";

$conn->close();
?>
