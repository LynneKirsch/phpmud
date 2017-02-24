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
	public $title = "";
	public $max_hit = 100;
	public $cur_hit = 100;
	public $max_ma = 100;
	public $cur_ma = 100;
	public $max_mv = 100;
	public $cur_mv = 100;
	
	function load($player_obj = null)
	{
		// load basics
		if(!is_null($player_obj))
		{
			foreach($player_obj as $key=>$val)
			{
				if(property_exists($this, $key))
				{
					if(empty($val))
					{
						$this->{$key} = new stdClass();
					}
					else
					{
						$this->{$key} = $val;
					}
				}
			}
		}
		
		// load eq obj
		$eq = new Equipment();
		$eq->load($this->equipment);	
		$this->equipment = clone($eq);
		
		$this->save();
	}
	
	function getEq()
	{	
		
	}
	
	function save()
	{
		$player_file = fopen("src/db/player/".strtolower($this->name).".json", "w");
		fwrite($player_file, json_encode(clone($this), JSON_PRETTY_PRINT));
		fclose($player_file);
	}
}
