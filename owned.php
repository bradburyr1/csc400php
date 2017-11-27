<?php
$response = array();

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connect to db
$db = new db_connect();
$uid = $_GET["user_id"];
/* echo $sport;
echo $city;
echo $comp;*/

$first = true;//for proper sql syntax

$sql = "SELECT * FROM markers WHERE user_id = '$uid'";

$result = $db->conn->query($sql);

if (count($result) > 0) {
    $response['markers'] = array();
	
	while($row = mysqli_fetch_assoc($result)){
        // temp user array
        $marker = array();
		$marker['gameid'] = $row['game_id'];
		$marker['latitude'] = $row['latitude'];
		$marker["longitude"] = $row["longitude"];
		$marker["title"] = $row["title"];
		$marker["city"] = $row["address"];
		$marker['time'] = $row['time'];
		$marker['date'] = $row['date'];
		$marker["comp_level"] = $row["comp_level"];
		$marker['postalAddress'] = $row['postalAddress'];
		$marker['user_id'] = $row['user_id'];
		
		// push single marker into final response array
        array_push($response['markers'], $marker);
	 }
	 // success
    $response["success"] = 1;
 
    // echoing JSON response
    echo json_encode($response);
} else {
    $response["success"] = 0;
    $response["message"] = "No markers found";
 
    echo json_encode($response);
}//https://csc-182021.appspot.com/?sport=any&city=any&comp=true&fun=false
?>