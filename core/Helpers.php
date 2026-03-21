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
