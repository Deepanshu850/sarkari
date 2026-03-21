CREATE DATABASE IF NOT EXISTS sarkari
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sarkari;

-- Users
CREATE TABLE users (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    email           VARCHAR(255) NOT NULL UNIQUE,
    phone           VARCHAR(15)  DEFAULT NULL,
    password_hash   VARCHAR(255) NOT NULL,
    role            ENUM('user','admin') DEFAULT 'user',
    email_verified  TINYINT(1)   DEFAULT 0,
    verify_token    VARCHAR(64)  DEFAULT NULL,
    reset_token     VARCHAR(64)  DEFAULT NULL,
    reset_expires   DATETIME     DEFAULT NULL,
    created_at      DATETIME     DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- Exams (seeded)
CREATE TABLE exams (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    category        VARCHAR(50)  NOT NULL,
    description     TEXT         DEFAULT NULL,
    syllabus_json   JSON         DEFAULT NULL,
    icon            VARCHAR(50)  DEFAULT 'briefcase',
    is_active       TINYINT(1)   DEFAULT 1,
    sort_order      INT          DEFAULT 0,
    created_at      DATETIME     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Exam subjects (for weak-subject picker)
CREATE TABLE exam_subjects (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    exam_id         INT UNSIGNED NOT NULL,
    name            VARCHAR(100) NOT NULL,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Blueprints
CREATE TABLE blueprints (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED NOT NULL,
    exam_id         INT UNSIGNED NOT NULL,
    education       VARCHAR(100) NOT NULL,
    weak_subjects   JSON         NOT NULL,
    study_hours     DECIMAL(3,1) NOT NULL,
    exam_date       DATE         NOT NULL,
    status          ENUM('pending_payment','generating','ready','failed') DEFAULT 'pending_payment',
    ai_response     LONGTEXT     DEFAULT NULL,
    summary         TEXT         DEFAULT NULL,
    pdf_path        VARCHAR(255) DEFAULT NULL,
    generated_at    DATETIME     DEFAULT NULL,
    created_at      DATETIME     DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (exam_id) REFERENCES exams(id),
    INDEX idx_user   (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Blueprint days (30-day plan)
CREATE TABLE blueprint_days (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    blueprint_id    INT UNSIGNED NOT NULL,
    day_number      TINYINT UNSIGNED NOT NULL,
    title           VARCHAR(200) NOT NULL,
    subjects_json   JSON         NOT NULL,
    tips            TEXT         DEFAULT NULL,
    resources       JSON         DEFAULT NULL,
    FOREIGN KEY (blueprint_id) REFERENCES blueprints(id) ON DELETE CASCADE,
    INDEX idx_blueprint (blueprint_id)
) ENGINE=InnoDB;

-- Payments
CREATE TABLE payments (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id             INT UNSIGNED NOT NULL,
    blueprint_id        INT UNSIGNED NOT NULL,
    razorpay_order_id   VARCHAR(50)  NOT NULL,
    razorpay_payment_id VARCHAR(50)  DEFAULT NULL,
    razorpay_signature  VARCHAR(255) DEFAULT NULL,
    amount              INT          NOT NULL DEFAULT 49900,
    currency            CHAR(3)      DEFAULT 'INR',
    status              ENUM('created','captured','failed','refunded') DEFAULT 'created',
    webhook_payload     JSON         DEFAULT NULL,
    created_at          DATETIME     DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (blueprint_id) REFERENCES blueprints(id),
    UNIQUE INDEX idx_order (razorpay_order_id),
    INDEX idx_user (user_id)
) ENGINE=InnoDB;
