<?php
class Wiz extends GameInterface
{
	function createObject()
	{
		$object_dir = new FilesystemIterator(OBJ_DIR, FilesystemIterator::SKIP_DOTS);
		$object_count = iterator_count($object_dir);
		$object = new Object(parent::$ch);
		$object->id = $object_count+1;
		$object->save();
	}
	
	function loadObject($args)
	{
		global $clients;
		
		if(!is_numeric($args) || count(explode(' ', $args))>1)
		{
			parent::$ch->send("Syntax: load_obj [object_id]");
			return;
		}
		
		if(file_exists(OBJ_DIR.$args.".json"))
		{
			$object = json_decode(file_get_contents(OBJ_DIR.$args.'.json'));
			$this->objToChar($object, parent::$ch);
			parent::$ch->send("You have created " . $object->short . "!\n");
			
			foreach($clients as $client)
			{
				if($client != parent::$ch && $client->pData->in_room == parent::$ch->pData->in_room)
				{
					$client->send(parent::$ch->pData->name . " creates " . $object->short . "!\n");
				}
			}
		}
		else
		{
			parent::$ch->send("No such object.");
		}	
	}
	
	function doPurge()
	{
		$room = new Room(parent::$ch);
		$room->load();
		
		if(count($room->objects)>0)
		{
			foreach($room->objects as $key=>$object)
			{
				unset($room->objects->{$key});
			}
		}
		
		if(count($room->mobiles)>0)
		{
			foreach($room->mobiles as $key=>$mobile)
			{
				unset($room->mobiles[$key]);
			}
		}
		
		$room->save();
		$this->toChar(parent::$ch, "Room purged.");
	}
	
	function createRoom()
	{
		$room_dir = new FilesystemIterator(ROOM_DIR, FilesystemIterator::SKIP_DOTS);
		$room_count = iterator_count($room_dir);
		$room = new Room(parent::$ch);
		$room->id = $room_count+1;
		$room->save();
	}
	
	function pDump()
	{
		ob_start();
		echo '<pre>';
		print_r(parent::$ch->pData);
		echo '</pre>';
		$output = ob_get_clean();
		parent::$ch->send($output);
	}
	function rDump()
	{
		$room = new Room(parent::$ch);
		$room->load();
		
		ob_start();
		echo '<pre>';
		print_r($room->dumpRoom());
		echo '</pre>';
		$output = ob_get_clean();
		parent::$ch->send($output);

	}
}

