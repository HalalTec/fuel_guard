<?php
declare(strict_types=1);

class TripModel
{
    public function __construct(private PDO $db) {}

    public function create(array $data): void
    {
        $stmt = $this->db->prepare('
            INSERT INTO trips
                (vehicle_id, driver_id, route_id, start_odometer, end_odometer, fuel_consumed, alert_flag)
            VALUES
                (:vehicle_id, :driver_id, :route_id, :start_odometer, :end_odometer, :fuel_consumed, :alert_flag)
        ');
        $stmt->bindValue(':vehicle_id', (int) $data['vehicle_id'], PDO::PARAM_INT);
        $stmt->bindValue(':driver_id', (int) $data['driver_id'], PDO::PARAM_INT);
        $stmt->bindValue(':route_id', (int) $data['route_id'], PDO::PARAM_INT);
        $stmt->bindValue(':start_odometer', (string) $data['start_odometer']);
        $stmt->bindValue(':end_odometer', (string) $data['end_odometer']);
        $stmt->bindValue(':fuel_consumed', (string) $data['fuel_consumed']);
        $stmt->bindValue(':alert_flag', (bool) $data['alert_flag'], PDO::PARAM_BOOL);
        $stmt->execute();
    }

    /** Full trip list joined with vehicle, driver, and route data for the dashboard */
    public function getAllWithDetails(): array
    {
        return $this->db->query('
            SELECT
                t.id,
                t.start_odometer,
                t.end_odometer,
                t.fuel_consumed,
                t.alert_flag,
                t.created_at,
                v.plate_number,
                v.model        AS vehicle_model,
                u.name         AS driver_name,
                r.route_name,
                r.est_distance_km,
                r.expected_fuel_liters,
                (t.fuel_consumed - r.expected_fuel_liters) AS fuel_variance
            FROM trips t
            JOIN vehicles v ON t.vehicle_id = v.id
            JOIN users    u ON t.driver_id  = u.id
            JOIN routes   r ON t.route_id   = r.id
            ORDER BY t.created_at DESC
        ')->fetchAll();
    }

    public function getByDriverId(int $driverId): array
    {
        $stmt = $this->db->prepare('
            SELECT
                t.id,
                t.start_odometer,
                t.end_odometer,
                t.fuel_consumed,
                t.alert_flag,
                t.created_at,
                v.plate_number,
                v.model        AS vehicle_model,
                r.route_name,
                r.est_distance_km,
                r.expected_fuel_liters,
                (t.fuel_consumed - r.expected_fuel_liters) AS fuel_variance
            FROM trips t
            JOIN vehicles v ON t.vehicle_id = v.id
            JOIN routes   r ON t.route_id   = r.id
            WHERE t.driver_id = ?
            ORDER BY t.created_at DESC
        ');
        $stmt->execute([$driverId]);
        return $stmt->fetchAll();
    }

    public function getStats(): array
    {
        $row = $this->db->query('
            SELECT
                COUNT(*)              AS total_trips,
                COUNT(*) FILTER (WHERE alert_flag)     AS flagged_trips,
                COUNT(*) FILTER (WHERE NOT alert_flag) AS normal_trips
            FROM trips
        ')->fetch();

        return $row ?: ['total_trips' => 0, 'flagged_trips' => 0, 'normal_trips' => 0];
    }
}

