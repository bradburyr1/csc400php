<?php
$response = array();

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connect to db
$db = new db_connect();
$gid = $_GET["gid"];

#$sql = "INSERT INTO `attendees_$gid`(`user_id`, `role`) VALUES ('$uname', '$role')";

$sql = "DROP TABLE attendees_$gid";

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
$sql = "DELETE FROM `csc400db`.`markers` WHERE CONCAT(`markers`.`game_id`) = $gid";

if ($db->conn->query($sql) === TRUE) {
	echo "<br/>"; // new line
    echo "Deletion successful";
	}
else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}
	