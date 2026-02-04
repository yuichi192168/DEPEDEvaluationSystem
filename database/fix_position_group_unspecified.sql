-- Fix missing applicants.position_group and align enums
START TRANSACTION;

-- Ensure enum supports full groups
ALTER TABLE `positions`
  MODIFY `position_group` ENUM(
    'TEACHING',
    'NON-TEACHING LEVEL I',
    'NON-TEACHING LEVEL II',
    'RELATED TEACHING',
    'HIGHER TEACHING',
    'SCHOOL ADMINISTRATION'
  ) NOT NULL;

ALTER TABLE `applicants`
  MODIFY `position_group` ENUM(
    'TEACHING',
    'NON-TEACHING LEVEL I',
    'NON-TEACHING LEVEL II',
    'RELATED TEACHING',
    'HIGHER TEACHING',
    'SCHOOL ADMINISTRATION'
  ) NOT NULL DEFAULT 'NON-TEACHING LEVEL I';

ALTER TABLE `evaluations`
  MODIFY `position_group` ENUM(
    'TEACHING',
    'NON-TEACHING LEVEL I',
    'NON-TEACHING LEVEL II',
    'RELATED TEACHING',
    'HIGHER TEACHING',
    'SCHOOL ADMINISTRATION'
  ) NOT NULL DEFAULT 'NON-TEACHING LEVEL I';

-- Convert any legacy A/B/C values
UPDATE `positions` SET `position_group` = 'NON-TEACHING LEVEL I' WHERE `position_group` = 'A';
UPDATE `positions` SET `position_group` = 'NON-TEACHING LEVEL II' WHERE `position_group` = 'B';
UPDATE `positions` SET `position_group` = 'SCHOOL ADMINISTRATION' WHERE `position_group` = 'C';

UPDATE `applicants` SET `position_group` = 'NON-TEACHING LEVEL I' WHERE `position_group` = 'A';
UPDATE `applicants` SET `position_group` = 'NON-TEACHING LEVEL II' WHERE `position_group` = 'B';
UPDATE `applicants` SET `position_group` = 'SCHOOL ADMINISTRATION' WHERE `position_group` = 'C';

UPDATE `evaluations` SET `position_group` = 'NON-TEACHING LEVEL I' WHERE `position_group` = 'A';
UPDATE `evaluations` SET `position_group` = 'NON-TEACHING LEVEL II' WHERE `position_group` = 'B';
UPDATE `evaluations` SET `position_group` = 'SCHOOL ADMINISTRATION' WHERE `position_group` = 'C';

-- Backfill missing/blank groups from positions table
UPDATE `applicants` a
JOIN `positions` p ON a.position_applied_id = p.id
SET a.position_group = p.position_group
WHERE a.position_group IS NULL OR a.position_group = '';

UPDATE `evaluations` e
JOIN `positions` p ON e.position_id = p.id
SET e.position_group = p.position_group
WHERE e.position_group IS NULL OR e.position_group = '';

COMMIT;
