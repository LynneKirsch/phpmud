<?php
class Action extends GameInterface
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
			global $clients; 

			$room = new Room($this->ch);
			$room->load();

			foreach($clients as $client)
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
		global $clients;
		
		$room = new Room($this->ch);
		$room->load();
		
		foreach($clients as $client)
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
		global $clients;
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
		
		
		if(!empty($room->mobiles))
		{
			foreach($room->mobiles as $mobile)
			{
				$this->toChar($this->ch, "`f".$mobile->long."``");
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
		
		foreach($clients as $client)
		{
			if($client != $this->ch && $client->pData->in_room == $this->ch->pData->in_room)
			{
				$this->toChar($this->ch, $client->pData->name." is here.");
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
		$equipment = $this->ch->pData->equipment;
		foreach($equipment as $slot => $item)
		{
			$slot_val = is_null($item) ? 'nothing' : $item->short;
			$slot_name = $equipment->getDisplayName($slot);
			
			if($slot_name)
			{
				$this->toChar($this->ch, "[$slot_name] $slot_val");
			}
		}
		
		$this->doInventory();
	}
	
	function savePlayer()
	{
		$this->ch->pData->save();
		$this->ch->send("Saved.\n");
	}
	
	function doWear($args)
	{
		if($args == "")
		{
			$this->ch->send("Wear what?\n");
			return;
		}
		
		foreach($this->player->inventory as $item)
		{
			if($args == "all")
			{

			}
			else
			{
				if(in_array($args, explode(' ', $item->keywords)))
				{
					foreach(explode(' ', $item->wear_flags) as $wear_loc)
					{
						if($wear_loc != "" && $wear_loc != null)
						{
							$eq = new Equipment();
							
							if($eq->getDisplayName($wear_loc))
							{
								$this->player->inventory->{$wear_loc} = $item;
							}
							else
							{
								$this->toChar($this->ch, "You can't wear that.");
							}
						}
						else
						{
							$this->toChar($this->ch, "You can't wear that.");
						}
					}
					
					$this->objFromChar($item, $this->ch);
					break;
				}
				else
				{
					$this->toChar($this->ch, "You're not carrying that.");
				}
			}
		}
	}
}
