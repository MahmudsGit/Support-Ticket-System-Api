<?php
require_once 'controllers/UserController.php';

$routes = [
    // User routes
    ['method' => 'POST', 'pattern' => '#^api/register$#', 'handler' => ['UserController', 'register']],
    
];

