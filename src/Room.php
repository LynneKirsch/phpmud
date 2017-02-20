<?php
class Room extends GameInterface
{
	public $id;
	public $area_id;
	public $name;
	public $description;
	public $exits;
	public $objects;
	public $mobiles;
	public $resets;
	
	public function save()
	{
		
		if(!empty($this->id))
		{
			$ref_room_obj = new ReflectionClass($this);
			$room = new stdClass();
			
			foreach($ref_room_obj->getProperties() as $property)
			{
				if($property->class == $ref_room_obj->name)
				{
					$room->{$property->name} = $property->getValue($this);
				}
			}
			
			$room_file = fopen(ROOM_DIR.$room->id.".json", "w");
			fwrite($room_file, json_encode($room, JSON_PRETTY_PRINT));
			fclose($room_file);
		}
	}
	
	public function load($id = null)
	{
		if(is_null($id))
		{
			$id = $this->ch->pData->in_room;
		}
		
		$room = json_decode(file_get_contents(ROOM_DIR.$id.'.json'));
		
		foreach($room as $property => $value)
		{
			$this->{$property} = $value;
		}
		
		if(empty($this->objects))
		{
			$this->objects = new stdClass();
		}
		
		if(empty($this->mobiles))
		{
			$this->mobiles = new stdClass();
		}
		
		if(empty($this->resets))
		{
			$this->resets = new stdClass();
		}

		if(empty($this->exits))
		{
			$exit_obj = new stdClass();
			$exit_obj->to_room = null;
			$exit_obj->is_door = null;
			$exit_obj->door_name = null;
			$exit_obj->is_closed = null;
			$exit_obj->cur_closed = null;
			$exit_obj->is_locked = null;
			$exit_obj->cur_locked = null;
			$exit_obj->lock_difficulty = null;
		}
	}
	
	public function dumpRoom()
	{
		$ref_room = new ReflectionClass($this);
		$room = new stdClass();
		
		foreach($ref_room->getProperties() as $property)
		{
			if($property->class == $ref_room->name)
			{
				$room->{$property->name} = $property->getValue($this);
			}
		}
		
		return $room;
	}
}