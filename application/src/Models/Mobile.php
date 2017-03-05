<?php
class Mobile extends PlayerInterface
{
	public $id;
	public $short;
	public $long;
	public $keywords;
	public $in_room;
	public $max_hit;
	public $cur_hit;
	public $script;
	
	function load($id)
	{
		$mob = json_decode(file_get_contents(MOB_DIR.$id.'.json'));
		
		foreach($mob as $property=>$value)
		{
			$this->{$property} = $value;
		}
	}
	
		
	function mobToRoom($room_id)
	{
		global $world;
		
		$this->in_room = $room_id;
		
		if(isset($world->in_rooms[$room_id][$this->id]))
		{
			return ++$world->in_rooms[$room_id][$this->id]['count'];
		}
		else
		{
			$world->in_rooms[$room_id][$this->id]['count'] = 1;
		}
	}
	
	function mobFromRoom($mob_id, $room_id)
	{
		if(isset($this->in_rooms[$room_id][$mob_obj->id]))
		{
			return ++$this->in_rooms[$room_id][$mob_obj->id]['count'];
		}
		else
		{
			$this->in_rooms[$room_id][$mob_obj->id]['count'] = 1;
		}
	}
}

