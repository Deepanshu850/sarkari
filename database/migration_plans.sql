USE sarkari;

-- Add plan column to users
ALTER TABLE users ADD COLUMN plan ENUM('starter','pro','ultimate') DEFAULT 'starter' AFTER role;
ALTER TABLE users ADD COLUMN plan_blueprints_allowed TINYINT UNSIGNED DEFAULT 1 AFTER plan;
ALTER TABLE users ADD COLUMN plan_purchased_at DATETIME DEFAULT NULL AFTER plan_blueprints_allowed;

-- Add plan to payments for tracking
ALTER TABLE payments ADD COLUMN plan VARCHAR(20) DEFAULT 'single' AFTER currency;

-- Count how many blueprints a user has used
-- (we'll check this in code against plan_blueprints_allowed)
