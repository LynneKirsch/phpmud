<?php
echo '<pre>';
print_r($_SERVER['SERVER_ADDR']);
echo '</pre>';
die();

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
