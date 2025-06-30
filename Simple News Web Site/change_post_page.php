<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheet.css" >
    <title>Edit Post Here</title>
</head>
<body>
    <h1> Change Post</h1>
    <form action = "change_post.php" method = "POST"> 
    Edit Title: <input type = "text" id = "title" name = "title"> <br>
    Edit Content: <input type = "text" id = "content" name = "content"> <br>
    Edit Link: <input type = "url" id = "url" name = "url"> <br>
    <input type="hidden" name="token" value="<?php session_start(); echo $_SESSION['token'];?>" />
    <input type = "submit" value = "Edit"> </form>
</body>
</html>

