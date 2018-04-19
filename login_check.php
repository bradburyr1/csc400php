<?php//If the user is not in the system, add them in, and if they are, return the games they are signed up for. 
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

$sql = "SELECT * FROM users WHERE user_id = '$uid'";

$result = $db->conn->query($sql);

$num_rows = $result->num_rows;//Get a count for the number of rows, to check for existance of user id

if($num_rows==0){
	/* while($row = mysqli_fetch_assoc($result)){
	echo $row['user_id'];}
	echo "\nresult is zero, ".count($result); */
	
	//Since the name isn't in the system yet, add it: 
	$sql = "INSERT INTO `users`(`user_id`) VALUES ('$uid')";

if ($db->conn->query($sql) === TRUE) {
	echo "<br/>"; // new line
    echo "Insertion successful";
	}
else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}
	
	//Now we need a table for this user so we know what games they've signed up for: 
	$sql = "";
$sql = "CREATE TABLE gamelist_$uid(game_id varchar(35) NOT NULL, PRIMARY KEY(game_id))";

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
	/* while($row = mysqli_fetch_assoc($result)){
	echo $row['user_id'];}
	echo "\nnot zero, ".count($result); */
	
	//Use an else statement here to see what they are signed up for if they're in the system
	
	$sql = "";
	$sql = "SELECT * FROM markers INNER JOIN gamelist_$uid ON markers.game_id=gamelist_$uid.game_id;";
	
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
} else {
    $response["success"] = 0;
    $response["message"] = "No markers found";
 
    echo json_encode($response);
}
}
?>