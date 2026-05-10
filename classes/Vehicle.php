<?php

class Vehicle
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAllByUser(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM vehicles WHERE user_id = ? ORDER BY name");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getById(int $id, int $userId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM vehicles WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
        $vehicle = $stmt->fetch();

        return $vehicle ?: null;
    }

    public function create(int $userId, string $name, ?string $licensePlate, ?int $year): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO vehicles (user_id, name, license_plate, year) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$userId, $name, $licensePlate, $year]);
    }

    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM vehicles WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }
}
