<?php
class Wiz extends GameInterface
{
	function createObject()
	{
		$object_dir = new FilesystemIterator('src/db/objects', FilesystemIterator::SKIP_DOTS);
		$object_count = iterator_count($object_dir);
		$object = new Object($this->ch);
		$object->id = $object_count+1;
		$object->save();
	}
}

