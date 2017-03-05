<?php
define('APP', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('SRC', APP . 'src' . DIRECTORY_SEPARATOR);

class GameAutoload 
{
	function __construct()
	{
		$this->loadDir(SRC.'Config/');
		$this->loadDir(SRC.'Interface/');
		$this->loadDir(SRC.'Models/');
		$this->loadDir(SRC.'Core/');
		$this->loadDir(SRC.'Classes/');
		$this->loadDir(SRC.'Races/');
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

