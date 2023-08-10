<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;
use App\Session;

class Message extends Model
{
    public function insert($incomingId, $outgoingId, $message)
    {
        $stmt = $this->query("INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES (?, ?, ?)", [$incomingId, $outgoingId, $message]);
        $messageId = (int) $this->db->lastInsertId();

        return $messageId;
    }

    public function getAll($incomingId, $outgoingId)
    {
        $stmt = $this->query(
            "SELECT * FROM messages 
            LEFT JOIN users on users.unique_id = messages.outgoing_msg_id
            WHERE (outgoing_msg_id = $outgoingId AND incoming_msg_id = $incomingId) 
            OR (outgoing_msg_id = $incomingId AND incoming_msg_id = $outgoingId) ORDER BY msg_id"
        );

        $messages = $stmt->fetchAll();

        return $messages;
    }

    public function getLastMessage($uniqueId, $outgoingId)
    {
        $stmt = $this->query(
            "SELECT * FROM messages WHERE (incoming_msg_id = {$uniqueId}
                OR outgoing_msg_id = {$uniqueId}) AND (outgoing_msg_id = {$outgoingId} 
                OR incoming_msg_id = {$outgoingId}) ORDER BY msg_id DESC LIMIT 1"
        );

        $messages = $stmt->fetch();

        return $messages;
    }
}
