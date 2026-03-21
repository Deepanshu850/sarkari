<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\CSRF;
use App\Models\User;

class AuthController extends Controller {

    public function showRegister(): void {
        if (Auth::check()) redirect('/dashboard');
        $this->view('auth/register', ['pageTitle' => 'Create Account']);
    }

    public function register(): void {
        $this->validateCSRF();

        $name     = trim($_POST['name'] ?? '');
        $email    = trim(strtolower($_POST['email'] ?? ''));
        $phone    = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['password_confirmation'] ?? '';

        // Validation
        $errors = [];
        if (strlen($name) < 2) $errors[] = 'Name must be at least 2 characters.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
        if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
        if ($password !== $confirm) $errors[] = 'Passwords do not match.';

        $userModel = new User();
        if ($userModel->findByEmail($email)) {
            $errors[] = 'An account with this email already exists.';
        }

        if ($errors) {
            $this->storeOldInput();
            flash('error', implode(' ', $errors));
            redirect('/register');
        }

        $userId = $userModel->create([
            'name'          => $name,
            'email'         => $email,
            'phone'         => $phone ?: null,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
            'verify_token'  => bin2hex(random_bytes(32)),
        ]);

        $user = $userModel->find($userId);
        Auth::login($user);

        flash('success', 'Account created successfully! Welcome to Sarkari.');
        $intended = $_SESSION['intended_url'] ?? '/dashboard';
        unset($_SESSION['intended_url']);
        redirect($intended);
    }

    public function showLogin(): void {
        if (Auth::check()) redirect('/dashboard');
        $this->view('auth/login', ['pageTitle' => 'Login']);
    }

    public function login(): void {
        $this->validateCSRF();

        $email    = trim(strtolower($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        // Rate limiting (simple session-based)
        $attempts = $_SESSION['login_attempts'] ?? ['count' => 0, 'last' => 0];
        if ($attempts['count'] >= 5 && (time() - $attempts['last']) < 300) {
            flash('error', 'Too many login attempts. Please wait 5 minutes.');
            redirect('/login');
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $_SESSION['login_attempts'] = [
                'count' => ($attempts['count'] ?? 0) + 1,
                'last'  => time(),
            ];
            $this->storeOldInput();
            flash('error', 'Invalid email or password.');
            redirect('/login');
        }

        unset($_SESSION['login_attempts']);
        Auth::login($user);

        flash('success', 'Welcome back, ' . $user['name'] . '!');
        $intended = $_SESSION['intended_url'] ?? '/dashboard';
        unset($_SESSION['intended_url']);
        redirect($intended);
    }

    public function logout(): void {
        Auth::logout();
        redirect('/login');
    }

    public function showForgot(): void {
        $this->view('auth/forgot-password', ['pageTitle' => 'Forgot Password']);
    }

    public function forgotPassword(): void {
        $this->validateCSRF();

        $email = trim(strtolower($_POST['email'] ?? ''));
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        // Always show success to prevent email enumeration
        flash('success', 'If an account exists with that email, we have sent a password reset link.');

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $userModel->update($user['id'], [
                'reset_token'   => $token,
                'reset_expires' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            ]);
            // In production, send email here with reset link
        }

        redirect('/forgot-password');
    }
}
