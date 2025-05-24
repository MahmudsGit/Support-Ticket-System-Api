<?php
require_once 'controllers/UserController.php';

$routes = [
    // User routes
    ['method' => 'POST', 'pattern' => '#^api/register$#', 'handler' => ['UserController', 'register']],
    ['method' => 'POST', 'pattern' => '#^api/login$#', 'handler' => ['UserController', 'login']],
    
];

