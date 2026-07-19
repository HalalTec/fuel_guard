<?php
declare(strict_types=1);

class DriverController
{
    private AuthService $authService;
    private TripModel   $tripModel;
    private UserModel   $userModel;

    public function __construct(private PDO $db)
    {
        $this->userModel   = new UserModel($db);
        $this->tripModel   = new TripModel($db);
        $this->authService = new AuthService($this->userModel);
    }

    public function showTrips(): void
    {
        $this->authService->requireRole('driver');
        $trips = $this->tripModel->getByDriverId($_SESSION['user_id']);
        require __DIR__ . '/../view/driver/my_trips.php';
    }

    public function showSettings(): void
    {
        $this->authService->requireRole('driver');
        $user = $this->userModel->findById($_SESSION['user_id']);
        require __DIR__ . '/../view/driver/settings.php';
    }

    public function saveSettings(): void
    {
        $this->authService->requireRole('driver');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/settings');
            exit();
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword     = $_POST['new_password']     ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['error'] = 'All password fields are required.';
            header('Location: ' . BASE_URL . '/settings');
            exit();
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'New password and confirmation do not match.';
            header('Location: ' . BASE_URL . '/settings');
            exit();
        }

        if (strlen($newPassword) < 8) {
            $_SESSION['error'] = 'New password must be at least 8 characters long.';
            header('Location: ' . BASE_URL . '/settings');
            exit();
        }

        $user = $this->userModel->findById($_SESSION['user_id']);
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            $_SESSION['error'] = 'Current password is incorrect.';
            header('Location: ' . BASE_URL . '/settings');
            exit();
        }

        $this->userModel->updatePassword($_SESSION['user_id'], password_hash($newPassword, PASSWORD_BCRYPT));
        $_SESSION['success'] = 'Password updated successfully.';
        header('Location: ' . BASE_URL . '/settings');
        exit();
    }
}
