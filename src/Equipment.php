<?php
class Equipment extends GameInterface
{
	public $light = null;
	public $head = null;
	public $neck1 = null;
	public $neck2 = null;
	public $torso = null;
	public $chest = null;
	public $body = null;
	public $legs = null;
	public $arms = null;
	public $feet = null;
	public $waist = null;
	public $wrist_left = null;
	public $wrist_right = null;
	public $weapon_left = null;
	public $weapon_right = null;
	
<<<<<<< HEAD
	
	function __construct($ch = null)
	{
		if(!is_null($ch))
		{
			foreach($this->getFields() as $field)
			{
				if(isset($ch->equipment->{$field}))
				{
					$this->{$field} = parent::$ch->equipment->{$field};
				}
				else
				{
					$this->{$field} = null;
				}
			}
		}
	}
	
=======
>>>>>>> parent of 2a1ea82... wip
	function getDisplayName($slot)
	{
		$display_names = array(
			"light"=>"Used as Light",
			"head"=>"Worn on Head",
			"neck1"=>"Worn Around Neck",
			"neck2"=>"Worn Around Neck",
			"torso"=>"Worn On Torso",
			"chest"=>"Worn On Chest",
			"body"=>"Worn About Body",
			"legs"=>"Worn On Legs",
			"arms"=>"Worn On Arms",
			"feet"=>"Worn On Feet",
			"wrist_left"=>"Worn Around Wrist",
			"wrist_right"=>"Worn Around Wrist",
			"weapon_left"=>"Wielded Left",
			"weapon_right"=>"Wielded Right",
			"waist"=>"Worn Around Waist",
		);
		
		if(in_array($slot, array_keys($display_names)))
		{
			return $display_names[$slot];
		}
		else
		{
			return false;
		}
		
	}
	
	public function getFields()
    {   
        $parent = parent::getParentFields();
        $child = array_keys(get_class_vars($this));
        return array_diff($child, $parent);
    }
			
}