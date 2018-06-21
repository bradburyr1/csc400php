<?php
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 	
?>

<?php
//This creates games by adding them into the database and giving them attendee tables 
$response = array();
ini_set("allow_url_fopen", 1);

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
$uid = $_SESSION["userID"];
$players = $_GET["players"];
$refs = $_GET["refs"];

/*$gid = rand(0, 1000000);//Create a random number for use as the game's ID. If this app were to go on the app store I 
//would need to add a bit more to prevent conflicts.*/
//We need to give the new game a game id, which is simply adding one to the current number of rows
	$sql2 = "SELECT * FROM markers";
	$result2 = $db->conn->query($sql2);
	$gid = $result2->num_rows;

//gcp:
$sql = $db->conn->prepare("INSERT INTO `csc400db`.`markers` (`game_id`, `latitude`, `longitude`, `title`, `address`, `time`, `date`, `comp_level`, `postalAddress`, `user_id`, `curr_signed`, `curr_refs`, `max_signed`, `max_refs`) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '0', '0', ?, ?);");

//local:
/*$sql = $db->conn->prepare("INSERT INTO `location_database`.`markers` (`game_id`, `latitude`, `longitude`, `title`, `address`, `time`, `date`, `comp_level`, `postalAddress`, `user_id`, `max_signed`, `max_refs`)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '0', '0', ?, ?);");*/

$sql->bind_param('ssssssssssss', $gid, $lat, $long, $sport, $city, $time, $date, $comp, $postalAddress, $uid, $players, $refs);
$sql->execute();

//$result = $db->conn->query($sql);
if ($sql->get_result() === TRUE) {
	echo "<br/>"; // new line
    echo "Insertion successful";
	}
else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}

$sql = "";
$sql = "CREATE TABLE attendees_$gid(user_id varchar(35) NOT NULL, role varchar(15) NOT NULL, PRIMARY KEY(user_id))";

if ($db->conn->query($sql) === TRUE) {
	echo "<br/>"; // new line
    echo "Insertion successful";
	}
else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}