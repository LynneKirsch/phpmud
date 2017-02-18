<?php
class Update extends GameInterface
{
	function doTick()
	{
		$this->savePlayerCharacters();
	}
	
	function savePlayerCharacters()
	{
		global $clients;
		
		foreach($clients as $client)
		{
			$client->pData->save();
		}
	}
}