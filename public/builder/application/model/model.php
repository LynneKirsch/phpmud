<?php
class Model
{
	function getRooms()
	{
		$rooms = new FilesystemIterator(ROOT.'../src/db/rooms/', FilesystemIterator::SKIP_DOTS);
		$room_obj_array = array();
		
		foreach($rooms as $room)
		{
			$room_obj = json_decode(file_get_contents($room->getPath().'/'.$room->getFilename()));
			$room_obj_array[$room_obj->id] = $room_obj;
		}
		
		return $room_obj_array;
	}
	
	function getRoom($id)
	{
		$room_obj = json_decode(file_get_contents(ROOT.'../src/db/rooms/'.$id.'.json'));
		$room_obj->area = $this->getArea($room_obj->area_id);
		return $room_obj;
	}
	
	function getArea($id)
	{
		$area_obj = json_decode(file_get_contents(ROOT.'../src/db/areas/'.$id.'.json'));
		return $area_obj;
	}
	
	function getAreaRooms($area_id)
	{
		$rooms = $this->getRooms();
		$room_obj_array = array();
		
		foreach($rooms as $room)
		{
			if($room->area_id == $area_id)
			{
				$room_obj_array[$room->id] = $room;
			}
		}
		
		return $room_obj_array;
		
	}
	
	function getAreas()
	{
		$rooms = $this->getRooms();
		
		$areas = new FilesystemIterator(ROOT.'../src/db/areas/', FilesystemIterator::SKIP_DOTS);
		$area_obj_array = array();
		
		foreach($areas as $area)
		{
			$area_obj = json_decode(file_get_contents($area->getPath().'/'.$area->getFilename()));
			$area_obj->rooms = array();
			$area_obj_array[$area_obj->id] = $area_obj;
			
			foreach($rooms as $room)
			{
				if($room->area_id == $area_obj->id)
				{
					$area_obj->rooms[$room->id] = $room;
				}
			}
		}
		
		return $area_obj_array;
	}
}
