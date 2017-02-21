<?php
class Mobile extends GameInterface
{
	public $id;
	public $short;
	public $long;
	public $keywords;
	public $max_hit;
	public $cur_hit;
	
	function load($id)
	{
		$mob = json_decode(file_get_contents(MOB_DIR.$id.'.json'));
		
		foreach($mob as $property=>$value)
		{
			$this->{$property} = $value;
		}
	}
	
	function get()
	{
		return $this;
	}
}

