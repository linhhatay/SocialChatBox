<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Request;
use App\Session;
use App\View;

class UserController
{
    public function __construct(
        private Message $messageModel,
        private User $userModel,
        private Request $request
    ) {
    }

    public function getAll()
    {
        $session = Session::getInstance();
        $uniqueId = (int) $session->get('user')['unique_id'];

        $users = $this->userModel->getAll($uniqueId);
        $output = '';


        if (count($users) <= 0) {
            $output .= "No users are available to chat";
        }

        $outgoingId = (int) $session->get('user')['unique_id'];
        foreach ($users as $user) {
            $lastMessage = $this->messageModel->getLastMessage($user['unique_id'], $outgoingId);
            if (!$lastMessage) {
                $result = 'No messages available';
            } else {
                $result = $lastMessage['msg'];
            }
            (strlen($result) > 28) ? $msg =  substr($result, 0, 28) . '...' : $msg = $result;
            if (isset($lastMessage['outgoing_msg_id'])) {
                ($outgoingId === $lastMessage['outgoing_msg_id']) ? $you = "You: " : $you = "";
            } else {
                $you = "";
            }
            ($user['status'] === 'Offline now') ? $offline = 'offline' : $offline = '';
            $output .= '<a href="/Chatbox/chat/' . $user['unique_id'] . '">
                    <div class="content">
                    <img src="' . $user['img'] . '" alt="">
                    <div class="details">
                        <span>' . $user['fname'] . " " . $user['lname'] . '</span>
                        <p>' . $you . $msg . '</p>
                    </div>
                    </div>
                    <div class="status-dot ' . $offline . '"><i class="fas fa-circle"></i></div>
                </a>';
        }

        echo $output;
    }

    public function searchUser()
    {
        $session = Session::getInstance();
        $outgoingId = (int) $session->get('user')['unique_id'];
        $keyword = (string) $this->request->post('key');
        $users = $this->userModel->search($keyword, $outgoingId);
        $output = "";


        if (count($users) <= 0) {
            $output .= 'No user found related to your search term';
        }

        foreach ($users as $user) {
            $lastMessage = $this->messageModel->getLastMessage($user['unique_id'], $outgoingId);
            if (!$lastMessage) {
                $result = 'No messages available';
            } else {
                $result = $lastMessage['msg'];
            }
            (strlen($result) > 28) ? $msg =  substr($result, 0, 28) . '...' : $msg = $result;
            if (isset($lastMessage['outgoing_msg_id'])) {
                ($outgoingId === $lastMessage['outgoing_msg_id']) ? $you = "You: " : $you = "";
            } else {
                $you = "";
            }
            ($user['status'] === 'Offline now') ? $offline = 'offline' : $offline = '';

            $output .= '<a href="/Chatbox/chat/' . $user['unique_id'] . '">
                    <div class="content">
                    <img src="' . $user['img'] . '" alt="">
                    <div class="details">
                        <span>' . $user['fname'] . " " . $user['lname'] . '</span>
                        <p>' . $you . $msg . '</p>
                    </div>
                    </div>
                    <div class="status-dot ' . $offline . '"><i class="fas fa-circle"></i></div>
                </a>';
        }

        echo $output;
    }

    public function chat($uniqueId): View
    {
        $user = $this->userModel->get($uniqueId);
        return View::make('chat', ['user' => $user]);
    }
}
