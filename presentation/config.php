<?php 

//Start Session
if (session_status() == PHP_SESSION_NONE) {
	session_start(); // Start session
}

	
//config or constant to save db
define('LOCALHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', '');
define('DB_NAME', 'lasttaskmanager');
//make sure to match db name with the one on here
define('SITEURL', 'http://localhost/lasttaskmanager/');



			


?>