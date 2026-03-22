<?php

// Security headers
header_remove('X-Powered-By');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Block direct access to sensitive files/dirs
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$blocked = ['/config/', '/core/', '/controllers/', '/models/', '/services/', '/views/', '/database/', '/storage/', '/vendor/'];
foreach ($blocked as $dir) {
    if (str_starts_with($uri, $dir)) {
        http_response_code(403);
        exit('Forbidden');
    }
}
if (basename($uri) === '.env' || basename($uri) === '.htaccess') {
    http_response_code(403);
    exit('Forbidden');
}

// Serve static files for PHP built-in server
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (str_starts_with($uri, '/public/')) {
    $filePath = __DIR__ . $uri;
    if (is_file($filePath)) {
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeTypes = [
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
            'svg'  => 'image/svg+xml',
            'ico'  => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2'=> 'font/woff2',
            'ttf'  => 'font/ttf',
        ];
        header('Content-Type: ' . ($mimeTypes[$ext] ?? 'application/octet-stream'));
        header('Cache-Control: public, max-age=86400');
        readfile($filePath);
        exit;
    }
}

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/core/Helpers.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/CSRF.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Auth.php';

// Models
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Exam.php';
require_once __DIR__ . '/models/Blueprint.php';
require_once __DIR__ . '/models/Payment.php';

// Services
require_once __DIR__ . '/services/AIService.php';
require_once __DIR__ . '/services/RazorpayService.php';
require_once __DIR__ . '/services/PdfService.php';

// Controllers
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/BlueprintController.php';
require_once __DIR__ . '/controllers/PaymentController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/PdfController.php';
require_once __DIR__ . '/controllers/AdminController.php';

// Session
ini_set('session.gc_maxlifetime', 7200); // 2 hours
session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'cookie_lifetime' => 7200,
    'use_strict_mode' => true,
]);

// Router
$router = new \App\Core\Router();

// Public routes
$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/login', [\App\Controllers\AuthController::class, 'showLogin']);
$router->post('/login', [\App\Controllers\AuthController::class, 'login']);
$router->get('/register', [\App\Controllers\AuthController::class, 'showRegister']);
$router->post('/register', [\App\Controllers\AuthController::class, 'register']);
$router->get('/logout', [\App\Controllers\AuthController::class, 'logout']);
$router->get('/forgot-password', [\App\Controllers\AuthController::class, 'showForgot']);
$router->post('/forgot-password', [\App\Controllers\AuthController::class, 'forgotPassword']);

// Quick checkout (NO login required - payment first)
$router->post('/checkout', [\App\Controllers\PaymentController::class, 'checkout']);
$router->get('/payment/callback', [\App\Controllers\PaymentController::class, 'callback']);
$router->post('/payment/webhook', [\App\Controllers\PaymentController::class, 'webhook']);

// Post-payment customization
$router->get('/customize/{id}', [\App\Controllers\BlueprintController::class, 'customize']);
$router->post('/customize/{id}', [\App\Controllers\BlueprintController::class, 'saveCustomize']);

// Blueprint (auth required)
$router->get('/blueprint/view/{id}', [\App\Controllers\BlueprintController::class, 'show']);
$router->get('/blueprint/retry/{id}', [\App\Controllers\BlueprintController::class, 'retry']);

// Old multi-step flow (kept for logged-in users)
$router->get('/blueprint/step1', [\App\Controllers\BlueprintController::class, 'step1']);
$router->post('/blueprint/step1', [\App\Controllers\BlueprintController::class, 'saveStep1']);
$router->get('/blueprint/step2', [\App\Controllers\BlueprintController::class, 'step2']);
$router->post('/blueprint/step2', [\App\Controllers\BlueprintController::class, 'saveStep2']);
$router->get('/blueprint/step3', [\App\Controllers\BlueprintController::class, 'step3']);
$router->post('/blueprint/step3', [\App\Controllers\BlueprintController::class, 'saveStep3']);
$router->get('/blueprint/review', [\App\Controllers\BlueprintController::class, 'review']);
$router->get('/blueprint/generate', [\App\Controllers\BlueprintController::class, 'generate']);
$router->post('/payment/initiate', [\App\Controllers\PaymentController::class, 'initiate']);

// API
$router->get('/api/exam-subjects/{id}', [\App\Controllers\BlueprintController::class, 'getSubjects']);
$router->post('/api/progress/toggle', [\App\Controllers\BlueprintController::class, 'toggleProgress']);
$router->post('/api/result/submit', [\App\Controllers\BlueprintController::class, 'submitResult']);
$router->post('/api/blueprint/generate/{id}', [\App\Controllers\BlueprintController::class, 'doGenerate']);
$router->get('/api/blueprint/status/{id}', [\App\Controllers\BlueprintController::class, 'checkStatus']);

// PDF
$router->get('/blueprint/download/{id}', [\App\Controllers\PdfController::class, 'download']);

// Dashboard
$router->get('/dashboard', [\App\Controllers\DashboardController::class, 'index']);

// Admin
$router->get('/admin', [\App\Controllers\AdminController::class, 'dashboard']);
$router->get('/admin/users', [\App\Controllers\AdminController::class, 'users']);
$router->get('/admin/blueprints', [\App\Controllers\AdminController::class, 'blueprints']);
$router->get('/admin/payments', [\App\Controllers\AdminController::class, 'payments']);

// Dispatch
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
