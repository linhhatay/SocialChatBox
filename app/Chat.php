<?php

namespace App;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require dirname(__DIR__) . '/vendor/autoload.php';

class Chat implements MessageComponentInterface
{
    protected $clients;
    protected $db;
    protected $uniqueId;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $servername = "localhost:3307";
        $username = "root";
        $password = "";
        $database = "chatbox";

        $this->db = mysqli_connect($servername, $username, $password, $database);
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        echo sprintf(
            'Connection %d sending message "%s" to %d other connection%s' . "\n",
            $from->resourceId,
            $msg,
            $numRecv,
            $numRecv == 1 ? '' : 's'
        );



        $data = json_decode($msg, true);
        var_dump($data);

        if (($data['type'] === 'login') && isset($data['userId'])) {

            $this->uniqueId = $data['userId'];

            $this->updateUserStatus($this->uniqueId, 'Active now');
            return;
        }

        if (($data['type'] === 'logout') && isset($data['userId'])) {

            $this->uniqueId = $data['userId'];

            $this->updateUserStatus($this->uniqueId, 'Offline now');
            return;
        }

        $data['img'] = $this->getImage($data['outgoing_id']);
        $data['date'] = date('Y-m-d H:i:s');
        $data['type'] = 'chat';

        foreach ($this->clients as $client) {
            // if ($from !== $client) {
            //     // The sender is not the receiver, send to each client connected
            //     $client->send($msg);
            // }

            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send(json_encode($data));
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    public function getImage($uniqueId)
    {

        $sql = "SELECT * FROM users 
        LEFT JOIN messages ON users.unique_id = messages.outgoing_msg_id  
        WHERE outgoing_msg_id = $uniqueId";
        $result = mysqli_query($this->db, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data[0]['img'];
        } else {
            echo "Không có dữ liệu.";
        }
        mysqli_close($this->db);
    }

    private function updateUserStatus($uniqueId, $status)
    {
        $sql = "UPDATE users SET status = '$status' WHERE unique_id = '$uniqueId'";
        mysqli_query($this->db, $sql);
        // mysqli_close($this->db);
        $data = [
            'uniqueId' => $uniqueId,
            'userStatus' => $status,
        ];

        foreach ($this->clients as $client) {
            $client->send(json_encode($data));
        }
    }
}
