<?php
class Database extends GameInterface
{
	public $commands;
	
	function __construct()
	{
		$this->commands =  json_decode(file_get_contents('src/db/commands.json'));
	}
	
	function getCommands()
	{
		return $this->commands;
	}
	
	function getCommand($arg)
	{
		$commands = (array)$this->commands; 
		if(in_array($arg, array_keys($commands)))
		{
			return $commands[$arg];
		}
		else
		{
			return false;
		}
		
	}
}
