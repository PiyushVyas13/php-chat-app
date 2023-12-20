<?php
require_once '../database.php';

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class SocketServer implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Add new client connection to clients list
        $this->clients->attach($conn);

        // Send welcome message to new client
        // $conn->send("Welcome to the chat room!");
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {


        $message = json_decode($msg);
        $data = json_decode($message->data);

        insert_chat_message($data->room_id, $data->user_id, $data->content);


        // Broadcast message to all clients
        foreach ($this->clients as $client) {
            if ($client !== $from) {
                $client->send($msg);
            }
        }
        echo $msg;
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Remove client connection from clients list
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // Handle WebSocket errors
        echo "WebSocket error: {$e->getMessage()}\n";
        $conn->close();
    }
}
