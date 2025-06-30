<?php
    session_start();
    require 'database.php';
    $author = $_SESSION['username'];
    $title = $_POST['title'];
    
    if (empty($title)) {
        $post_title_error = "Title cannot be empty. Try again.";
        header("Location:userpage.php?post_title_error=".$post_title_error);
        exit;
    } 

    $content = $_POST['content'];
    $author_id = $_SESSION['user_id'];
    $link = $_POST['url'];
    $stmt = $mysqli->prepare("insert into storyTable(title, author, author_id, content, link) values (?,?,?,?,?)");

    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param("ssiss", $title, $author, $author_id, $content, $link);
    $stmt->execute();
    $stmt->close();

    $post_result = "success";
    header("Location:userpage.php?post_result=".$post_result);
?>