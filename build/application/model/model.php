<?php
class Model
{
	function getRooms()
	{
		$areas = $this->getAreas();
		
		$rooms = new FilesystemIterator(ROOT.'../src/db/rooms/', FilesystemIterator::SKIP_DOTS);
		$room_obj_array = array();
		
		foreach($rooms as $room)
		{
			$room_obj = json_decode(file_get_contents($room->getPath().'/'.$room->getFilename()));
			$room_obj->area = $areas[$room_obj->area_id];
			$room_obj_array[$room_obj->id] = $room_obj;
		}
		
		return $room_obj_array;
	}
	
	function getRoom($id)
	{
		$areas = $this->getAreas();
		$room_obj = json_decode(file_get_contents(ROOT.'../src/db/rooms/'.$id.'.json'));
		$room_obj->area = $areas[$room_obj->area_id];
		return $room_obj;
	}
	
	function getAreas()
	{
		$areas = new FilesystemIterator(ROOT.'../src/db/areas/', FilesystemIterator::SKIP_DOTS);
		$area_obj_array = array();
		
		foreach($areas as $area)
		{
			$area_obj = json_decode(file_get_contents($area->getPath().'/'.$area->getFilename()));
			$area_obj_array[$area_obj->id] = $area_obj;
		}
		
		return $area_obj_array;
	}
}
