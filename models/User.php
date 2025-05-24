<?php
require_once 'config/db.php';
require_once 'helpers/Auth.php';

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

    public static function login($data) {
        $pdo = getPDO();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$data['email']]);
        $user = $stmt->fetch();

        if ($user && password_verify($data['password'], $user['password_hash'])) {
            $token = Auth::generateToken($user['id']);
            return ['token' => $token, 'user' => ['id' => $user['id'], 'name' => $user['name'], 'role' => $user['role']]];
        }

        return ['error' => 'Invalid credentials'];
    }
}
