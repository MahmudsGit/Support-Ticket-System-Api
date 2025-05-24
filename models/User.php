<?php
require_once 'config/db.php';

class User {
    public static function create($data) {
        $pdo = getPDO();

        // email validation
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Invalid email'];
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $data['name'],
                $data['email'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['role']
            ]);

            return ['success' => true, 'message' => 'User registered'];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Email already exists or invalid role'];
        }
    }
}
