<?php

class Communication extends PlayerInterface
{
	function doSay($args)
	{
		$this->toChar($this->ch, "You say '`k" . $args . "``'");
		
		foreach($this->players as $client)
		{
			if($client != $this->ch && $client->pData->in_room == $this->ch->pData->in_room)
			{
				$this->toChar($client, $this->ch->pData->name . " says '`k" . $args . "``'");
			}
		}
	}
	
	function doOOC($args)
	{
		$this->toChar($this->ch, "`l[`fOOC`l] `hYou: ``'`f" . $args . "``'");
		
		foreach($this->players as $client)
		{
			if($client != $this->ch)
			{
				$this->toChar($client, "`l[`fOOC`l] `h".$this->ch->pData->name . ": ``'`f" . $args . "``'");
			}
		}
	}
}