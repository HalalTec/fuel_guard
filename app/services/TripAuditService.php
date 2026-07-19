<?php
declare(strict_types=1);

class TripAuditService
{
    public function __construct(
        private TripModel    $tripModel,
        private RouteModel   $routeModel,
        private VehicleModel $vehicleModel
    ) {}

    /**
     * Core audit logic:
     * 1. Fetch the expected fuel benchmark for the chosen route.
     * 2. Flag the trip if actual consumption exceeds the benchmark.
     * 3. Persist the trip record.
     * 4. Update the vehicle's odometer.
     *
     * Returns an array with keys: success, alert_flag, expected, consumed, message
     */
    public function processTrip(array $input): array
    {
        $route = $this->routeModel->findById((int) $input['route_id']);

        if (!$route) {
            return ['success' => false, 'message' => 'Selected route does not exist.'];
        }

        $expected   = (float) $route['expected_fuel_liters'];
        $consumed   = (float) $input['fuel_consumed'];
        $alertFlag  = $consumed > $expected;

        $this->tripModel->create([
            'vehicle_id'     => (int)   $input['vehicle_id'],
            'driver_id'      => (int)   $input['driver_id'],
            'route_id'       => (int)   $input['route_id'],
            'start_odometer' => (float) $input['start_odometer'],
            'end_odometer'   => (float) $input['end_odometer'],
            'fuel_consumed'  => $consumed,
            'alert_flag'     => $alertFlag,
        ]);

        // Keep the vehicle's current odometer in sync
        $this->vehicleModel->updateOdometer((int) $input['vehicle_id'], (float) $input['end_odometer']);

        return [
            'success'    => true,
            'alert_flag' => $alertFlag,
            'expected'   => $expected,
            'consumed'   => $consumed,
            'route_name' => $route['route_name'],
        ];
    }
}

