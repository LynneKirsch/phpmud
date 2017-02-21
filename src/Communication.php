<?php

class Communication extends GameInterface
{
	function doSay($args)
	{
		global $clients;
		
		$this->toChar(parent::$ch, "You say '`k" . $args . "``'");
		
		foreach($clients as $client)
		{
			if($client != parent::$ch && $client->pData->in_room == parent::$ch->pData->in_room)
			{
				$this->toChar($client, parent::$ch->pData->name . " says '`k" . $args . "``'");
			}
		}
	}
	
	function doOOC($args)
	{
		global $clients;
		
		$this->toChar(parent::$ch, "`l[`fOOC`l] `hYou: ``'`f" . $args . "``'");
		
		foreach($clients as $client)
		{
			if($client != parent::$ch)
			{
				$this->toChar($client, "`l[`fOOC`l] `h".parent::$ch->pData->name . ": ``'`f" . $args . "``'");
			}
		}
	}
}