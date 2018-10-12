<?php
require 'connection.php';
session_start();
$lat= $_POST["lat"];
$lng=$_POST["lng"];
$product=$_POST["medicine"];
$number=0;
$distance=$_POST["distance"];
echo  $lat."<br>";
echo $lng."<br>";
echo $product."<br>";
echo $distance."<br>";
$lat_array=array($lat);
$lng_array=array($lng);
$information =array("you");
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
<script>
function myMap() {
var marker;
var latlng;
var window;
var mapProp= {
    center:new google.maps.LatLng(<?php echo $lat  ?>,<?php echo $lng  ?>),
    zoom:12,
};
var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
<?php

/* create a prepared statement*/
$select_distance_preparedstmt =mysqli_prepare($con, "SELECT  ( 6371 * acos( cos( radians(?) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(?) ) + sin( radians(?) ) * sin( radians( lat ) ) ) ) AS distance,username,lat,lng FROM shops  HAVING distance< ? ORDER BY distance LIMIT 0 , 20");

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
	//got the nearest stores from the given latitude and longitude
     $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

		

   /* close statement */
    mysqli_stmt_close($select_distance_preparedstmt);
	   while($row)
	 {
       //for each store checking if the product exists 
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
        //echo " <div>Product exists at {$row["username"]}at price".$price."</div>";
		$number++;
		array_push($lat_array,$row["lat"]);
		array_push($lng_array,$row["lng"]);
		array_push($information,$row["username"]."<br>"."Rs.".$price);
		}
		  // close statement 
		mysqli_stmt_close($select_product_preparedstmt);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		
	 
    }

 
//inserting data into the table data using prepared insert statements
//possibilty for optimizing code here
	$insert_data_preparedstmt =mysqli_prepare($con, "INSERT INTO data(latitude,longitude,product,number)VALUES (?,?,?,?) ");

if ( !$insert_data_preparedstmt ) {
  die('mysqli error: '.mysqli_error($con));
}
/* bind parameters for markers */
    mysqli_stmt_bind_param($insert_data_preparedstmt, "ddsi", $lat,$lng,$product,$number);
if ( !mysqli_execute($insert_data_preparedstmt) ) {
  die( 'stmt error: '.mysqli_stmt_error($insert_data_preparedstmt) );
}
   mysqli_stmt_close($insert_data_preparedstmt);    
/* close connection */
mysqli_close($con);
while($number>=0)
{	
?>
latlng = new google.maps.LatLng(<?php echo array_pop($lat_array);?>,<?php echo array_pop($lng_array);?>);
marker = new google.maps.Marker({position: latlng,
animation:google.maps.Animation.BOUNCE});     
marker.setMap(map);
infowindow = new google.maps.InfoWindow({
   content: "<?php echo array_pop($information);?>"
 });
 infowindow.open(map,marker);
 
<?php 
$number--;}?>
}
</script>
<button>Search something else</button>
<div id="googleMap" style="width:100%;height:400px;"></div>
<script src="https://maps.googleapis.com/maps/api/js?key=&callback=myMap"></script>
</body>
</html>

