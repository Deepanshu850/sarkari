USE sarkari;

-- Demo user passwords: all are "demo123"
-- $2y$12$ hash of "demo123"
SET @demo_hash = '$2y$12$LJ7kFLzJr1mR7YPfQXKzXOkj8Q3Z1r5wy5YDh9EKrAHjwV.G6bSdq';

-- 1. Starter Plan User
INSERT INTO users (name, email, phone, password_hash, role, plan, plan_blueprints_allowed, plan_purchased_at)
VALUES ('Rahul Sharma', 'starter@demo.com', '9876543001', @demo_hash, 'user', 'starter', 1, NOW())
ON DUPLICATE KEY UPDATE plan='starter', plan_blueprints_allowed=1;

-- 2. Pro Plan User
INSERT INTO users (name, email, phone, password_hash, role, plan, plan_blueprints_allowed, plan_purchased_at)
VALUES ('Priya Verma', 'pro@demo.com', '9876543002', @demo_hash, 'user', 'pro', 2, NOW())
ON DUPLICATE KEY UPDATE plan='pro', plan_blueprints_allowed=2;

-- 3. Ultimate Plan User
INSERT INTO users (name, email, phone, password_hash, role, plan, plan_blueprints_allowed, plan_purchased_at)
VALUES ('Amit Kumar', 'ultimate@demo.com', '9876543003', @demo_hash, 'user', 'ultimate', 3, NOW())
ON DUPLICATE KEY UPDATE plan='ultimate', plan_blueprints_allowed=3;
