<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "waste_record"; // User authentication database
$db_waste_warehouse = "push"; // User authentication database

$con = mysqli_connect($servername, $username, $password, $db);
$con_waste_warehouse = mysqli_connect($servername, $username, $password, $db_waste_warehouse);
if (!$con || !$con_waste_warehouse) {
    // If the connection fails, display an error message and exit the script
    die("Connection to users database failed: " . mysqli_connect_error());
}
?>
