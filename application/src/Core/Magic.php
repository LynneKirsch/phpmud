<?php
class Magic extends PlayerInterface
{
	function doCast($args)
	{
		if($args == "")
		{
			$this->toChar($this->ch, "Cast what, bitch?");
			return;
		}
		
		$p_class = $this->player->class;
		$class = $this->getClass($p_class);

		if(in_array($args, $class->spells))
		{
			$spell = $this->getSpell($args);
			$c_class = $spell->spell_class;
			$class = new $c_class($this->ch);
			$class->{$spell->spell_action}();
		}
		else
		{
			$this->toChar($this->ch, "You don't know that spell.");
		}
	}
	
	function getClass($class_param)
	{
		$class = json_decode(file_get_contents('src/db/classes/'.$class_param.'.json'));
		return $class;
	}
	
	function getSpell($spell_param)
	{
		$spell = json_decode(file_get_contents('src/db/spells/'.$spell_param.'.json'));
		return $spell;
	}
}