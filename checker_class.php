<?php
// include db connect class
require_once __DIR__ . '/db_connect.php';

// connect to db


class InputChecker{
//The purpose of this program is to have methods other files can use
public $db = "";

function __construct() {
	$this->db = new db_connect();
}

/*
This method is used to make sure that the Game ID that is provided is one that is really a game id, and not something
like SQL Injection, to make sure it is OK to use since the game's lists of attendees use the ID in their table names. 
*/
function checkGID($gid){
	$sql = $this->db->conn->prepare("SELECT * FROM markers WHERE game_id = ?");
	$sql->bind_param('s', $gid);
	$sql->execute();
	$result1 = $sql->get_result();
	
	//Now we try to assign the result to FoundID
	$foundID = "";
	while($row = mysqli_fetch_assoc($result1)){
	$foundID = $row['game_id'];}
	//Now we check if the ID the query found, if any, are equal to the given ID
	if(strcmp($foundID,$gid) == 0){
		//echo "Found ID: ".$foundID;
	}
	else{//If the Game ID is not found in the database, throw it away
	echo "Found ID is NULL!";
	$gid = " ";}
	return $gid;
	}

	/*
	Since the User ID is their email address, which can be used for SQL Injection, their table of games they are 
	signed up for is identified using a unique "index number", which this method finds and returns. 
	*/
function get_index($uid){
	//Consult the users table to get the index number for this user id
	$sql3 = $this->db->conn->prepare("SELECT index_num FROM users WHERE user_id = ?");
	$sql3->bind_param('s', $uid);
	$sql3->execute();
	$result3 = $sql3->get_result();
	
	//Get the number from the result
	$foundIndex = "";
	while($row = mysqli_fetch_assoc($result3)){
	$foundIndex = $row['index_num'];}
	return $foundIndex;
}
}
?>