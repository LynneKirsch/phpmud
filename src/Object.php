<?php


class Object extends GameInterface
{
	public $id;
	public $short;
	public $long;
	public $keywords;
	
	public function save()
	{
		$new_object = false;
		
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
			
			if(!file_exists(OBJ_DIR.$this->id.".json"))
			{
				$new_object = true;
			}
			
			$object_file = fopen(OBJ_DIR.$object->id.".json", "w");
			fwrite($object_file, json_encode($object, JSON_PRETTY_PRINT));
			fclose($object_file);
			
			$this->ch->editing_object = $this->id;
			
			if($new_object)
			{
				$this->ch->send("You are now editing object #".$this->id);
			}
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
	
	public function edit($args)
	{
		if(count(explode(' ', $args))>1 || !is_numeric($args))
		{
			$this->ch->send("\nSyntax: edit_object [ID]");
			return;
		}
		
		if(file_exists(OBJ_DIR.$args.'.json'))
		{
			$this->ch->editing_object = $args;
			$this->ch->send("You are now editing object #" . $args);
		}
		else
		{
			$this->ch->send("No such object found.");
		}
	}
	
	public function parseEditCommand($args)
	{
		$this->load($this->ch->editing_object);
		
		$arg_array = explode(' ', $args);
		$command = array_shift($arg_array);
		
		$edit_commands = array(
			'exit',
			'',
			'short',
			'long',
			'keywords'
		);
		
		if(in_array($command, $edit_commands))
		{
			if($command == "exit")
			{
				unset($this->ch->editing_object);
				$this->ch->send("Exiting object edit.");
			}
			elseif($command == "")
			{
				$this->showObject();
			}
			elseif($command == "short"
				|| $command == "long"
				|| $command == "keywords")
			{
				$this->{$command} = implode(' ', $arg_array);
				$this->save();
				$this->showObject();
			}
		}
		else
		{
			$this->ch->send("Invalid edit commands. Command list: \n");
			
			foreach($edit_commands as $edit_command)
			{
				if($edit_command != "")
				{
					$this->ch->send($edit_command . "\n");
				}
			}
			
			$this->ch->send("Or enter to view object.\n");
		}
	}
	
	public function showObject()
	{
		$ref_object = new ReflectionClass($this);

		foreach($ref_object->getProperties() as $property)
		{
			if($property->class == $ref_object->name)
			{
				$this->ch->send($property->name.": ".$property->getValue($this)."\n");
			}
		}
	}
}

