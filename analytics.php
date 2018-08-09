<?php require 'connection.php';
session_start();
if(!isset($_SESSION["table"])){//session name is not set so logout
  header('location:login.php');
}
$d= date("Y-m-d H:i:s");
$date=date_create($d);
date_sub($date,date_interval_create_from_date_string("30 days"));
$limit=date_format($date,"Y-m-d H:i:s");
echo "last entry that can be accessed : ".$limit."<br>";
?>
<html>
<head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
	$("button").click(function(){
		window.location="inventory.php";
	});

});
</script>
</head>
<body><button> Back</button>
	<table>
	<caption>Last 30 days activity around your  area<br>
	</caption>
	
	<tr>
	<td>Product Name</td> <td>Availabilty</td><td>Timestamp</td>
	</tr>
	<//to get location of the store logged in
	<?php
	$select_ll_preparedstmt= mysqli_prepare($con, "SELECT lat,lng from shops where username={$_SESSION["table"]}");
	    //check the prepared statement
		if ( !$select_ll_preparedstmt ) {
		die('mysqlii error: '.mysqli_error($con));
			}

			if ( !mysqli_execute($select_ll_preparedstmt) ) {
			die( 'stmt error: '.mysqli_stmt_error($select_ll_preparedstmt) );
			}
			// execute query 
		
		//bind variables to prepared statement 
		mysqli_stmt_bind_result($select_ll_preparedstmt, $lat,$lng);
	
		  // close statement 
		mysqli_stmt_close($select_ll_preparedstmt);
	
	/* create a prepared statement*/
	//distance needs to be selected
$select_data_preparedstmt =mysqli_prepare($con, "SELECT  ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance,product,number,time FROM data  HAVING distance < 100 and time> ? ORDER BY time desc");

if ( !$select_data_preparedstmt ) {
  die('mysqli error: '.mysqli_error($con));
}
/* bind parameters for markers */
    mysqli_stmt_bind_param($select_data_preparedstmt, "ddds", $lat,$lng,$lat,$limit);
if ( !mysqli_execute($select_data_preparedstmt) ) {
  die( 'stmt error: '.mysqli_stmt_error($select_data_preparedstmt) );
}
    /* execute query */
    mysqli_stmt_execute($select_data_preparedstmt);
 /* bind variables to prepared statement */
    mysqli_stmt_bind_result($select_data_preparedstmt, $distance, $product,$number,$timestamp);

    /* fetch values */
    while (mysqli_stmt_fetch($select_data_preparedstmt)) {
        echo " <tr><td>".$product."</td><td>".$number."</td><td>".$timestamp."</td></tr>";
	}
	 mysqli_stmt_close($select_data_preparedstmt);
?>
</table>
</body>
</html>