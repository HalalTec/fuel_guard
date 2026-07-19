<?php
declare(strict_types=1);

class TripController
{
    private AuthService      $authService;
    private TripAuditService $auditService;
    private VehicleModel     $vehicleModel;
    private RouteModel       $routeModel;
    private UserModel        $userModel;

    public function __construct(private PDO $db)
    {
        $this->userModel    = new UserModel($db);
        $this->vehicleModel = new VehicleModel($db);
        $this->routeModel   = new RouteModel($db);
        $this->authService  = new AuthService($this->userModel);
        $this->auditService = new TripAuditService(
            new TripModel($db),
            $this->routeModel,
            $this->vehicleModel
        );
    }

    public function showForm(): void
    {
        $this->authService->requireRole('officer');

        $vehicles = $this->vehicleModel->getAll();
        $routes   = $this->routeModel->getAll();
        $drivers  = $this->userModel->getDrivers();

        require __DIR__ . '/../view/officer/trip_form.php';
    }

    public function saveTrip(): void
    {
        $this->authService->requireRole('officer');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/trip');
            exit();
        }

        // Validate and sanitize every field
        $vehicleId      = filter_input(INPUT_POST, 'vehicle_id',     FILTER_VALIDATE_INT);
        $driverId       = filter_input(INPUT_POST, 'driver_id',      FILTER_VALIDATE_INT);
        $routeId        = filter_input(INPUT_POST, 'route_id',       FILTER_VALIDATE_INT);
        $startOdometer  = filter_input(INPUT_POST, 'start_odometer', FILTER_VALIDATE_FLOAT);
        $endOdometer    = filter_input(INPUT_POST, 'end_odometer',   FILTER_VALIDATE_FLOAT);
        $fuelConsumed   = filter_input(INPUT_POST, 'fuel_consumed',  FILTER_VALIDATE_FLOAT);

        if (in_array(false, [$vehicleId, $driverId, $routeId, $startOdometer, $endOdometer, $fuelConsumed], true)
            || in_array(null,  [$vehicleId, $driverId, $routeId, $startOdometer, $endOdometer, $fuelConsumed], true)
        ) {
            $_SESSION['error'] = 'All fields are required and must contain valid numeric values.';
            header('Location: ' . BASE_URL . '/trip');
            exit();
        }

        if ($endOdometer <= $startOdometer) {
            $_SESSION['error'] = 'End odometer must be greater than start odometer.';
            header('Location: ' . BASE_URL . '/trip');
            exit();
        }

        if ($fuelConsumed <= 0) {
            $_SESSION['error'] = 'Fuel consumed must be a positive value.';
            header('Location: ' . BASE_URL . '/trip');
            exit();
        }

        $result = $this->auditService->processTrip([
            'vehicle_id'     => $vehicleId,
            'driver_id'      => $driverId,
            'route_id'       => $routeId,
            'start_odometer' => $startOdometer,
            'end_odometer'   => $endOdometer,
            'fuel_consumed'  => $fuelConsumed,
        ]);

        if (!$result['success']) {
            $_SESSION['error'] = $result['message'];
            header('Location: ' . BASE_URL . '/trip');
            exit();
        }

        if ($result['alert_flag']) {
            $over = round($result['consumed'] - $result['expected'], 2);
            $_SESSION['warning'] = sprintf(
                'Alert: Trip recorded for "%s" but fuel consumed (%.2fL) exceeds the %.2fL benchmark by %.2fL.',
                htmlspecialchars($result['route_name']),
                $result['consumed'],
                $result['expected'],
                $over
            );
        } else {
            $_SESSION['success'] = sprintf(
                'Trip recorded successfully for "%s". Fuel consumption is within the %.2fL benchmark.',
                htmlspecialchars($result['route_name']),
                $result['expected']
            );
        }

        header('Location: ' . BASE_URL . '/trip');
        exit();
    }
}
