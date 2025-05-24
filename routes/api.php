<?php
require_once 'controllers/UserController.php';
require_once 'middleware/authMiddleware.php';

$routes = [
    // User routes
    ['method' => 'POST', 'pattern' => '#^api/register$#', 'handler' => ['UserController', 'register']],
    ['method' => 'POST', 'pattern' => '#^api/login$#', 'handler' => ['UserController', 'login']],
    
    // Protected routes (require auth)
    ['method' => 'POST', 'pattern' => '#^api/logout$#', 'handler' => ['UserController', 'logout'], 'middleware' => ['authMiddleware']],
];

