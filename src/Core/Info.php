<?php
class Info extends PlayerInterface
{
	function doWho()
    {	
		echo '<pre>';
		print_r(array_keys($this->players));
		echo '</pre>';
		echo '<pre>';
		print_r(array_keys($this->connecting));
		echo '</pre>';
		$buf = "";

		$buf .= "`a:`b----------------------------------------------------------------------------`a:`` \n";
		$buf .= "`d[`h\`d][`h/`d][`h\`d][`h/`d][`h\`d][`h/`d][`h\`d][`h/`d][`h\`d]`o Players in this Realm  `d[`h\`d][`h/`d][`h\`d][`h/`d][`h\`d][`h/`d][`h\`d][`h/`d][`h\`d]`` \n";
		$buf .= "`a:`b----------------------------------------------------------------------------`a:`` \n";
		$players = array_reverse($this->players);
		$count = 0;

		foreach($players as $client)
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

				$buf .= sprintf($line_format, $chData->level, $race, $class, $chData->name, ", the wanderer");
				$buf .= "\n";

				$count++;
			}
		}

		$buf .= "`a:`b----------------------------------------------------------------------------`a:`` \n";
		$buf .= " Players Found: ".$count." \n";
		$buf .= "`a:`b----------------------------------------------------------------------------`a:`` \n";

		$this->toChar($this->ch, $buf);
    }
}
