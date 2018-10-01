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
    background-color: #fefefe;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Could be more or less, depending on screen size */
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
<button id="history">Nearby history</button>
<button id="logout">Logout</button>
<table>
<caption>INVENTORY</caption>
<tr> <td>Product</td><td> Price</td> <td> Quantity</td> </tr>
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
		<tr colspan="3"> <td><button id="myBtn">Update Inventory</button></td>
		</tr>
		</table>
		<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
	
    <form name ="inventory" method ="post" action="pureinventory.php" onsubmit="check()">
	Update Item<input type="radio" name="choice" value="update">
	Delete Item<input type="radio" name="choice" value="delete">
	Insert Item<input type="radio" name="choice" value="add">
	<br>
	Medicine<input type="text" placeholder="Enter the correct medicine information" name="medicine"required><br>
	Price <input type ="number" placeholder ="Enter the price of the medicine" id="price" name="price"><br>
	Quantity <input type ="number" placeholder ="Enter the current quantity of the medicine" id="quantity" name="quantity"><br>
	<input type="submit" value="submit">
	</form>
  </div>

</div>
</body>
</html>