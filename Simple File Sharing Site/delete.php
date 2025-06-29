<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Delete File</title>
	<link rel="stylesheet" href="mainpage.css" >
</head>
<body>
	<h1>Delte File</h1>
    <form action = "delete.php" method = "GET"> Choose file:
	<input type = "text" name = "delFile" id = "delFile"> 
    <input type="submit" value="Delete"> </form> <br> <br>

	<?php
	session_start();
	$filename =  (String) $_GET['delFile'];

	if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
		echo "File name check: ";
		echo "Invalid filename";
		echo "<br>";
	}

	$username = $_SESSION['username'];
	if( !preg_match('/^[\w_\-]+$/', $username) ){
		echo "Invalid username";
		echo "<br>";
	}

	$full_path = sprintf("/srv/module2group/%s/%s", $username, $filename);
	echo "File delete status: ";
	if(!unlink($full_path)){
		echo "File Delete Fail";
	}
	else{
		echo "File Delete Success";
	}
	?>

	<br> <a href="mainpage.php"> Back to Main Page</a>
</body>
</html>

