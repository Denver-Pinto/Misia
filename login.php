<?php
require 'connection.php';
session_start();
?>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
Login form
<form action="purelogin.php" method ="post">
	<input type="text" placeholder="Enter your username" name="username" required> 
	<br>
	<input type="password" placeholder="Enter your password" name="password" required>
	<br>
	<input type ="submit" value ="login">
	<br>
</form>
<a href="signup.php">Sign up</a> if you haven't registered your store with us yet!
</body>
</html>