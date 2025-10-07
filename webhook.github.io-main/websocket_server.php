<?php
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server as Reactor;

require __DIR__ . '/vendor/autoload.php';

$loop = Factory::create();

// Replace 'your_cert.pem' and 'your_key.pem' with your SSL certificate and private key paths
$webSock = new Reactor('wss://localhost:8080', $loop, [
    'local_cert' => 'cert.pem',
    'local_pk' => 'key.pem',
    'verify_peer' => false, // Set to true in production for peer verification
]);

$webServer = new IoServer(
    new HttpServer(
        new WsServer(
            new MyWebSocket()
        )
    ),
    $webSock
);

echo "Secure WebSocket server started on port 8080...\n";
$loop->run();
