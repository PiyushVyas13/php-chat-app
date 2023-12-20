<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require dirname(__DIR__) . '/vendor/autoload.php'; // Include Ratchet library
require_once 'SocketServer.php'; // Include SocketServer class

// Create WebSocket server object using SocketServer class
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new SocketServer()
        )
    ),
    8800 // Port number to listen on
);

// Start the server
echo "Starting server...\n";
$server->run();
