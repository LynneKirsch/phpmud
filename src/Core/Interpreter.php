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
	
	function doPrompt()
	{
		$max_hit = $this->ch->pData->max_hit;
		$cur_hit = $this->ch->pData->cur_hit;
		$max_ma = $this->ch->pData->max_ma;
		$cur_ma = $this->ch->pData->cur_ma;
		$max_mv = $this->ch->pData->max_mv;
		$cur_mv = $this->ch->pData->cur_mv;
		
		$room = new Room($this->ch);
		$room->load();
		
		$this->ch->send("\n\r".$this->colorize("[Health: `i$cur_hit``/`b$max_hit`` | Mana: `n$cur_ma``/`g$max_ma`` | Move: `j$cur_mv``/`c$max_mv`` ] - $room->name")." \n\r");
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