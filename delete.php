<?php
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
?>

<?php

//var_dump($_SESSION);
// remove all session variables
session_unset(); 

// destroy the session 
session_destroy(); 

//var_dump($_SESSION);

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

echo "Signed out.";
?>