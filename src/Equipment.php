<?php
class Equipment extends GameInterface
{
	function load($eq)
	{
		$slots = json_decode(file_get_contents('src/db/equipment_slots.json'));
		
		if(!is_null($this->ch))
		{
			foreach($slots as $slot => $slot_obj)
			{
				if(isset($this->ch->pData->equipment->{$slot}) && !is_null($this->ch->pData->equipment->{$slot}))
				{
					$this->{$slot} = $eq->{$slot};
				}
				else
				{
					$this->{$slot} = null;
				}
			}
		}

	}
	
	function slots()
	{
		$slots = json_decode(file_get_contents('src/db/equipment_slots.json'));
		return $slots;
	}
	
	function getSlot($slot)
	{
		$slots = json_decode(file_get_contents('src/db/equipment_slots.json'));
		
		if(in_array($slot, array_keys((array)$slots)))
		{
			return $slots->{$slot};
		}
		else
		{
			return false;
		}
		
	}
			
}