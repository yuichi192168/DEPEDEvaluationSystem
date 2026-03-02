-- Database Migration: Make created_by_user_id nullable for guest submissions
-- This allows unauthenticated users to submit evaluations

-- Step 1: Drop the existing UNIQUE constraint that includes created_by_user_id
ALTER TABLE performance_evaluations 
DROP INDEX uq_perf_name_item_user;

-- Step 2: Make created_by_user_id nullable
ALTER TABLE performance_evaluations 
MODIFY COLUMN created_by_user_id INT NULL;

-- Step 3: Recreate the UNIQUE constraint without created_by_user_id
-- This allows multiple guest submissions with the same name/item combination
ALTER TABLE performance_evaluations 
ADD CONSTRAINT uq_perf_name_item UNIQUE (name, item_number);

-- Step 4: Optionally add an index for better query performance on guest records
ALTER TABLE performance_evaluations 
ADD INDEX idx_perf_guest_records (created_by_user_id);

-- Verify the changes
-- DESCRIBE performance_evaluations;
-- SHOW CREATE TABLE performance_evaluations;
