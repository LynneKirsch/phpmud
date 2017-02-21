<?php

class Interpreter extends GameInterface
{
	function interpret($args)
	{
		global $DB_OBJ;
		
		$input_string = $args;
		$arg_array = explode(' ', $args);
		$arg = array_shift($arg_array);
		

		if($arg == "")
		{
			parent::$ch->send("");
		}
		else
		{
			$command_string = implode(' ', $arg_array);
			$command = $DB_OBJ->getCommand($arg);

			if($command && $command->level <= parent::$ch->pData->level)
			{
				$c_class = $command->class;
				$class = new $c_class(parent::$ch);
				$class->{$command->action}($command_string);
			}
			else
			{
				parent::$ch->send("Huh?\n");
			}
		}

		$this->doPrompt();
		
	}
	
	function doPrompt()
	{
		$max_hit = parent::$ch->pData->max_hit;
		$cur_hit = parent::$ch->pData->cur_hit;
		$max_ma = parent::$ch->pData->max_ma;
		$cur_ma = parent::$ch->pData->cur_ma;
		$max_mv = parent::$ch->pData->max_mv;
		$cur_mv = parent::$ch->pData->cur_mv;
		
		$room = new Room(parent::$ch);
		$room->load();
		
		parent::$ch->send("\n\r".$this->colorize("[Health: `i$cur_hit``/`b$max_hit`` | Mana: `n$cur_ma``/`g$max_ma`` | Move: `j$cur_mv``/`c$max_mv`` ] - $room->name")." \n\r");
	}
}