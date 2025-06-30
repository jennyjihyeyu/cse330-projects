<?php 
    session_start();

    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    require 'database.php'; 
    
    $comment = (string) $_POST['comment'];
    $comment_id = (int) $_SESSION['comment_id']; 
    $stmt = $mysqli->prepare("update commentTable set comment = '$comment' where comment_id = ?");
   
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
