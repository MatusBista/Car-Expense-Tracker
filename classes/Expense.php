<?php

class Expense
{
    public const CATEGORIES = ['Palivo', 'Servis', 'Poistenie', 'Parkovanie', 'Iné'];

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAllByUser(int $userId, ?int $vehicleId = null): array
    {
        $sql = "SELECT e.*, v.name AS vehicle_name
                FROM expenses e
                JOIN vehicles v ON v.id = e.vehicle_id
                WHERE v.user_id = ?";
        $params = [$userId];

        if ($vehicleId !== null) {
            $sql .= " AND e.vehicle_id = ?";
            $params[] = $vehicleId;
        }

        $sql .= " ORDER BY e.expense_date DESC, e.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById(int $id, int $userId): ?array
    {
        $sql = "SELECT e.* FROM expenses e
                JOIN vehicles v ON v.id = e.vehicle_id
                WHERE e.id = ? AND v.user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id, $userId]);
        $expense = $stmt->fetch();

        return $expense ?: null;
    }

    public function create(int $vehicleId, string $category, float $amount, string $date, ?string $description): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO expenses (vehicle_id, category, amount, expense_date, description)
             VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$vehicleId, $category, $amount, $date, $description]);
    }

    public function update(int $id, int $vehicleId, string $category, float $amount, string $date, ?string $description): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE expenses
             SET vehicle_id = ?, category = ?, amount = ?, expense_date = ?, description = ?
             WHERE id = ?"
        );
        return $stmt->execute([$vehicleId, $category, $amount, $date, $description, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM expenses WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getTotalByUser(int $userId): float
    {
        $sql = "SELECT COALESCE(SUM(e.amount), 0) AS total
                FROM expenses e
                JOIN vehicles v ON v.id = e.vehicle_id
                WHERE v.user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $row = $stmt->fetch();

        return (float) $row['total'];
    }

    public function getSummaryByCategory(int $userId): array
    {
        $sql = "SELECT e.category, SUM(e.amount) AS total
                FROM expenses e
                JOIN vehicles v ON v.id = e.vehicle_id
                WHERE v.user_id = ?
                GROUP BY e.category
                ORDER BY total DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
