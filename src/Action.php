<?php
class Action extends GameInterface
{
	function doInventory()
	{
		$inventory = $this->ch->pData->inventory;
		$this->ch->send("You are carrying: \n");
		
		if(count($inventory)>0)
		{
			foreach($inventory as $id => $item)
			{
				if(isset($item->quantity) && $item->quantity > 1)
				{
					$this->ch->send("($item->quantity) ");
				}

				$this->ch->send($item->short . "\n");
			}
		}
		else
		{
			$this->ch->send("Nothing.\n");
		}
		
	}
	
	function doGet($args)
	{
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
						$this->objFromRoom($obj, $room);
						$this->objToChar($obj, $this->ch);
						$this->ch->send("You get " . $obj->short."\n");
					}
				}
				else
				{
					$this->objFromRoom($obj, $room);
					$this->objToChar($obj, $this->ch);
					$this->ch->send("You get " . $obj->short."\n");
				}
			}
			else
			{
				if(in_array($args, explode(' ', $obj->keywords)))
				{
					$this->objFromRoom($obj, $room);
					$this->objToChar($obj, $this->ch);
					$this->ch->send("You get " . $obj->short ."\n");
					break;
				}
				else
				{
					$this->ch->send("You don't see that here.\n");
				}
			}
		}	
	}
	
	function doDrop($args)
	{
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
						$this->objFromChar($item, $this->ch);
						$this->ch->send("You drop " . $item->short."\n");
						$this->objToRoom($item, $room);
					}
				}
				else
				{
					$this->ch->send("You drop " . $item->short."\n");
					$this->objFromChar($item, $this->ch);
					$this->objToRoom($item, $room);
				}
			}
			else
			{
				if(in_array($args, explode(' ', $item->keywords)))
				{
					$this->objFromChar($item, $this->ch);
					$this->objToRoom($item, $room);
					$this->ch->send("You drop " . $item->short);
					break;
				}
			}
		}
	}
	
	function doLook()
	{
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
				
				$this->ch->send($object->long);
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
