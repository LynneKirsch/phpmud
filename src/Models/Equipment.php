<?php
class Equipment extends PlayerInterface
{
	function load($eq)
	{
		$slots = json_decode(file_get_contents('src/db/equipment_slots.json'));
		
		if(!is_null($eq))
		{
			foreach($slots as $slot => $slot_obj)
			{
				if($slot_obj->instances > 1)
				{
					if(isset($eq->{$slot}) && !is_null($eq->{$slot}) && is_array($eq->{$slot}))
					{
						foreach($eq->{$slot} as $key => $item_obj)
						{
							if(!is_null($item_obj) && !is_null($item_obj->id))
							{
								$object = new Object();
								$object->load($item_obj->id);
								$this->{$slot}[$key] = clone $object;
							}
							else
							{
								$this->{$slot}[$key] = null;
							}
						}
					}
					else
					{
						$this->{$slot} = array_fill(0, $slot_obj->instances, null);
					}
				}
				else
				{
					if(isset($eq->{$slot}) && !is_null($eq->{$slot}))
					{
						$object = new Object();
						$object->load($eq->{$slot}->id);
						$this->{$slot} = clone $object;
					}
					else
					{
						$this->{$slot} = null;
					}
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