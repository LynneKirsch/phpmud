<?php
class Play_Model extends Model
{
	function helloWorld()
	{
		$text = file_get_contents(APP.'src/db/rooms/1.json');
		echo '<pre>';
		print_r($text);
		echo '</pre>';
		die();
	}
}