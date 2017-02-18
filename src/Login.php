<?php
class Login extends GameInterface
{
	function __construct($ch, $args)
	{
		$this->ch = $ch;
		$this->args = $args;
		
	}
	function start()
	{
		$this->{$this->ch->CONN_STATE}($this->args);
	}
	
	function GET_NAME($name)
	{
		$arg_array = explode(' ', $name);
		
		if(count($arg_array) > 1)
		{
			$this->ch->send("\nNo spaces. Tsk tsk.");
			$this->ch->send("\nWho dares storm our wayward path? ");
		}
		else
		{
			if(file_exists('src/db/player/' . $name . '.json'))
			{
				$this->ch->TMP_NAME = $name;
				$this->GET_EXISTING_PLAYER_PASS();
			}
			else
			{
				$this->ch->pData = new Player();
				$this->ch->pData->name = ucfirst($name);
				$this->INITIALIZE_CREATION();
			}
		}
	}
	
	function GET_EXISTING_PLAYER_PASS()
	{
		$this->ch->CONN_STATE = "VALIDATE_PLAYER_LOGIN";
		$this->ch->send("\nPassword: ");
	}
	
	function VALIDATE_PLAYER_LOGIN($password)
	{
		$player_obj = json_decode(file_get_contents('src/db/player/'.strtolower($this->ch->TMP_NAME).'.json'));
		$existing_pass = $player_obj->password;
		
		if(password_verify($password, $existing_pass))
		{
			$this->ch->CONN_STATE = "CONNECTED";
			$player = new Player();
			$player->load($player_obj);
			$this->ch->pData = $player;
			$this->ch->send("\nConnected.\n");
		}
		else
		{
			$this->ch->CONN_STATE = "GET_NAME";
			$this->ch->send("\nWrong password. ");
			$this->ch->send("\nWho dares storm our wayward path? ");
		}
	}
	
	function INITIALIZE_CREATION()
	{
		$this->ch->send("\nWelcome to Exodus, " . $this->ch->pData->name . ". This is player creation.");
		$this->ch->send("\nView a summary of your character by typing 'summary'. ");
		$this->ch->send("\nSet your character attributes by typing [name] [option].");
		$this->ch->send("\nExample: race human.");
		$this->ch->send("\nGet more help by typing 'help'.");
		
		$this->ch->CONN_STATE = "CHARACTER_CREATION";
	}
	
	function CHARACTER_CREATION($args)
	{
		$arg_array = explode(' ', $args);
		
		if($arg_array[0] == 'summary')
		{
			$this->GET_CREATION_SUMMARY();
		}
		elseif($arg_array[0] == 'password')
		{
			$this->ch->pData->password = password_hash($arg_array[1], PASSWORD_BCRYPT);
			$this->ch->send("\nPassword set.");
			$this->GET_CREATION_SUMMARY();
		}
		elseif($arg_array[0] == 'race')
		{
			$this->ch->pData->race = $arg_array[1];
			$this->ch->send("\nRace set.");
			$this->GET_CREATION_SUMMARY();
		}
		elseif($arg_array[0] == 'class')
		{
			$this->ch->pData->class = $arg_array[1];
			$this->ch->send("\nClass set.");
			$this->GET_CREATION_SUMMARY();
		}
		elseif($arg_array[0] == 'title')
		{
			$this->ch->pData->title = $arg_array[1];
			$this->ch->send("\Title set.");
			$this->GET_CREATION_SUMMARY();
		}
		elseif($arg_array[0] == 'attribute')
		{
			if($arg_array[1] == 'add')
			{
				$this->ch->pData->attributes[$arg_array[2]] = $this->ch->pData->attributes[$arg_array[2]] + $arg_array[3];
				$this->ch->pData->attribute_points = $this->ch->pData->attribute_points - $arg_array[3];
			}
			elseif($arg_array[1] == 'subtract')
			{
				$this->ch->pData->attributes[$arg_array[2]] = $this->ch->pData->attributes[$arg_array[2]] - $arg_array[3];
				$this->ch->pData->attribute_points = $this->ch->pData->attribute_points + $arg_array[3];
			}
			else
			{
				
			}
		}
		elseif($arg_array[0] == 'done')
		{
			$this->VALIDATE_CREATION();
		}
		else
		{
			$this->ch->send("\n I don't know what that means.");
		}
	}
	
	function GET_ATTRIBUTES()
	{
		foreach($this->ch->pData->attributes as $attribute=>$value)
		{
			$this->ch->send("\n$attribute: $value");
		}
	}
	
	function GET_CREATION_SUMMARY()
	{
		$this->ch->send("\n+=============================================+");
		$this->ch->send("\n BASICS FOR " . $this->ch->pData->name);                                      
		$this->ch->send("\n+---------------------------------------------+");
		$this->ch->send("\n Race: " . (empty($this->ch->pData->race) ? "[not set]" : $this->ch->pData->race)); 
		$this->ch->send("\n Class: " . (empty($this->ch->pData->class) ? "[not set]" : $this->ch->pData->class)); 
		$this->ch->send("\n Password: " . (empty($this->ch->pData->password) ? "[not set]" : $this->ch->pData->password));
		$this->ch->send("\n Title: " . (empty($this->ch->pData->title) ? "[not set]" : $this->ch->pData->title));
		$this->ch->send("\n+---------------------------------------------+\n");
		$this->ch->send("\nATTRIBUTES\n");
		$this->GET_ATTRIBUTES();
		$this->ch->send("\n+---------------------------------------------+\n");
		$this->ch->send("\nAvailable points: " . $this->ch->pData->attribute_points);
		$this->ch->send("\nattribute add [stat] [points] or attribute subtract [stat] [points]");
		$this->ch->send("\n+---------------------------------------------+\n");
	}
	
	function VALIDATE_CREATION()
	{
		$player_file = fopen('src/db/player/'.strtolower($this->ch->pData->name) . '.json', 'w');
		fwrite($player_file, json_encode($this->ch->pData, JSON_PRETTY_PRINT));
		fclose($player_file);
		$this->ch->CONN_STATE = "CONNECTED";
		$this->ch->send("\nYou're is a done.");
	}
}