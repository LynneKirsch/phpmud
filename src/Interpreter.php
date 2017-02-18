<?php

class Interpreter extends GameInterface
{
	function interpret($args)
	{
		global $DB_OBJ;
		
		$input_string = $args;
		$arg_array = explode(' ', $args);
		$arg = array_shift($arg_array);
		
		if(!empty($this->ch->editing_object))
		{
			$object = new Object($this->ch);
			$object->parseEditCommand($input_string);
		}
		elseif(!empty($this->ch->editing_room))
		{
			$room = new Room($this->ch);
			$room->parseEditCommand($input_string);
		}
		elseif($arg == "")
		{
			$this->ch->send("");
		}
		else
		{
			$command_string = implode(' ', $arg_array);
			$command = $DB_OBJ->getCommand($arg);

			if($command && $command->level <= $this->ch->pData->level)
			{
				$c_class = $command->class;
				$class = new $c_class($this->ch);
				$class->{$command->action}($command_string);
			}
			else
			{
				$this->ch->send("Huh?\n");
			}
		}
	}
}