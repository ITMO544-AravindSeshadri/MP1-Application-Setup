<html>
<head>
<title>Gallery</title>
<meta charset="utf-8">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<link rel="stylesheet" href="example.css">
  <link rel="stylesheet" href="font-awesome.min.css">
<style>
    #slides {
      display: none
    }

    .container {
      margin: 0 auto
    }

    /* For tablets & smart phones */
    @media (max-width: 767px) {
      body {
        padding-left: 20px;
        padding-right: 20px;
      }
      .container {
        width: auto
      }
    }

    /* For smartphones */
    @media (max-width: 480px) {
      .container {
        width: auto
      }
    }

    /* For smaller displays like laptops */
    @media (min-width: 768px) and (max-width: 979px) {
      .container {
        width: 724px
      }
    }

    /* For larger displays */
    @media (min-width: 1200px) {
      .container {
        width: 1170px
      }
    }
  </style>
</head>
<body>
<div class="container">
    <div id="slides"> 

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
$link->real_query("SELECT * FROM MP1");
$res = $link->use_result();
echo "Result set order...\n";
while ($row = $res->fetch_assoc()) {
    echo "<img src =\" " . $row['RawS3URL'] . "\" />";
# echo $row['ID'] . "Email: " . $row['email'];
}
$link->close();
?>
<a href="#" class="slidesjs-previous slidesjs-navigation"><i class="icon-chevron-left icon-large"></i></a>
	   <a href="#" class="slidesjs-next slidesjs-navigation"><i class="icon-chevron-right icon-large"></i></a>
 </div>
  </div>
  <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="jquery.slides.min.js"></script>
<script>
    $(function() {
      $('#slides').slidesjs({
        width: 940,
        height: 528,
        navigation: false
      });
    });
  </script>
</body>
</html>
