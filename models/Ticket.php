<?php
require_once 'config/db.php';

class Ticket
{
    public static function create($data, $userId)
    {
        $pdo = getPDO();
        try {
            $stmt = $pdo->prepare("
            INSERT INTO tickets (title, description, user_id, department_id)
            VALUES (?, ?, ?, ?)
        ");
            $stmt->execute([
                $data['title'],
                $data['description'],
                $userId,
                $data['department_id']
            ]);

            $id = $pdo->lastInsertId();

            $stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
            $stmt->execute([$id]);
            $ticket = $stmt->fetch();

            return ['success' => true, 'ticket' => $ticket];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Some thing wrong , Ticket not created'];
        }
    }

    public static function updateStatus($ticketId, $status)
    {
        $pdo = getPDO();
        try {
            $stmt = $pdo->prepare("UPDATE tickets SET status = ? WHERE id = ?");
            $stmt->execute([$status, $ticketId]);

            if ($stmt->rowCount() === 0) {
                return ['success' => false, 'error' => 'Ticket not found or no changes made'];
            }

            $stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
            $stmt->execute([$ticketId]);
            $ticket = $stmt->fetch();

            return ['success' => true, 'ticket' => $ticket];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Some thing wrong , Ticket not Updated'];
        }
    }

    public static function getByUser($userId)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT * FROM tickets WHERE user_id = ?");
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    public static function getAll()
    {
        $pdo = getPDO();
        $stmt = $pdo->query("SELECT * FROM tickets");

        return $stmt->fetchAll();
    }

    public static function getById($ticketId)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
        $stmt->execute([$ticketId]);

        return $stmt->fetch();
    }

    public static function isUserBelongsToTicket($ticketId, $userId) {
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT id FROM tickets WHERE id = ? AND user_id = ?");
        $stmt->execute([$ticketId, $userId]);
        return $stmt->fetch() !== false;
    }
}
