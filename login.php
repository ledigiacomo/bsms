<?php
session_start();
if(isset($_SESSION['onyen'])) {
    header("Location: index.php");
}
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
	   <form action="https://onyen.unc.edu/cgi-bin/unc_id/authenticator.pl"
	      name="form1" method="POST">

	      <input type="hidden"
		     name="title"
		     value="Computer Science BS/MS Application">

	      <input type="hidden"
		     name="backgroundimage"
		     value="none">

	      <input type="hidden"
		     name="csslink"
		     value="https://xkcd.com/s/46a610.css">
		     
	      <input type="hidden"
		     name="targetpass"
		     value="http://bsms.cs.unc.edu/index.php">

	      <input type="hidden"
		     name="targetfail"
		     value="http://bsms.cs.unc.edu/login.php">

	      <center>
	      <div class="text-center">
		<input <a class="btn btn-primary" name="submit" type="submit" id="authenticateBtn" value="Authenticate"></a>
	      </div>

	      </center>
	    </form>
	</body>
</html>
