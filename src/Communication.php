<?php

class Communication extends GameInterface
{
	function doSay($args)
	{
		global $clients;
		
		$this->ch->send("\nYou say '" . $args . "'\n");
		
		foreach($clients as $client)
		{
			if($client != $this->ch)
			{
				$client->send($this->ch->name . "says '" . $args . "'\n");
			}
		}
	}
}