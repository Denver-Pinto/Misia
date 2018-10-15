<?php
require 'connection.php';
session_start();
if(isset($_SESSION["table"])){//session name is not set so logout
  header('location:inventory.php');
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/global.css">
<link rel="stylesheet" type="text/css" href="css/login.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
	$("button").click(function(){
		window.location="signup.php";
	});

});
</script>
</head>
<body>
<div id="pagewrapper">
<div class="header">
LOGIN FORM <button class="header_button">SIGNUP</button>
</div>
<form action="purelogin.php" method ="post">
	<p><label>USERNAME</label><input type="text" placeholder="Enter username" name="username" required> 
	</p>
	<p><label>PASSWORD</label><input type="password" placeholder="Enter password" name="password" required>
	</p>
	<p><label></label><input type ="submit" value ="login">
	</p>
</form>
</div>
<div class="footer">Medicine Inventory Search & Improvement Assistant</div>
</body>
</html>