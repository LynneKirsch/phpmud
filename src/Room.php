<?php
class Room extends GameInterface
{
	public $id;
	public $area_id;
	public $name;
	public $description;
	public $north_to;
	public $south_to;
	public $west_to;
	public $east_to;
	public $down_to;
	public $up_to;
	
	public function save()
	{
		$new_room = false;
		
		if(!empty($this->id))
		{
			$ref_room_obj = new ReflectionClass($this);
			$room = new stdClass();
			
			foreach($ref_room_obj->getProperties() as $property)
			{
				if($property->class == $ref_room_obj->name)
				{
					$room->{$property->name} = $property->getValue($this);
				}
			}
			
			if(!file_exists(ROOM_DIR.$this->id.".json"))
			{
				$new_room = true;
			}
			
			$room_file = fopen(ROOM_DIR.$room->id.".json", "w");
			fwrite($room_file, json_encode($room, JSON_PRETTY_PRINT));
			fclose($room_file);
			
			if($new_room)
			{
				$this->ch->editing_room = $this->id;
				$this->ch->send("You are now editing room #".$this->id);
			}
		}
	}
	
	public function load($id)
	{
		$room = json_decode(file_get_contents(ROOM_DIR.$id.'.json'));
		
		foreach($room as $property => $value)
		{
			$this->{$property} = $value;
		}
	}
	
	public function edit($args)
	{
		if(count(explode(' ', $args))>1 || !is_numeric($args))
		{
			$this->ch->send("\nSyntax: edit_room [ID]");
			return;
		}
		
		if(file_exists(ROOM_DIR.$args.'.json'))
		{
			$this->ch->editing_room = $args;
			$this->ch->send("You are now editing room #" . $args);
		}
		else
		{
			$this->ch->send("No such room found.");
		}
	}
	
	public function parseEditCommand($args)
	{
		$this->load($this->ch->editing_room);
		
		$arg_array = explode(' ', $args);
		$command = array_shift($arg_array);
		
		$edit_commands = array(
			'exit',
			'',
			'name',
			'description',
			'north_to',
			'south_to',
			'west_to',
			'east_to',
			'up_to',
			'down_to',
			'area_id'
		);
		
		if(in_array($command, $edit_commands))
		{
			if($command == "exit")
			{
				unset($this->ch->editing_room);
				$this->ch->send("Exiting room edit.");
			}
			elseif($command == "")
			{
				$this->showRoom();
			}
			else
			{
				$this->{$command} = implode(' ', $arg_array);
				$this->save();
				$this->showRoom();
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
			
			$this->ch->send("Or enter to view room.\n");
		}
	}
	
	public function showRoom()
	{
		$ref_room = new ReflectionClass($this);

		foreach($ref_room->getProperties() as $property)
		{
			if($property->class == $ref_room->name)
			{
				$this->ch->send($property->name.": ".$property->getValue($this)."\n");
			}
		}
	}
}