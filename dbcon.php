<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "waste_record"; // User authentication database

$con = mysqli_connect($servername, $username, $password, $db);
if (!$con) {
    die("Connection to users database failed: " . mysqli_connect_error());
}
?>
