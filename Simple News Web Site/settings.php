<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheet.css" >
    <title>Personal Settings</title>
</head>
<body> 
    <?php 
        session_start();
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    ?>
    <h2> Personal Settings Here </h2>
    <h3> Change Username </h3>

    <form action = "change_username.php" method = "POST">
    Username: <input type = "text" id = "username" name = "username"> 
    <br> 
    New Username: <input type = "text" id = "newusername" name = "newusername"> 
    <br> 
    Password: <input type = "password" id = "password" name = "password"> <br> 
    <input type = "submit" value = "Change"> </form> 

    <?php
     if(isset($_GET['result'])){
        $result = $_GET['result'];
     }
     echo "Change Status: ";
     echo $result;
    ?> 

    <p> -------------- <p>
    <h3> Forgot Password? Reset Here</h3>
    <form action = "question.php" method = "POST">
    Username:  <input type = "text" id = "username" name = "username">  <br>
    New Password: <input type = "text" id = "new_password" name = "new_password">  <br>
     Where is your hometown?: <input type = "question" id = "question" name = "question"> <br> 
    <input type = "submit" value = "submit"> </form> 

    <?php
     if(isset($_GET['rest_result'])){
        $rest_result = $_GET['rest_result'];
     }
     echo "Reset Password Status: ";
     echo $rest_result;
    ?>

    <br> <a href="startpage.php"> Back to Start Page </a>
</body>
</html>