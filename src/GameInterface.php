<?php

class GameInterface 
{
	public $ch;
	
	function __construct($ch = null)
	{
		$this->ch = $ch;
	}
	
	function objFromChar($obj, $ch)
	{
		
		if(isset($obj->quantity) && $obj->quantity > 1)
		{
			$ch->pData->inventory->{$obj->id}->quantity = $ch->pData->inventory->{$obj->id}->quantity - 1;
		}
		else
		{
			unset($ch->pData->inventory->{$obj->id});
		}
		
		$ch->pData->save();
		
	}
	
	function objToChar($obj, $ch)
	{
		$clone_obj = new Object();
		$clone_obj->load($obj->id);
		
		if(in_array($clone_obj->id, array_keys((array)$ch->pData->inventory)))
		{
			if(isset($ch->pData->inventory->{$clone_obj->id}->quantity))
			{
				$ch->pData->inventory->{$clone_obj->id}->quantity++;
			}
			else
			{
				$ch->pData->inventory->{$clone_obj->id}->quantity = 2;
			}
		}
		else
		{
			$ch->pData->inventory->{$clone_obj->id} = $clone_obj;
		}
		
		$ch->pData->save();
	}
	
	function objToRoom($obj, $room)
	{
		$clone_obj = new Object();
		$clone_obj->load($obj->id);
		
		if(in_array($clone_obj->id, array_keys((array)$room->objects)))
		{
			if(isset($room->objects->{$clone_obj->id}->quantity))
			{
				$room->objects->{$clone_obj->id}->quantity++;
			}
			else
			{
				$room->objects->{$clone_obj->id}->quantity = 2;
			}
		}
		else
		{
			$room->objects->{$clone_obj->id} = $clone_obj;
		}
		
		$room->save();
	}
	
	function objFromRoom($obj, $room)
	{
		if(in_array($obj->id, array_keys((array)$room->objects)))
		{
			if(isset($room->objects->{$obj->id}->quantity) && $room->objects->{$obj->id}->quantity > 1)
			{
				$room->objects->{$obj->id}->quantity = $room->objects->{$obj->id}->quantity - 1;
			}
			else
			{
				unset($room->objects->{$obj->id});
			}
		}

		$room->save();
	}
}
