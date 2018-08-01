<?php
$servername = "localhost";
$username = "root";
$password = "";
$mdb ="maindb";
// Create connection
$con = mysqli_connect($servername, $username, $password,$mdb);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully <br>";
?>