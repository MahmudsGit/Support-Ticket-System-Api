<?php
require_once 'models/TicketNote.php';
require_once 'helpers/Auth.php';
require_once 'helpers/Response.php';
require_once 'models/Ticket.php';

class TicketNoteController {
    public static function addNote($ticketId) {
        $userId = $GLOBALS['currentUserId'];
        $data = json_decode(file_get_contents("php://input"), true);
    
        if (empty($data['note'])) {
            return Response::json(['error' => 'Note content is required'], 400);
        }
    
        $result = TicketNote::addNote($ticketId, $userId, $data['note']);
    
        return Response::json($result['note'], 201);
    }
    
    public static function getNotes($ticketId) {
        $userId = $GLOBALS['currentUserId'];
        if (!Ticket::isUserBelongsToTicket($ticketId, $userId)) {
            return Response::json(['error' => 'You are not authorized to get notes to this ticket'], 403);
        }
        $notes = TicketNote::getNotesByTicket($ticketId);

        return Response::json($notes);
    }

    public static function allNotes($ticketId) {
        $notes = TicketNote::getNotesByTicket($ticketId);

        return Response::json($notes);
    }

    public static function delete($ticketId, $id) {
        $userId = $GLOBALS['currentUserId'];
        $result = TicketNote::delete($ticketId, $id);
    
        if (!$result['success']) {
            return Response::json(['error' => 'Note not found or not allowed to delete'], 404);
        }
    
        return Response::json(['message' => 'Note deleted successfully'], 200);
    }    
}
