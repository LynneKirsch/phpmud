<?php
class Equipment extends GameInterface
{
	function load()
	{
		$slots = json_decode(file_get_contents('src/db/equipment_slots.json'));
		
		if(!is_null($this->ch))
		{
			foreach($slots as $slot => $name)
			{
				if(isset($this->ch->pData->equipment->{$slot}))
				{
					$this->{$slot} = $this->ch->pData->equipment->{$slot};
				}
				else
				{
					$this->{$slot} = null;
				}
			}
		}

	}
	
	function getDisplayName($slot)
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