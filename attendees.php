<?php
//Return the attendees of a game 
$response = array();

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connect to db
$db = new db_connect();
$gid = $_GET["gid"];
/* echo $sport;
echo $city;
echo $comp;*/

$first = true;//for proper sql syntax

$sql = "SELECT * FROM `attendees_$gid`";

$result = $db->conn->query($sql);

if (count($result) > 0) {
    $response['markers'] = array();
	
	while($row = mysqli_fetch_assoc($result)){
        // temp user array
        $marker = array();
		$marker['user_id'] = $row['user_id'];
		$marker['role'] = $row['role'];
		
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