<?php
	require 'database.php';

	$username = (string) $_POST['username'];
	$password = (string) $_POST['password'];
	$question = (string) $_POST['question'];
	$hash = password_hash($password, PASSWORD_DEFAULT);

	$stmt = $mysqli->prepare("INSERT into userTable(username, password, question) values (?,?,?)");
	
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}

	$stmt->bind_param('sss', $username, $hash, $question);
	$stmt->execute();
	$stmt->close();
	
	$signup_result = "sign up success";
	header("Location:login_signup.php?signup_result=".$signup_result);
?>
