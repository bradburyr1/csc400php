<?php
$response = array();

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connect to db
$db = new db_connect();
$sport = $_GET["sport"];
$city = $_GET["city"];
$time = $_GET["time"];
$postalAddress = $_GET["address"];
$date = $_GET["date"];
$comp = $_GET["comp"];
$lat = $_GET["lat"];
$long = $_GET["long"];
$uid = $_GET["uid"];

$gid = rand(0, 1000000);//Create a random number for use as the game's ID. This is obviously a temporary solution, with the potential for conflicts. 

//gcp:
$sql = "INSERT INTO `csc400db`.`markers` (`game_id`, `latitude`, `longitude`, `title`, `address`, `time`, `date`, `comp_level`, `postalAddress`, `user_id`) 
VALUES ('$gid', '$lat', '$long', '$sport', '$city', '$time', '$date', '$comp', '$postalAddress', '$uid');";

//local:
/* $sql = "INSERT INTO `location_database`.`markers` (`game_id`, `latitude`, `longitude`, `title`, `address`, `time`, `date`, `comp_level`, `postalAddress`, `user_id`) 
VALUES ('$gid', '$lat', '$long', '$sport', '$city', '$time', '$date', '$comp', '$postalAddress', '$uid');"; */

//$result = $db->conn->query($sql);
if ($db->conn->query($sql) === TRUE) {
	echo "<br/>"; // new line
    echo "Insertion successful";
} else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
}