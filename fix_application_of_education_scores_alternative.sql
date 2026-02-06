-- ============================================================================
-- Alternative Fix: Recalculate from old scores
-- ============================================================================
-- Use this if the evaluations table doesn't have the original rating values
-- This reverse-engineers the rating from the old score and applies new calculation
-- ============================================================================

-- Show what will change
SELECT 
    car.id,
    car.application_code,
    a.name as applicant_name,
    car.application_of_education_score as old_score,
    -- Reverse engineer the rating: old_score = (rating/5) × 10, so rating = (old_score × 5) / 10
    ROUND((car.application_of_education_score * 5) / 10, 1) as inferred_rating,
    -- New score is just the rating (max 5)
    LEAST(ROUND((car.application_of_education_score * 5) / 10, 1), 5) as new_score,
    car.total_score as old_total,
    -- New total = old total - old score + new score
    car.total_score - car.application_of_education_score + 
        LEAST(ROUND((car.application_of_education_score * 5) / 10, 1), 5) as new_total
FROM comparative_assessment_results car
LEFT JOIN applicants a ON car.applicant_id = a.id
WHERE car.application_of_education_score IS NOT NULL
  AND car.application_of_education_score > 0
ORDER BY car.id;

-- Create backup
DROP TABLE IF EXISTS car_backup_before_aoe_fix;
CREATE TABLE car_backup_before_aoe_fix AS 
SELECT * FROM comparative_assessment_results;

-- Apply the fix
UPDATE comparative_assessment_results car
SET 
    car.total_score = car.total_score - car.application_of_education_score + 
        LEAST(ROUND((car.application_of_education_score * 5) / 10, 1), 5),
    car.application_of_education_score = LEAST(ROUND((car.application_of_education_score * 5) / 10, 1), 5),
    car.updated_at = NOW()
WHERE car.application_of_education_score IS NOT NULL
  AND car.application_of_education_score > 0;

-- Verify changes
SELECT 
    COUNT(*) as records_updated,
    AVG(application_of_education_score) as avg_new_score,
    MIN(application_of_education_score) as min_new_score,
    MAX(application_of_education_score) as max_new_score
FROM comparative_assessment_results
WHERE application_of_education_score IS NOT NULL;
