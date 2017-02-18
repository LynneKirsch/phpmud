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
	
	function doDrop($args)
	{
		if($args == "")
		{
			$this->ch->send("Drop what?\n");
			return;
		}
		
		$inventory = $this->ch->pData->inventory;
		
		foreach($inventory as $key=>$item)
		{
			if($args == "all")
			{
				unset($this->ch->pData->inventory->{$key});
				$this->ch->send("You drop " . $item->short);
			}
			else
			{
				if(in_array($args, explode(' ', $item->keywords)))
				{
					if(isset($item->quantity) && $item->quantity > 1)
					{
						$item->quantity = $item->quantity - 1;
					}
					else
					{
						unset($this->ch->pData->inventory->{$key});
					}
					
					$this->ch->send("You drop " . $item->short);
					return;
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
