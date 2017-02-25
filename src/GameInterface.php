<?php

class GameInterface 
{
	public $ch;
	
	function __construct($ch = null)
	{
		$this->ch = $ch;
		
		if(!is_null($ch))
		{
			if(isset($this->ch->pData))
			{
				$this->player = $this->ch->pData;
			}
		}
	}
	
	// Game interface stuff that doesn't need to be duplicated
	// on every instance of the game objects gets unset here 
	// whenever an extending object is cloned.
	function __clone()
	{
		unset($this->ch);
		unset($this->player);
	}
	
	function damageToChar($ch, $dmg)
	{
		$hp = $ch->pData->cur_hit;
		$new_hp = $hp - $dmg;

		if($new_hp < 0)
		{
			$this->toChar($ch, "`bAlas, you have died!!``");
			// to do: other death shit
		}
		else
		{
			$ch->pData->cur_hit = $new_hp;
		}
		
		$ch->pData->save();
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
			$ch->pData->inventory->{$clone_obj->id} = clone $clone_obj;
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
	
	function charToRoom($ch, $room_id)
	{
		$ch->pData->in_room = $room_id;
		$action = new Action($ch);
		$action->doLook();
	}
	
	function toChar($ch, $msg)
	{
		if($ch->CONN_STATE == "CONNECTED")
		{
			$ch->send($this->colorize(htmlspecialchars($msg))."\n");
		}
	}
	
	function colorize($msg)
    {
		$msg = $msg;

		$colors = array(
			'`6' => '#000000',
			'`a' => '#808080',
			'`b' => '#800000',
			'`c' => '#008000',
			'`d' => '#808000',
			'`e' => '#000080',
			'`f' => '#800080',
			'`g' => '#008080',
			'`h' => '#c0c0c0',
			'`i' => '#ff0000',
			'`j' => '#00ff00',
			'`k' => '#ffff00',
			'`l' => '#0000ff',
			'`m' => '#ff00ff',
			'`n' => '#00ffff',
			'`o' => '#ffffff',
		);

		foreach($colors as $key => $color)
		{
			$msg = str_replace($key, '<span style="color: ' . $color . '">', $msg);
		}

		$msg = str_replace('``', '<span style="color: #eeeeee;">', $msg);
		return $msg;
    }
}
