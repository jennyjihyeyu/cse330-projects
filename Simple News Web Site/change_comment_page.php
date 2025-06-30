<?php 
    session_start();
    require 'database.php'; 
    $comment_id = (int) $_GET['comment_id'];
    $_SESSION['comment_id'] = $comment_id;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheet.css" >
    <title>Edit Comment</title>
</head>
<body>
    <h1> Edit Comment Here </h1>
  
    <form action = "change_comment.php" method = "POST"> 
        New Comment: <input type = "text" id = "comment" name = "comment"> <br>
        <input type="hidden" name="token" value="<?php session_start(); echo $_SESSION['token'];?>" />
        <input type = "submit" value = "submit">
    </form>
</body>
</html>



