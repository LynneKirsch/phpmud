<?php
class Player extends GameInterface
{
	public $name;
	public $level;
	public $password;
	public $race;
	public $class;
	public $equipment;
	public $inventory;
	public $in_room = 1;
	public $attributes = array(
		'strength' => 8,
		'dexterity' => 8,
		'constitution' => 8,
		'wisdom' => 8,
		'charisma' => 8,
		'intelligence' => 8,
	);
	public $attribute_points = 70;
	
	function load($player_obj = null)
	{
		if(!is_null($player_obj))
		{
			foreach($player_obj as $key=>$val)
			{
				if(property_exists($this, $key))
				{
					$this->{$key} = $val;
				}
			}
			
			if(empty($this->inventory))
			{
				$this->inventory = new stdClass();
			}
			if(empty($this->equipment))
			{
				$this->equipment = new stdClass();
			}
		}
		else
		{
			$this->equipment = new stdClass();
			$this->inventory = new stdClass();
		}
	}
	
	function save()
	{
		$player_file = fopen("src/db/player/".strtolower($this->name).".json", "w");
		fwrite($player_file, json_encode($this, JSON_PRETTY_PRINT));
		fclose($player_file);
	}
}
