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
$_SESSION["POST_VARS"]=$_POST;
$lat= $_SESSION["POST_VARS"]["lat"];
$lng=$_SESSION["POST_VARS"]["lng"];
$product=$_SESSION["POST_VARS"]["medicine"];
$number=0;
$distance=$_SESSION["POST_VARS"]['distance'];
echo  $lat."<br>";
echo $lng."<br>";
echo $product."<br>";
echo $distance."<br>";
/* create a prepared statement*/
$select_distance_preparedstmt =mysqli_prepare($con, "SELECT  ( 6371 * acos( cos( radians(?) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(?) ) + sin( radians(?) ) * sin( radians( lat ) ) ) ) AS distance,servername,username,password,dbname FROM shops  HAVING distance< ? ORDER BY distance LIMIT 0 , 20");

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
 /* bind variables to prepared statement */
    mysqli_stmt_bind_result($select_distance_preparedstmt, $distance, $sname,$uname,$pword,$db);

    /* fetch values */
    while (mysqli_stmt_fetch($select_distance_preparedstmt)) {
       // echo " $distance $sname $uname $pword $db <br> ";
		$sub_con = mysqli_connect($sname, $uname, $pword,$db);

		// Check connection
		if (!$sub_con) {
			die("Connection failed: " . mysqli_connect_error());
		}
		//echo "Connected successfully <br>";
		//create prepared statement
	$select_product_preparedstmt= mysqli_prepare($sub_con, "SELECT price from products where product=?");
	    //check the prepared statement
		if ( !$select_product_preparedstmt ) {
		die('mysqli error: '.mysqli_error($sub_con));
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
        echo " <div>Product exists at ".$db."at price".$price."</div>";
		$number++;}
		else{
			//echo"Product doesnt exist<br>";
		}
		  // close statement 
		mysqli_stmt_close($select_product_preparedstmt);


			//close connection 
				mysqli_close($sub_con);
    }

    /* close statement */
    mysqli_stmt_close($select_distance_preparedstmt);
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