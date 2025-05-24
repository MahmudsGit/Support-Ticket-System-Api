<?php
class Auth
{
    public static function generateToken($userId)
    {
        $token = bin2hex(random_bytes(16));
        file_put_contents("tokens/$token", $userId);
        
        return $token;
    }

}
