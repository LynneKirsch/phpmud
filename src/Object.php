<?php


class Object extends GameInterface
{
	public $id;
	public $short;
	public $long;
	public $keywords;
	
	public function save()
	{
		if(!empty($this->id))
		{
			$ref_object = new ReflectionClass($this);
			$object = new stdClass();
			
			foreach($ref_object->getProperties() as $property)
			{
				if($property->class == $ref_object->name)
				{
					$object->{$property->name} = $property->getValue($this);
				}
			}
			
			$object_file = fopen(OBJ_DIR.$object->id.".json", "w");
			fwrite($object_file, json_encode($object, JSON_PRETTY_PRINT));
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

