<?php
require_once 'helpers/Auth.php';
require_once 'helpers/Response.php';
require_once 'models/TicketAttachment.php';
require_once 'models/Ticket.php';

class FileUploadController {
    public static function upload($ticketId) {
        $userId = $GLOBALS['currentUserId'];
        if (!Ticket::isUserBelongsToTicket($ticketId, $userId)) {
            return Response::json(['error' => 'You are not authorized to upload to this ticket'], 403);
        }
        if (!isset($_FILES['file'])) {
            return Response::json(['error' => 'No file uploaded'], 400);
        }

        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir);

        $filename = basename($_FILES['file']['name']);
        $targetPath = $uploadDir . uniqid() . "_" . $filename;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
            $attachment = TicketAttachment::create($ticketId, $targetPath);
            return Response::json($attachment, 201);
        } else {
            return Response::json(['error' => 'Upload failed'], 500);
        }
    }

    public static function delete($ticketId, $id) {
        $userId = $GLOBALS['currentUserId'];
        if (!Ticket::isUserBelongsToTicket($ticketId, $userId)) {
            return Response::json(['error' => 'You are not authorized to upload to this ticket'], 403);
        }
        $result = TicketAttachment::delete($id);

        if (!$result['success']) {
            return Response::json(['error' => $result['error']], 403);
        }

        return Response::json(['message' => 'Attachment deleted successfully'], 200);
    }
}
