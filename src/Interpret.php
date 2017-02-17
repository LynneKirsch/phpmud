<?php

class Interpret extends GameInterface
{
	public $argument;
	public $result;
	public $command;
	public $command_string;
	public $ch;
	
	function __construct($ch, $args)
	{
		global $DB_OBJ;
		
		$this->ch = $ch;
		$this->argument = $args;
		$arg_array = explode(' ', $args);
		$arg = array_shift($arg_array);
		$this->command_string = implode(' ', $arg_array);
		$command = $DB_OBJ->getCommand($arg);
		
		if($command)
		{
			$this->command = $command;
		}
		else
		{
			$this->command = false;
		}
	}
	
	function interpret()
	{
		if(!empty($this->ch->editing_object))
		{
			$object = new Object($this->ch);
			$object->parseEditCommand($this->argument);
		}
		else
		{
			if($this->command)
			{
				$c_class = $this->command->class;
				$class = new $c_class($this->ch);
				$class->{$this->command->action}($this->command_string);
			}
			else
			{
				$this->ch->send("Huh?\n");
			}
		}
		
	}
}