<?php

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function base_url(): string {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $scheme . '://' . $host;
}

function redirect(string $url): void {
    header("Location: " . base_url() . $url);
    exit;
}

function asset(string $path): string {
    return base_url() . '/public/' . ltrim($path, '/');
}

function old(string $key, string $default = ''): string {
    return e($_SESSION['old_input'][$key] ?? $default);
}

function flash(string $key, mixed $value = null): mixed {
    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
        return null;
    }
    $val = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $val;
}

function flash_has(string $key): bool {
    return isset($_SESSION['flash'][$key]);
}

function csrf_field(): string {
    return '<input type="hidden" name="_token" value="' . e(\App\Core\CSRF::generate()) . '">';
}

function auth(): ?array {
    return $_SESSION['user'] ?? null;
}

function is_admin(): bool {
    return (auth()['role'] ?? '') === 'admin';
}

function current_path(): string {
    return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
}

function format_inr(int $paise): string {
    return '₹' . number_format($paise / 100, 0);
}

function json_response(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function redirect_external(string $url): void {
    header("Location: " . $url);
    exit;
}

function abort(int $code, string $message = ''): void {
    http_response_code($code);
    echo $message ?: "Error $code";
    exit;
}

/**
 * Get current user's plan key (starter/pro/ultimate)
 */
function user_plan(): string {
    return auth()['plan'] ?? 'starter';
}

/**
 * Get current user's plan config array
 */
function user_plan_config(): array {
    return PLANS[user_plan()] ?? PLANS['starter'];
}

/**
 * Check if current user has a specific plan feature
 */
function has_feature(string $feature): bool {
    $config = user_plan_config();
    return in_array($feature, $config['features'] ?? []);
}

/**
 * Get number of blueprints allowed for current user
 */
function blueprints_allowed(): int {
    return auth()['plan_blueprints_allowed'] ?? PLANS[user_plan()]['blueprints'] ?? 1;
}

/**
 * Get plan badge HTML
 */
/**
 * Get upgrade price from current plan to target plan (differential)
 */
function upgrade_price(string $targetPlan): int {
    $currentPlan = user_plan();
    $currentPrice = PLANS[$currentPlan]['price'] ?? 0;
    $targetPrice = PLANS[$targetPlan]['price'] ?? 0;
    return max(0, $targetPrice - $currentPrice);
}

/**
 * Get all available upgrades for current user
 */
function available_upgrades(): array {
    $currentPlan = user_plan();
    $planRank = ['starter' => 1, 'pro' => 2, 'ultimate' => 3];
    $currentRank = $planRank[$currentPlan] ?? 1;
    $upgrades = [];

    foreach (PLANS as $key => $plan) {
        $rank = $planRank[$key] ?? 0;
        if ($rank > $currentRank) {
            $upgrades[$key] = [
                'key' => $key,
                'label' => $plan['label'],
                'full_price' => $plan['price'],
                'upgrade_price' => $plan['price'] - (PLANS[$currentPlan]['price'] ?? 0),
                'blueprints' => $plan['blueprints'],
                'features' => $plan['features'],
            ];
        }
    }
    return $upgrades;
}

function plan_badge(): string {
    $plan = user_plan();
    $label = PLANS[$plan]['label'] ?? 'Starter';
    $colors = [
        'starter'  => 'bg-gold-50 text-gold-700 border-gold-200',
        'pro'      => 'bg-saffron-50 text-saffron-700 border-saffron-200',
        'ultimate' => 'bg-navy-50 text-navy-700 border-navy-200',
    ];
    $color = $colors[$plan] ?? $colors['starter'];
    return '<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border ' . $color . '">' . e($label) . '</span>';
}
