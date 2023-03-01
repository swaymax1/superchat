<?php

$db_hostname = "localhost";
$db_database = "website";
$db_username = "root";
$db_password = "";

$server = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);

if (mysqli_connect_error()) {
    die("cannot connect: " . mysqli_connect_error());
}
