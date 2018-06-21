<?php
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 	
?>

<?php
//Allows the user to leave games 
$response = array();
ini_set("allow_url_fopen", 1);

// include db connect class
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/checker_class.php';
$check = new InputChecker();

// connect to db
$db = new db_connect();
$gid = $_GET["gid"];
$uid = $_SESSION["userID"];
$role = "";

$gid = $check->checkGID($gid);//This method ensures GID is safe to use

$sql = "";
//$sql = "SELECT * FROM attendees_$gid WHERE user_id = '$uid'";

$sql = $db->conn->prepare("SELECT * FROM `csc400db`.`attendees_$gid` WHERE user_id = ?");
$sql->bind_param('s', $uid);
$sql->execute();
$result = $sql->get_result();

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
	$sql = $db->conn->prepare("SELECT curr_signed FROM markers WHERE game_id = ?");
	$sql->bind_param('s', $gid);
	$sql->execute();
	
	$curr_signed = $sql->get_result();
	$row = $curr_signed->fetch_assoc();
	$curr_signed = $row["curr_signed"];
	$curr_signed = $curr_signed - 1;
	
	$sql = "";
	$sql = $db->conn->prepare("UPDATE markers SET curr_signed = ? WHERE game_id = ?");
	$sql->bind_param('ss', $curr_signed, $gid);
	$sql->execute();
	
	$curr_signed = $sql->get_result();
	
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
	$sql = $db->conn->prepare("SELECT curr_refs FROM markers WHERE game_id = ?");
	$sql->bind_param('s', $gid);
	$sql->execute();
	
	$curr_refs = $sql->get_result();
	$row = $curr_refs->fetch_assoc();
	$curr_refs = $row["curr_refs"];
	$curr_refs = $curr_refs - 1;
	
	$sql = "";
	$sql = $db->conn->prepare("UPDATE markers SET curr_refs = ? WHERE game_id = ?");
	$sql->bind_param('ss', $curr_refs, $gid);
	$sql->execute();
	
	$curr_signed = $sql->get_result();
	
	if ($db->conn->query($sql) === TRUE) {
	echo "<br/>"; // new line
    echo "Update successful";
	}
	else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}
	}

//$sql = "DELETE FROM attendees_$gid WHERE CONCAT(`attendees_$gid`.`user_id`) = '$uid'";

$sql = $db->conn->prepare("DELETE FROM `csc400db`.`attendees_$gid` WHERE CONCAT(`attendees_$gid`.`user_id`) = ?");
$sql->bind_param('s', $uid);
$sql->execute();

if ($sql->get_result() === TRUE) {
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
//$sql = "DELETE FROM `csc400db`.`gamelist_$uid` WHERE CONCAT(`gamelist_$uid`.`game_id`) = $gid";
//$sql = "DELETE FROM gamelist_$uid WHERE CONCAT(`gamelist_$uid`.`game_id`) = $gid";

	$foundIndex = $check->get_index($uid);//This consults the user table to find which index number the user is

$sql = "DELETE FROM `csc400db`.`gamelist_$foundIndex` WHERE CONCAT(`gamelist_$foundIndex`.`game_id`) = $gid";

if ($db->conn->query($sql) === TRUE) {
	echo "<br/>"; // new line
    echo "Deletion successful";
	}
else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}