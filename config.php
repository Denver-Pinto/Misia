<html>
<head>
</head>
<body><div>Please make  sure that Apache and MySql have been started through the Xampp Control Panel.
all the files should be placed under the htdocs directory of xampp directory</div>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$mdb ="misia";

// Create connection before creating the database

$con = mysqli_connect($servername, $username, $password);
// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error()."<br>");
}

// Create database misia
$sql = "CREATE DATABASE misia";
if (mysqli_query($con, $sql)) {
    echo "Database created successfully"."<br>";
} else {
    echo "Error creating database: " . mysqli_error($con)."<br>";
}

mysqli_close($con);
//create connection to database
$con = mysqli_connect($servername, $username, $password,$mdb);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
//creating data table and shops table 

$sql="CREATE TABLE data (latitude float(10,6),longitude float(10,6), number int(11),product varchar(100),
timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)";
if (mysqli_query($con, $sql)) {
    echo "Necessary tables created successfully"."<br>";
} else {
    echo "Error creating table data: " . mysqli_error($con)."<br>";
}


$sql="CREATE TABLE shops ( username varchar(100),
password varchar(100),lat float(10,6),lng float(10,6) )";
if (mysqli_query($con, $sql)) {
    echo "Necessary tables created successfully"."<br>";
} else {
    echo "Error creating table shops: " . mysqli_error($con)."<br>";
}
echo" Creating dummy shop accounts...<br>";


$sql="Insert into shops (username,password,lat,lng) values('shop1','password',19.073641,72.897804),('shop2','password',19.073278,72.908592),('shop3','password',19.088770,72.907738);";

$sql.= "CREATE TABLE shop1 (
	product  varchar(100),
	price int(11),
	quantity int(11)
);";
$sql.="Insert into shop1 (product,price,quantity) values('a',10,10),('b',10,10),('c',10,10);";
$sql.= "CREATE TABLE shop3 (
	product  varchar(100),
	price int(11),
	quantity int(11)
);";
$sql.="Insert into shop3 (product,price,quantity) values('c',10,10),('d',10,10),('a',10,10);";
$sql.= "CREATE TABLE shop2 (
	product varchar(100),
	price int(11),
	quantity int(11)
);";
$sql.="Insert into shop2 (product,price,quantity) values('b',10,10),('c',10,10),('d',10,10);";
if(mysqli_multi_query($con, $sql))
{
	 echo "account created->username:shop1 password:password"."<br>";
	  echo "account created->username:shop2 password:password"."<br>";
	   echo "account created->username:shop3 password:password"."<br>";
} else {
    echo "Error creating accounts:Will have to do it through the website :( " . mysqli_error($con)."<br>";
}
	
/* close connection */
 
mysqli_close($con);

?>
</body>
</html>