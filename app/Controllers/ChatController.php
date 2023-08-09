<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Request;
use App\View;

class ChatController
{
    public function __construct(
        private Message $messageModel,
        private User $userModel,
        private Request $request
    ) {
    }

    public function index($uniqueId): View
    {
        $user = $this->userModel->get($uniqueId);
        return View::make('chat', ['user' => $user]);
    }

    public function insert()
    {
        $data = $this->request->all();
        $incomingId = $this->request->input('incoming_id');
        $outgoingId = $this->request->input('outgoing_id');
        $message = $this->request->input('message');

        $this->messageModel->insert($incomingId, $outgoingId, $message);
    }

    public function getAll()
    {
        $incomingId = (int) $this->request->post('incoming_id');
        $outgoingId = (int) $this->request->post('outgoing_id');

        $messages = $this->messageModel->getAll($incomingId, $outgoingId);
        $output = "";

        if (count($messages) > 0) {
            foreach ($messages as $message) {
                if ($message['outgoing_msg_id'] === $outgoingId) {
                    $output .= '<div class="chat outgoing">
                                    <div class="details">
                                        <p>' . $message['msg']  . '</p>
                                    </div>
                                </div>';
                } else {
                    $output .= '<div class="chat incoming">
                                    <img src="https://t4.ftcdn.net/jpg/05/49/98/39/360_F_549983970_bRCkYfk0P6PP5fKbMhZMIb07mCJ6esXL.jpg" alt="">
                                    <div class="details">
                                        <p>' . $message['msg'] . '</p>
                                    </div>
                                </div>';
                }
            }
        } else {
            $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
        }

        echo $output;
    }
}
