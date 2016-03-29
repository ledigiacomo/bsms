<?php
include 'opendb.php';

//Drop tables if they exist
mysql_query("drop table Admins");
mysql_query("drop table Applications");
mysql_query("drop table Uploads");

//Create tables
mysql_query("create table Admins(Username varchar(50) primary key not null, Email varchar(50) not null, Access bool not null) engine=MYISAM") or die("Error: couldn't create admins table " . mysql_error());

mysql_query("create table Applications(ID int primary key not null auto_increment, Received date not null, Firstname varchar(50) not null, Lastname varchar(50) not null, Onyen varchar(50) not null, Email varchar(50) not null, PID int(9) not null, GPA decimal(4, 2) not null, Advisor1 varchar(10), Advisor2 varchar(10), Term varchar(50) not null, A1Feedback varchar(250), A2Feedback varchar(250), Status bool not null, Approval bool) engine=MYISAM") or die("Error: couldn't create applications table " . mysql_error());

mysql_query("create table Uploads(UploadID int primary key not null auto_increment, FileName1 varchar(30) not null, FileName2 varchar(30) not null, FileName3 varchar(30) not null, File1 mediumblob not null, File2 mediumblob not null, File3 mediumblob not null, foreign key(UploadID) references Applications(ID)) engine=MYISAM") or die("Error: couldn't create uploads table " . mysql_error());

//Admins
mysql_query("insert into Admins values('ahalt', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('asiraj', 'test@test.com', '1')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('ron', 'mhbragg@live.unc.edu', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('anderson', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('baruah', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('gb', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('brooks', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('dewan', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('rjf', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('jmf', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('jha', 'test@test.com', '1')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('jogregor', 'test@test.com', '1')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('fuchs', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('hedlund', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('jeffay', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('jasleen', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('kum', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('lastra', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('lazebnik', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('lin', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('dm', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('kmp', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
//mysql_query("insert into Admins values('kyu4002', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('mcmillan', 'test@test.com', '1')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('mhbragg', 'mhbragg@live.unc.edu', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('fabian', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('nicholas', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('mn', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('smp', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('plaisted', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('marc', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('pozefsky', 'test@test.com', '1')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('prins', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('quigg', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('reiter', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('rosenman', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('montek', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('smithfd', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('jbs', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('snoeyink', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('stotts', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('styner', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('rsuper', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('taylorr', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('vicci', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('weiwang', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('welch', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('whitton', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());
mysql_query("insert into Admins values('wright', 'test@test.com', '0')") or die("Error: couldn't insert into assignment table " . mysql_error());

//Applications
mysql_query("insert into Applications values(NULL, 20150125, 'Test', 'One', 'tesssa', 'testing1@test.com', '123456789', '3.15', 'kyu4002', 'asiraj', 'F15', '', '', 1, 1)") or die("Error: couldn't insert into applications table " . mysql_error());
mysql_query("insert into Applications values(NULL, 20150126, 'Test', 'Two', 'asiraj', 'testing2@test.com', '111111111', '3.5', 'jeffay', 'mhbragg', 'F15', '', '', 1, 1)") or die("Error: couldn't insert into applications table " . mysql_error());
mysql_query("insert into Applications values(NULL, 20150127, 'One', 'Test', 'twneal', '1testing@test.com', '987654321', '3.00', 'asiraj', 'kmp', 'F15', '', '', 0, 1)") or die("Error: couldn't insert into applications table " . mysql_error());

echo "Successfully created the database!\n";
?>
