-- ============================================================================
-- Combined Fix: Application of Education AND Experience Scores
-- ============================================================================
-- This script fixes BOTH scoring issues in one operation:
--
-- 1. APPLICATION OF EDUCATION:
--    OLD: (rating/5) × 10 = weighted score (e.g., rating 4 → 8.00)
--    NEW: rating directly (e.g., rating 4 → 4.00)
--
-- 2. EXPERIENCE (NON-TEACHING LEVEL II):
--    OLD: baseline=24 months, weight=20 (possibly)
--    NEW: baseline=0 months, weight=15
--
-- This is the most efficient approach if both issues affect your data.
-- Note: Uses drafts table JSON data since evaluations table lacks detail columns
-- ============================================================================

-- Step 1: Create helper table to extract draft data
CREATE TEMPORARY TABLE draft_data AS
SELECT 
    car.id,
    car.application_code,
    a.name as applicant_name,
    CAST(JSON_EXTRACT(d.data, '$.applicant_application_of_education') AS DECIMAL(10,2)) as applicant_aoe,
    CAST(JSON_EXTRACT(d.data, '$.applicant_experience') AS DECIMAL(10,2)) as applicant_exp
FROM comparative_assessment_results car
LEFT JOIN drafts d ON car.application_code COLLATE utf8mb4_unicode_ci = d.application_code COLLATE utf8mb4_unicode_ci
LEFT JOIN applicants a ON car.applicant_id = a.id
WHERE car.application_of_education_score IS NOT NULL 
   OR car.experience_score IS NOT NULL;

-- Step 2: Show what will change
SELECT 
    dd.id,
    dd.application_code,
    dd.applicant_name,
    car.application_of_education_score as old_aoe_score,
    LEAST(GREATEST(COALESCE(dd.applicant_aoe, 0), 0), 5) as new_aoe_score,
    car.experience_score as old_exp_score,
    CASE
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 10 THEN 10 * 1.5
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 8 THEN 8 * 1.5
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 6 THEN 6 * 1.5
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 4 THEN 4 * 1.5
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 2 THEN 2 * 1.5
        ELSE 0
    END as new_exp_score,
    car.total_score as old_total,
    car.total_score 
        - COALESCE(car.application_of_education_score, 0)
        + LEAST(GREATEST(COALESCE(dd.applicant_aoe, 0), 0), 5)
        - COALESCE(car.experience_score, 0)
        + CASE
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 10 THEN 10 * 1.5
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 8 THEN 8 * 1.5
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 6 THEN 6 * 1.5
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 4 THEN 4 * 1.5
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 2 THEN 2 * 1.5
            ELSE 0
        END as new_total
FROM draft_data dd
INNER JOIN comparative_assessment_results car ON car.id = dd.id
ORDER BY dd.id;

-- Step 3: Create backup table
DROP TABLE IF EXISTS comparative_assessment_results_backup_combined_fix;
CREATE TABLE comparative_assessment_results_backup_combined_fix AS 
SELECT * FROM comparative_assessment_results;

SELECT 'Backup created: comparative_assessment_results_backup_combined_fix' as status;

-- Step 4: Apply combined fix
UPDATE comparative_assessment_results car
INNER JOIN draft_data dd ON car.id = dd.id
SET
    car.total_score = car.total_score 
        - COALESCE(car.application_of_education_score, 0)
        + LEAST(GREATEST(COALESCE(dd.applicant_aoe, 0), 0), 5)
        - COALESCE(car.experience_score, 0)
        + CASE
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 10 THEN 10 * 1.5
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 8 THEN 8 * 1.5
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 6 THEN 6 * 1.5
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 4 THEN 4 * 1.5
            WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 2 THEN 2 * 1.5
            ELSE 0
        END,
    car.application_of_education_score = LEAST(GREATEST(COALESCE(dd.applicant_aoe, 0), 0), 5),
    car.experience_score = CASE
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 10 THEN 10 * 1.5
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 8 THEN 8 * 1.5
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 6 THEN 6 * 1.5
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 4 THEN 4 * 1.5
        WHEN (FLOOR(COALESCE(dd.applicant_exp, 0) / 6) + 1) - 1 >= 2 THEN 2 * 1.5
        ELSE 0
    END,
    car.updated_at = NOW();

SELECT 'Combined fix applied successfully (Application of Education + Experience)' as status;

-- Step 5: Show updated state
SELECT 
    car.id,
    car.application_code,
    a.name as applicant_name,
    car.application_of_education_score,
    car.experience_score,
    car.total_score
FROM comparative_assessment_results car
LEFT JOIN applicants a ON car.applicant_id = a.id
ORDER BY car.id;

-- Step 6: Summary statistics
SELECT 
    'Summary Statistics' as info,
    COUNT(*) as total_records,
    AVG(application_of_education_score) as avg_aoe_score,
    AVG(experience_score) as avg_exp_score,
    AVG(total_score) as avg_total_score,
    MIN(total_score) as min_total,
    MAX(total_score) as max_total
FROM comparative_assessment_results;

-- Step 7: Before/After comparison
SELECT 
    b.id,
    b.application_code,
    b.application_of_education_score as old_aoe,
    c.application_of_education_score as new_aoe,
    b.application_of_education_score - c.application_of_education_score as aoe_change,
    b.experience_score as old_exp,
    c.experience_score as new_exp,
    b.experience_score - c.experience_score as exp_change,
    b.total_score as old_total,
    c.total_score as new_total,
    b.total_score - c.total_score as total_change
FROM comparative_assessment_results_backup_combined_fix b
INNER JOIN comparative_assessment_results c ON b.id = c.id
ORDER BY b.id;

-- ============================================================================
-- ROLLBACK (if needed)
-- ============================================================================
-- Uncomment to restore from backup:
--
-- UPDATE comparative_assessment_results car
-- INNER JOIN comparative_assessment_results_backup_combined_fix backup 
--   ON car.id = backup.id
-- SET 
--   car.application_of_education_score = backup.application_of_education_score,
--   car.experience_score = backup.experience_score,
--   car.total_score = backup.total_score,
--   car.updated_at = backup.updated_at;
--
-- DROP TABLE comparative_assessment_results_backup_combined_fix;
-- ============================================================================
