<?php
declare(strict_types=1);

class AdminController
{
    private AuthService $authService;
    private UserModel $userModel;
    private VehicleModel $vehicleModel;
    private RouteModel $routeModel;

    public function __construct(private PDO $db)
    {
        $this->userModel = new UserModel($db);
        $this->vehicleModel = new VehicleModel($db);
        $this->routeModel = new RouteModel($db);
        $this->authService = new AuthService($this->userModel);
    }

    public function index(): void
    {
        $this->authService->requireRole('manager');
        $this->authService->ensureCsrfToken();

        $users = $this->userModel->getStaff();
        $vehicles = $this->vehicleModel->getAll();
        $routes = $this->routeModel->getAll();

        require __DIR__ . '/../view/Manager/manage.php';
    }

    public function storeUser(): void
    {
        $this->guardPost();

        $name = trim((string) ($_POST['name'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');
        $role = (string) ($_POST['role'] ?? '');

        if ($name === '' || strlen($name) > 100) {
            $this->fail('Please enter a name of no more than 100 characters.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
            $this->fail('Please enter a valid email address.');
        }
        if (strlen($password) < 8 || strlen($password) > 72) {
            $this->fail('The temporary password must contain between 8 and 72 characters.');
        }
        if ($password !== $passwordConfirmation) {
            $this->fail('The password and confirmation do not match.');
        }
        if (!in_array($role, ['officer', 'driver'], true)) {
            $this->fail('Only officer and driver accounts can be created here.');
        }
        if ($this->userModel->findByEmail($email)) {
            $this->fail('An account already uses that email address.');
        }

        try {
            $this->userModel->create($name, $email, password_hash($password, PASSWORD_DEFAULT), $role);
        } catch (PDOException $e) {
            $this->handleDatabaseError($e, 'Could not create the account.');
        }

        $this->succeed(ucfirst($role) . ' account created successfully.');
    }

    public function storeVehicle(): void
    {
        $this->guardPost();

        $plateNumber = strtoupper(trim((string) ($_POST['plate_number'] ?? '')));
        $model = trim((string) ($_POST['model'] ?? ''));
        $odometer = filter_var($_POST['current_odometer'] ?? null, FILTER_VALIDATE_FLOAT);

        if ($plateNumber === '' || strlen($plateNumber) > 20) {
            $this->fail('Please enter a plate number of no more than 20 characters.');
        }
        if ($model === '' || strlen($model) > 100) {
            $this->fail('Please enter a vehicle model of no more than 100 characters.');
        }
        if ($odometer === false || $odometer < 0 || $odometer > 99999999.99) {
            $this->fail('Current odometer must be between 0 and 99,999,999.99 km.');
        }

        try {
            $this->vehicleModel->create($plateNumber, $model, (float) $odometer);
        } catch (PDOException $e) {
            $this->handleDatabaseError($e, 'Could not add the vehicle.');
        }

        $this->succeed('Vehicle added successfully.');
    }

    public function storeRoute(): void
    {
        $this->guardPost();

        $routeName = trim((string) ($_POST['route_name'] ?? ''));
        $distance = filter_var($_POST['est_distance_km'] ?? null, FILTER_VALIDATE_FLOAT);
        $expectedFuel = filter_var($_POST['expected_fuel_liters'] ?? null, FILTER_VALIDATE_FLOAT);

        if ($routeName === '' || strlen($routeName) > 150) {
            $this->fail('Please enter a route name of no more than 150 characters.');
        }
        if ($distance === false || $distance <= 0 || $distance > 999999.99) {
            $this->fail('Estimated distance must be between 0.01 and 999,999.99 km.');
        }
        if ($expectedFuel === false || $expectedFuel <= 0 || $expectedFuel > 999999.99) {
            $this->fail('Expected fuel must be between 0.01 and 999,999.99 litres.');
        }
        if ($this->routeModel->findByName($routeName)) {
            $this->fail('A route with that name already exists.');
        }

        try {
            $this->routeModel->create($routeName, (float) $distance, (float) $expectedFuel);
        } catch (PDOException $e) {
            $this->handleDatabaseError($e, 'Could not add the route.');
        }

        $this->succeed('Route added successfully.');
    }

    private function guardPost(): void
    {
        $this->authService->requireRole('manager');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/manage');
            exit();
        }

        $submittedToken = (string) ($_POST['csrf_token'] ?? '');
        if (!$this->authService->validateCsrfToken($submittedToken)) {
            $this->fail('Your session token expired. Please try again.');
        }
    }

    private function handleDatabaseError(PDOException $e, string $fallback): never
    {
        if ($e->getCode() === '23505') {
            $this->fail('That value already exists. Please use a different one.');
        }
        error_log('FuelGuard admin error: ' . $e->getMessage());
        $this->fail($fallback);
    }

    private function fail(string $message): never
    {
        $_SESSION['error'] = $message;
        header('Location: ' . BASE_URL . '/manage');
        exit();
    }

    private function succeed(string $message): never
    {
        $_SESSION['success'] = $message;
        header('Location: ' . BASE_URL . '/manage');
        exit();
    }
}