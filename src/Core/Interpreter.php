<?php

class Interpreter extends PlayerInterface
{
	function interpret($args)
	{
		global $DB_OBJ;
		
		$input_string = $args;
		$arg_array = explode(' ', $args);
		$arg = array_shift($arg_array);
		

		if($arg == "")
		{
			$this->ch->send("");
		}
		elseif($arg[0] === "/")
		{
			$communication = new Communication($this->ch);
			$communication->doSocial(substr($arg, 1));
		}
		else
		{
			$command_string = implode(' ', $arg_array);
			$command = $this->getCommand($arg);

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

		$this->doPrompt();
		
	}
	
	function getCommands()
	{
		return json_decode(file_get_contents('src/db/commands.json'));
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