USE sarkari;

-- Progress tracking for each day
CREATE TABLE IF NOT EXISTS blueprint_progress (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    blueprint_id    INT UNSIGNED NOT NULL,
    day_number      TINYINT UNSIGNED NOT NULL,
    completed       TINYINT(1) DEFAULT 0,
    completed_at    DATETIME DEFAULT NULL,
    notes           TEXT DEFAULT NULL,
    FOREIGN KEY (blueprint_id) REFERENCES blueprints(id) ON DELETE CASCADE,
    UNIQUE KEY uk_bp_day (blueprint_id, day_number)
) ENGINE=InnoDB;

-- Referral system
CREATE TABLE IF NOT EXISTS referrals (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    referrer_id     INT UNSIGNED NOT NULL,
    referee_id      INT UNSIGNED DEFAULT NULL,
    referral_code   VARCHAR(20) NOT NULL UNIQUE,
    status          ENUM('pending','completed','rewarded') DEFAULT 'pending',
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (referrer_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Add referral_code to users
ALTER TABLE users ADD COLUMN referral_code VARCHAR(20) DEFAULT NULL AFTER reset_expires;
ALTER TABLE users ADD COLUMN referred_by INT UNSIGNED DEFAULT NULL AFTER referral_code;

-- Exam results tracking
CREATE TABLE IF NOT EXISTS exam_results (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED NOT NULL,
    blueprint_id    INT UNSIGNED NOT NULL,
    result          ENUM('selected','not_selected','waiting','appeared') DEFAULT 'waiting',
    score           VARCHAR(50) DEFAULT NULL,
    testimonial     TEXT DEFAULT NULL,
    is_public       TINYINT(1) DEFAULT 0,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (blueprint_id) REFERENCES blueprints(id)
) ENGINE=InnoDB;
