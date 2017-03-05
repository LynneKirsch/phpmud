<?php

class Interpreter extends PlayerInterface
{
	function interpret($args)
	{
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
			$command = $this->db->getCommand($arg);

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
}