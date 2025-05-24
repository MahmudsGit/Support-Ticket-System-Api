<?php
require_once 'controllers/UserController.php';
require_once 'middleware/authMiddleware.php';
require_once 'middleware/authAdminMiddleware.php';
require_once 'controllers/DepartmentController.php';

$routes = [
    // User routes
    ['method' => 'POST', 'pattern' => '#^api/register$#', 'handler' => ['UserController', 'register']],
    ['method' => 'POST', 'pattern' => '#^api/login$#', 'handler' => ['UserController', 'login']],
    
    // Protected routes (require auth)
    ['method' => 'POST', 'pattern' => '#^api/logout$#', 'handler' => ['UserController', 'logout'], 'middleware' => ['authMiddleware']],

    // departments
    ['method' => 'GET', 'pattern' => '#^api/departments$#', 'handler' => ['DepartmentController', 'index'], 'middleware' => ['authMiddleware']],
    ['method' => 'POST', 'pattern' => '#^api/departments$#', 'handler' => ['DepartmentController', 'create'], 'middleware' => ['authAdminMiddleware']],  // Admin only
    ['method' => 'PUT', 'pattern' => '#^api/departments/(\d+)$#', 'handler' => ['DepartmentController', 'update'], 'middleware' => ['authAdminMiddleware']], // Admin only
    ['method' => 'DELETE', 'pattern' => '#^api/departments/(\d+)$#', 'handler' => ['DepartmentController', 'delete'], 'middleware' => ['authAdminMiddleware']], // Admin only
];

