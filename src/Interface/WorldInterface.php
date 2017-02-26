<?php
class WorldInterface
{
	// things in the world
	public $connecting = array();
	public $players = array();
	public $mobiles = array();
	public $objects = array();
	public $mobs_in_rooms = array();
	public $mobs_in_areas = array();
	public $in_combat = array(
		'mobiles' => array(),
		'players' => array()
	);
	
	function getMobileRoomCount($mob_id, $room_id)
	{
		if(isset($this->mobs_in_rooms[$room_id][$mob_id]))
		{
			return count($this->mobs_in_rooms[$room_id][$mob_id]);
		}
		else
		{
			return 0;
		}
	}
	
	function getMobileAreaCount($mob_id, $area_id)
	{
		if(isset($this->mobs_in_areas[$area_id][$mob_id]))
		{
			return count($this->mobs_in_areas[$area_id][$mob_id]);
		}
		else
		{
			return 0;
		}
	}
	
	function mobToArea($area_id, $mob_id, $instance_key)
	{
		$this->mobs_in_areas[$area_id][$mob_id][$instance_key] = $mob_id;
	}
	
	function mobToRoom($room_id, $mob_id, $instance_key)
	{
		$this->mobs_in_rooms[$room_id][$mob_id][$instance_key] = $mob_id;
	}
	
	function mobFromArea($area_id, $mob_id, $instance_key)
	{
		if(isset($this->mobs_in_areas[$area_id][$mob_id][$instance_key]))
		{
			unset($this->mobs_in_areas[$area_id][$mob_id][$instance_key]);
		}
	}
	
	function mobFromRoom($room_id, $mob_id, $instance_key)
	{
		if(isset($this->mobs_in_rooms[$room_id][$mob_id][$instance_key]))
		{
			unset($this->mobs_in_rooms[$room_id][$mob_id][$instance_key]);
		}
	}
	
	function spawnMobile($mob, $room)
	{
		$this->mobiles[] = $mob;
		$instance = end(array_keys($this->mobiles));
		
		$this->mobToRoom($room->id, $mob->id, $instance);
		$this->mobToArea($room->area_id, $mob->id, $instance);
	}
	
	function canMobSpawn($max_in_room, $max_in_area, $mob_id, $room)
	{
		$room_ok = false;
		$area_ok = false;
		
		if($this->getMobileRoomCount($mob_id, $room->id)<$max_in_room)
		{
			$room_ok = true;
		}
		
		if($this->getMobileAreaCount($mob_id, $room->area_id)<$max_in_area)
		{
			$area_ok = true;
		}
		
		if($area_ok && $room_ok)
		{
			return true;
		}
		else
		{
			return false;
		}
			
	}
}