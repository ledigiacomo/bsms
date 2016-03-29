<?php
session_start();
if(!isset($_SESSION['onyen'])) {
    header("Location: login.php");
}
if(isset($_GET['id']))
{
// if id is set then get the file with the id from database

include 'opendb.php';

$id    = $_GET['id'];
$file  = $_GET['file'];

$query = "SELECT FileName$file, File$file " .
         "FROM Uploads WHERE UploadID = '$id'";

$result = mysql_query($query) or die('Error, query failed');
list($fn, $data) = mysql_fetch_array($result);

header("Content-Disposition: attachment; filename=$fn");
echo $data;

mysql_close($connection);
die;
}
?>
