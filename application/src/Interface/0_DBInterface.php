<?php
class DBInterface 
{
	function getPlayer($name)
	{
		return json_decode(file_get_contents(SRC.'db/player/'.strtolower($name).'.json'));
	}
	
	function getCommands()
	{
		return json_decode(file_get_contents(SRC.'db/commands.json'));
	}
	
	function getCommand($arg)
	{
		$commands = (array)$this->getCommands(); 
		$command_found = false;
		
		if(in_array($arg, array_keys($commands)))
		{
			return $commands[$arg];
		}
		else
		{
			foreach($commands as $key=>$command)
			{
				if(strpos($key, $arg)===0)
				{
					$command_found = true;
					return $command;
				}
			}
			
			if(!$command_found)
			{
				return false;
			}
		}
	}
}
