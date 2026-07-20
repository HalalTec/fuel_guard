<?php
declare(strict_types=1);

class UserModel
{
    public function __construct(private PDO $db) {}

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE LOWER(email) = LOWER(?) LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function updatePassword(int $id, string $hashedPassword): void
    {
        $stmt = $this->db->prepare('UPDATE users SET password = ? WHERE id = ?');
        $stmt->execute([$hashedPassword, $id]);
    }

    public function getStaff(): array
    {
        return $this->db
            ->query("SELECT id, name, email, role, created_at FROM users WHERE role IN ('officer', 'driver') ORDER BY role, name")
            ->fetchAll();
    }

    public function create(string $name, string $email, string $hashedPassword, string $role): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?) RETURNING id'
        );
        $stmt->execute([$name, $email, $hashedPassword, $role]);
        return (int) $stmt->fetchColumn();
    }
    /** Returns only users with role = 'driver' for trip-form dropdowns */
    public function getDrivers(): array
    {
        return $this->db
            ->query("SELECT id, name FROM users WHERE role = 'driver' ORDER BY name")
            ->fetchAll();
    }
}
