<?php 
    session_start();
    require 'database.php'; 
        
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }

    $title = (string) $_POST['title'];

    if (empty($title)) {
        $change_post_title = "Title cannot be empty. Try again.";
        header("Location:userpage.php?change_post_title=".$change_post_title);
        exit;
    } 

    $content = (string) $_POST['content'];
    $link = (string) $_POST['url'];
    $story_id = (int) $_SESSION['story_id'];    
    

    $stmt = $mysqli->prepare("update storyTable set title = '$title', content = '$content', link = '$link' where story_id = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    
    $stmt->bind_param('i', $story_id);
    $stmt->execute();
    $stmt->close();
    unset($_SESSION['story_id']);

    $change_post_result = "success";
    header("Location:userpage.php?change_post_result=".$change_post_result);
?>

