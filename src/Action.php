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
		global $clients; 
		
		if($args == "")
		{
			$this->ch->send("Get what?\n");
			return;
		}
		
		$room = new Room($this->ch);
		$room->load();
		
		foreach($room->objects as $obj)
		{
			if($args == "all")
			{
				if(isset($obj->quantity) && $obj->quantity > 1)
				{
					$quantity = $obj->quantity;
					
					for($i=1;$i<=$quantity;$i++)
					{
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
				}
				else
				{
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
			}
			else
			{
				if(in_array($args, explode(' ', $obj->keywords)))
				{
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
					break;
				}
				else
				{
					$this->toChar($this->ch, "You don't see that here.");
				}
			}
		}	
	}
	
	function doDrop($args)
	{
		global $clients;
		
		if($args == "")
		{
			$this->ch->send("Drop what?\n");
			return;
		}
		
		$room = new Room($this->ch);
		$room->load();
		
		foreach($this->ch->pData->inventory as $item)
		{
			if($args == "all")
			{
				if(isset($item->quantity) && $item->quantity > 1)
				{
					$quantity = $item->quantity;
					
					for($i=1;$i<=$quantity;$i++)
					{
						foreach($clients as $client)
						{
							if($client != $this->ch && $client->pData->in_room == $this->ch->pData->in_room)
							{
								$this->toChar($client, $this->ch->pData->name . " drops " . $item->short);
							}
						}
						
						$this->toChar($this->ch, "You drop " . $item->short);
						$this->objFromChar($item, $this->ch);
						$this->objToRoom($item, $room);
					}
				}
				else
				{
					foreach($clients as $client)
					{
						if($client != $this->ch && $client->pData->in_room == $this->ch->pData->in_room)
						{
							$this->toChar($client, $this->ch->pData->name . " drops " . $item->short);
						}
					}
						
					$this->toChar($this->ch, "You drop " . $item->short);
					$this->objFromChar($item, $this->ch);
					$this->objToRoom($item, $room);
				}
			}
			else
			{
				if(in_array($args, explode(' ', $item->keywords)))
				{
					foreach($clients as $client)
					{
						if($client != $this->ch && $client->pData->in_room == $this->ch->pData->in_room)
						{
							$this->toChar($client, $this->ch->pData->name . " drops " . $item->short);
						}
					}
						
					$this->objFromChar($item, $this->ch);
					$this->objToRoom($item, $room);
					$this->toChar($this->ch, "You drop " . $item->short);
					break;
				}
			}
		}
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
			if($client->pData->in_room == $this->ch->pData->in_room)
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

	}
	
	function savePlayer()
	{
		$this->ch->pData->save();
		$this->ch->send("Saved.\n");
	}
}
