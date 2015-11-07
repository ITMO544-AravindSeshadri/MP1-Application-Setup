<!DOCTYPE html>
<html>
<head><title>Submit</title>
</head>
<body>
<?php
session_start();
echo $_POST['useremail'];
$uploaddir = '/tmp/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
$filename=$_FILES['userfile']['name'];
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
        'region'  => 'us-west-2'
]);
$bucket = uniqid("aravindbuck3",false);

# AWS PHP SDK version 3 create bucket
$result = $s3->createBucket([
    'ACL' => 'public-read',
    'Bucket' => $bucket,
]); 

$result = $s3->putObject([
    'ACL' => 'public-read',
    'Bucket' => $bucket,
    'Key' => $filename,
    'SourceFile' => $uploadfile,
    ]);
$url=$result['ObjectURL'];
print_r($url);
$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);
$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'ITMO544AravindDb',
]);
 $endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
echo $endpoint;
$link = mysqli_connect($endpoint,"aravind","password","ITMO544AravindDb") or die("Error " . mysqli_error($link));

$uname = "Aravind";
$email = $_POST['useremail'];
$phoneforSMS = $_POST['phone'];
$RawS3URL = $url; //  $result['ObjectURL'];
$FinishedS3URL = "none";
$jpegfilename = basename($_FILES['userfile']['name']);
$state = 0;
$DateTime = date("Y-m-d H:i:s");
$sql="INSERT INTO MP1 (uname,email,phoneforSMS,RawS3URL,FinishedS3URL,jpegfilename,state,DateTime) VALUES ('$uname','$email','$phoneforSMS','$RawS3URL','$FinishedS3URL','$jpegfilename',$state,'$DateTime')";
if (!mysqli_query($link,$sql))
{
die("Error: " . mysqli_error($link));
}
echo "Record successfully inserted!";
$link->real_query("SELECT * FROM MP1");
$res = $link->use_result();
echo "Result set order...\n";
while ($row = $res->fetch_assoc()) {
echo $row['ID'] . " " . $row['email']. " " . $row['phoneforSMS'];
}
$link->close();
?>
</body>
</html>