<?php
class GameAutoload 
{
	function __construct()
	{
		$this->loadDir('Config');
		$this->loadDir('Interface');
		$this->loadDir('Models');
		$this->loadDir('Core');
		$this->loadDir('Classes');
		$this->loadDir('Races');
	}
	
	function loadDir($dir)
	{
		$files = new FilesystemIterator($dir, FilesystemIterator::SKIP_DOTS);
		
		foreach($files as $file)
		{
			require_once($file->getPath()."/".$file->getBasename());
		}
		
	}
}

$autoload = new GameAutoload();

