<?php
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 	
?>

<?php
//search the database based on the user's search criteria. 
$response = array();
ini_set("allow_url_fopen", 1);

// include db connect class
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/checker_class.php';
$check = new InputChecker();

// connect to db
$db = new db_connect();
$sport = $_GET["sport"];
$city = $_GET["city"];
$comp = $_GET["comp"];
$fun = $_GET["fun"];
$uid = $_SESSION["userID"];

//var_dump($_SESSION);
/* echo $sport;
echo $city;
echo $comp;*/

//$first = true;//for proper sql syntax

//$sql = "SELECT l.* FROM markers l";
//echo $sql;

$sportOpp = "=";
$cityOpp = "=";
$compLevel = "%";

if($sport == 'any'){
		//$sql .= " WHERE title = '$sport'";
		$sportOpp = "LIKE";
		$sport = "%";
}
//echo $sql;
if($city == 'any'){
	$cityOppOpp = "LIKE";
	$city = "%";
}
//echo $sql;
//echo $compLevel;
if($comp == 'true' && $fun != 'true'){
	/*if($first == true){
		$sql .= " WHERE comp_level = 'Competitive'";
		$first = false;
	}
	else if($first == false){
		$sql .= " AND comp_level = 'Competitive'";
	}*/
	$compLevel = "Competitive";
}
//echo $sql;
//echo $compLevel;
if($fun == 'true' && $comp != 'true'){
	/*if($first == true){
		$sql .= " WHERE comp_level = 'Just For Fun' OR comp_level = 'Fun'";
		$first = false;
	}
	else if($first == false){
		$sql .= " AND comp_level = 'Just For Fun' OR comp_level = 'Fun'";
	}*/
	$compLevel = "Fun";
}
//$sql .= " AND l.game_id NOT IN (SELECT game_id FROM gamelist_$uid r)";

	$foundIndex = $check->get_index($uid);//This consults the user table to find which index number the user is

//echo "The SQL Query: ".$sql;

//Get all markers

$sql = $db->conn->prepare("SELECT * FROM `csc400db`.`markers` l WHERE title LIKE ? AND address LIKE ? AND comp_level LIKE '$compLevel' AND l.game_id NOT IN (SELECT game_id FROM gamelist_$foundIndex r)");
$sql->bind_param('ss', $sport, $city);
$sql->execute();
$result = $sql->get_result();
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
} else {
    $response["success"] = 0;
    $response["message"] = "No markers found";
 
    echo json_encode($response);
}//https://csc-182021.appspot.com/?sport=any&city=any&comp=true&fun=false
?>