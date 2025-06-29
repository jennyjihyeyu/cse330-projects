<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Upload File</title>
	<link rel="stylesheet" href="mainpage.css" >
</head>
<body>
	<h1>Upload File</h1>
	<form enctype="multipart/form-data" action="upload.php" method="POST">
    <p> <input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
    Choose file: <input name="uploadfile_input" type="file" id="uploadfile_input" /> </p>
    <p> <input type="submit" value="Upload"> </p>
    </form>

	<?php
	session_start();
	$filename = basename($_FILES['uploadfile_input']['name']);
	if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
		echo "File name check: ";
		echo "Invalid filename";
		echo "<br>";
	}
	$username = $_SESSION['username'];
	if( !preg_match('/^[\w_\-]+$/', $username) ){
		echo "Username check: ";
		echo "Invalid username \n";
		echo "<br>";
	}
	$filepath = sprintf("/srv/module2group/%s/%s", $username, $filename);
	echo "File upload status: ";
	if(move_uploaded_file($_FILES['uploadfile_input']['tmp_name'], $filepath) ){
		echo "File Upload Success \n";
	}else{
		echo "File Upload Fail \n";
	}
	?>
	<br><br> <a href="mainpage.php"> Back to Main Page</a>
</body>
</html>


