<?php
	$mysqli = new mysqli('localhost', 'root', 'qwaszx', 'module3');
	if($mysqli->connect_errno) {
		printf("Connection Failed: %s\n", $mysqli->connect_error);
		exit;
	}
?>