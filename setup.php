<?php
$link = new mysqli("itmo544aravinddb.czw4txekmxpp.us-west-2.rds.amazonaws.com","aravind","password","ITMO544AravindDb",3306) or die("Error " . mysqli_error($link));
echo "Successfully connected to Database....";
$result=$link->query("CREATE TABLE MP1(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
uname VARCHAR(20),
email VARCHAR(20),
phoneforSMS VARCHAR(20),
RawS3URL VARCHAR(256),
FinishedS3URL VARCHAR(256),
jpegfilename VARCHAR(256),
state TinyInt(3) CHECK (state IN (0,1,2)),
DateTime Timestamp)");
echo "\nTable Created/Present....";
shell_exec("chmod 600 setup.php");
echo "\nFile permission successfully modified....\n";
?>
