<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head><title>My Page</title>
<meta charset="utf-8">
</head>
<body>
<h2>Input Form</h2>
<form enctype="multipart/form-data" action="submit.php" method="POST">
Enter Email: <input type="email" name="useremail"><br>
Enter Phone Number: <input type="phone" name="phone"><br>
<input type="hidden" name="MAX_FILE_SIZE" value="3000000"><br>
Load Image: <input type="file" name="userfile"><br>
<input type="submit" value="Submit Form" />
</form>
<br>
<br>
<form enctype="multipart/form-data" action="gallery.php" method="POST">
Enter email of the Gallery to find: <input type="email" name="email"><br>
<input type="submit" value="View Gallery" />
</form>
</body>
</html>