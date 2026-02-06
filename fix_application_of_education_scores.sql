-- ============================================================================
-- Fix Application of Education Scores in Database
-- ============================================================================
-- This script recalculates Application of Education scores
-- 
-- ISSUE: Database contains old scores calculated with:
--   - Old formula: (rating/5) × 10
--   - Example: rating 4 → 8.00
--
-- FIX: Recalculate using current formula:
--   - New formula: rating directly (0-5)
--   - Example: rating 4 → 4.00
--
-- Note: Data is extracted from drafts table JSON since it's not in evaluations
-- ============================================================================

-- Step 1: Create helper table to extract draft data
CREATE TEMPORARY TABLE draft_aoe_data AS
SELECT 
    car.id,
    car.application_code,
    a.name as applicant_name,
    CAST(JSON_EXTRACT(d.data, '$.applicant_application_of_education') AS DECIMAL(10,2)) as applicant_aoe
FROM comparative_assessment_results car
LEFT JOIN drafts d ON car.application_code COLLATE utf8mb4_unicode_ci = d.application_code COLLATE utf8mb4_unicode_ci
LEFT JOIN applicants a ON car.applicant_id = a.id
WHERE car.application_of_education_score IS NOT NULL;

-- Step 2: Show current state
SELECT 
    car.id,
    car.application_code,
    dd.applicant_name,
    car.application_of_education_score as old_score,
    LEAST(GREATEST(COALESCE(dd.applicant_aoe, 0), 0), 5) as new_score,
    car.total_score as old_total,
    car.total_score - car.application_of_education_score + 
        LEAST(GREATEST(COALESCE(dd.applicant_aoe, 0), 0), 5) as new_total
FROM comparative_assessment_results car
INNER JOIN draft_aoe_data dd ON car.id = dd.id
WHERE car.application_of_education_score IS NOT NULL
ORDER BY car.id;

-- Step 3: Create backup table
DROP TABLE IF EXISTS comparative_assessment_results_backup_aoe_fix;
CREATE TABLE comparative_assessment_results_backup_aoe_fix AS 
SELECT * FROM comparative_assessment_results;

SELECT 'Backup created: comparative_assessment_results_backup_aoe_fix' as status;

-- Step 4: Apply the fix
UPDATE comparative_assessment_results car
INNER JOIN draft_aoe_data dd ON car.id = dd.id
SET 
    car.total_score = car.total_score - car.application_of_education_score + 
        LEAST(GREATEST(COALESCE(dd.applicant_aoe, 0), 0), 5),
    car.application_of_education_score = LEAST(GREATEST(COALESCE(dd.applicant_aoe, 0), 0), 5),
    car.updated_at = NOW()
WHERE car.application_of_education_score IS NOT NULL;

SELECT 'Application of Education scores updated successfully' as status;

-- Step 5: Show updated state
SELECT 
    car.id,
    car.application_code,
    a.name as applicant_name,
    car.application_of_education_score,
    car.total_score
FROM comparative_assessment_results car
LEFT JOIN applicants a ON car.applicant_id = a.id
WHERE car.application_of_education_score IS NOT NULL
ORDER BY car.id;

-- Step 6: Summary statistics
SELECT 
    COUNT(*) as records_updated,
    AVG(application_of_education_score) as avg_new_score,
    MIN(application_of_education_score) as min_new_score,
    MAX(application_of_education_score) as max_new_score
FROM comparative_assessment_results
WHERE application_of_education_score IS NOT NULL;

-- Step 7: Before/After comparison
SELECT 
    b.id,
    b.application_code,
    b.application_of_education_score as old_score,
    c.application_of_education_score as new_score,
    b.application_of_education_score - c.application_of_education_score as difference,
    b.total_score as old_total,
    c.total_score as new_total,
    b.total_score - c.total_score as total_change
FROM comparative_assessment_results_backup_aoe_fix b
INNER JOIN comparative_assessment_results c ON b.id = c.id
ORDER BY b.id;

-- ============================================================================
-- ROLLBACK (if needed)
-- ============================================================================
-- Uncomment to restore from backup:
--
-- UPDATE comparative_assessment_results car
-- INNER JOIN comparative_assessment_results_backup_aoe_fix backup 
--   ON car.id = backup.id
-- SET 
--   car.application_of_education_score = backup.application_of_education_score,
--   car.total_score = backup.total_score,
--   car.updated_at = backup.updated_at;
--
-- DROP TABLE comparative_assessment_results_backup_aoe_fix;
-- ============================================================================
