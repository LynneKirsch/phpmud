<?php
class Equipment
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
	
	
	function __construct($ch = null)
	{
		if(!is_null($ch))
		{
			foreach($this as $key=>$val)
			{
				if(isset($ch->equipment->{$key}))
				{
					$this->{$key} = $this->ch->equipment->{$key};
				}
				else
				{
					$this->{$key} = $val;
				}
			}
		}
	}
	
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
			
}