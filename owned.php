<?php
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 	
?>

<?php
//This gets the games that the user has made
$response = array();
ini_set("allow_url_fopen", 1);

// include db connect class
require_once __DIR__ . '/db_connect.php';

//var_dump($_SESSION);
// connect to db
$db = new db_connect();
$uid = $_SESSION["userID"];
//$uid = $_GET["uid"];;

$first = true;//for proper sql syntax
$sql = $db->conn->prepare("SELECT * FROM markers WHERE user_id = ?");
$sql->bind_param('s', $uid);
$sql->execute();
$result = $sql->get_result();

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
		$marker['curr_signed'] = $row['curr_signed'];
		$marker['curr_refs'] = $row['curr_refs'];
		$marker['max_signed'] = $row['max_signed'];
		$marker['max_refs'] = $row['max_refs'];
		
		// push single marker into final response array
        array_push($response['markers'], $marker);
	 }
	 // success
    $response["success"] = 1;
 
    // echoing JSON response
    echo json_encode($response);
} 
else {
    $response["success"] = 0;
    $response["message"] = "No markers found";
 
    echo json_encode($response);
}//https://csc-182021.appspot.com/?sport=any&city=any&comp=true&fun=false
?>