<?php
class Info extends GameInterface
{
	function doWho()
	{
		global $clients;
		
		foreach($clients as $client)
		{
			$this->ch->send($client->pData->name."\n");
		}
	}
}
