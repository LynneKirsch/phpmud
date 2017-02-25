<?php
class CLS_Mage extends GameInterface
{
	function fireball()
	{
		$this->toChar($this->ch, "Your fireball backfires!");
		$this->damageToChar($this->ch, 10);
	}
}