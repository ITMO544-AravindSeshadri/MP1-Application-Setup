<?php
session_start();
$uploaddir = '/tmp/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
} else {
    echo "Possible file upload attack!\n";
}
echo 'Here is some more debugging info:';
print_r($_FILES);
print "</pre>";
require 'vendor/autoload.php';

$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);
$bucket = uniqid("aravindbucket3",false);

# AWS PHP SDK version 3 create bucket
$result = $s3->createBucket([
    'ACL' => 'public-read',
    'Bucket' => $bucket
]);

$result = $client->putObject([
    'ACL' => 'public-read',
    'Bucket' => $bucket,
   'Key' => $uploadfile
]);  

$url = $result['ObjectURL'];
echo $url;
$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);
$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'ITMO544AravindDb',
]);
$endpoint = $result['DBInstances']['Endpoint']['Address']

$link = mysqli_connect($endpoint,"aravind","password","ITMO544AravindDb") or die("Error " . mysqli_error($link));

if (!($stmt = $link->prepare("INSERT INTO MP1 (uname,email,phoneforSMS,RawS3URL,FinishedS3URL,jpegfilename,state,DateTime) VALUES (?,?,?,?,?,?,?,?)"))) {
    echo "Prepare failed: (" . $link->errno . ") " . $link->error;
}
$uname = "Aravind";
$email = $_POST['useremail'];
$phoneforSMS = $_POST['phone'];
$RawS3URL = $url; //  $result['ObjectURL'];
$FinishedS3URL = "none";
$jpegfilename = basename($_FILES['userfile']['name']);
$state = 0;
$DateTime = time();
$stmt->bind_param($uname,$email,$phoneforSMS,$RawS3URL,$FinishedS3URL,$jpegfilename,$state,$DateTime);
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
printf("%d Row inserted.\n", $stmt->affected_rows);
/* explicit close recommended */
$stmt->close();
$link->real_query("SELECT * FROM MP1");
$res = $link->use_result();
echo "Result set order...\n";
while ($row = $res->fetch_assoc()) {
    echo $row['id'] . " " . $row['email']. " " . $row['phoneforSMS'];
}
$link->close();
//add code to detect if subscribed to SNS topic 
//if not subscribed then subscribe the user and UPDATE the column in the database with a new value 0 to 1 so that then each time you don't have to resubscribe them
// add code to generate SQS Message with a value of the ID returned from the most recent inserted piece of work
//  Add code to update database to UPDATE status column to 1 (in progress)
?>