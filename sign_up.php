<?php
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 	
?>

<?php
//This signs a user up for games
$response = array();
ini_set("allow_url_fopen", 1);

// include db connect class
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/checker_class.php';
$check = new InputChecker();

// connect to db
$db = new db_connect();
$gid = $_GET["gid"];
$uname = $_SESSION["userID"];
$role = $_GET["role"];

$gid = $check->checkGID($gid);//This method ensures GID is safe to use

//Now we can insert it into the game's table
//$sql = "INSERT INTO `attendees_$gid`(`user_id`, `role`) VALUES ('$uname', '$role')";

$sql = $db->conn->prepare("INSERT INTO `attendees_$gid`(`user_id`, `role`) VALUES (?, ?)");
$sql->bind_param('ss', $uname, $role);
$sql->execute();

if ($sql->get_result() === TRUE) {
	echo "<br/>"; // new line
    echo "Insertion successful";
	}
else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}
	
	$foundIndex = $check->get_index($uname);//This consults the user table to find which index number the user is
	
	//echo "Found Index Is: " . $foundIndex;
	//Insert into the user's record of games they're attending
	//$sql = "INSERT INTO `gamelist_$foundIndex`(`game_id`) VALUES ('$gid')";
	
	$sql = $db->conn->prepare("INSERT INTO `gamelist_$foundIndex`(`game_id`) VALUES (?)");
	$sql->bind_param('s', $gid);
	$sql->execute();
	$result = $sql->get_result();

if ($result === TRUE) {
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
	//$sql = "SELECT curr_signed FROM markers WHERE game_id = '$gid'";
	
	$sql = $db->conn->prepare("SELECT curr_signed FROM markers WHERE game_id = ?");
	$sql->bind_param('s', $gid);
	$sql->execute();
	
	$curr_signed = $sql->get_result();
	$row = $curr_signed->fetch_assoc();
	$curr_signed = $row["curr_signed"];
	$curr_signed = $curr_signed + 1;
	
	$sql = "";
	//$sql = "UPDATE markers SET curr_signed = '$curr_signed' WHERE game_id = $gid";
	$sql = $db->conn->prepare("UPDATE markers SET curr_signed = ? WHERE game_id = ?");
	$sql->bind_param('ss', $curr_signed, $gid);
	$sql->execute();
	
	if ($sql->get_result() === TRUE) {
	echo "<br/>"; // new line
    echo "Update successful";
	}
	else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}
	}
	
	if($role == 'referee'){
	//$sql = "SELECT curr_refs FROM markers WHERE game_id = '$gid'";
	
	$sql = $db->conn->prepare("SELECT curr_refs FROM markers WHERE game_id = ?");
	$sql->bind_param('s', $gid);
	$sql->execute();
	
	$curr_refs = $sql->get_result();
	$row = $curr_refs->fetch_assoc();
	$curr_refs = $row["curr_refs"];
	$curr_refs = $curr_refs + 1;
	
	$sql = "";
	//$sql = "UPDATE markers SET curr_refs = '$curr_refs' WHERE game_id = $gid";
	
	$sql = $db->conn->prepare("UPDATE markers SET curr_refs = ? WHERE game_id = ?");
	$sql->bind_param('ss', $curr_refs, $gid);
	$sql->execute();
	
	if ($sql->get_result() === TRUE) {
	echo "<br/>"; // new line
    echo "Update successful";
	}
	else {
	echo "<br/>"; // new line 
    echo "Error accessing database: " . $db->conn->error;
	}
	}