<?php
//Start session and kick out if no credentials are present
session_start();
if(!isset($_SESSION["active"]) || $_SESSION["active"] != true || !isset($_SESSION["user"]))
{
    header("Location: index.php");
}
if(!isset($_SESSION["ticketedit"])) //This means no ticket was selected, go back to admin main page
{
    header("Location: admin.php");
}

include 'opendb.php';

// Fetch the ticket we are suppose to be looking at
$result = mysql_query("select Applications.ID, Applications.Received, Applications.Firstname, Applications.Lastname, Applications.Onyen, Applications.Email, Applications.PID, Applications.GPA, Applications.Advisor1, Applications.Advisor2, Applications.Term, Applications.A1Feedback, Applications.A2Feedback, Applications.Status, Applications.Approval from Applications where Applications.ID = '" . $_SESSION["ticketedit"] . "'");
$row = mysql_fetch_array($result);

$result1 = mysql_query("select Username, Access from Admins where Admins.Username = '" . $_SESSION["onyen"] . "'");
$row1 = mysql_fetch_array($result1);

// If we received a form, handle it!
if(isset($_POST["submit"]))
{
    if(!strcmp($_POST["submit"], "Decide/Withdraw")) //Toggle the status
    {
        if($row["Status"]) //Set to closed and mail to sender the result
        {
            mysql_query("update Applications set Status = 0 where Applications.ID = '" . addslashes(strip_tags($_SESSION["ticketedit"])) . "'");
			mail($row["Email"], "Your Application", "Your application has been resolved, thank you for your patience!");
			// mail($row["Email"], "Your Application: \"" . $row["ID"] . "\"", "Your application has been resolved, thank you for your patience!");
        }
        else //Set to open
            mysql_query("update Applications set Status = 1 where Applications.ID = '" . addslashes(strip_tags($_SESSION["ticketedit"])) . "'");
    }
    if(!strcmp($_POST["submit"], "Approve/Disapprove")){
        if($row["Approval"]) //Set to approved and mail to sender the result
        {
            mysql_query("update Applications set Approval = 0 where Applications.ID = '" . addslashes(strip_tags($_SESSION["ticketedit"])) . "'");
        }
        else //Set to approved
            mysql_query("update Applications set Approval = 1 where Applications.ID = '" . addslashes(strip_tags($_SESSION["ticketedit"])) . "'");
    }
    else if(!strcmp($_POST["submit"], "Unassign")) //Unassign yourself
        if(stripslashes($row["Advisor1"]) == addslashes(strip_tags($_SESSION["user"]))){
            mysql_query("update Applications set Applications.Advisor1 = NULL where Applications.Advisor1 = '" . addslashes(strip_tags($_SESSION["user"])) . "' and Applications.ID = '" . addslashes(strip_tags($_SESSION["ticketedit"])) . "'");
        } else {
            mysql_query("update Applications set Applications.Advisor2 = NULL where Applications.Advisor2 = '" . addslashes(strip_tags($_SESSION["user"])) . "' and Applications.ID = '" . addslashes(strip_tags($_SESSION["ticketedit"])) . "'");
        }
    else if(!strcmp($_POST["submit"], "Assign")) //Assign yourself
        if((stripslashes($row["Advisor1"])) == NULL){
            mysql_query("update Applications set Applications.Advisor1 = '" . addslashes(strip_tags($_SESSION["user"])) . "' where Applications.ID = '" . addslashes(strip_tags($_SESSION["ticketedit"])) . "'");
        } else {
            mysql_query("update Applications set Applications.Advisor2 = '" . addslashes(strip_tags($_SESSION["user"])) . "' where Applications.ID = '" . addslashes(strip_tags($_SESSION["ticketedit"])) . "'");
        }
    else if(!strcmp($_POST["submit"], "Email")) //Show the email form and quit
    {
        show_html("show_email", "");
        die();
    }
    else if(!strcmp($_POST["submit"], "Admin Home"))     //Link to admin home
        header("Location: admin.php");
    else if(!strcmp($_POST["submit"], "Post")) //Post Comments
        if(!isset($_POST['chk1']) || !isset($_POST['chk2']) || !isset($_POST['chk3'])) {
        echo 'Please answer all questions before submitting';
        } else {
        if(stripslashes($row["Advisor1"]) == addslashes(strip_tags($_SESSION["user"]))){
            mysql_query("update Applications set Applications.A1Feedback = '" . addslashes(strip_tags($_POST["comment"])) . "' where Applications.ID = '" . addslashes(strip_tags($_SESSION["ticketedit"])) . "'");
        } else {
            mysql_query("update Applications set Applications.A2Feedback = '" . addslashes(strip_tags($_POST["comment"])) . "' where Applications.ID = '" . addslashes(strip_tags($_SESSION["ticketedit"])) . "'");
        }
        }
}
if(isset($_POST["send"])) //If we are on the email form we will receive this instead, just mail, if error show page again
{
    if(!mail($_POST["to"], $_POST["subject"], $_POST["message"], "From: " . $_POST["from"]))
        show_html("show_email", "Error: failed to send email");
}

show_html("show_ticket", ""); //Show the default form

function show_ticket($error) //The default form, shows the one ticket and all the buttons for options
{
    //Get the ticket to display
    $result = mysql_query("select Applications.ID, Applications.Received, Applications.Firstname, Applications.Lastname, Applications.Onyen, Applications.Email, Applications.PID, Applications.GPA, Applications.Advisor1, Applications.Advisor2, Applications.Term, Uploads.UploadID, Uploads.File1, Uploads.File2, Uploads.File3, Applications.A1Feedback, Applications.A2Feedback, Applications.Status, Applications.Approval from Applications LEFT JOIN Uploads on Uploads.UploadID = Applications.ID where Applications.ID = '" . $_SESSION["ticketedit"] . "'");
    $row = mysql_fetch_array($result);


    $result1 = mysql_query("select Username, Access from Admins where Admins.Username = '" . $_SESSION["onyen"] . "'");
    $row1 = mysql_fetch_array($result1);

    //Show error if there is one
    if(isset($error) && strcmp($error, ""))
        echo $error . "<br />";

    //Build form

    echo "<form action='adminedit.php' method='POST'>
		<table id='table-student' class='table table-hover'>
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
			   <th>Term</th>
			   <th>Resume</th>
			   <th>Statement of Purpose</th>
			   <th>Transcript</th>
			   <th>A1 Feedback</th>
			   <th>A2 Feedback</th>
			   <th>Status</th>
			   <th>Approved</th>
			</tr>
	      		</thead>
		<tbody>";


		echo "<tr>
			<td>".stripslashes($row["ID"])."</td>
			<td>".stripslashes($row["Received"])."</td>
			<td>".stripslashes($row["Firstname"])." ".stripslashes($row["Lastname"])."</td>
			<td>".stripslashes($row["Onyen"])."</td>
			<td>".stripslashes($row["Email"])."</td>
			<td>".stripslashes($row["PID"])."</td>
			<td>".stripslashes($row["GPA"])."</td>
			<td>".stripslashes($row["Advisor1"])."</td>
			<td>".stripslashes($row["Advisor2"])."</td>
			<td>".stripslashes($row["Term"])."</td>";

    $id = stripslashes($row["UploadID"]);
    if(!empty($row["File1"])){
        echo "<td>"."<a href='download.php?file=1&id=$id'>Download</a></td>";
    } else {
        echo "<td>None</td>";
    }
    if(!empty($row["File2"])){
        echo "<td>"."<a href='download.php?file=2&id=$id'>Download</a></td>";
    } else {
        echo "<td>None</td>";
    }
    if(!empty($row["File3"])){
        echo "<td>"."<a href='download.php?file=3&id=$id'>Download</a></td>";
    } else {
        echo "<td>None</td>";
    }
    echo "<td>".stripslashes($row["A1Feedback"])."</td>";
    echo "<td>".stripslashes($row["A2Feedback"])."</td>";
    if(stripslashes($row["Status"]) == 1)
        echo "<td>Open</td>";
    else if(stripslashes($row["Status"]) == 0)
        echo "<td>Decided</td>";
    if(stripslashes($row["Approval"]) == 1)
        echo "<td>Yes</td></tr></tbody></table>";
    else if(stripslashes($row["Approval"]) == 0)
        echo "<td>No</td></tr></tbody></table>";

    echo "
		<table class='table' id='feedback-table'>
		<tbody>
		<tr>
		<div align='center' id='btn-group'>
    ";
    if($row1["Username"] == $_SESSION["onyen"] && $row1["Access"] == '1'){
    echo "
		<input type='submit' name='submit' value='Decide/Withdraw' class='btn btn-primary' />";
        echo	"<input type='submit' name='submit' value='Approve/Disapprove' class='btn btn-primary' />";
    }
    if(!strcmp(stripslashes($row["Advisor1"]), $_SESSION["user"]) || !strcmp(stripslashes($row["Advisor2"]), $_SESSION["user"]))
        echo "<input type='submit' name='submit' value='Unassign' class='btn btn-primary' />";
    else if(is_null($row["Advisor1"]) || is_null($row["Advisor2"]))
        echo "<input type='submit' name='submit' value='Assign' class='btn btn-primary' />";
    echo "<input type='submit' name='submit' value='Email' class='btn btn-primary'/>";
    echo "<input type='submit' name='submit' value='Admin Home' class='btn btn-primary' />";
    if($row1["Username"] == $_SESSION["onyen"] && $row1["Access"] == '0'){
    	echo "<a type='button' data-toggle='collapse' class='btn btn-primary' aria-expanded='false' href='#Feedback' aria-controls='Feedback'>Feedback</a>";
    }
    echo "
	</div></tr>
	<tr>
	<td colspan='12'>
	<div class='collapse' id='Feedback' align='center'>
		<table>
		<tr>
			<td class='blue'>Is this student capable of doing grad level work? </td>
			<td>	Yes <input name='chk1' value='Yes' type='radio' /> No <input name='chk1' value='No' type='radio' /> </td>
		</tr>
	    	<tr>
			<td class='blue'>Would you be willing to the advisor for student writing paper? </td>
			<td>	Yes <input name='chk2' value='Yes' type='radio' /> No <input name='chk2' value='No' type='radio' /></td>
		</tr>
		<tr>
		    	<td class='blue'>Willing to have student as TA? If yes which course? </td>
			<td>	Yes <input name='chk3' value='Yes' type='radio' /> No <input name='chk3' value='No' type='radio' /></td>
		</tr>
		<tr>
			<td><div class='blue' id='comments-label'>Other Comments</div></td>
			<td><textarea name='comment' id='comment'></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td><div align='center'><input type='submit' name='submit' value='Post' id='post-btn'/></div></td>
		</tr>
		</table>
	</div>
	</td>
	</tr>
</tbody>
</table>
</form>";
}
function show_email($error) //This will show the email form
{
    //Show error if there is one
    if(isset($error) && strcmp($error, ""))
        echo $error . "<br />";

    //Show the form with auto filled from and to boxes.
    echo "<form method='POST' action='adminedit.php' id='email-form'><table class='table' id='email-table'>";
    $result = mysql_query("select Applications.Email from Applications where Applications.ID = '" . $_SESSION["ticketedit"] . "'");
    $row = mysql_fetch_array($result);
    echo "<tr><td>To:</td><td><input type='text' name='to' value='" . stripslashes($row["Email"]) . "' /></td></tr>";
    $result = mysql_query("select Email from Admins where Username = '" . $_SESSION["user"] . "'");
    $row = mysql_fetch_array($result);
    echo "<tr><td>From:</td><td><input type='text' name='from' value='" .stripslashes($row["Email"]) . "' /></td></tr>";
    echo "<tr><td>Subject:</td><td><input type='text' name='subject' /></td></tr>";
    echo "<tr><td>Message:</td></tr>";
    echo "<tr><td><textarea name='message' cols=40 rows=6></textarea></td></tr>";
    echo "<tr><td><input type='submit' name='send' value='Send' /></td></tr></table></form>";
}
function show_html($object, $error) //The basic html for all this.
{
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Computer Science BS/MS Application</title>
		<link rel="stylesheet" type="text/css" href="Resources/bootstrap/css/theme.min.css">
		<link rel="stylesheet" type="text/css" href="Resources/main.css">
		<script src="Resources/jquery-1.11.2.min.js"></script>
		<script src="Resources/bootstrap/js/bootstrap.min.js"></script>
        	<script src="script.js"></script>
	</head>
	<body>
	<div class="container" id="individual-student">
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
		<div class="well wider">
			<?php call_user_func($object, "") //Calls the proper object to be shown ?>
		</div><!--well-->
	</div><!--container-->
	</body>
</html>
<?php
}
?>
