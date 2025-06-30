<?php 
    session_start();
    require 'database.php'; 
    $comment_id = htmlentities((int) $_GET['comment_id']);
    $stmt = $mysqli->prepare("delete from commentTable where comment_id = ?");
   
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    
    $stmt->bind_param('i', $comment_id);
    $stmt->execute();
    $stmt->close();
    unset($_SESSION['comment_id']);
    header("Location: userpage.php");
?>
