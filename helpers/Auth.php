<?php
class Auth
{
    public static function generateToken($userId)
    {
        $token = bin2hex(random_bytes(16));
        file_put_contents("tokens/$token", $userId);
        
        return $token;
    }

    public static function validateToken($token)
    {
        $file = "tokens/$token";

        return file_exists($file) ? file_get_contents($file) : false;
    }

    public static function logout() {
        $headers = apache_request_headers();

        if (isset($headers['Authorization'])) {
            $token = str_replace('Bearer ', '', $headers['Authorization']);
            $tokenFile = __DIR__ . '/../tokens/' . $token;

            if (file_exists($tokenFile)) {
                if (unlink($tokenFile)) {
                    return ['success' => true, 'message' => 'Logged out successfully'];
                } else {
                    return ['success' => false, 'message' => 'Failed to delete token'];
                }
            } else {
                return ['success' => false, 'message' => 'Token not found'];
            }
        }

        return ['success' => false, 'message' => 'Authorization header missing'];
    }
}
