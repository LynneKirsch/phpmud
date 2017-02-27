<?php
class Action extends PlayerInterface
{
	function doInventory()
	{
		$inventory = $this->ch->pData->inventory;
		$this->toChar($this->ch, "You are carrying:");
		
		if(count($inventory)>0)
		{
			foreach($inventory as $id => $item)
			{
				if(isset($item->quantity) && $item->quantity > 1)
				{
					$this->ch->send("($item->quantity) ");
				}

				$this->toChar($this->ch, $item->short);
			}
		}
		else
		{
			$this->toChar($this->ch, "Nothing.");
		}
		
	}
	
	function doGet($args)
	{
		$room = new Room($this->ch);
		$room->load();
		
		if($args == "")
		{
			$this->ch->send("Get what?\n");
			return;
		}

		foreach($room->objects as $obj)
		{
			if($args == "all")
			{
				if(isset($obj->quantity) && $obj->quantity > 1)
				{
					$quantity = $obj->quantity;
					
					for($i=1;$i<=$quantity;$i++)
					{
						$this->getAction($obj);
					}
				}
				else
				{
					$this->getAction($obj);
				}
			}
			else
			{
				if(in_array($args, explode(' ', $obj->keywords)))
				{
					$this->getAction($obj);
					break;
				}
				else
				{
					$this->toChar($this->ch, "You don't see that here.");
				}
			}
		}	
	}
	
	function getAction($obj)
	{
		if(in_array('take', explode(' ', $obj->wear_flags)))
		{
			$room = new Room($this->ch);
			$room->load();

			foreach($this->players as $client)
			{
				if($client != $this->ch && $client->pData->in_room == $this->ch->pData->in_room)
				{
					$this->toChar($client, $this->ch->pData->name . " gets " . $obj->short);
				}
			}

			$this->objFromRoom($obj, $room);
			$this->objToChar($obj, $this->ch);
			$this->toChar($this->ch, "You get " . $obj->short);
		}
		else
		{
			$this->toChar($this->ch, "You can't take that.");
		}
	}
	
	function doDrop($args)
	{
		if($args == "")
		{
			$this->ch->send("Drop what?\n");
			return;
		}
		
		foreach($this->player->inventory as $item)
		{
			if($args == "all")
			{
				if(isset($item->quantity) && $item->quantity > 1)
				{
					$quantity = $item->quantity;
					
					for($i=1;$i<=$quantity;$i++)
					{
						$this->dropAction($item);
					}
				}
				else
				{
					$this->dropAction($item);
				}
			}
			else
			{
				if(in_array($args, explode(' ', $item->keywords)))
				{
					$this->dropAction($item);
					break;
				}
			}
		}
	}
	
	function dropAction($item)
	{
		$room = new Room($this->ch);
		$room->load();
		
		foreach($this->players as $client)
		{
			if($client != $this->ch && $client->pData->in_room == $this->player->in_room)
			{
				$this->toChar($client, $this->player->name . " drops " . $item->short);
			}
		}

		$this->objFromChar($item, $this->ch);
		$this->objToRoom($item, $room);
		$this->toChar($this->ch, "You drop " . $item->short);
	}
	
	function doLook()
	{
		global $world;
		
		$room = new Room($this->ch);
		$room->load($this->ch->pData->in_room);
		$this->ch->send($room->name."\n");
		$this->ch->send($room->description."\n");
		
		
		$this->ch->send("[Exits: ");
		
		$exits = 0;
		foreach($room->exits as $exit_name=>$exit)
		{
			if(!empty($exit->to_room))
			{
				if($exit->is_door && $exit->cur_closed)
				{
					$this->ch->send("{");
				}
				
				$this->ch->send("$exit_name");
				
				if($exit->is_door && $exit->cur_closed)
				{
					$this->ch->send("}");
				}
				
				$this->ch->send(" ");
				$exits++;
			}
		}
		
		if($exits == 0)
		{
			$this->ch->send("none");
		}
		
		$this->ch->send("]\n");
		
		if(isset($world->mobs_in_rooms[$room->id]) 
			&& is_array($world->mobs_in_rooms[$room->id]))
		{
			foreach($world->mobs_in_rooms[$room->id] as $mob_id => $mobs)
			{
				foreach($mobs as $instance => $mob_id)
				{
					if(isset($world->mobiles[$instance]))
					{
						$this->toChar($this->ch, "`k*`` `f".$world->mobiles[$instance]->long."``");
					}
				}
			}
		}
		
		
		if(!empty($room->objects))
		{
			foreach($room->objects as $object)
			{
				if(isset($object->quantity) && $object->quantity > 1)
				{
					$this->ch->send("($object->quantity) ");
				}
				
				$this->toChar($this->ch, $object->long);
			}
		}
		
		foreach($this->players as $player)
		{
			if($player != $this->ch && $player->pData->in_room === $this->player->in_room)
			{
				$this->toChar($this->ch, $player->pData->name." is here.");
			}
		}
	}
	
	function doOpen($args)
	{
		$room = new Room($this->ch);
		$room->load($this->ch->pData->in_room);
		
		if(count(explode(' ', $args))>1)
		{
			$this->ch->send("Syntax: open [item/direction]");
			return;
		}
	}
	
	function doEquipment()
	{	
		$eq = $this->player->equipment;

		foreach($eq->slots() as $key=>$slot)
		{
			if(is_array($eq->{$key}))
			{
				foreach($eq->{$key} as $item_obj)
				{
					if(!is_null($item_obj))
					{
						$slot_val = $item_obj->short;
					}
					else
					{
						$slot_val = "nothing";
					}
					
					$line_format = "%-25s %s";
					$this->toChar($this->ch, sprintf($line_format, $slot->slot_name, $slot_val));
				}
			}
			else
			{
				if(!is_null($eq->{$key}))
				{
					$slot_val = $eq->{$key}->short;
				}
				else
				{
					$slot_val = "nothing";
				}
				
				$line_format = "%-25s %s";
				$this->toChar($this->ch, sprintf($line_format, $slot->slot_name, $slot_val));
			}
			
		}
		
		$this->ch->send("\n");
		$this->doInventory();
	}
	
	function savePlayer()
	{
		$this->ch->pData->save();
		$this->ch->send("Saved.\n");
	}
	
	
	function doRemove($args)
	{
		$eq = $this->player->equipment;
		
		if($args == "")
		{
			$this->ch->send("Remove what?\n");
			return;
		}
		
		foreach($eq as $slot => $eq_item)
		{
			if(is_array($eq_item))
			{
				
			}
			else
			{
				if(!is_null($eq_item))
				{
					if(in_array($args, explode(' ', $eq_item->keywords)))
					{
						$this->toChar($this->ch, "You remove " . $eq_item->short);
						$this->objToChar($eq_item, $this->ch);
						$eq->{$slot} = null;
					}	
				}
			}
		}
		
		$this->player->save();
	}
	
	function doWear($args)
	{
		$eq = $this->player->equipment;
		
		if($args == "")
		{
			$this->ch->send("Wear what?\n");
			return;
		}
		
		$item_found = false;
		
		foreach($this->player->inventory as $item)
		{
			if($args == "all")
			{

			}
			else
			{
				if(in_array($args, explode(' ', $item->keywords)))
				{
					if($item->worn != null)
					{
						if(is_array($eq->{$item->worn}))
						{
							$found_empty_slot = false;
							
							foreach($eq->{$item->worn} as $key => $eq_item)
							{
								if(is_null($eq_item))
								{
									$eq->{$item->worn}[$key] = $item;
									$found_empty_slot = true;
								}
							}
							
							if(!$found_empty_slot)
							{
								$this->toChar($this->ch, "You remove " . $eq->{$item->worn}[0]->short);
								$this->objToChar($eq->{$item->worn}[0], $this->ch); 
								$eq->{$item->worn}[0] = $item;	
							}
						}
						else
						{
							if(!is_null($eq->{$item->worn}))
							{
								$this->toChar($this->ch, "You remove " . $eq->{$item->worn}->short);
								$this->objToChar($eq->{$item->worn}, $this->ch); 
								$eq->{$item->worn} = $item;					
							}
							else
							{
								$this->player->equipment->{$item->worn} = $item;
							}
						}
						
						$item_found = true;
						$this->objFromChar($item, $this->ch);
						$wear_buf = sprintf($eq->slots()->{$item->worn}->wear_text, $item->short);
						$this->toChar($this->ch, $wear_buf);
					}
					else
					{
						$this->toChar($this->ch, "You can't wear that.");
					}
				}
			}
		}
		
		if(!$item_found)
		{
			$this->toChar($this->ch, "You're not carrying that.");
		}
		
		$this->player->save();
				
	}
}
