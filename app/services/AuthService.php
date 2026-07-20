<?php
declare(strict_types=1);

class AuthService
{
    public function __construct(private UserModel $userModel) {}

    public function authenticate(string $email, string $password): ?array
    {
        $user = $this->userModel->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }

    public function createSession(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        unset($_SESSION['csrf_token']);
        $this->ensureCsrfToken();
    }

    public function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function ensureCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public function validateCsrfToken(string $submittedToken): bool
    {
        $sessionToken = (string) ($_SESSION['csrf_token'] ?? '');
        return $sessionToken !== '' && $submittedToken !== '' && hash_equals($sessionToken, $submittedToken);
    }

    public function requireRole(string $role): void
    {
        $this->requireAnyRole([$role]);
    }

    public function requireAnyRole(array $roles): void
    {
        if (!$this->isAuthenticated()) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        if (!in_array($_SESSION['user_role'] ?? '', $roles, true)) {
            header('Location: ' . BASE_URL . $this->homeForRole($_SESSION['user_role'] ?? ''));
            exit();
        }
    }

    private function homeForRole(string $role): string
    {
        if ($role === 'manager') {
            return '/dashboard';
        }
        if ($role === 'driver') {
            return '/my-trips';
        }
        return '/trip';
    }
}