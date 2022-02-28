<?php

$local = 'y'; //Check if the code is running in localhost (y = yes, n = not). 
 
if ($local == 'y'){

//Localhost connection data
$hostname = "localhost";
$database = "solar";
$user = "root";
$password = "";

}elseif($local == 'n'){

//External server connection data
$hostname = "";
$database = "";
$user = "";
$password = "";

}else{

    exit();
};

$mysqli = new mysqli($hostname, $user, $password, $database);

?>