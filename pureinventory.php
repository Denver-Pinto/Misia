<?php
require 'connection.php';
session_start();
$choice= $_POST["choice"];
$medicine=$_POST["medicine"];
$price=$_POST["price"];
$quantity=$_POST["quantity"];
/*echo  $choice."<br>";
echo $medicine."<br>";
echo $price."<br>";
echo $quantity."<br>";*/
if($choice=="update")
{
	$update_data_preparedstmt =mysqli_prepare($con, "update {$_SESSION["table"]} set price =? , quantity=? where product =? ");

if ( !$update_data_preparedstmt ) {
  die('mysqli error: '.mysqli_error($con));
}
/* bind parameters for markers */
    mysqli_stmt_bind_param($update_data_preparedstmt, "iis", $_POST["price"],$quantity,$medicine);

if ( !mysqli_execute($update_data_preparedstmt) ) {
  die( 'stmt error: '.mysqli_stmt_error($update_data_preparedstmt) );
}
     
/* close connection */
mysqli_close($con);
 header("Location:inventory.php");
}
if($choice=="add")
{
	$insert_data_preparedstmt =mysqli_prepare($con, "INSERT INTO {$_SESSION["table"]} (product,price,quantity)VALUES (?,?,?) ");

if ( !$insert_data_preparedstmt ) {
  die('mysqli error: '.mysqli_error($con));
}
/* bind parameters for markers */
    mysqli_stmt_bind_param($insert_data_preparedstmt, "sii", $medicine,$price,$quantity);
if ( !mysqli_execute($insert_data_preparedstmt) ) {
  die( 'stmt error: '.mysqli_stmt_error($insert_data_preparedstmt) );
}
     
/* close connection */
mysqli_close($con);
 header("Location:inventory.php");
}
if($choice=="delete")
{
	$delete_data_preparedstmt =mysqli_prepare($con, "delete from {$_SESSION["table"]} where product =? ");

if ( !$delete_data_preparedstmt ) {
  die('mysqli error: '.mysqli_error($con));
}
/* bind parameters for markers */
    mysqli_stmt_bind_param($delete_data_preparedstmt, "s",$medicine);

if ( !mysqli_execute($delete_data_preparedstmt) ) {
  die( 'stmt error: '.mysqli_stmt_error($delete_data_preparedstmt) );
}
     
/* close connection */
mysqli_close($con);
 header("Location:inventory.php");
}


?>