<?php
require_once 'controllers/UserController.php';
require_once 'middleware/authMiddleware.php';
require_once 'middleware/authAdminMiddleware.php';
require_once 'controllers/DepartmentController.php';
require_once 'controllers/TicketController.php';
require_once 'controllers/TicketNoteController.php';
require_once 'controllers/FileUploadController.php';

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

    // Ticket notes
    ['method' => 'POST', 'pattern' => '#^api/tickets/(\d+)/notes$#', 'handler' => ['TicketNoteController', 'addNote'], 'middleware' => ['authMiddleware']], // auth user Create Add note to a ticket
    ['method' => 'GET', 'pattern' => '#^api/tickets/(\d+)/notes/my$#', 'handler' => ['TicketNoteController', 'getNotes'], 'middleware' => ['authMiddleware']], // User's own notes for a ticket
    ['method' => 'GET', 'pattern' => '#^api/tickets/(\d+)/notes$#', 'handler' => ['TicketNoteController', 'allNotes'], 'middleware' => ['authAdminMiddleware']], // Admin only - list all notes for a ticket
    ['method' => 'DELETE', 'pattern' => '#^api/tickets/(\d+)/notes/(\d+)$#', 'handler' => ['TicketNoteController', 'delete'], 'middleware' => ['authAdminMiddleware']], // Admin only - delete a note
    
    // File upload
    ['method' => 'POST', 'pattern' => '#^api/tickets/(\d+)/attachments$#', 'handler' => ['FileUploadController', 'upload'], 'middleware' => ['authMiddleware']], // auth user Upload attachment to a ticket
    ['method' => 'DELETE', 'pattern' => '#^api/tickets/(\d+)/attachments/(\d+)$#', 'handler' => ['FileUploadController', 'delete'], 'middleware' => ['authMiddleware']], // auth user Delete an attachment from a ticket
];
