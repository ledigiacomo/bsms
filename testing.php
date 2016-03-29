<?php   
 public function testCheckSession(){ 
    	if(isset($_SESSION['onyen'])){ 
    		return true 
    	} else { 
    		return false 
    	} 

 }

 public function testShowAdminView()
    {
        include 'opendb.php';
		$result = mysql_query(""select Username from Admins"");
		$row = mysql_fetch_array($result);
		while($row = mysql_fetch_array($result))
		{
    		if($row['Username'] == $_SESSION['onyen']){
        		return true
    		} else {
        		return false;
    		}
		}
    }

  public function testSubmitApplication() { 
	    if (empty($_FILES['file1']['name']) || empty($_FILES['file2']['name']) || 
	    	empty($_FILES['file3']['name']) || strcmp($_POST["firstname"], "") || 
	    	strcmp($_POST["lastname"], "") || strcmp($_POST["email"], "") || 
	    	strcmp($_POST["pid"], "") || strcmp($_POST["gpa"], "") || 
	    	strcmp($_POST["advisor1"], "") || strcmp($_POST["advisor2"], "") || strcmp($_POST["term"], "")) { 
	    	return false 
	    } else { 
	    	return true 
	    }
 }

 public function testChangeAdvisors()
    { 
    	if (!strcmp(stripslashes($row["Advisor1"]), $_SESSION["user"]) || 
    		!strcmp(stripslashes($row["Advisor2"]), $_SESSION["user"]))
    	{ 
    		return true; 
    	} else { 
    		return false; 
    	} 
 }
