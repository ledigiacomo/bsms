<?php
//Start session
session_start();
if(!isset($_SESSION["active"]) || $_SESSION["active"] != true || !isset($_SESSION["user"])) //Kick if not suppose to be here
{
	header("location: index.php");
} else if(!strcmp($_POST["submit"], "Log Out"))	//Log out by destroying session and going to home page
{
	header("Location: index.php");
	unset($_SESSION['active']);
	if(isset($_SESSION["admin"])){
		unset($_SESSION['admin']);
	}
} else if(!strcmp($_POST["submit"], "View Selected Application")) //View the selected application
{
	$_SESSION["ticketedit"] = $_POST["choice"]; //Store the application to look at in a session
	header("Location: adminedit.php"); //Kick over to the application viewer
}

if(isset($_SESSION["admin"])){
function show_Applications($all, $my, $unassigned, $sort) //This function takes 3 bools, all, my, and unassigned and then sorts it
{
	include 'opendb.php';

	//If we received a form process it
	if(isset($_POST["submit"]))
	{
		// What these series of ifs do is flip the bools if they were triggered or set the sort, plus any other buttons
		if(!strcmp($_POST["submit"], "View Open Applications") || !strcmp($_POST["submit"], "View All Applications"))		//If view all/open application button is selected
			$_SESSION["all"] = !$_SESSION["all"];
		else if(!strcmp($_POST["submit"], "Sort")) //Change the sort
			$_SESSION["sort"] = $_POST["sort"];
		else if(!strcmp($_POST["submit"], "View Other Applications") || !strcmp($_POST["submit"], "View My Applications"))	//View other/my application is selected
			$_SESSION["my"] = !$_SESSION["my"];
		else if(!strcmp($_POST["submit"], "View Assigned Applications") || !strcmp($_POST["submit"], "View Unassigned Applications") )		//View unassigned/assigned Applications
			$_SESSION["unassigned"] = !$_SESSION["unassigned"];
	}
	else	// First time visiting set the initial views and sort to default
	{
		$_SESSION["all"] = false;
		$_SESSION["my"] = false;
		$_SESSION["unassigned"] = false;
		$_SESSION["sort"] = "";
	}

	//This block is the corresponding SQL statement to the pattern of bits set
	if(!$_SESSION["all"] && !$_SESSION["my"] && !$_SESSION["unassigned"]) // Default Home View Done
        $query = "select Applications.ID, Applications.Received, Applications.Firstname, Applications.Lastname, Applications.Onyen, Applications.Email, Applications.PID, Applications.GPA, Applications.Advisor1, Applications.Advisor2, Applications.Status from Applications where Applications.Status = 1";
    else if(!$_SESSION["all"] && !$_SESSION["my"] && $_SESSION["unassigned"]) // Only Unassigned Open Applications Done
        $query = "select Applications.ID, Applications.Received, Applications.Firstname, Applications.Lastname, Applications.Onyen, Applications.Email, Applications.PID, Applications.GPA, Applications.Advisor1, Applications.Advisor2, Applications.Status from Applications where Applications.Status = 1 and (Applications.Advisor1 is NULL OR Applications.Advisor2 is NULL)";
    else if(!$_SESSION["all"] && $_SESSION["my"] && !$_SESSION["unassigned"]) // Mine Done
        $query = "select Applications.ID, Applications.Received, Applications.Firstname, Applications.Lastname, Applications.Onyen, Applications.Email, Applications.PID, Applications.GPA, Applications.Advisor1, Applications.Advisor2, Applications.Status from Applications LEFT JOIN Admins on Admins.Username IN (Applications.Advisor1, Applications.Advisor2) where Applications.Status = 1 and Admins.Username = '" . $_SESSION["user"] . "'";
    else if(!$_SESSION["all"] && $_SESSION["my"] && $_SESSION["unassigned"]) // Will return no results
        $query = "select Applications.ID, Applications.Received, Applications.Firstname, Applications.Lastname, Applications.Onyen, Applications.Email, Applications.PID, Applications.GPA, Applications.Advisor1, Applications.Advisor2, Applications.Status from Applications where Applications.Status = 1 and (Applications.Advisor1 is NULL OR Applications.Advisor2 is NULL) and Admins.Username = '" . $_SESSION["user"] . "'";
    else if($_SESSION["all"] && !$_SESSION["my"] && !$_SESSION["unassigned"]) // All Applications Done
        $query = "select Applications.ID, Applications.Received, Applications.Firstname, Applications.Lastname, Applications.Onyen, Applications.Email, Applications.PID, Applications.GPA, Applications.Advisor1, Applications.Advisor2, Applications.Status from Applications";
    else if($_SESSION["all"] && !$_SESSION["my"] && $_SESSION["unassigned"]) // All Unassigned Applications Done
        $query = "select Applications.ID, Applications.Received, Applications.Firstname, Applications.Lastname, Applications.Onyen, Applications.Email, Applications.PID, Applications.GPA, Applications.Advisor1, Applications.Advisor2, Applications.Status from Applications where Applications.Advisor1 is NULL OR Applications.Advisor2 is NULL";
    else if($_SESSION["all"] && $_SESSION["my"] && !$_SESSION["unassigned"]) // All and mine Done
        $query = "select Applications.ID, Applications.Received, Applications.Firstname, Applications.Lastname, Applications.Onyen, Applications.Email, Applications.PID, Applications.GPA, Applications.Advisor1, Applications.Advisor2, Applications.Status from Applications LEFT JOIN Admins on Admins.Username IN (Applications.Advisor1, Applications.Advisor2) where Admins.Username = '" . $_SESSION["user"] . "'";
    else if($_SESSION["all"] && $_SESSION["my"] && $_SESSION["unassigned"]) // Will return no results
        $query = "select Applications.ID, Applications.Received, Applications.Firstname, Applications.Lastname, Applications.Onyen, Applications.Email, Applications.PID, Applications.GPA, Applications.Advisor1, Applications.Advisor2, Applications.Status from Applications LEFT JOIN Admins on Admins.Username IN (Applications.Advisor1, Applications.Advisor2) where Admins.Username is NULL and Admins.Username = '" . $_SESSION["user"] . "'";

	//This block tacks on the sort to the end of the SQL statement
	if(!strcmp($_SESSION["sort"], "date"))
		$query = $query . " order by Applications.Received";
	else if(!strcmp($_SESSION["sort"], "name"))
		$query = $query . " order by Applications.Firstname, Applications.Lastname";
	else if(!strcmp($_SESSION["sort"], "onyen"))
		$query = $query . " order by Applications.Onyen";
	else if(!strcmp($_SESSION["sort"], "email"))
		$query = $query . " order by Applications.Email";

	//Execute query on db
	$results = mysql_query($query);
	$num_rows = mysql_num_rows($results);

	//Show results!
	echo "<form action='admin.php' method='POST'>
	      <table class='table table-hover'>";
	echo "<thead>
		<tr>
		   <th>Application #</th>
		   <th>Received</th>
		   <th>Full Name</th>
		   <th>Onyen</th>
		   <th>Email</th>
		   <th>PID</th>
		   <th>GPA</th>
		   <th>Advisor 1</th>
		   <th>Advisor 2</th>
		   <th>Status</th>
		   <th>Select</th>
		</tr>
	      </thead>
	      <tbody>";
	for($i = 0; $i < $num_rows; $i++)
	{
		$row = mysql_fetch_array($results);
		echo "<tr>
				<td>".stripslashes($row["ID"])."</td>
				<td>".stripslashes($row["Received"])."</td>
				<td>".stripslashes($row["Firstname"])." ".stripslashes($row["Lastname"])."</td>
				<td>".stripslashes($row["Onyen"])."</td>
				<td>".stripslashes($row["Email"])."</td>
				<td>".stripslashes($row["PID"])."</td>
				<td>".stripslashes($row["GPA"])."</td>
				<td>".stripslashes($row["Advisor1"])."</td>
				<td>".stripslashes($row["Advisor2"])."</td>";
		if(stripslashes($row["Status"]) == 1)
			echo "<td>Open</td>";
		else
			echo "<td>Closed</td>";
		echo "<td><input type='radio' name='choice' value='".stripslashes($row["ID"])."' /></td></tr>";

	}
	echo "<tr>
		<td></td>
		<td>Sort By <input type='radio' name='sort' value='date' /></td>
		<td>Sort By <input type='radio' name='sort' value='name' /></td>
		<td>Sort By <input type='radio' name='sort' value='onyen' /></td>
		<td>Sort By <input type='radio' name='sort' value='email' /></td>
	      </tr>
	</tbody>
	</table>";

	//This is the table for the buttons, the ifs are for the toggling of the button names
	if($_SESSION["all"]) { $allsubmit = "View Open Applications"; } else { $allsubmit = "View All Applications"; }
	echo "<br /><input class='btn btn-primary' type='submit' name='submit' value='" . $allsubmit . "' />";
	echo "<input class='btn btn-primary' type='submit' name='submit' value='Sort' />";
	echo "<input class='btn btn-primary' type='submit' name='submit' value='View Selected Application' />";
	if($_SESSION["my"]) { $mysubmit = "View Other Applications"; } else { $mysubmit = "View My Applications"; }
	echo "<input class='btn btn-primary' type='submit' name='submit' value='" . $mysubmit . "' />";
	if($_SESSION["unassigned"]) { $unassignedsubmit = "View Assigned Applications"; } else { $unassignedsubmit = "View Unassigned Applications"; }
	echo "<input class='btn btn-primary' type='submit' name='submit' value='" . $unassignedsubmit . "' />

	</form>";
}
} else {
function showAdvisor_Applications($all, $my, $sort) //This function takes 1 bool, 'my' and then sorts it
{
	include 'opendb.php';

	//If we received a form process it
	if(isset($_POST["submit"]))
	{
		if(!strcmp($_POST["submit"], "View Open Applications") || !strcmp($_POST["submit"], "View All Applications"))		//If view all/open application button is selected
			$_SESSION["all"] = !$_SESSION["all"];
		else if(!strcmp($_POST["submit"], "Sort")) //Change the sort
			$_SESSION["sort"] = $_POST["sort"];
		else if(!strcmp($_POST["submit"], "View My Applications")) //View my application is selected
			$_SESSION["my"] = !$_SESSION["my"];
	}
	else	// First time visiting set the initial views and sort to default
	{
		$_SESSION["all"] = false;
		$_SESSION["my"] = true;
		$_SESSION["sort"] = "";
	}

	//This block is the corresponding SQL statement to the pattern of bits set
	if(!$_SESSION["all"] && $_SESSION["my"]) // All Applications Done
        $query = "select Applications.ID, Applications.Received, Applications.Firstname, Applications.Lastname, Applications.Onyen, Applications.Email, Applications.PID, Applications.GPA, Applications.Advisor1, Applications.Advisor2, Applications.Status from Applications LEFT JOIN Admins on Admins.Username IN (Applications.Advisor1, Applications.Advisor2) where Admins.Username = '" . $_SESSION["user"] . "' AND Applications.Status = 1";
	else if($_SESSION["all"] && $_SESSION["my"]) // All Closed Applications
        $query = "select Applications.ID, Applications.Received, Applications.Firstname, Applications.Lastname, Applications.Onyen, Applications.Email, Applications.PID, Applications.GPA, Applications.Advisor1, Applications.Advisor2, Applications.Status from Applications LEFT JOIN Admins on Admins.Username IN (Applications.Advisor1, Applications.Advisor2) where Admins.Username = '" . $_SESSION["user"] . "'";

	//This block tacks on the sort to the end of the SQL statement
	if(!strcmp($_SESSION["sort"], "date"))
		$query = $query . " order by Applications.Received";
	else if(!strcmp($_SESSION["sort"], "name"))
		$query = $query . " order by Applications.Firstname, Applications.Lastname";
	else if(!strcmp($_SESSION["sort"], "Onyen"))
		$query = $query . " order by Applications.Onyen";
	else if(!strcmp($_SESSION["sort"], "email"))
		$query = $query . " order by Applications.Email";

	//Execute query on db
	$results = mysql_query($query);
	$num_rows = mysql_num_rows($results);

	//Show results!
	echo "<form action='admin.php' method='POST'>
		<table class='table table-hover'>
			<thead>
			<tr>
			   <th>Application #</th>
			   <th>Received</th>
			   <th>Full Name</th>
			   <th>Onyen</th>
			   <th>Email</th>
			   <th>PID</th>
			   <th>GPA</th>
			   <th>Advisor 1</th>
			   <th>Advisor 2</th>
			   <th>Status</th>
			   <th>Select</th>
			</tr>
	      		</thead>
		<tbody>";

	for($i = 0; $i < $num_rows; $i++)
	{
		$row = mysql_fetch_array($results);
		echo "<tr>
			<td>".stripslashes($row["ID"])."</td>
			<td>".stripslashes($row["Received"])."</td>
			<td>".stripslashes($row["Firstname"])." ".stripslashes($row["Lastname"])."</td>
			<td>".stripslashes($row["Onyen"])."</td>
			<td>".stripslashes($row["Email"])."</td>
			<td>".stripslashes($row["PID"])."</td>
			<td>".stripslashes($row["GPA"])."</td>
			<td>".stripslashes($row["Advisor1"])."</td>
			<td>".stripslashes($row["Advisor2"])."</td>";
		if(stripslashes($row["Status"]) == 1)
			echo "<td>Open</td>";
		else
			echo "<td>Decided</td>";
		echo "<td><input type='radio' name='choice' value='".stripslashes($row["ID"])."' /></td></tr>";
	}
	echo "<tr>
		<td></td>
		<td>Sort By <input type='radio' name='sort' value='date' /></td>
		<td>Sort By <input type='radio' name='sort' value='name' /></td>
		<td>Sort By <input type='radio' name='sort' value='onyen' /></td>
		<td>Sort By <input type='radio' name='sort' value='email' /></td>
	      </tr>
	</tbody>
	</table>";

	//This is the table for the buttons, the ifs are for the toggling of the button names
	if($_SESSION["all"]) { $allsubmit = "View Open Applications"; } else { $allsubmit = "View All Applications"; }
	echo "<input class='btn btn-primary' type='submit' name='submit' value='" . $allsubmit . "' />";
	echo "<input class='btn btn-primary' type='submit' name='submit' value='Sort' /></a>";
	echo "<input class='btn btn-primary' type='submit' name='submit' value='View Selected Application' />";
	echo "<input class='btn btn-primary' type='submit' name='submit' value='Log Out' />";
}
}
//Show the html!
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Computer Science BS/MS Application</title>
		<link rel="stylesheet" type="text/css" href="Resources/bootstrap/css/theme.min.css">
		<link rel="stylesheet" type="text/css" href="Resources/main.css">
		<script src="Resources/jquery-1.11.2.min.js"></script>
		<script src="Resources/bootstrap/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container">
	    	<div class="header">
		       <!-- <a href="http://www.unc.edu/"><img class="logo" src="Resources/images/OldWell.png"></a>-->
		        <ul class="nav nav-pills navbar-right">
		          <li><a href="index.php">Home</a></li>
		          <li><a href="faq.html">FAQ</a></li>
		          <li><a href="logout.php">Log Off</a></li>
		        </ul>
	    	</div>
		<h1>Welcome to the <span class="blue"></br>Computer </br>Science</span> </br>BS/MS Application</h1>
		<br />
		<div class="well">
			<?php
			if(isset($_SESSION["admin"])){
			?>
			<div align="center"><?php show_Applications($_SESSION["all"], $_SESSION["my"], $_SESSION["unassigned"], $_SESSION["sort"])?></div>
			<?php
			} else {
			?>
			<div align="center"><?php showAdvisor_Applications($_SESSION["all"], $_SESSION["my"], $_SESSION["sort"])?></div>
			<?php
			}
			?>
		</div><!--well-->
		</div> <!--container-->
	</body>
</html>
