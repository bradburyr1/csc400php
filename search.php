<?php
$response = array();

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connect to db
$db = new db_connect();
$sport = $_GET["sport"];
$city = $_GET["city"];
$comp = $_GET["comp"];
$fun = $_GET["fun"];
/* echo $sport;
echo $city;
echo $comp;*/

$first = true;//for proper sql syntax

$sql = "SELECT * FROM markers";
//echo $sql;

if($sport != 'any'){
		$sql .= " WHERE title = '$sport'";
		$first = false;
}
//echo $sql;
if($city != 'any'){
	if($first == true){
		$sql .= " WHERE address = '$city'";
	}
	else if($first == false){
		$sql .= " AND address = '$city'";
	}
}
//echo $sql;
//echo $comp;
if($comp == 'true' && $fun != 'true'){
	if($first == true){
		$sql .= " WHERE comp_level = 'Competitive'";
	}
	else if($first == false){
		$sql .= " AND comp_level = 'Competitive'";
	}
}
//echo $sql;
//echo $fun;
if($fun == 'true' && $comp != 'true'){
	if($first == true){
		$sql .= " WHERE comp_level = 'Fun'";
	}
	else if($first == false){
		$sql .= " AND comp_level = 'Fun'";
	}
}
//echo "line 55: ".$sql;

//Get all markers
$result = $db->conn->query($sql);
//$values = $result->fetch_assoc();
//echo $db->username;
// check for empty result
//echo (string)$result;
if (count($result) > 0) {
    $response['markers'] = array();
	//echo "hello";
	//memory_get_peak_usage();
	 //while ($row == count($result)) {
		 while($row = mysqli_fetch_assoc($result)){
        // temp user array
        $marker = array();
		$marker['latitude'] = $row['latitude'];
		//echo "hello";
		//echo $marker["latitude"];
		$marker["longitude"] = $row["longitude"];
		$marker["title"] = $row["title"];
		$marker["city"] = $row["address"];
		$marker["comp_level"] = $row["comp_level"];
		
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
}
?>