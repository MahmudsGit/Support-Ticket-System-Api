<?php
require_once 'config/db.php';

class TicketNote {
    public static function addNote($ticketId, $userId, $note) {
        $pdo = getPDO();
        $stmt = $pdo->prepare("INSERT INTO ticket_notes (user_id, ticket_id, note) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $ticketId, $note]);
    
        $id = $pdo->lastInsertId();
    
        $stmt = $pdo->prepare("
            SELECT tn.*, u.name AS author
            FROM ticket_notes tn
            JOIN users u ON tn.user_id = u.id
            WHERE tn.id = ?
        ");
        $stmt->execute([$id]);
    
        return ['success' => true, 'note' => $stmt->fetch()];
    }
    
    public static function getNotesByTicket($ticketId) {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT tn.id, tn.ticket_id, tn.note, tn.created_at, u.name AS author 
            FROM ticket_notes tn 
            JOIN users u ON tn.user_id = u.id 
            WHERE tn.ticket_id = ?
            ORDER BY tn.created_at ASC
        ");
        $stmt->execute([$ticketId]);
        return $stmt->fetchAll();
    }

    public static function delete($ticketId, $id) {
        $pdo = getPDO();
    
        $stmt = $pdo->prepare("DELETE FROM ticket_notes WHERE ticket_id = ? AND id = ?");
        $stmt->execute([$ticketId, $id]);
    
        return ['success' => $stmt->rowCount() > 0];
    }    
}
