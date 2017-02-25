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

$world = new WorldInterface();

class Server implements MessageComponentInterface
{   
    public function __construct(React\EventLoop\LoopInterface $loop) 
    {
        $loop->addPeriodicTimer(45, function() 
        {
            $this->doTick();
        });
		
        $loop->addPeriodicTimer(2, function() 
        {
            $this->doBeat();
        });
	}

    public function onOpen(ConnectionInterface $ch) 
    {
        global $world;
        $world->players[$ch->resourceId] = $ch;
		$ch->CONN_STATE = "GET_NAME";
		$ch->pData = new stdClass();
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
        global $world;
        unset($world->players[$ch->resourceId]);
		
		if(isset($ch->pData) && $ch->CONN_STATE == "CONNECTED")
		{
			echo "Player {$ch->pData->name} has disconnected\n";
		}
		else
		{
			echo "Connection " . $ch->resourceId . " has disconnected.";
		}
       
    }

    public function onError(ConnectionInterface $conn, \Exception $e) 
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    public function doTick()
    {
        $update = new Update();
		$update->doTick();
    }
	
	
    public function doBeat()
    {
		$combat = new Combat();
		$combat->initializeBeat();
    }
}

$loop = LoopFactory::create();
$socket = new Reactor($loop);
$socket->listen(9000, 'localhost');
$server = new IoServer(new HttpServer(new WsServer(new Server($loop))), $socket, $loop);
$server->run();