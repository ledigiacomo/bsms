<?php
session_start();
if(!isset($_SESSION['onyen'])) {
    header("Location: login.php");
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
                <div class="container">
                    <div class="header">
                       <!-- <a href="http://www.unc.edu/"><img class="logo" src="Resources/images/OldWell.png"></a>-->
                        <ul class="nav nav-pills navbar-right">
                          <li><a href="index.php">Home</a></li>
		          <li><a href="faq.html">FAQ</a></li>
                          <li class="active"><a href="#">Status</a></li>
                          <li><a href="logout.php">Log Off</a></li>
                        </ul>
                    </div>
                <h1>Welcome to the <span class="blue"></br>Computer </br>Science</span> </br>BS/MS Application</h1>
 
<?php
    include 'opendb.php';
    $result = mysql_query("select Onyen, A1Feedback, A2Feedback, Status from Applications where Applications.Onyen = '" . $_SESSION["onyen"] . "'");
    $row = mysql_fetch_array($result);
 
    if($row['A1Feedback'] == '' AND $row['A2Feedback'] == '' AND $row['Status'] == '1'){
        echo '
                <div class="well">    
                    <div class="progress">
                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width:25%">
                        Application Started
                        </div>
                    </div>
                </div>
		</div>

                ';
    }
    else if($row['A1Feedback'] !== '' AND $row['A2Feedback'] == '' AND $row['Status'] == '1'){
        echo '

                <div class="well">    
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"  style="width:25%">
                        Application Started
                        </div>
                        <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"  style="width:25%">
                        Advisor 1 Feedback Submitted
                        </div>
                    </div>
                </div>
	</div>

        ';
    }
    else if($row['A1Feedback'] == '' AND $row['A2Feedback'] !== '' AND $row['Status'] == '1'){
        echo '

                <div class="well">    
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"  style="width:25%">
                        Application Started
                        </div>
                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"  style="width:25%">
                        Advisor 1 Feedback Submitted
                        </div>
                        <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"  style="width:25%">
                        Advisor 2 Feedback Submitted
                        </div>
                    </div>
                </div>
	</div>
        ';
    }
    else if($row['A1Feedback'] !== '' AND $row['A1Feedback'] !== '' AND $row['Status'] == '1'){
        echo '

                <div class="well">    
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"  style="width:25%">
                        Application Started
                        </div>
                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"  style="width:25%">
                        Advisor 1 Feedback Submitted
                        </div>
                        <div class="progress-bar progress-bar-info " role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"  style="width:25%">
                        Advisor 2 Feedback Submitted
                        </div>
                        <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width:25%">
                        Decision Being Made
                        </div>
                    </div>
                </div>
	</div>
        ';
    }
    else if($row['Status'] == '0'){
        echo '
                <div class="well">    
                    <div class="progress">
                        <div class="progress-bar progress-bar-success progress-bar-striped"" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                        Decision Made
                        </div>
                    </div>
                </div>
	</div>
        ';
    }
    else {
        echo '
                <div class="well">    
                    <div class="progress">
                        <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                        No Application
                        </div>
                    </div>
                </div>
	</div>
        ';
    }
?>
</body>
</html>
