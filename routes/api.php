<?php
require_once 'controllers/UserController.php';
require_once 'middleware/authMiddleware.php';
require_once 'middleware/authAdminMiddleware.php';
require_once 'controllers/DepartmentController.php';
require_once 'controllers/TicketController.php';

$routes = [
    // User routes
    ['method' => 'POST', 'pattern' => '#^api/register$#', 'handler' => ['UserController', 'register']],
    ['method' => 'POST', 'pattern' => '#^api/login$#', 'handler' => ['UserController', 'login']],

    // Protected routes (require auth)
    ['method' => 'POST', 'pattern' => '#^api/logout$#', 'handler' => ['UserController', 'logout'], 'middleware' => ['authMiddleware']],

    // departments
    ['method' => 'GET', 'pattern' => '#^api/departments$#', 'handler' => ['DepartmentController', 'index'], 'middleware' => ['authMiddleware']], // auth user can only
    ['method' => 'POST', 'pattern' => '#^api/departments$#', 'handler' => ['DepartmentController', 'create'], 'middleware' => ['authAdminMiddleware']],  // Admin only - Create a new departments
    ['method' => 'PUT', 'pattern' => '#^api/departments/(\d+)$#', 'handler' => ['DepartmentController', 'update'], 'middleware' => ['authAdminMiddleware']], // Admin only - update departments
    ['method' => 'DELETE', 'pattern' => '#^api/departments/(\d+)$#', 'handler' => ['DepartmentController', 'delete'], 'middleware' => ['authAdminMiddleware']], // Admin only - delete departments

    // Tickets
    ['method' => 'POST', 'pattern' => '#^api/tickets$#', 'handler' => ['TicketController', 'create'], 'middleware' => ['authMiddleware']], // auth user Create a new ticket
    ['method' => 'GET', 'pattern' => '#^api/tickets/my$#', 'handler' => ['TicketController', 'myTickets'], 'middleware' => ['authMiddleware']], // User's own tickets can only view
    ['method' => 'GET', 'pattern' => '#^api/tickets$#', 'handler' => ['TicketController', 'allTickets'], 'middleware' => ['authAdminMiddleware']], // Admin only - list all tickets
    ['method' => 'PUT', 'pattern' => '#^api/tickets/(\d+)/status$#', 'handler' => ['TicketController', 'changeStatus'], 'middleware' => ['authAdminMiddleware']], // Admin only - change ticket status
];
