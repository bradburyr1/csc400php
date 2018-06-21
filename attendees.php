<?php
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 	
?>

<?php
//Return the attendees of a game 
$response = array();
ini_set("allow_url_fopen", 1);

// include db connect class
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/checker_class.php';
$check = new InputChecker();

// connect to db
$db = new db_connect();

$gid = $_GET["gid"];
$first = true;//for proper sql syntax
//var_dump($_SESSION);
if (isset($_SESSION["userID"]))
{

$gid = $check->checkGID($gid);//This method ensures GID is safe to use

//Now this statement is safe to use, because if GID isn't a blank space, it's in the database
$sql2 = "SELECT * FROM `attendees_$gid`";
$result = $db->conn->query($sql2);

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
} 
else {
    $response["success"] = 0;
    $response["message"] = "No markers found";
 
    echo json_encode($response);
}
}//https://csc-182021.appspot.com/?sport=any&city=any&comp=true&fun=false
?>