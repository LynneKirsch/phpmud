<?php
class Wiz extends GameInterface
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
			$this->objToChar($object);
		}
		else
		{
			$this->ch->send("No such object.");
		}	
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
		echo '<pre>';
		print_r($this->ch->pData);
		echo '</pre>';
	}
}

