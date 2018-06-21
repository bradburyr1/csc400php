<?php
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
	
//echo "Login check session ID: " . session_id();
?>

<?php
//If the user is not in the system, add them in, and if they are, return the games they are signed up for. 
$response = array();
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/checker_class.php';
$check = new InputChecker();

$id_token = $_POST["idToken"];
//echo "ID Token: ".$id_token;

$json = file_get_contents("https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=$id_token");
$obj = json_decode($json);
if($obj->aud == "971016684167-g5oisid1qotpa92c41kf622bp3qipbh9.apps.googleusercontent.com"){

$rawuid =  $obj->email;//We need to change the format of the user id, the email is "raw", with the "@" and "." symbols 

$token = preg_split("/[@ .]/", $rawuid);
   
$uid = implode("_", $token);

//echo $uid;

$_SESSION["userID"] = $uid;
//var_dump($_SESSION);
//End new code

// connect to db
$db = new db_connect();
//$uid = $_GET["user_id"];

$first = true;//for proper sql syntax

$sql = $db->conn->prepare("SELECT * FROM users WHERE user_id = ?");//Initial check to see if they are in the system or not
$sql->bind_param('s', $uid);
$sql->execute();
$result = $sql->get_result();
$num_rows = $result->num_rows;//Get a count for the number of rows, to check for existance of user id


if($num_rows==0){
	//We need to give the new user an index number, which is simply adding one to the current number of rows
	$sql2 = "SELECT * FROM users";
	$result2 = $db->conn->query($sql2);
	$totRows = $result2->num_rows;
	
	//Since the name isn't in the system yet, add it: 
	$sql = $db->conn->prepare("INSERT INTO `users`(`user_id`, `index_num`) VALUES (?, ?)");
	$sql->bind_param('ss', $uid, $totRows);
	$sql->execute();

	if ($sql->get_result() === TRUE) {
		echo "<br/>"; // new line
		echo "Insertion successful";
		}
	else {
		echo "<br/>"; // new line 
		echo "Error accessing database: " . $db->conn->error;
		}
	
	//Now we need a table for this user so we know what games they've signed up for: 
	$sql = "";
	$sql = "CREATE TABLE gamelist_$totRows(game_id varchar(35) NOT NULL, PRIMARY KEY(game_id))";

	if ($db->conn->query($sql) === TRUE) {
		echo "<br/>"; // new line
		echo "Insertion successful";
		}
	else {
		echo "<br/>"; // new line 
		echo "Error accessing database: " . $db->conn->error;
		}
	}
else{
	$foundIndex = $check->get_index($uid);//This consults the user table to find which index number the user is
	
	//echo "The found index is: " . $foundIndex;
	//Use an else statement here to see what they are signed up for if they're in the system
	$sql = "";
	$sql = "SELECT * FROM markers INNER JOIN gamelist_$foundIndex ON markers.game_id=gamelist_$foundIndex.game_id;";
	
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
		}
}
}
else{
	echo "AUD not valid";
}
?>