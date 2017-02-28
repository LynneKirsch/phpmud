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
			$process_array = array(
				'trigger_beat' => $world->beats + 2,
				'function' => 'toRoom',
				'class' => 'PlayerInterface',
				'params' => array($this->mobile->in_room, $this->mobile->short . " says '`kOh, hello!``'")
			);

			$world->createProcessObject($process_array);
		}
	}
}