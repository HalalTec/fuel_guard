<?php
declare(strict_types=1);

class AuthController
{
    private AuthService $authService;

    public function __construct(private PDO $db)
    {
        $this->authService = new AuthService(new UserModel($db));
    }

    public function showLogin(): void
    {
        // Already logged in → send to role home
        if ($this->authService->isAuthenticated()) {
            $this->redirectByRole();
        }
        $this->authService->ensureCsrfToken();
        require __DIR__ . '/../view/auth/login.php';
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        if (!$this->authService->validateCsrfToken((string) ($_POST['csrf_token'] ?? ''))) {
            $_SESSION['error'] = 'Your session token expired. Please try again.';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        $email    = filter_input(INPUT_POST, 'email',    FILTER_SANITIZE_EMAIL) ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email and password are required.';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        $user = $this->authService->authenticate($email, $password);

        if ($user) {
            $this->authService->createSession($user);
            $this->redirectByRole();
        }

        $_SESSION['error'] = 'Invalid email or password. Please try again.';
        header('Location: ' . BASE_URL . '/login');
        exit();
    }

    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST'
            || !$this->authService->validateCsrfToken((string) ($_POST['csrf_token'] ?? ''))
        ) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        $_SESSION = [];
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit();
    }

    private function redirectByRole(): void
    {
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
