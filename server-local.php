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
		$update = new Update();
		$update->doTick();
		
        $loop->addPeriodicTimer(1, function() 
        {
            $this->doBeat();	
        });
	}

    public function onOpen(ConnectionInterface $ch) 
    {
        global $world;
        $world->connecting[$ch->resourceId] = $ch;
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
		
		if(isset($ch->pData->name))
		{
			if(isset($world->players[$ch->pData->name]))
			{
				echo "Player {$ch->pData->name} has disconnected\n";
				unset($world->players->{$ch->pData->name});
			}
		}
		
		if(isset($world->connecting->{$ch->resourceId}))
		{
			echo "Connection " . $ch->resourceId . " has disconnected.";
			unset($world->connecting->{$ch->resourceId});
		}
    }

    public function onError(ConnectionInterface $conn, \Exception $e) 
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    public function doBeat()
    {
		global $world;
		++$world->beats;
		
		foreach($world->process_queue as $trigger_beat => $process_array)
		{
			// if the beat # that the function should fire on is less than,
			// or equal to the current beat, fire the function.
			if($trigger_beat <= $world->beats)
			{
				foreach($process_array as $process)
				{
					$class = new $process->class();
					call_user_func_array(array($class, $process->function), $process->params);
				}
						
				// remove it from the queue
				unset($world->process_queue[$trigger_beat]);
			}
			// else, the beat # the function should fire on is greater than the current beat, 
			// so break out of the loop.
			else
			{
				break;
			}
		}
		
		print_r(array_keys($world->process_queue));
		
		if($world->beats % 2 === 0)
		{
			$update = new Update();
			$update->doBeat();
		}
    }
}

$loop = LoopFactory::create();
$socket = new Reactor($loop);
$socket->listen(9000, 'localhost');
$server = new IoServer(new HttpServer(new WsServer(new Server($loop))), $socket, $loop);
$server->run();