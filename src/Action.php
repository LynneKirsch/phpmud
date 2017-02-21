<?php
class Action extends GameInterface
{
	function doInventory()
	{
		$inventory = parent::$ch->pData->inventory;
		$this->toChar(parent::$ch, "You are carrying:");
		
		if(count($inventory)>0)
		{
			foreach($inventory as $item)
			{
				if(isset($item->quantity) && $item->quantity > 1)
				{
					parent::$ch->send("($item->quantity) ");
				}

				$this->toChar(parent::$ch, $item->short);
			}
		}
		else
		{
			$this->toChar(parent::$ch, "Nothing.");
		}
		
	}
	
	function doGet($args)
	{
		$room = new Room(parent::$ch);
		$room->load();
		
		if($args == "")
		{
			$this->toChar(parent::$ch, "Get what?");
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
					$this->toChar(parent::$ch, "You don't see that here.");
				}
			}
		}	
	}
	
	function getAction($obj)
	{
		if(in_array('take', explode(' ', $obj->wear_flags)))
		{
			global $clients; 

			$room = new Room(parent::$ch);
			$room->load();

			foreach($clients as $client)
			{
				if($client != parent::$ch && $client->pData->in_room == parent::$ch->pData->in_room)
				{
					$this->toChar($client, parent::$ch->pData->name . " gets " . $obj->short);
				}
			}

			$this->objFromRoom($obj, $room);
			$this->objToChar($obj, parent::$ch);
			$this->toChar(parent::$ch, "You get " . $obj->short);
		}
		else
		{
			$this->toChar(parent::$ch, "You can't take that.");
		}
	}
	
	function doDrop($args)
	{
		if($args == "")
		{
			$this->toChar(parent::$ch, "Drop what?");
			return;
		}
		
		foreach(parent::$ch->pData->inventory as $item)
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
		
		$room = new Room(parent::$ch);
		$room->load();
		
		foreach($clients as $client)
		{
			if($client != parent::$ch && $client->pData->in_room == parent::$ch->pData->in_room)
			{
				$this->toChar($client, parent::$ch->pData->name . " drops " . $item->short);
			}
		}

		$this->objFromChar($item, parent::$ch);
		$this->objToRoom($item, $room);
		$this->toChar(parent::$ch, "You drop " . $item->short);
	}
	
	function doLook()
	{
		global $clients;
		$room = new Room(parent::$ch);
		$room->load(parent::$ch->pData->in_room);
		parent::$ch->send($room->name."\n");
		parent::$ch->send($room->description."\n");
		
		
		parent::$ch->send("[Exits: ");
		
		$exits = 0;
		foreach($room->exits as $exit_name=>$exit)
		{
			if(!empty($exit->to_room))
			{
				if($exit->is_door && $exit->cur_closed)
				{
					parent::$ch->send("{");
				}
				
				parent::$ch->send("$exit_name");
				
				if($exit->is_door && $exit->cur_closed)
				{
					parent::$ch->send("}");
				}
				
				parent::$ch->send(" ");
				$exits++;
			}
		}
		
		if($exits == 0)
		{
			parent::$ch->send("none");
		}
		
		parent::$ch->send("]\n");
		
		
		if(!empty($room->mobiles))
		{
			foreach($room->mobiles as $mobile)
			{
				$this->toChar(parent::$ch, "`f".$mobile->long."``");
			}
		}
		
		if(!empty($room->objects))
		{
			foreach($room->objects as $object)
			{
				if(isset($object->quantity) && $object->quantity > 1)
				{
					parent::$ch->send("($object->quantity) ");
				}
				
				$this->toChar(parent::$ch, $object->long);
			}
		}
		
		foreach($clients as $client)
		{
			if($client != parent::$ch && $client->pData->in_room == parent::$ch->pData->in_room)
			{
				$this->toChar(parent::$ch, $client->pData->name." is here.");
			}
		}
	}
	
	function doOpen($args)
	{
		$room = new Room(parent::$ch);
		$room->load(parent::$ch->pData->in_room);
		
		if(count(explode(' ', $args))>1)
		{
			parent::$ch->send("Syntax: open [item/direction]");
			return;
		}
	}
	
	function doEquipment()
	{
		$equipment = parent::$ch->pData->equipment;
		foreach($equipment as $slot => $item)
		{
			$slot_val = is_null($item) ? 'nothing' : $item->short;
			$slot_name = $equipment->getDisplayName($slot);
			
			if($slot_name)
			{
				$this->toChar(parent::$ch, "[$slot_name] $slot_val");
			}
		}
		
		$this->doInventory();
	}
	
	function savePlayer()
	{
		parent::$ch->pData->save();
		parent::$ch->send("Saved.\n");
	}
	
	function doWear($args)
	{
		if($args == "")
		{
			parent::$ch->send("Wear what?\n");
			return;
		}
		
		foreach(parent::$ch->pData->inventory as $item)
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
								parent::$ch->pData->inventory->{$wear_loc} = $item;
							}
							else
							{
								$this->toChar(parent::$ch, "You can't wear that.");
							}
						}
						else
						{
							$this->toChar(parent::$ch, "You can't wear that.");
						}
					}
					
					$this->objFromChar($item, parent::$ch);
					break;
				}
				else
				{
					$this->toChar(parent::$ch, "You're not carrying that.");
				}
			}
		}
	}
}
