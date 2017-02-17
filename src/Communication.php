<?php

class Communication extends GameInterface
{
	function doSay($args)
	{
		$this->ch->send("\nYou say '" . $args . "'\n");
	}
}