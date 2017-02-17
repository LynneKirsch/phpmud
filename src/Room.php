<?php
class Room extends GameInterface
{
	public $id;
	public $area_id;
	public $name;
	public $description;
	public $north_to;
	public $south_to;
	public $west_to;
	public $east_to;
	public $down_to;
	public $up_to;
	
	public function create()
	{
		if(!empty($this->id))
		{
			$ref_object = new ReflectionClass($this);
			$room = new stdClass();
			
			foreach($ref_object->getProperties() as $property)
			{
				if($property->class == $ref_object->name)
				{
					$room->{$property->name} = $property->getValue($this);
				}
			}
		
			$room_file = fopen("src/db/rooms/".$room->id .".json", "w");
			fwrite($room_file, json_encode($room, JSON_PRETTY_PRINT));
			fclose($room_file);
		}
	}
}