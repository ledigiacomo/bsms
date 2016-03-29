<?php
session_start();
if(!isset($_SESSION['onyen'])){
header("Location: login.php");
}
include 'opendb.php';
$result = mysql_query("select Username, Access from Admins where Admins.Username = '" . $_SESSION["onyen"] . "'");
$result1 = mysql_query("update Admins set Admins.Access = 1 where Admins.Username = '" . $_SESSION["onyen"] . "'");
$result2 = mysql_query("update Admins set Admins.Access = 0 where Admins.Username = '" . $_SESSION["onyen"] . "'");
$result3 = mysql_query("insert into Admins (username, access) values('" . $_SESSION["onyen"] . "', 0)");
$result4 = mysql_query("delete from Admins where where Admins.Username = '" . $_SESSION["onyen"] . "'");
$row = mysql_fetch_array($result);
$row1 = mysql_fetch_array($result1);
$row2 = mysql_fetch_array($result2);
$row3 = mysql_fetch_array($result3);
$row4 = mysql_fetch_array($result4);
    //Add admin
    if($row['Access'] == '1'){
        echo '<b>Add admin</b><br />';
	echo 'true<br />';
    } else {
        echo '<b>Add admin</b><br />';
        echo 'false<br />';
    }
    //Remove admin
    if($row['Access'] == '0'){
        echo '<b>Remove admin</b><br />';
	echo 'true<br />';
    } else {
        echo '<b>Remove admin</b><br />';
        echo 'false<br />';
    }
    //Add faculty member
    if($row['Access'] == '0'){
        echo '<b>Add faculty member</b><br />';
	echo 'true<br />';
    } else {
        echo '<b>Add faculty member</b><br />';
        echo 'false<br />';
    }
    //Remove faculty member
    if($row['Access'] != '0'){
        echo '<b>Remove faculty member</b><br />';
	echo 'true<br />';
    } else {
        echo '<b>Remove faculty member</b><br />';
        echo 'false<br />';
    }
?>
