<?php
 
//This file connects to the database
 
class DB_CONNECT {
 public $servername = "localhost";
	public $username = "root";
	public $password = "";
	public $dbname = "location_database";
	public $conn = "";
	
    function __construct() {
        // connect to database
        $this->connect();
		/*global $servername;
		$servername = "localhost";
	$this->username = "root";
	$this->password = "";
	$this->dbname = "location_database";*/
    }
 
 function __destruct() {
        // closing db connection
        $this->close();
    }
    function connect() {
		
        // import database connection variables
        //require_once __DIR__ . '/db_config.php';
 
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
	// Check connection
	if ($this->conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
 
        // return connection cursor
        return $this->conn;
		
		$this->conn -> close();
    }
 
    function close() {
        $this->conn->close();
    }
 
}
 
?>