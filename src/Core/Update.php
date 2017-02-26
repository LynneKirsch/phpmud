<?php
class Update extends PlayerInterface
{
	function doTick()
	{
		$this->savePlayerCharacters();
		$this->doResets();
	}
	
	function savePlayerCharacters()
	{
		foreach($this->players as $player)
		{
			if(isset($player->CONN_STATE) && $player->CONN_STATE == "CONNECTED")
			{
				$player->pData->save();
			}
		}
	}
	
	function doResets()
	{
		$rooms = new FilesystemIterator(ROOM_DIR, FilesystemIterator::SKIP_DOTS);
		
		foreach($rooms as $room_file)
		{
			$this->roomReset($room_file);
		}
	}
	
	function roomReset($room_file)
	{
		
		$room_obj = json_decode(file_get_contents(ROOM_DIR.$room_file->getFilename()));
		$room = new Room();
		$room->load($room_obj->id);

		foreach($room->resets->mobiles as $mob_reset)
		{
			

			global $world;
			$room_max = $mob_reset->max_in_room;
			$area_max = $mob_reset->max_in_area;
			
			if($world->canMobSpawn($room_max, $area_max, $mob_reset->id, $room))
			{
				$mob = new Mobile();
				$mob->load($mob_reset->id);
				$mob->in_room = $room->id;
				$clone = clone $mob;
				$world->spawnMobile($clone, $room);
			}
		}
	}
}