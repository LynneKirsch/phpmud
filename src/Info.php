<?php
class Info extends GameInterface
{
	function doWho()
    {	
		global $clients; 
		
		$buf = "";

		$buf .= "`a:`b----------------------------------------------------------------------------`a:`` <br>";
		$buf .= "`d[`h\`d][`h/`d][`h\`d][`h/`d][`h\`d][`h/`d][`h\`d][`h/`d][`h\`d]`o Players in this Realm  `d[`h\`d][`h/`d][`h\`d][`h/`d][`h\`d][`h/`d][`h\`d][`h/`d][`h\`d]`` <br>";
		$buf .= "`a:`b----------------------------------------------------------------------------`a:`` <br>";
		$clients = array_reverse($clients);
		$count = 0;

		foreach($clients as $client)
		{

			$chData = $client->pData;
			
			if($chData && $client->CONN_STATE == "CONNECTED")
			{
				$line_format = "`b(`h%-2s `g%-10s %-15s`b)`` %s%s";

				$race_length = strlen($chData->race);
				$race_pre = str_repeat(" ", (10 - $race_length)/2);
				$race = $race_pre.$chData->race;

				$class_length = strlen($chData->class);
				$class_pre = str_repeat(" ", (10 - $class_length)/2);
				$class = $class_pre.$chData->class;

				$buf .= sprintf($line_format, $chData->level, $race, $class, $chData->name, $chData->title);
				$buf .= "<br>";

				$count++;
			}
		}

		$buf .= "`a:`b----------------------------------------------------------------------------`a:`` <br>";
		$buf .= " Players Found: ".$count." <br>";
		$buf .= "`a:`b----------------------------------------------------------------------------`a:`` <br>";

		$this->toChar($this->ch, $buf);
    }
}
