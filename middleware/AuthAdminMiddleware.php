<?php
require_once 'config/db.php';
require_once 'helpers/Auth.php';
require_once 'helpers/Response.php';

function authAdminMiddleware() {
    // First, validate token like authMiddleware
    $headers = apache_request_headers();
    if (!isset($headers['Authorization'])) {
        Response::json(['error' => 'Authorization header missing'], 401);
        return false;
    }

    $token = str_replace('Bearer ', '', $headers['Authorization']);
    $userId = Auth::validateToken($token);

    if (!$userId) {
        Response::json(['error' => 'Invalid or expired token'], 401);
        return false;
    }

    // Check if user is admin
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user || $user['role'] !== 'admin') {
        Response::json(['error' => 'Forbidden - admin only'], 403);
        return false;
    }

    // Store user id globally
    $GLOBALS['currentUserId'] = $userId;

    return true;
}
