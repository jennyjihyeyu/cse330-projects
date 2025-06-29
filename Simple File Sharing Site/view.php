<?php
session_start();
$filename = $_GET['openFile'];
if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
	echo "File name check: ";
	echo "Invalid filename";
}
$username = $_SESSION['username'];
if( !preg_match('/^[\w_\-]+$/', $username) ){
	echo "Invalid username";
}
$filepath = sprintf("/srv/module2group/%s/%s", $username, $filename);
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($filepath);
header("Content-Type: ".$mime);
header('content-disposition: inline; filename="'.$filename.'";');
readfile($filepath);
?>
