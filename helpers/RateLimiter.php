<?php
class RateLimiter {
    private static $limit = 10; 

    public static function check($token, $endpoint) {
        $key = md5($token . $endpoint);
        $file = "rate_limits/$key";

        if (!file_exists("rate_limits")) mkdir("rate_limits");

        $now = time();
        if (file_exists($file)) {
            $last = file_get_contents($file);
            if ($now - $last < self::$limit) {
                return false;
            }
        }

        file_put_contents($file, $now);
        return true;
    }
}
