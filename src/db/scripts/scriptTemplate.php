<?php
class MobileScript_MOBID extends PlayerInterface
{
	public $mobile; //the target mobile
	
	function __construct($ch, $mobile)
	{
		parent::__construct($ch);
		$this->mobile = $mobile;
	}
	
	function onSay($string)
	{
		global $world;
		
		$trigger_words = array(
			'hi',
			'hello',
			'greetings'
		);
		
		$triggered = false;
		
		foreach($trigger_words as $trigger_word)
		{
			if(stristr($string, $trigger_word))
			{
				$triggered = true;
			}
		}
		
		if($triggered)
		{
			foreach($world->players as $player)
			{
				if($player->pData->in_room === $this->mobile->in_room)
				{
					$this->toChar($player, $this->mobile->short . " says '`kOh, hello!``'");
				}
			}
		}
	}
}