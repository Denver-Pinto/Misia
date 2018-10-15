<?php 
require 'connection.php';
session_start();
$q="SELECT * FROM shops WHERE username= '{$_POST["username"]}'";
 $r=mysqli_query($con,$q);
$row=mysqli_fetch_row($r);
//Now to check, we use an if() statement
if($row != NULL) {
 //print "Username exists";
 mysqli_close($con);
header("Location:signup.php");
  } else {
 //print "Username doesn't exist";
 $insert_data_preparedstmt =mysqli_prepare($con, "INSERT INTO shops(username,password,lat,lng)VALUES (?,?,?,?) ");
//insert shop data 
if ( !$insert_data_preparedstmt ) {
  die('mysqli error: '.mysqli_error($con));
}
/* bind parameters for markers */
    mysqli_stmt_bind_param($insert_data_preparedstmt, "ssdd", $_POST["username"],$_POST["password"],$_POST["lat"],$_POST["lng"]);
if ( !mysqli_execute($insert_data_preparedstmt) ) {
 // die( 'stmt error: '.mysqli_stmt_error($insert_data_preparedstmt) );
  //redirect to signup page/* close connection */
  mysqli_stmt_close($insert_data_preparedstmt);
mysqli_close($con);
header("Location:signup.php");
}else{
//redirect to analytics page after creating table
      $_SESSION["table"]=$_POST["username"];
	   mysqli_stmt_close($insert_data_preparedstmt);
	  $sql = "CREATE TABLE {$_SESSION["table"]} (
	product  varchar(100),
	price int(11),
	quantity int(11)
)";

if (mysqli_query($con, $sql)) {
    echo "Table created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}
/* close connection */
 
mysqli_close($con);
header("Location:analytics.php");
}
}
?>