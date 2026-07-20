<?php
declare(strict_types=1);

class DriverController
{
    private AuthService $authService;
    private TripModel $tripModel;
    private UserModel $userModel;

    public function __construct(private PDO $db)
    {
        $this->userModel = new UserModel($db);
        $this->tripModel = new TripModel($db);
        $this->authService = new AuthService($this->userModel);
    }

    public function showTrips(): void
    {
        $this->authService->requireRole('driver');
        $this->authService->ensureCsrfToken();
        $trips = $this->tripModel->getByDriverId((int) $_SESSION['user_id']);
        require __DIR__ . '/../view/driver/my_trips.php';
    }

    public function showSettings(): void
    {
        $this->authService->requireAnyRole(['manager', 'driver', 'officer']);
        $this->authService->ensureCsrfToken();
        $user = $this->userModel->findById((int) $_SESSION['user_id']);
        require __DIR__ . '/../view/driver/settings.php';
    }

    public function saveSettings(): void
    {
        $this->authService->requireAnyRole(['manager', 'driver', 'officer']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithError('Invalid request method.');
        }
        if (!$this->authService->validateCsrfToken((string) ($_POST['csrf_token'] ?? ''))) {
            $this->redirectWithError('Your session token expired. Please try again.');
        }

        $currentPassword = (string) ($_POST['current_password'] ?? '');
        $newPassword = (string) ($_POST['new_password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

        if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            $this->redirectWithError('All password fields are required.');
        }
        if ($newPassword !== $confirmPassword) {
            $this->redirectWithError('New password and confirmation do not match.');
        }
        if (strlen($newPassword) < 8 || strlen($newPassword) > 72) {
            $this->redirectWithError('New password must contain between 8 and 72 characters.');
        }

        $user = $this->userModel->findById((int) $_SESSION['user_id']);
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            $this->redirectWithError('Current password is incorrect.');
        }

        $this->userModel->updatePassword((int) $_SESSION['user_id'], password_hash($newPassword, PASSWORD_DEFAULT));
        $_SESSION['success'] = 'Password updated successfully.';
        header('Location: ' . BASE_URL . '/settings');
        exit();
    }

    private function redirectWithError(string $message): never
    {
        $_SESSION['error'] = $message;
        header('Location: ' . BASE_URL . '/settings');
        exit();
    }
}