<?php 

//Start Session
if (session_status() == PHP_SESSION_NONE) {
	session_start(); // Start session
}

	
//Create Constants to save Database Credentials
define('LOCALHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', '');
define('DB_NAME', 'lasttaskmanager');

define('SITEURL', 'http://localhost/lasttaskmanager/');



			


?>