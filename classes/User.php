<?php

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function register(string $username, string $password): bool
    {
        if ($this->findByUsername($username) !== null) {
            return false;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
        return $stmt->execute([$username, $hash]);
    }

    public function login(string $username, string $password): ?array
    {
        $user = $this->findByUsername($username);

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }

        return null;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        return $user ?: null;
    }
}
