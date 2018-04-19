<?php
//Allows the user to leave games 
$response = array();

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connect to db
$db = new db_connect();
$gid = $_GET["gid"];
$uid = $_GET["uid"];
$role = "";

$sql = "";
$sql = "SELECT * FROM `csc400db`.`attendees_$gid` WHERE user_id = '$uid'";

$result = $db->conn->query($sql);

$row = mysqli_fetch_assoc($result);
echo "<br/>";
$role = $row['role'];

if ($result === TRUE) {
	echo "<br/>"; // new line
    echo "Deletion successful";
	}
else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}

if($role == 'player'){
	$sql = "SELECT curr_signed FROM markers WHERE game_id = '$gid'";
	
	$curr_signed = $db->conn->query($sql);
	$row = $curr_signed->fetch_assoc();
	$curr_signed = $row["curr_signed"];
	$curr_signed = $curr_signed - 1;
	
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
	$curr_refs = $curr_refs - 1;
	
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

$sql = "DELETE FROM `csc400db`.`attendees_$gid` WHERE CONCAT(`attendees_$gid`.`user_id`) = '$uid'";

if ($db->conn->query($sql) === TRUE) {
	echo "<br/>"; // new line
    echo "Deletion successful";
	}
else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}

$sql = "";
#Localhost
#$sql = "DELETE FROM `location_database`.`markers` WHERE CONCAT(`markers`.`game_id`) = $gid";

#GCP
$sql = "DELETE FROM `csc400db`.`gamelist_$uid` WHERE CONCAT(`gamelist_$uid`.`game_id`) = $gid";

if ($db->conn->query($sql) === TRUE) {
	echo "<br/>"; // new line
    echo "Deletion successful";
	}
else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}