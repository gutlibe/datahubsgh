<?php

namespace App\Classes;

use PDO;

class User
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function create($firstName, $lastName, $phoneNumber, $email, $password, $role = 'user')
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare(
            "INSERT INTO users (first_name, last_name, phone_number, email, password, role, balance) VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$firstName, $lastName, $phoneNumber, $email, $hashedPassword, $role, 0]);

        return $this->conn->lastInsertId();
    }

    public function findByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function login($email, $password)
    {
        $user = $this->findByEmail($email);
        if ($user && $this->verifyPassword($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function findById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByIdForUpdate($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ? FOR UPDATE");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }

    public function updateBalance(int $userId, float $newBalance)
    {
        $stmt = $this->conn->prepare("UPDATE users SET balance = ? WHERE id = ?");
        return $stmt->execute([$newBalance, $userId]);
    }

    public function getAll($search = '', $filter = 'email')
    {
        $sql = "SELECT id, CONCAT(first_name, ' ', last_name) as name, email, role, balance, created_at, phone_number FROM users";
        $params = [];

        if (!empty($search)) {
            $sql .= " WHERE " . ($filter === 'phone_number' ? "phone_number" : "email") . " LIKE ?";
            $params[] = "%$search%";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUserDetails($userId, $balance, $role)
    {
        $stmt = $this->conn->prepare("UPDATE users SET balance = ?, role = ? WHERE id = ?");
        return $stmt->execute([$balance, $role, $userId]);
    }

    public function countAll()
    {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM users");
        return $stmt->fetchColumn();
    }
}
