<?php//This signs a user up for games
$response = array();

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connect to db
$db = new db_connect();
$gid = $_GET["gid"];
$uname = $_GET["uname"];
$role = $_GET["role"];

//Insert into the owner's table
$sql = "INSERT INTO `attendees_$gid`(`user_id`, `role`) VALUES ('$uname', '$role')";

if ($db->conn->query($sql) === TRUE) {
	echo "<br/>"; // new line
    echo "Insertion successful";
	}
else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}
	
//Insert into the user's record of games they're attending
$sql = "INSERT INTO `gamelist_$uname`(`game_id`) VALUES ('$gid')";

if ($db->conn->query($sql) === TRUE) {
	echo "<br/>"; // new line
    echo "Insertion successful";
	}
else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}
	
	//This next part iterates the current player or referee entree
	$curr_signed = "";
	$curr_refs = "";
	
	$sql = "";
if($role == 'player'){
	$sql = "SELECT curr_signed FROM markers WHERE game_id = '$gid'";
	
	$curr_signed = $db->conn->query($sql);
	$row = $curr_signed->fetch_assoc();
	$curr_signed = $row["curr_signed"];
	$curr_signed = $curr_signed + 1;
	
	$sql = "";
	$sql = "UPDATE markers SET curr_signed = '$curr_signed' WHERE game_id = $gid";
	
	$curr_signed = $db->conn->query($sql);
	
	if ($db->conn->query($sql) === TRUE) {
	echo "<br/>"; // new line
    echo "Update successful";
	}
	else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}
	}
	
	if($role == 'referee'){
	$sql = "SELECT curr_refs FROM markers WHERE game_id = '$gid'";
	
	$curr_refs = $db->conn->query($sql);
	$row = $curr_refs->fetch_assoc();
	$curr_refs = $row["curr_refs"];
	$curr_refs = $curr_refs + 1;
	
	$sql = "";
	$sql = "UPDATE markers SET curr_refs = '$curr_refs' WHERE game_id = $gid";
	
	$curr_signed = $db->conn->query($sql);
	
	if ($db->conn->query($sql) === TRUE) {
	echo "<br/>"; // new line
    echo "Update successful";
	}
	else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}
	}