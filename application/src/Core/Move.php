<?php
class Move extends PlayerInterface
{
	function doNorth()
	{
		$this->doMoveDir('north');
	}
	function doSouth()
	{
		$this->doMoveDir('south');
	}
	function doEast()
	{
		$this->doMoveDir('east');
	}
	function doWest()
	{
		$this->doMoveDir('west');
	}
	function doUp()
	{
		$this->doMoveDir('up');
	}
	function doDown()
	{
		$this->doMoveDir('down');
	}
	
	function doMoveDir($dir)
	{
		$room = new Room($this->ch);
		$room->load();
		
		if(is_null($room->exits->{$dir}->to_room))
		{
			$this->toChar($this->ch, "Alas, you cannot go that way.");
		}
		elseif($room->exits->{$dir}->is_door && $room->exits->{$dir}->cur_closed)
		{
			$this->toChar($this->ch, "The " . $room->exits->{$dir}->door_name . " is closed.");
		}
		else
		{
			$this->charToRoom($this->ch, $room->exits->{$dir}->to_room);
		}
	}
}
