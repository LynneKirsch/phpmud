<?php
class Wiz extends PlayerInterface
{
	function createObject()
	{
		$object_dir = new FilesystemIterator(OBJ_DIR, FilesystemIterator::SKIP_DOTS);
		$object_count = iterator_count($object_dir);
		$object = new Object($this->ch);
		$object->id = $object_count+1;
		$object->save();
	}
	
	function loadObject($args)
	{
		if(!is_numeric($args) || count(explode(' ', $args))>1)
		{
			$this->ch->send("Syntax: load_obj [object_id]");
			return;
		}
		
		if(file_exists(OBJ_DIR.$args.".json"))
		{
			$object = json_decode(file_get_contents(OBJ_DIR.$args.'.json'));
			$this->objToChar($object, $this->ch);
			$this->toChar($this->ch, "You have created " . $object->short . "!");
			
			foreach($this->players as $client)
			{
				if($client != $this->ch && $client->pData->in_room == $this->ch->pData->in_room)
				{
					$client->send($this->ch->pData->name . " creates " . $object->short . "!\n");
				}
			}
		}
		else
		{
			$this->ch->send("No such object.");
		}	
	}
	
	function doPurge()
	{
		$room = new Room($this->ch);
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
		$this->toChar($this->ch, "Room purged.");
	}
	
	function createRoom()
	{
		$room_dir = new FilesystemIterator(ROOM_DIR, FilesystemIterator::SKIP_DOTS);
		$room_count = iterator_count($room_dir);
		$room = new Room($this->ch);
		$room->id = $room_count+1;
		$room->save();
	}
	
	function pDump()
	{
		ob_start();
		echo '<pre>';
		print_r($this->ch->pData);
		echo '</pre>';
		$output = ob_get_clean();
		$this->ch->send($output);
	}
	function rDump()
	{
		$room = new Room($this->ch);
		$room->load();
		
		ob_start();
		echo '<pre>';
		print_r($room->dumpRoom());
		echo '</pre>';
		$output = ob_get_clean();
		$this->ch->send($output);

	}
}

