-- ============================================================================
-- Fix Experience Scores in Database
-- ============================================================================
-- This script recalculates experience scores for NON-TEACHING LEVEL II positions
-- 
-- ISSUE: Database contains old experience scores calculated with:
--   - Old baseline: 24 months (Level 5) 
--   - Old weight: Possibly 20 (from previous configuration)
--
-- FIX: Recalculate using current configuration:
--   - New baseline: 0 months (Level 1)
--   - New weight: 15 (for NON-TEACHING LEVEL II, SG 10-22, 27)
--
-- Formula:
--   appLevel = FLOOR(months / 6) + 1
--   baseLevel = 1 (for 0 months)
--   increment = appLevel - baseLevel = appLevel - 1
--   basePoints = rubric(increment): 0→0, 2-3→2, 4-5→4, 6-7→6, 8-9→8, 10+→10
--   score = basePoints × 1.5
-- ============================================================================

-- Step 1: Create helper table to extract draft data
CREATE TEMPORARY TABLE draft_exp_data AS
SELECT 
    car.id,
    car.application_code,
    a.name as applicant_name,
    CAST(JSON_EXTRACT(d.data, '$.applicant_experience') AS DECIMAL(10,2)) as applicant_exp
FROM comparative_assessment_results car
LEFT JOIN drafts d ON car.application_code COLLATE utf8mb4_unicode_ci = d.application_code COLLATE utf8mb4_unicode_ci
LEFT JOIN applicants a ON car.applicant_id = a.id
WHERE car.experience_score IS NOT NULL;

-- Step 2: Show current state
SELECT 
    car.id,
    car.application_code,
    dd.applicant_name,
    dd.applicant_exp as experience_months,
    FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1 as app_level,
    car.experience_score as old_score,
    CASE
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 10 THEN 10 * 1.5
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 8 THEN 8 * 1.5
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 6 THEN 6 * 1.5
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 4 THEN 4 * 1.5
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 2 THEN 2 * 1.5
        ELSE 0
    END as new_score,
    car.total_score as old_total,
    car.total_score - car.experience_score + 
        CASE
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 10 THEN 10 * 1.5
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 8 THEN 8 * 1.5
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 6 THEN 6 * 1.5
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 4 THEN 4 * 1.5
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 2 THEN 2 * 1.5
            ELSE 0
        END as new_total
FROM comparative_assessment_results car
INNER JOIN draft_exp_data dd ON car.id = dd.id
WHERE car.experience_score IS NOT NULL
ORDER BY car.id;

-- Step 2: Create backup table
DROP TABLE IF EXISTS comparative_assessment_results_backup_exp_fix;
CREATE TABLE comparative_assessment_results_backup_exp_fix AS 
SELECT * FROM comparative_assessment_results;

SELECT 'Backup created: comparative_assessment_results_backup_exp_fix' as status;

-- Step 4: Apply the fix
UPDATE comparative_assessment_results car
INNER JOIN draft_exp_data dd ON car.id = dd.id
SET
    car.total_score = car.total_score - car.experience_score + 
        CASE
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 10 THEN 10 * 1.5
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 8 THEN 8 * 1.5
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 6 THEN 6 * 1.5
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 4 THEN 4 * 1.5
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 2 THEN 2 * 1.5
            ELSE 0
        END,
    car.experience_score = CASE
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 10 THEN 10 * 1.5
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 8 THEN 8 * 1.5
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 6 THEN 6 * 1.5
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 4 THEN 4 * 1.5
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 2 THEN 2 * 1.5
        ELSE 0
    END,
    car.updated_at = NOW()
WHERE car.experience_score IS NOT NULL;

SELECT 'Experience scores updated successfully' as status;

-- Step 4: Show updated state
SELECT 
    car.id,
    car.application_code,
    a.name as applicant_name,
    car.experience_score as new_score,
    car.total_score as new_total
FROM comparative_assessment_results car
LEFT JOIN applicants a ON car.applicant_id = a.id
WHERE car.experience_score IS NOT NULL
ORDER BY car.id;

-- Step 5: Summary statistics
SELECT 
    COUNT(*) as records_updated,
    AVG(experience_score) as avg_new_score,
    MIN(experience_score) as min_new_score,
    MAX(experience_score) as max_new_score,
    AVG(total_score) as avg_total_score
FROM comparative_assessment_results
WHERE experience_score IS NOT NULL;

-- Step 6: Compare with backup
SELECT 
    'BEFORE' as state,
    id,
    application_code,
    experience_score,
    total_score
FROM comparative_assessment_results_backup_exp_fix
WHERE experience_score IS NOT NULL
UNION ALL
SELECT 
    'AFTER' as state,
    id,
    application_code,
    experience_score,
    total_score
FROM comparative_assessment_results
WHERE experience_score IS NOT NULL
ORDER BY id, state;

-- ============================================================================
-- ROLLBACK (if needed)
-- ============================================================================
-- Uncomment the following lines to restore from backup:
--
-- UPDATE comparative_assessment_results car
-- INNER JOIN comparative_assessment_results_backup_exp_fix backup 
--   ON car.id = backup.id
-- SET 
--   car.experience_score = backup.experience_score,
--   car.total_score = backup.total_score,
--   car.updated_at = backup.updated_at;
--
-- DROP TABLE comparative_assessment_results_backup_exp_fix;
-- ============================================================================
