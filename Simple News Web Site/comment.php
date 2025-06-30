<?php
session_start();
require 'database.php';

$username = (string) $_SESSION['username'];
$comment = (string) $_POST['comment'];
$user_id = (int) $_SESSION['user_id'];
$story_id = (int) $_SESSION['story_id'];

$stmt = $mysqli->prepare("insert into commentTable(username, comment, user_id, story_id) values (?,?,?,?)");

if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->bind_param("ssii", $username, $comment, $user_id, $story_id);
$stmt->execute();
$stmt->close();

if(isset($_SESSION['user_id'])){
header("Location: userpage.php");}
else{header("location: startpage.php");
}

?>
