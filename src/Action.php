<?php
class Action extends GameInterface
{
	function doInventory()
	{
		$inventory = $this->ch->pData->inventory;
		$this->ch->send("You are carrying: \n");
		
		foreach($inventory as $vnum => $item)
		{
			if($item->quantity > 1)
			{
				$this->ch->send("($item->quantity) ");
			}
			
			$this->ch->send($item->short_description . "\n");
		}
	}
	
	function doDrop($args)
	{
		$inventory = $this->ch->pData->inventory;
		
		foreach($inventory as $key=>$item)
		{
			if(in_array($args, explode(' ', $item->keywords)))
			{
				if($item->quantity > 1)
				{
					$item->quantity = $item->quantity - 1;
				}
				else
				{
					unset($this->ch->pData->inventory->{$key});
				}
			}
		}
	}
	
	function doEquipment()
	{

	}
}
