<?php
$response = array();

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connect to db
$db = new db_connect();

$sql = "SELECT * FROM markers";
//Get all markers
$result = $db->conn->query($sql);
//$values = $result->fetch_assoc();
//echo $db->username;
// check for empty result
//echo (string)$result;
if (count($result) > 0) {
    $response["markers"] = array();
	//echo "hello";
	//memory_get_peak_usage();
	 //while ($row == count($result)) {
		 while($row = mysqli_fetch_assoc($result)){
        // temp user array
        $marker = array();
		$marker["latitude"] = $row["latitude"];
		//echo "hello";
		//echo $marker["latitude"];
		$marker["longitude"] = $row["longitude"];
		$marker["title"] = $row["title"];
		
		// push single marker into final response array
        array_push($response["markers"], $marker);
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