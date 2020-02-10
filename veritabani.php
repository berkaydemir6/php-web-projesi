<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "web_projesi";

$link = mysqli_connect($servername, $username, $password, $database);

$link->set_charset('utf-8');

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>