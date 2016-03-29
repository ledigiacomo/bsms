<?php
session_start();
if(isset($_POST['onyen'])){
$_SESSION['onyen'] = $_POST['onyen'];
}
//if(!isset($_SESSION['onyen'])){
//header("Location: login.php");
//} else {
//for_test
{$_SESSION['onyen'] = 'pozefsky';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Computer Science BS/MS Application</title>
		<!-- <link rel="stylesheet" type="text/css" href="Resources/bootstrap/css/bootstrap.min.css"> -->
		<link rel="stylesheet" type="text/css" href="Resources/bootstrap/css/theme.min.css">
		<link rel="stylesheet" type="text/css" href="Resources/main.css"> 
	    	<script src="Resources/jquery-1.11.2.min.js"></script>
		<script src="Resources/bootstrap/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container">
	    	<div class="header">
		        <ul class="nav nav-pills navbar-right">
		          <li class="active"><a href="#">Home</a></li>
		          <li><a href="faq.html">FAQ</a></li>
		          <li><a href="status.php">Status</a></li>
		          <li><a href="logout.php">Log Off</a></li>
		        </ul>
	    	</div>
		<h1>Welcome to the <span class="blue"></br>Computer </br>Science</span> </br>BS/MS Application</h1>
			<div class="well" id="infopanel">
			   <p>You are logged in as:<b> <?php echo $_SESSION['onyen'] ?> </b></p>
				<?php
				include 'opendb.php';
				$result = mysql_query("select Username, Access from Admins where Admins.Username = '" . $_SESSION["onyen"] . "'");
				$row = mysql_fetch_array($result);
				    if($row['Username'] == $_SESSION["onyen"]){
					$_SESSION["user"] = $_SESSION["onyen"];
					echo '<p><a href="adminlogin.php">Admin Panel</a></p>';
				    }
				    if($row['Access'] == '1'){
					$_SESSION["admin"] = true;
				    }
				?>
			</div>

			<div class="well blue">
Students in the B.S. degree program with a GPA of 3.2 or better after five or more semesters of study have the option of
 applying to the combined B.S.M.S. program to pursue graduate coursework leading to the degree of master of science. </br></br>Such students must complete the requirements for the bachelor of science degree within eight semesters. Upon completion
 of the B.S. degree, students then enroll as a graduate student to continue work towards the master of science degree. Admission to the master's portion of the program cannot be deferred. Master's work must begin the semester following 
the completion of the B.S. degree.

The requirements for both degrees remain the same. The primary benefit of the program is a significantly simplified and expedited admissions process into the M.S. program.</br></br>
				<div class="text-center">
				<a class="btn btn-primary" href="form.php">Submit Application</a>
				</div>
			</div>
		</div><!-- container -->
	</body>
</html>
<?php
}
?>
