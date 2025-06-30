<?php 
    session_start();
    require 'database.php'; 
    $story_id = (int) $_SESSION['story_id'];    
    $stmt = $mysqli->prepare("delete from storyTable where story_id = ?");
   
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('i', $story_id);
    $stmt->execute();
    $stmt->close();
    unset($_SESSION['story_id']);
    header("Location: userpage.php");
?>
