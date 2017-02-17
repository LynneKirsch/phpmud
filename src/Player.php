<?php
class Player extends GameInterface
{
	public $name;
	public $level;
	public $race;
	public $class;
	public $equipment;
	public $inventory = array();
	public $attributes = array(
		'strength' => 8,
		'dexterity' => 8,
		'constitution' => 8,
		'wisdom' => 8,
		'charisma' => 8,
		'intelligence' => 8,
	);
	public $attribute_points = 70;
	
	function __construct($player_obj = null)
	{
		if(!is_null($player_obj))
		{
			foreach($player_obj as $key=>$val)
			{
				if(isset($this->{$key}))
				{
					$this->{$key} = $val;
				}
			}
		}
		else
		{
			$this->equipment = new stdClass();
		}
	}
}
