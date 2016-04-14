<?php
session_start();
if(!isset($_SESSION['onyen'])) {
    header("Location: login.php");
}
if(isset($_POST["submit"]))
{
	//Check to be sure there are no blank fields, and show error if we do
	if (empty($_POST["firstname"]) || empty($_POST["lastname"]) || empty($_POST["email"]) || empty($_POST["pid"]) || empty($_POST["gpa"]) || empty($_POST["advisor1"]) || empty($_POST["advisor2"]) || empty($_POST["term"]))
	{
		showForm("Please enter all information.");
		die();
	} else if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
		showForm("Please enter a valid email address.");
		die();
	} else if(!preg_match('/^[0-9]{9}$/', $_POST["pid"])){
		showForm("PID must be nine-digits long.");
		die();
	} else if(!preg_match('/([0-9]{1,})\.([0-9]{2,2})/', $_POST['gpa'])) {
		showForm("GPA must be in #.## format.");
		die();
	} else if($_POST["advisor1"] == $_POST["advisor2"]){
		showForm("Advisor 1 and Advisor 2 cannot be the same.");
		die();
	} else if(empty($_FILES['file1']['name'])) {
		showForm("Please upload your resume to the application.");
		die();
  } else if($_FILES['file1']['type'] != "application/pdf") {
    showForm("Please upload the PDF version.");
		die();
	} else if(empty($_FILES['file2']['name'])) {
		showForm("Please upload your Statement of Purpose to the application.");
		die();
  } else if($_FILES['file2']['type'] != "application/pdf") {
    showForm("Please upload the PDF version.");
		die();
	} else if(empty($_FILES['file3']['name'])) {
		showForm("Please upload your Transcript to the application.");
		die();
  } else if($_FILES['file3']['type'] != "application/pdf") {
    showForm("Please upload the PDF version.");
		die();
	} else {

	include 'opendb.php';

		//Insert the application in the db
		mysql_query("insert into Applications values(NULL, NOW(), '" .
			addslashes(strip_tags($_POST["firstname"])) .
			"', '" . addslashes(strip_tags($_POST["lastname"])) .
			"', '" . addslashes(strip_tags($_SESSION["onyen"])) .
			"', '" . addslashes(strip_tags($_POST["email"])) .
			"', '" . addslashes(strip_tags($_POST["pid"])) .
			"', '" . addslashes(strip_tags($_POST["gpa"])) .
			"', '" . addslashes(strip_tags($_POST["advisor1"])) .
			"', '" . addslashes(strip_tags($_POST["advisor2"])) .
			"', '" . addslashes(strip_tags($_POST["term"])) . "', '', '', 1, 0)")
			or die("Error: Could not insert into table " . mysql_error());

		$result1 = mysql_query("select Username, Email, Access from Admins where Admins.Username = '" . $_POST["advisor1"] . "'");
		$row1 = mysql_fetch_array($result1);

		$result2 = mysql_query("select Username, Email, Access from Admins where Admins.Username = '" . $_POST["advisor2"] . "'");
		$row2 = mysql_fetch_array($result2);

		//Mail the submitter
		mail($_POST["email"], "Successful", "Hi, ".$_POST['firstname']."! Thank you for applying to the BSMS Program at UNC Chapel Hill. You will be contacted when a decision is made. \n\n Until then please feel free to log back in to periodically check the status of your application. Best of luck! \n\nThis is an automated message, please do not respond.");
		//Mail the Advisor 1
	mail($row1["Email"], "Successful", "" .$_POST['firstname']. " has applied to the BSMS program and listed you as an advisor. \n\nThis is an automated message, please do not respond.");
		//Mail the Advisor 2
		mail($row2["Email"], "Successful", "".$_POST['firstname']. " has applied to the BSMS program and listed you as an advisor. \n\nThis is an automated message, please do not respond.");

		//Mail to all admins
		$results = mysql_query("select * from Admins where Admins.Access = '1'");
		while($row = mysql_fetch_array($results))
			mail($row["Email"], "New Application!", "A new Application was received!");

		$fileName1 = $_FILES['file1']['name'];
		$fileName2 = $_FILES['file2']['name'];
		$fileName3 = $_FILES['file3']['name'];

		$tmpName1  = $_FILES['file1']['tmp_name'];
		$tmpName2  = $_FILES['file2']['tmp_name'];
		$tmpName3  = $_FILES['file3']['tmp_name'];

		$fp1      = fopen($tmpName1, 'r');
		$file1 = fread($fp1, filesize($tmpName1));
		$file1 = addslashes($file1);
		fclose($fp1);

		$fp2      = fopen($tmpName2, 'r');
		$file2 = fread($fp2, filesize($tmpName2));
		$file2 = addslashes($file2);
		fclose($fp2);

		$fp3      = fopen($tmpName3, 'r');
		$file3 = fread($fp3, filesize($tmpName3));
		$file3 = addslashes($file3);
		fclose($fp3);

		mysql_query("INSERT INTO Uploads(UploadID, FileName1, FileName2, FileName3, File1, File2, File3) VALUES ((SELECT MAX(ID) FROM Applications), '$fileName1', '$fileName2', '$fileName3', '$file1', '$file2', '$file3')")
			or die("Error: Couldn't select db " . mysql_error());

		//Show success on completion
		echo '
		<!DOCTYPE html>
		<html>
		<head>
			<title>Computer Science BS/MS Application</title>
			<link rel="stylesheet" type="text/css" href="Resources/bootstrap/css/theme.min.css">
			<link rel="stylesheet" type="text/css" href="Resources/main.css">
			<script src="Resources/jquery-1.11.2.min.js"></script>
			<script src="Resources/bootstrap/js/boostrap.min.js"></script>
		</head>
		<body>
		<div class="container">
	    	<div class="header">
		       <!-- <a href="http://www.unc.edu/"><img class="logo" src="Resources/images/OldWell.png"></a>-->
		        <ul class="nav nav-pills navbar-right">
		          <li><a href="index.php">Home</a></li>
		          <li><a href="#">FAQ</a></li>
		          <li class="active"><a href="#">Status</a></li>
		          <li><a href="logout.php">Log Off</a></li>
		        </ul>
	    	</div>
		<h1>Welcome to the <span class="blue"></br>Computer </br>Science</span> </br>BS/MS Application</h1>
		<div class="well">
			<div align="center" class="blue">Thank you for submitting an application!</div>
		</div>
		</div>
		</body>
		</html>';
		mysql_close($connection);
		die();
	}
}
else
{
	showForm("");
	die();
}
function showForm($error)
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
	</head>
	<body>
		<div class="container">
	    	<div class="header">
		       <!-- <a href="http://www.unc.edu/"><img class="logo" src="Resources/images/OldWell.png"></a>-->
		        <ul class="nav nav-pills navbar-right">
		          <li><a href="index.php">Home</a></li>
		          <li><a href="faq.html">FAQ</a></li>
		          <li><a href="status.php">Status</a></li>
		          <li><a href="logout.php">Log Off</a></li>
		        </ul>
	    	</div>
		<h1>Welcome to the <span class="blue"></br>Computer </br>Science</span> </br>BS/MS Application</h1>
		<br />
		<?php
			 if(strcmp($error, ""))
				echo "<div class='panel panel-warning' id='errorBubble'>". $error . "</div>"
		?>
		<div class="row text-center">
		   <div class="col-lg-12">
			<div class="well" id="form-well">
			   <div class="box">
			<form class="form-horizontal" action="form.php" enctype="multipart/form-data" method="post" enctype="multipart/form-data">
			<div class="form-group">
			   <label for="inputFname" class="col-lg-2 control-label">First Name</label>
			   <div class="col-lg-8">
				<input type="text" class="form-control input-sm" id="inputFname" name="firstname" placeholder="First Name" value="<?php if (!empty($_POST["firstname"])) echo $_POST["firstname"];?>"/>
			   </div>			
			</div>
			<div class="form-group">
			   <label for="inputLname" class="col-lg-2 control-label">Last Name</label>
			   <div class="col-lg-8">
				<input type="text" class="form-control input-sm" id="inputLname" name="lastname" placeholder="Last Name" value="<?php if (!empty($_POST["lastname"])) echo $_POST["lastname"];?>"/>
			   </div>			
			</div>
			<div class="form-group">
			   <label for="inputEmail" class="col-lg-2 control-label">Email</label>
			   <div class="col-lg-8">
				<input type="text" class="form-control input-sm" id="inputEmail" name="email" placeholder="Email" value="<?php if (!empty($_POST["email"])) echo $_POST["email"];?>"/>
				<div class='hint'>must be of form 'example@example.com'</div>
			   </div>			
			</div>
			<div class="form-group">
			   <label for="inputPid" class="col-lg-2 control-label">PID</label>
			   <div class="col-lg-8">
				<input type="text" class="form-control input-sm" id="inputPid" name="pid" placeholder="PID" value="<?php if (!empty($_POST["pid"])) echo $_POST["pid"];?>"/>
				<div class='hint'>must be 9 digits long</div>
			   </div>			
			</div>
			<div class="form-group">
			   <label for="inputGpa" class="col-lg-2 control-label">GPA</label>
			   <div class="col-lg-8">
				<input type="text" class="form-control input-sm" id="inputGpa" name="gpa" placeholder="GPA" value="<?php if (!empty($_POST["gpa"])) echo $_POST["gpa"];?>"/>
				<div class='hint'>must be of form '#.##'</div>
			   </div>
			</div><!--form-group-->
			<div class="form-group">
				<label for="inputTerm" class="col-lg-2 control-label">Anticipated Start Date</label>
				<div class="col-lg-8">
				<select class="form-control" name="term" id="inputTerm">
					<option value="" selected>Please Select</option>
					<option value="F15">Fall, 2015</option>
					<option value="S16">Spring, 2016</option>
					<option value="F16">Fall, 2016</option>
					<option value="S17">Spring, 2017</option>
					<option value="F17">Fall, 2017</option>
					<option value="S18">Spring, 2018</option>
					<option value="F18">Fall, 2018</option>
				</select><!--form-control-->
				</div><!--col-lg-8-->
			</div><!--form-group-->
			<div class="form-group">
				<label for="inputAdv1" class="col-lg-2 control-label">Advisor 1</label>
				<div class="col-lg-8">
				<select class="form-control" name="advisor1" id="inputAdv1">
					<option value="" selected>Please Select</option>
					<option value="ahalt">Ahalt, Stan</option>
					<option value="ron">Alterovitz, Ron</option>
					<option value="jha">Anderson, James</option>
					<option value="baruah">Baruah, Sanjoy</option>
					<option value="gb">Bishop, Gary</option>
					<option value="brooks">Brooks, Frederick</option>
					<option value="mhbragg">Bragg, Mariah</option>
					<option value="dewan">Dewan, Prasun</option>
					<option value="rjf">Fowler, Robert</option>
					<option value="jmf">Frahm, Jan-Michael</option>
					<option value="fuchs">Fuchs, Henry</option>
					<option value="jeffay">Jeffay, Kevin</option>
					<option value="jasleen">Kaur, Jasleen</option>
					<option value="kum">Kum, Hye-Chung</option>
					<option value="lastra">Lastra, Anselmo</option>
					<option value="lazebnik">Lazebnik, Lana</option>
					<option value="lin">Lin, Ming</option>
					<option value="pozefsky">Pozefsky, Diane</option>
					<option value="dm">Manocha, Dinesh</option>
					<option value="kmp">Mayer-Patel, Ketan</option>
					<option value="mcmillan">McMillan, Leonard</option>
					<option value="fabian">Monrose, Fabian</option>
				</select><!--form-control-->
				</div><!--col-lg-8-->
			</div><!--form-group-->
			<div class="form-group">
				<label for="inputAdv2" class="col-lg-2 control-label">Advisor 2</label>
				<div class="col-lg-8">
				<select class="form-control" name="advisor2" id="inputAdv2">
					<option value="" selected>Please Select</option>
					<option value="ahalt">Ahalt, Stan</option>
					<option value="ron">Alterovitz, Ron</option>
					<option value="jha">Anderson, James</option>
					<option value="baruah">Baruah, Sanjoy</option>
					<option value="gb">Bishop, Gary</option>
					<option value="brooks">Brooks, Frederick</option>
					<option value="mhbragg">Bragg, Mariah</option>
					<option value="dewan">Dewan, Prasun</option>
					<option value="rjf">Fowler, Robert</option>
					<option value="jmf">Frahm, Jan-Michael</option>
					<option value="fuchs">Fuchs, Henry</option>
					<option value="jeffay">Jeffay, Kevin</option>
					<option value="jasleen">Kaur, Jasleen</option>
					<option value="kum">Kum, Hye-Chung</option>
					<option value="lastra">Lastra, Anselmo</option>
					<option value="lazebnik">Lazebnik, Lana</option>
					<option value="lin">Lin, Ming</option>
					<option value="dm">Manocha, Dinesh</option>
					<option value="kmp">Mayer-Patel, Ketan</option>
					<option value="mcmillan">McMillan, Leonard</option>
					<option value="fabian">Monrose, Fabian</option>
				</select><!--form-control-->
				</div><!--col-lg-8-->
			</div><!--form-group-->
			<div class="form-group">
			   <label for="inputResume" class="col-lg-2 control-label">Resume</label>
			   <div class="col-lg-8">
				<input type="file" name="file1" id="file1">
			   </div>
			</div><!--form-group-->
			<div class="form-group">
			   <label for="inputSOP" class="col-lg-2 control-label">Statement of Purpose</label>
			   <div class="col-lg-8">
				<input type="file" name="file2" id="file2">
			   </div>
			</div><!--form-group-->
			<div class="form-group">
			   <label for="inputSOP" class="col-lg-2 control-label">Transcript</label>
			   <div class="col-lg-8">
				<input type="file" name="file3" id="file3">
			   </div>
			</div><!--form-group-->
				<?php
				include 'opendb.php';
				$result = mysql_query("select Username from Admins where Admins.Username = '" . $_SESSION["onyen"] . "'");
				$row = mysql_fetch_array($result);
				    if($row['Username'] !== $_SESSION["onyen"]){
					$result1 = mysql_query("select Onyen from Applications where Applications.Onyen = '" . $_SESSION["onyen"] . "'");
					$row1 = mysql_fetch_array($result1);
					if($row1['Onyen'] !== $_SESSION["onyen"]){
						echo '<input <a class="btn btn-primary" name="submit" type="submit" value="Submit Application" id="submitBtn"></a>';
					}
					else{
						echo '<script type="text/javascript">alert("You have already submitted an application");
window.location.href="index.php";
</script>';
					}
				    }
				    else{
					echo '<input <a class="btn btn-primary" name="submit" type="submit" value="Submit Application" id="submitBtn"></a>';
				    }
				    <input <a="" class="btn btn-primary" name="cancel" value="Cancel" id="cancelBtn" onClick="history.go(-1);return true;"> 
				?>
			</form>
	    		</div><!--box-->
			</div><!--well-->
		   </div><!--col-lg-12-->
		</div><!--row-->
	</div> <!--container-->
	</body>
	</html>
	<?php
}
?>
