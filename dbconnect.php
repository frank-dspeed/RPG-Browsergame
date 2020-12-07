<?php

if(file_exists('../logindata.php')) {
	 include("logindata.php");
} else {
	$host = $_ENV['DB_HOST'];
	$user = $_ENV['DB_USER'];
	$password = $_ENV['DB_PASSWORD'];
	$db = $_ENV['DB_NAME'];
}
	$connection = new mysqli($host,$user,$password,$db);
	if($connection)
		//echo "Verbunden";

?>