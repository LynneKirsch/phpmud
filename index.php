<?php
define('URL_PUBLIC_FOLDER', 'public');
define('URL_PROTOCOL', '//');
define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
define('URL_SUB_FOLDER', str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])));
define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER);
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
		<script>var host = '<?php echo URL_DOMAIN; ?>';</script>
		<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
		<script src="js/application.js"></script>
	</body>
</html>
