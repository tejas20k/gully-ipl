<?php
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'id5201685_ipldb');
	define('DB_USER','id5201685_ipl');
	define('DB_PASSWORD','pass123');
	$con=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD) or die("Failed to connect to MySQL: " . mysql_error());
?>