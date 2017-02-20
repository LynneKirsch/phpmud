<?php 
require 'vendor/autoload.php';
require 'src/autoload.php';
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\Socket\Server as Reactor;
use React\EventLoop\Factory as LoopFactory;;

$clients = array();
$DB_OBJ = new Database();

class Server implements MessageComponentInterface
{   
    public function __construct(React\EventLoop\LoopInterface $loop) 
    {
        $loop->addPeriodicTimer(40, function() 
        {
            $this->doTick();
        });
	}

    public function onOpen(ConnectionInterface $ch) 
    {
        global $clients;
        $clients[$ch->resourceId] = $ch;
		$ch->CONN_STATE = "GET_NAME";
		$ch->send("Who dares storm our wayward path? ");
    }

    public function onMessage(ConnectionInterface $ch, $args) 
    {	
		if($ch->CONN_STATE == "CONNECTED")
		{
			$ch->send("> " . $args . "\n");
			$interpreter = new Interpreter($ch);
			$interpreter->interpret($args);
		}
		else
		{
			$ch->send($args);
			$login = new Login($ch, $args);
			$login->start();
		}

    }

    public function onClose(ConnectionInterface $ch) 
    {
        global $clients;
        unset($clients[$ch->resourceId]);
        echo "Player {$ch->pData->name} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) 
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    public function doTick()
    {
        global $clients;
		
        foreach ($clients as $client) 
		{
			if($client->CONN_STATE == "CONNECTED")
			{
				$update = new Update();
				$update->doTick();
			}
        }
    }
}

$loop = LoopFactory::create();
$socket = new Reactor($loop);
$socket->listen(9000, '174.138.76.27');
$server = new IoServer(new HttpServer(new WsServer(new Server($loop))), $socket, $loop);
$server->run();