<?php
class Update extends GameInterface
{
	function doTick()
	{
		$this->savePlayerCharacters();
		$this->doResets();
	}
	
	function savePlayerCharacters()
	{
		global $clients;
		
		foreach($clients as $client)
		{
			$client->pData->save();
		}
	}
	
	function doResets()
	{
		$rooms = new FilesystemIterator(ROOM_DIR, FilesystemIterator::SKIP_DOTS);
		
		foreach($rooms as $room_file)
		{
			$room_obj = json_decode(file_get_contents(ROOM_DIR.$room_file->getFilename()));
			
			$room = new Room($this->ch);
			$room->load($room_obj->id);
			
			foreach($room->resets->mobiles as $id=>$mobile_reset_obj)
			{
				$mob = new Mobile();
				$mob->load($id);
				$room->mobiles[] = $mob->get();
			}
			
			$room->save();
		}
	}
}