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

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM routes WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}
