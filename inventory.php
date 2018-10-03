<<?php
require 'connection.php';
session_start();
if(!isset($_SESSION["table"])){//session name is not set so logout
  header('location:login.php');
}
echo "WELCOME".$_SESSION["table"]."<br>";
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="global.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
	$("#history").click(function(){
		window.location="analytics.php";
	});
	$("#myBtn").click(function(){
		$("#myModal").show();
	});
	$(".close").click(function(){
		$("#myModal").hide();
	});
	$("#logout").click(function(){
		window.location="logouter.php";
	});

});
</script>
<script>
//finding a better way to do this !
function check()
{
	
	if($('input[name=choice]:checked').length<=0)
{
 alert("No choice checked")
 return false;
}
else{
	 var isChecked = $("input[name=choice]:checked").val();
	 if(isChecked=="update")
	 {
		if(!$("#price").val())
		{
			alert("enter the updated price");
				$("#myModal").show();
			return false;
		}
		if(!$("#quantity").val())
		{
			alert("enter the updated quantity");
				$("#myModal").show();
			return false;
		}
	 }
	 if(isChecked=="add")
	 {
		 if(!$("#price").val())
		{
			alert("enter the updated price");
				$("#myModal").show();
			return false;
		}
		if(!$("#quantity").val())
		{
			alert("enter the updated quantity");
				$("#myModal").show();
			return false;
		}
		
	 }
	 if(isChecked=="delete")
	 {
		 
	 }
}
return true;
}
</script>
<style>
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
    background-color: lightgreen;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 60%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
input[type=text],input[type=number]
{width:100%;
}
</style>
</head>
<body >
<div id="pagewrapper">
<div class="header">
INVENTORY PAGE
<button class="header_button" id="history">Nearby history</button>
<button class="header_button" id="logout">Logout</button>
<button class="header_button" id="myBtn">Update Inventory</button>
</div>
<table>

<tr> <th>PRODUCT</th><th>PRICE</th> <th>QUANTITY</th> </tr>
   <?php
	$select_product_preparedstmt= mysqli_prepare($con, "SELECT product,price,quantity from {$_SESSION["table"]}");
	    //check the prepared statement
		if ( !$select_product_preparedstmt ) {
		die('mysqlii error: '.mysqli_error($con));
			}

			if ( !mysqli_execute($select_product_preparedstmt) ) {
			die( 'stmt error: '.mysqli_stmt_error($select_product_preparedstmt) );
			}
			// execute query 
		
		//bind variables to prepared statement 
		mysqli_stmt_bind_result($select_product_preparedstmt, $pr,$p,$q);
		while(mysqli_stmt_fetch($select_product_preparedstmt)) {
        echo " <tr><td>".$pr."</td><td>".$p."</td><td>".$q."</td></tr>";
		}
		  // close statement 
		mysqli_stmt_close($select_product_preparedstmt);
		?>
		</table>
		<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
	
    <form name ="inventory" method ="post" action="pureinventory.php" onsubmit="check()">
	<p><label>Update Item</label><input type="radio" name="choice" value="update"></p>
	<p><label>Delete Item</label><input type="radio" name="choice" value="delete"></p>
	<p><label>Insert Item</label><input type="radio" name="choice" value="add"></p>
	<p><label>Medicine</label><input type="text"  name="medicine"required></p>
	<p><label>Price</label> <input type ="number" id="price" name="price"></p>
	<p><label>Quantity</label><input type ="number" id="quantity" name="quantity"></p>
	<p><label></label><input type="submit" value="submit"></p>
	</form>
  </div>

</div>
</body>
</html>