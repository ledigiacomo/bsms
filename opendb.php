<?php
//Connect to db
$connection = mysql_connect("localhost", "root", "BigTunaFish88");
	if(!$connection)
		die("Could not connect to db " . mysql_error());
	mysql_select_db("BSMSdb") or die("Error: Couldn't select db " . mysql_error());
?>
