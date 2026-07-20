<?php
declare(strict_types=1);

class VehicleModel
{
    public function __construct(private PDO $db) {}

    public function getAll(): array
    {
        return $this->db
            ->query('SELECT * FROM vehicles ORDER BY plate_number')
            ->fetchAll();
    }

    public function create(string $plateNumber, string $model, float $currentOdometer): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO vehicles (plate_number, model, current_odometer) VALUES (?, ?, ?) RETURNING id'
        );
        $stmt->execute([$plateNumber, $model, $currentOdometer]);
        return (int) $stmt->fetchColumn();
    }
    public function updateOdometer(int $vehicleId, float $odometer): void
    {
        $stmt = $this->db->prepare(
            'UPDATE vehicles SET current_odometer = ? WHERE id = ?'
        );
        $stmt->execute([$odometer, $vehicleId]);
    }

    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM vehicles')->fetchColumn();
    }
}
