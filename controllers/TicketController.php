<?php
require_once 'models/Ticket.php';
require_once 'helpers/Auth.php';
require_once 'helpers/Response.php';
require_once 'helpers/RateLimiter.php';

class TicketController
{
    public static function create()
    {
        $userId = $GLOBALS['currentUserId'];
        $data = json_decode(file_get_contents("php://input"), true);

        $requiredFields = ['title', 'description', 'department_id'];
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            return Response::json([
                'error' => 'Missing required fields',
                'missing_fields' => $missingFields
            ], 400);
        }

        $token = self::getTokenFromHeader();
        if (!RateLimiter::check($token, 'ticket_create')) {
            return Response::json(['error' => 'Rate limit exceeded ! you can not create ticket wihtin 10 seconds'], 429);
        }
        $result = Ticket::create($data, $userId);

        if (!$result['success']) {
            return Response::json(['error' => $result['error']], 400);
        }

        return Response::json($result['ticket'], 201);
    }

    public static function myTickets()
    {
        $userId = $GLOBALS['currentUserId'];
        $tickets = Ticket::getByUser($userId);

        return Response::json($tickets);
    }

    public static function allTickets()
    {
        $tickets = Ticket::getAll();

        return Response::json($tickets);
    }

    public static function changeStatus($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $status = $data['status'] ?? null;

        if (!in_array($status, ['open', 'in_progress', 'closed'])) {
            return Response::json(['error' => 'Invalid status'], 400);
        }

        $result = Ticket::updateStatus($id, $status);

        if (!$result['success']) {
            return Response::json(['error' => $result['error']], 404);
        }

        return Response::json($result['ticket'], 200);
    }

    private static function getTokenFromHeader()
    {
        $headers = apache_request_headers();

        return str_replace('Bearer ', '', $headers['Authorization'] ?? '');
    }
}
