<?php
class Equipment extends GameInterface
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
					if(isset($eq->{$slot}) && !is_null($eq->{$slot}))
					{
						foreach($eq->{$slot} as $item_obj)
						{
							if(!is_null($item_obj))
							{
								$object = new Object();
								$object->load($item_obj->id);
								$this->{$slot} = clone $object;
							}
						}
					}
					else
					{
						$this->{$slot} = new stdClass();
						
						for($i=0;$i>=$slot->instances;$i++)
						{
							$this->{$slot}->{$i} = null;
						}
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