<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

define('APP_NAME', $_ENV['APP_NAME'] ?? 'Sarkari');
define('APP_URL', rtrim($_ENV['APP_URL'] ?? 'http://localhost:8000', '/'));
define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
define('APP_DEBUG', ($_ENV['APP_DEBUG'] ?? 'false') === 'true');

define('DB_HOST', $_ENV['DB_HOST'] ?? '127.0.0.1');
define('DB_PORT', $_ENV['DB_PORT'] ?? '3306');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'sarkari');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

define('RAZORPAY_KEY_ID', $_ENV['RAZORPAY_KEY_ID'] ?? '');
define('RAZORPAY_KEY_SECRET', $_ENV['RAZORPAY_KEY_SECRET'] ?? '');

// Buzzino Payment Gateway
define('BUZZINO_PAY_URL', $_ENV['BUZZINO_PAY_URL'] ?? 'https://buzzino.in/pay.html');
define('BUZZINO_PRODUCT_NAME', $_ENV['BUZZINO_PRODUCT_NAME'] ?? 'Sarkari Blueprint');

define('AI_PROVIDER', $_ENV['AI_PROVIDER'] ?? 'claude');
define('AI_API_KEY', $_ENV['AI_API_KEY'] ?? '');
define('AI_MODEL', $_ENV['AI_MODEL'] ?? 'claude-sonnet-4-20250514');

define('BLUEPRINT_DAYS', 30);

// Plan pricing and limits
define('PLANS', [
    'starter'  => ['price' => 99,   'paise' => 9900,   'blueprints' => 1, 'label' => 'Starter',  'features' => ['blueprint', 'pdf', 'progress', 'countdown', 'guarantee']],
    'pro'      => ['price' => 199,  'paise' => 19900,  'blueprints' => 2, 'label' => 'Pro',      'features' => ['blueprint', 'pdf', 'progress', 'countdown', 'guarantee', 'edit_regenerate', 'priority', 'referral']],
    'ultimate' => ['price' => 299,  'paise' => 29900,  'blueprints' => 3, 'label' => 'Ultimate', 'features' => ['blueprint', 'pdf', 'progress', 'countdown', 'guarantee', 'edit_regenerate', 'priority', 'referral', 'unlimited_regen', 'lifetime']],
]);

// Default/fallback for old code
define('BLUEPRINT_PRICE', 99);
define('BLUEPRINT_PRICE_PAISE', 9900);

define('STORAGE_PATH', __DIR__ . '/../storage');

// Error handling
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

ini_set('log_errors', '1');
ini_set('error_log', STORAGE_PATH . '/logs/error.log');

// Timezone
date_default_timezone_set('Asia/Kolkata');
