<?php

class GameInterface 
{
	public $ch;
	
	function __construct($ch = null)
	{
		$this->ch = $ch;
	}
	
	function objFromChar($obj)
	{
		
	}
	
	function objToChar($obj)
	{
		if(in_array($obj->id, array_keys((array)$this->ch->pData->inventory)))
		{
			if(isset($this->ch->pData->inventory->{$obj->id}->quantity))
			{
				$this->ch->pData->inventory->{$obj->id}->quantity++;
			}
			else
			{
				$this->ch->pData->inventory->{$obj->id}->quantity = 2;
			}
		}
		else
		{
			$this->ch->pData->inventory->{$obj->id} = $obj;
		}
	}
	
	function objToRoom($obj, $room)
	{
		if(in_array($obj->id, array_keys((array)$room->objects)))
		{
			if(isset($room->objects->{$obj->id}->quantity))
			{
				$room->objects->{$obj->id}->quantity++;
			}
			else
			{
				$room->objects->{$obj->id}->quantity = 2;
			}
		}
		else
		{
			$room->objects->{$obj->id} = $obj;
		}
		
		$room->save();
	}
}
