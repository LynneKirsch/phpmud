<?php
//class Class1
//{
//	public $class1 = "class1";
//	
//	function __clone()
//	{
//		unset($this->class1);
//	}
//}
//
//class Class3 
//{
//	public $class31;
//	public $class32;
//}
//class Class2 extends Class1
//{
//	public $class21 = "class2_1";
//	public $class22 = "class2_2";
//	public $class23 = "class2_3";
//	
//	function __construct()
//	{
//		$this->class21 = new Class3();
//		$this->class21->test1 = new stdClass();
//		$this->class21->test1->test1 = "test1_1";
//		$this->class21->test2 = "test2";
//		$this->class21->test3 = "test3";
//	}
//}
//
//$class = new Class2();
//$clone = clone($class);
//echo '<pre>';
//print_r($clone);
//echo '</pre>';
//die();


?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title></title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/style.css" rel="stylesheet">
	</head>
	<body>
		<pre id="console"></pre>
		<div id="input"><input type="text"></div>
		
		<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
		<script src="js/application.js"></script>
	</body>
</html>
