<?php
declare(strict_types=1);

class AuthService
{
    public function __construct(private UserModel $userModel) {}

    /** Verify credentials; returns the user row on success, null on failure */
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
        session_regenerate_id(true); // prevent session fixation
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
    }

    public function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Guard a page to a specific role.
     * Unauthenticated users → /login
     * Wrong role → their own home page
     */
    public function requireRole(string $role): void
    {
        if (!$this->isAuthenticated()) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        if ($_SESSION['user_role'] !== $role) {
            if ($_SESSION['user_role'] === 'manager') {
                $home = '/dashboard';
            } elseif ($_SESSION['user_role'] === 'driver') {
                $home = '/my-trips';
            } else {
                $home = '/trip';
            }
            header('Location: ' . BASE_URL . $home);
            exit();
        }
    }
}
