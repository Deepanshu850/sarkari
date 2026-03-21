<?php

namespace App\Core;

class Controller {
    protected function view(string $path, array $data = [], string $layout = 'app'): void {
        extract($data);
        ob_start();
        require __DIR__ . '/../views/' . $path . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layouts/' . $layout . '.php';
    }

    protected function requireAuth(): void {
        if (!auth()) {
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];
            flash('error', 'Please login to continue.');
            redirect('/login');
        }
    }

    protected function requireAdmin(): void {
        $this->requireAuth();
        if (!is_admin()) {
            abort(403, 'Access denied');
        }
    }

    protected function validateCSRF(): void {
        CSRF::validateOrAbort();
    }

    protected function storeOldInput(): void {
        $_SESSION['old_input'] = $_POST;
    }
}
