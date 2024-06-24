<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'user_managament');

/* Attempt to connect to MySQL database */
$conexion = mysqli_connect('localhost', 'root', '', 'user_managament');

// Check connection
if($conexion === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

