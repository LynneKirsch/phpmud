<?php
class Update extends PlayerInterface
{
	function doTick()
	{
		$this->savePlayerCharacters();
		//$this->doResets();
	}
	
	function savePlayerCharacters()
	{
		global $clients;
		
		foreach($clients as $client)
		{
			if(isset($client->CONN_STATE) && $client->CONN_STATE == "CONNECTED")
			{
				$client->pData->save();
			}
		}
	}
	
	function doResets()
	{
		$rooms = new FilesystemIterator(ROOM_DIR, FilesystemIterator::SKIP_DOTS);
		
		foreach($rooms as $room_file)
		{
			$room_obj = json_decode(file_get_contents(ROOM_DIR.$room_file->getFilename()));
			
			$room_id = $room_obj->id;
			$room = new Room($this->ch);
			$room->load($room_obj->id);
			
			foreach($room->resets->mobiles as $mob_reset)
			{
				global $counts;
				$do_reset = true;
				
				$mob_id = $mob_reset->id;
				
				if($counts->getMobileRoomCount($mob_id, $room_id) >= $mob_reset->max_in_room)
				{
					$do_reset = false;
				}
				
				if($do_reset)
				{
					$mob = new Mobile();
					$mob->load($mob_reset->id);
					$room->mobiles[] = clone($mob);
					$counts->addMob($mob_id, $room_id);
				}
			}
			
			$room->save();
		}
	}
}