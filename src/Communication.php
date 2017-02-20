<?php

class Communication extends GameInterface
{
	function doSay($args)
	{
		global $clients;
		
		$this->toChar($this->ch, "You say '`k" . $args . "``'");
		
		foreach($clients as $client)
		{
			if($client != $this->ch)
			{
				$this->toChar($client, $this->ch->pData->name . " says '`k" . $args . "``'");
			}
		}
	}
}