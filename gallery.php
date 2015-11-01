<html>
<head>
<title>Gallery</title>
<meta charset="utf-8">
</head>
<body>

<?php
session_start();
$email = $_POST["email"];
echo $email;
require 'vendor/autoload.php';

use Aws\Rds\RdsClient;
$client = RdsClient::factory([
'region'  => 'us-west-2',
    'version' => 'latest'
]);

$result = $client->describeDBInstances([
    'DBInstanceIdentifier' => 'ITMO544AravindDb',
]);

 $endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
echo $endpoint;

//echo "begin database";
$link = mysqli_connect($endpoint,"aravind","password","ITMO544AravindDb") or die("Error " . mysqli_error($link));

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//below line is unsafe - $email is not checked for SQL injection -- don't do this in real life or use an ORM instead
$link->real_query("SELECT * FROM MP1 WHERE email = '$email'");
$res = $link->use_result();
echo "Result set order...\n";
while ($row = $res->fetch_assoc()) {
    echo "<img src =\" " . $row['RawS3URL'] . "\" /><img src =\"" .$row['FinishedS3URL'] . "\"/>";
echo $row['ID'] . "Email: " . $row['email'];
}
$link->close();
?>
</body>
</html>
