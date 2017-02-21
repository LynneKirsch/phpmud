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
		if(!is_null($player_obj))
		{
			foreach($player_obj as $key=>$val)
			{
				if($key == "ch")
				{
					unset($player_obj->ch);
				}
				else
				{
					if(property_exists($this, $key))
					{
						$this->{$key} = $val;
					}
				}
			}
		}
		
		if(empty($this->inventory))
		{
			$this->inventory = new stdClass();
		}
<<<<<<< HEAD

		$this->equipment = new Equipment(parent::$ch);
=======
		
		if(empty($this->equipment))
		{
			$this->equipment = new Equipment();
		}
		
>>>>>>> parent of 2a1ea82... wip
	}
	
	function save()
	{
		if(empty($this->name))
		{
			$this->load(parent::$ch->pData);
		}
		
		$player_file = fopen("src/db/player/".strtolower($this->name).".json", "w");
		fwrite($player_file, json_encode($this, JSON_PRETTY_PRINT));
		fclose($player_file);
	}
	
	public function getFields()
    {   
        $parent = parent::getParentFields();
        $child = array_keys(get_class_vars($this));
        return array_diff($child, $parent);
    }
}
