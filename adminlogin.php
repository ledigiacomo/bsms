<?php
session_start();	
$_SESSION["active"] = true; //Session is set

if(isset($_SESSION["active"]) && isset($_SESSION["user"])) //If you come to the page with an active session and you are an admin, redirect to main page
{
	header("location: admin.php");
}
else //Redirect back to login page if you are not authenticated
{
	header("location: index.php");
}
?>