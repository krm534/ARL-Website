<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

if (empty($_SESSION["email"]) == true) {
    print("false");
} else {
    print("true");
}
?>