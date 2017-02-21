<?php
class Login extends GameInterface
{
	function __construct($ch, $args)
	{
		parent::__construct($ch);
		
		$this->args = $args;
	}
	
	function start()
	{
		$this->{parent::$ch->CONN_STATE}($this->args);
	}
	
	function GET_NAME($name)
	{
		$arg_array = explode(' ', $name);
		
		if(count($arg_array) > 1)
		{
			parent::$ch->send("\nNo spaces. Tsk tsk.");
			parent::$ch->send("\nWho dares storm our wayward path? ");
		}
		else
		{
			if(file_exists('src/db/player/' . $name . '.json'))
			{
				parent::$ch->TMP_NAME = $name;
				$this->GET_EXISTING_PLAYER_PASS();
			}
			else
			{
				parent::$ch->pData = new Player();
				parent::$ch->pData->name = ucfirst($name);
				parent::$ch->pData->level = 1;
				$this->INITIALIZE_CREATION();
			}
		}
	}
	
	function GET_EXISTING_PLAYER_PASS()
	{
		parent::$ch->CONN_STATE = "VALIDATE_PLAYER_LOGIN";
		parent::$ch->send("\nPassword: ");
	}
	
	function VALIDATE_PLAYER_LOGIN($password)
	{
		$player_obj = json_decode(file_get_contents('src/db/player/'.strtolower(parent::$ch->TMP_NAME).'.json'));
		$existing_pass = $player_obj->password;
		
		if(password_verify($password, $existing_pass))
		{
			parent::$ch->CONN_STATE = "CONNECTED";
			$player = new Player();
			$player->load($player_obj);
			parent::$ch->pData = $player;
			parent::$ch->send("\nConnected.\n");
		}
		else
		{
			parent::$ch->CONN_STATE = "GET_NAME";
			parent::$ch->send("\nWrong password. ");
			parent::$ch->send("\nWho dares storm our wayward path? ");
		}
	}
	
	function INITIALIZE_CREATION()
	{
		parent::$ch->send("\nWelcome to Exodus, " . parent::$ch->pData->name . ". This is player creation.");
		parent::$ch->send("\nView a summary of your character by typing 'summary'. ");
		parent::$ch->send("\nSet your character attributes by typing [name] [option].");
		parent::$ch->send("\nExample: race human.");
		parent::$ch->send("\nGet more help by typing 'help'.");
		
		parent::$ch->CONN_STATE = "CHARACTER_CREATION";
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
			parent::$ch->pData->password = password_hash($arg_array[1], PASSWORD_BCRYPT);
			parent::$ch->send("\nPassword set.");
			$this->GET_CREATION_SUMMARY();
		}
		elseif($arg_array[0] == 'race')
		{
			parent::$ch->pData->race = $arg_array[1];
			parent::$ch->send("\nRace set.");
			$this->GET_CREATION_SUMMARY();
		}
		elseif($arg_array[0] == 'class')
		{
			parent::$ch->pData->class = $arg_array[1];
			parent::$ch->send("\nClass set.");
			$this->GET_CREATION_SUMMARY();
		}
		elseif($arg_array[0] == 'title')
		{
			parent::$ch->pData->title = $arg_array[1];
			parent::$ch->send("\Title set.");
			$this->GET_CREATION_SUMMARY();
		}
		elseif($arg_array[0] == 'attribute')
		{
			if($arg_array[1] == 'add')
			{
				parent::$ch->pData->attributes[$arg_array[2]] = parent::$ch->pData->attributes[$arg_array[2]] + $arg_array[3];
				parent::$ch->pData->attribute_points = parent::$ch->pData->attribute_points - $arg_array[3];
			}
			elseif($arg_array[1] == 'subtract')
			{
				parent::$ch->pData->attributes[$arg_array[2]] = parent::$ch->pData->attributes[$arg_array[2]] - $arg_array[3];
				parent::$ch->pData->attribute_points = parent::$ch->pData->attribute_points + $arg_array[3];
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
			parent::$ch->send("\n I don't know what that means.");
		}
	}
	
	function GET_ATTRIBUTES()
	{
		foreach(parent::$ch->pData->attributes as $attribute=>$value)
		{
			parent::$ch->send("\n$attribute: $value");
		}
	}
	
	function GET_CREATION_SUMMARY()
	{
		parent::$ch->send("\n+=============================================+");
		parent::$ch->send("\n BASICS FOR " . parent::$ch->pData->name);                                      
		parent::$ch->send("\n+---------------------------------------------+");
		parent::$ch->send("\n Race: " . (empty(parent::$ch->pData->race) ? "[not set]" : parent::$ch->pData->race)); 
		parent::$ch->send("\n Class: " . (empty(parent::$ch->pData->class) ? "[not set]" : parent::$ch->pData->class)); 
		parent::$ch->send("\n Password: " . (empty(parent::$ch->pData->password) ? "[not set]" : parent::$ch->pData->password));
		parent::$ch->send("\n Title: " . (empty(parent::$ch->pData->title) ? "[not set]" : parent::$ch->pData->title));
		parent::$ch->send("\n+---------------------------------------------+\n");
		parent::$ch->send("\nATTRIBUTES\n");
		$this->GET_ATTRIBUTES();
		parent::$ch->send("\n+---------------------------------------------+\n");
		parent::$ch->send("\nAvailable points: " . parent::$ch->pData->attribute_points);
		parent::$ch->send("\nattribute add [stat] [points] or attribute subtract [stat] [points]");
		parent::$ch->send("\n+---------------------------------------------+\n");
	}
	
	function VALIDATE_CREATION()
	{
		$player_file = fopen('src/db/player/'.strtolower(parent::$ch->pData->name) . '.json', 'w');
		fwrite($player_file, json_encode(parent::$ch->pData, JSON_PRETTY_PRINT));
		fclose($player_file);
		parent::$ch->CONN_STATE = "CONNECTED";
		parent::$ch->send("\nYou're is a done.");
	}
}