<?php

namespace App\Core;

class CSRF {
    public static function generate(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validate(): bool {
        $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

        // No session at all = session expired or new visitor
        if (empty($_SESSION['csrf_token'])) {
            return false;
        }

        if (empty($token) || !hash_equals($_SESSION['csrf_token'], $token)) {
            return false;
        }
        return true;
    }

    public static function validateOrAbort(): void {
        if (!self::validate()) {
            // Instead of hard 403, redirect back with error so user can retry
            flash('error', 'Session expired. Please try again.');
            $referer = $_SERVER['HTTP_REFERER'] ?? '/';
            $path = parse_url($referer, PHP_URL_PATH) ?: '/';
            $fragment = parse_url($referer, PHP_URL_FRAGMENT) ?? '';
            redirect($path . ($fragment ? '#' . $fragment : ''));
        }
    }
}
