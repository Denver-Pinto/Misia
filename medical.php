<?php
require 'connection.php';
session_start();
?>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
	$("button").click(function(){
		window.location="home.php";
	});

});
</script>
</head>
<body>
<?php
//$_SESSION["POST_VARS"]=$_POST;
$lat= $_POST["lat"];//$_SESSION["POST_VARS"]["lat"];
$lng=$_POST["lng"];//$_SESSION["POST_VARS"]["lng"];
$product=$_POST["medicine"];//$_SESSION["POST_VARS"]["medicine"];
$number=0;
$distance=$_POST["distance"];//$_SESSION["POST_VARS"]['distance'];
echo  $lat."<br>";
echo $lng."<br>";
echo $product."<br>";
echo $distance."<br>";
/* create a prepared statement*/
$select_distance_preparedstmt =mysqli_prepare($con, "SELECT  ( 6371 * acos( cos( radians(?) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(?) ) + sin( radians(?) ) * sin( radians( lat ) ) ) ) AS distance,username FROM shops  HAVING distance< ? ORDER BY distance LIMIT 0 , 20");

if ( !$select_distance_preparedstmt ) {
  die('mysqli error: '.mysqli_error($con));
}
/* bind parameters for markers */
    mysqli_stmt_bind_param($select_distance_preparedstmt, "dddi", $lat,$lng,$lat,$distance);
if ( !mysqli_execute($select_distance_preparedstmt) ) {
  die( 'stmt error: '.mysqli_stmt_error($select_distance_preparedstmt) );
}
    /* execute query */
    mysqli_stmt_execute($select_distance_preparedstmt);
	$result = mysqli_stmt_get_result($select_distance_preparedstmt);
     $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

		

   /* close statement */
    mysqli_stmt_close($select_distance_preparedstmt);
	   while($row)
	 {
		// echo $row["username"];
       
	$select_product_preparedstmt= mysqli_prepare($con, "SELECT price from {$row["username"]} where product=?");
	    //check the prepared statement
		if ( !$select_product_preparedstmt ) {
		die('mysqlii error: '.mysqli_error($con));
			}
				// bind parameters for markers 
			mysqli_stmt_bind_param($select_product_preparedstmt, "s", $product);
			if ( !mysqli_execute($select_product_preparedstmt) ) {
			die( 'stmt error: '.mysqli_stmt_error($select_product_preparedstmt) );
			}
			// execute query 
		
		//bind variables to prepared statement 
		mysqli_stmt_bind_result($select_product_preparedstmt, $price);
		if(mysqli_stmt_fetch($select_product_preparedstmt)) {
        echo " <div>Product exists at {$row["username"]}at price".$price."</div>";
		$number++;}
		else{
			//echo"Product doesnt exist<br>";
		}
		  // close statement 
		mysqli_stmt_close($select_product_preparedstmt);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	 
    }

 
//inserting data into the table data using prepared insert statements

	$insert_data_preparedstmt =mysqli_prepare($con, "INSERT INTO data(latitude,longitude,product,number)VALUES (?,?,?,?) ");

if ( !$insert_data_preparedstmt ) {
  die('mysqli error: '.mysqli_error($con));
}
/* bind parameters for markers */
    mysqli_stmt_bind_param($insert_data_preparedstmt, "ddsi", $lat,$lng,$product,$number);
if ( !mysqli_execute($insert_data_preparedstmt) ) {
  die( 'stmt error: '.mysqli_stmt_error($insert_data_preparedstmt) );
}
     
/* close connection */
mysqli_close($con);
?>
<button>Search something else</button>
</body>
</html>