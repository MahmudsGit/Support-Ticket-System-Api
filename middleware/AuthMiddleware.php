<?php
require_once './helpers/Auth.php';
require_once './helpers/Response.php';

function authMiddleware() {
    $headers = apache_request_headers();
    if (!isset($headers['Authorization'])) {
        Response::json(['error' => 'Unauthorized'], 401);
        return false;
    }

    $token = str_replace('Bearer ', '', $headers['Authorization']);
    $userId = Auth::validateToken($token);

    if (!$userId) {
        Response::json(['error' => 'Invalid or expired token'], 401);
        return false;
    }

    // Store user id globally for later use (optional)
    $GLOBALS['currentUserId'] = $userId;

    return true;
}
