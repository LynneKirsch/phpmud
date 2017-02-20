<?php

$loop = LoopFactory::create();
$socket = new Reactor($loop);
$socket->listen(9000, '174.138.76.27');
$server = new IoServer(new HttpServer(new WsServer(new Server($loop))), $socket, $loop);
$server->run();