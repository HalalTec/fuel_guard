<?php
declare(strict_types=1);

class DashboardController
{
    private AuthService  $authService;
    private TripModel    $tripModel;
    private VehicleModel $vehicleModel;

    public function __construct(private PDO $db)
    {
        $this->authService  = new AuthService(new UserModel($db));
        $this->tripModel    = new TripModel($db);
        $this->vehicleModel = new VehicleModel($db);
    }

    public function index(): void
    {
        $this->authService->requireRole('manager');

        $trips          = $this->tripModel->getAllWithDetails();
        $stats          = $this->tripModel->getStats();
        $vehicleCount   = $this->vehicleModel->count();

        require __DIR__ . '/../view/manager/dashboard.php';
    }
}
