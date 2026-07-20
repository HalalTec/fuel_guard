<?php
declare(strict_types=1);

class RouteModel
{
    public function __construct(private PDO $db) {}

    public function getAll(): array
    {
        return $this->db
            ->query('SELECT * FROM routes ORDER BY route_name')
            ->fetchAll();
    }

    public function findByName(string $routeName): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM routes WHERE LOWER(route_name) = LOWER(?) LIMIT 1');
        $stmt->execute([$routeName]);
        return $stmt->fetch() ?: null;
    }
    public function create(string $routeName, float $distanceKm, float $expectedFuelLiters): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO routes (route_name, est_distance_km, expected_fuel_liters) VALUES (?, ?, ?) RETURNING id'
        );
        $stmt->execute([$routeName, $distanceKm, $expectedFuelLiters]);
        return (int) $stmt->fetchColumn();
    }
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM routes WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}
