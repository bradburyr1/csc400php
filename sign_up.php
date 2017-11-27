<?php
$response = array();

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connect to db
$db = new db_connect();
$gid = $_GET["gid"];
$uname = $_GET["uname"];
$role = $_GET["role"];

$sql = "INSERT INTO `attendees_$gid`(`user_id`, `role`) VALUES ('$uname', '$role')";

if ($db->conn->query($sql) === TRUE) {
	echo "<br/>"; // new line
    echo "Insertion successful";
	}
else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}
$sql = "INSERT INTO `gamelist_$uname`(`game_id`) VALUES ('$gid')";

if ($db->conn->query($sql) === TRUE) {
	echo "<br/>"; // new line
    echo "Insertion successful";
	}
else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}