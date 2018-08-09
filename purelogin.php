<?php
require 'connection.php';
session_start();
$select_data_preparedstmt =mysqli_prepare($con, "Select * from shops where username=? and password=? ");

if ( !$select_data_preparedstmt ) {
  die('mysqli error: '.mysqli_error($con));
}
/* bind parameters for markers */
    mysqli_stmt_bind_param($select_data_preparedstmt, "ss", $_POST["username"],$_POST["password"]);
if ( !mysqli_execute($select_data_preparedstmt) ) {
  die( 'stmt error: '.mysqli_stmt_error($select_data_preparedstmt) );
}
 if(mysqli_stmt_fetch($select_data_preparedstmt)) {    
   $_SESSION["table"]=$_POST["username"];
  /* close connection */
mysqli_close($con);
  header("Location:inventory.php");
 }else{
/* close connection */
mysqli_close($con);
 header("Location:login.php");
 }
?>