<?php


class Object extends PlayerInterface
{
	public $id;
	public $short;
	public $long;
	public $keywords;
	public $worn;
	public $take;
	
	public function save()
	{
		if(!empty($this->id))
		{
			$object_file = fopen(OBJ_DIR.$this->id.".json", "w");
			fwrite($object_file, json_encode(clone($this), JSON_PRETTY_PRINT));
			fclose($object_file);
		}
	}
	
	public function load($id)
	{
		$object = json_decode(file_get_contents(OBJ_DIR.$id.'.json'));
		
		foreach($object as $property => $value)
		{
			$this->{$property} = $value;
		}
	}
}

