<?php
class GameAutoload 
{
	function __construct()
	{
		$this->loadDir('src/Config');
		$this->loadDir('src/Interface');
		$this->loadDir('src/Models');
		$this->loadDir('src/Core');
		$this->loadDir('src/Classes');
		$this->loadDir('src/Races');
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

