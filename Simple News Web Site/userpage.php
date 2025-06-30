<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheet.css" >
    <title>User Page</title>
</head>
<body>
    <h1> <?php
    session_start();
    require 'database.php';
    $username = $_SESSION['username'];
    echo $username; 
    echo "'s main page <br>"; 
    ?> </h1>

    <p> List of Stories </p>

    <?php
    $stmt = $mysqli->prepare("select title, author,  story_id from storyTable");
    
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()){
        $title = $row["title"];
        $author = $row["author"];
        $story_id = $row["story_id"];
        echo "user: ";
        echo "$author";
        echo " | ";
        echo "post: ";
        echo '<a href="view.php?story_id='.$story_id.'">'.$title.'</a> <br>';
    }  
    ?>

    <p> ----------------------------- </p>   
    <p> Post Story Here </p>
    <form action = "poststory.php" method = "POST">
    Title: <input type = "text" id = "title" name = "title"> <br>
    Content: <input type = "textarea" id = "content" name = "content"> <br>
    Link: <input type = "url" name = "url" id = "url"> <br>
    <input type="hidden" name="token" value="<?php session_start(); echo $_SESSION['token'];?>" /> <br>
    <input type = "submit" value = "Post"> </form> <br>
   
   <?php
    if(isset($_GET['post_title_error'])){
        $post_title_error = $_GET['post_title_error'];
        echo "Post Status: ";
        echo $post_title_error;
        echo "<br>";
    }   

    if(isset($_GET['post_result'])){
        $post_result = $_GET['post_result'];
        echo "Post Status: ";
        echo $post_result;
        echo "<br>";
    }
    
    if(isset($_GET['change_post_title'])){
        $change_post_title = $_GET['change_post_title'];
        echo "Change Post Status: ";
        echo $change_post_title;
    }
  
    if(isset($_GET['change_post_result'])){
        $change_post_result = $_GET['change_post_result'];
        echo "Change Post Status: ";
        echo $change_post_result;
    }
  
    ?> 

    <p> ----------------------------- </p>
    
    <p> Log Out </p>
    <form action = "logout.php"> <input type = "submit" value = "Logout"> </form>
</body>
</html>
