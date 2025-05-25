<?php
require_once 'config/db.php';

class TicketAttachment {
    public static function create($ticketId, $filePath) {
        $pdo = getPDO();
        $stmt = $pdo->prepare("INSERT INTO ticket_attachments (ticket_id, file_path) VALUES (?, ?)");
        $stmt->execute([$ticketId, $filePath]);

        $id = $pdo->lastInsertId();
        $stmt = $pdo->prepare("SELECT * FROM ticket_attachments WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public static function delete($id) {
        $pdo = getPDO();

        $stmt = $pdo->prepare("SELECT * FROM ticket_attachments WHERE id = ?");
        $stmt->execute([$id]);
        $attachment = $stmt->fetch();

        if (!$attachment) return ['success' => false, 'error' => 'Attachment not found'];

        // Delete file
        if (file_exists($attachment['file_path'])) {
            unlink($attachment['file_path']);
        }

        // Delete DB record
        $stmt = $pdo->prepare("DELETE FROM ticket_attachments WHERE id = ?");
        $stmt->execute([$id]);

        return ['success' => true];
    }
}
