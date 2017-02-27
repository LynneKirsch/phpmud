<?php

class Communication extends PlayerInterface
{
	function doSay($args)
	{
		global $world;
		
		$this->toChar($this->ch, "You say '`k" . $args . "``'");
		
		foreach($world->players as $player)
		{
			if($player != $this->ch && $player->pData->in_room == $this->player->in_room)
			{
				$this->toChar($player, $this->player->name . " says '`k" . $args . "``'");
			}
		}
		
		if(isset($world->mobs_in_rooms[$this->player->in_room]) 
			&& is_array($world->mobs_in_rooms[$this->player->in_room]))
		{
			foreach($world->mobs_in_rooms[$this->player->in_room] as $mob_id => $mobs)
			{
				foreach($mobs as $instance => $mob_id)
				{
					if(isset($world->mobiles[$instance]))
					{
						require_once('src/db/scripts/scriptTemplate.php');
						$script = new MobileScript_MOBID($this->ch, $world->mobiles[$instance]);
						$script->onSay($args);
					}
				}
			}
		}
	}
	
	function doOOC($args)
	{
		global $world;
		
		$this->toChar($this->ch, "`l[`fOOC`l] `hYou: ``'`f" . $args . "``'");
		
		foreach($world->players as $player)
		{
			if($player != $this->ch)
			{
				$this->toChar($player, "`l[`fOOC`l] `h".$this->ch->pData->name . ": ``'`f" . $args . "``'");
			}
		}
	}
	
	function doEmote($args)
	{
		global $world;
		
		foreach($world->players as $player)
		{
			if($player->pData->in_room == $this->player->in_room)
			{
				$this->toChar($player, $this->player->name . " " . $args);
			}
		}
	}
	
	function doSocial($args)
	{
		$socials = (array)json_decode(file_get_contents('src/db/social.json'));
		if(count(explode(' ', $args))>1)
		{
			$this->toChar($this->ch, "Huh?");
		}
		
		if(in_array($args, array_keys($socials)))
		{
			global $world;
			$social = $socials[$args];
			
			$vars = array(
				'%n' => $this->player->name
			);
			
			$to_room = str_replace(array_keys($vars), $vars, $social->to_room);
			
			foreach($world->players as $player)
			{
				if($player != $this->ch)
				{
					$this->toChar($player, $to_room);
				}
				else
				{
					$this->toChar($player, $social->to_char);
				}
			}
		}
	}
}