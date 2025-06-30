<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheet.css" >
    <title>Login/Sign Up</title>
</head>
<body>
<?php 
    session_start();
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    ?>
    <h2>Login/Sign Up Here</h2>
    <h3> Sign Up </h3>

    <form action = "signup.php" method = "POST">
    Username: <br>
    <input type = "text" id = "signupusername" name = "username"> 
    <br> <br> Password: <br>
    <input type = "password" id = "signuppassword" name = "password"> <br> <br>
    <input type = "submit" value = "Sign Up"> </form>  

    <?php
     if(isset($_GET['signup_result'])){
        $signup_result = $_GET['signup_result'];
     }
     echo "Sign Up Status: ";
     echo $signup_result;
    ?> <br> 
    
    <h3> -------------- </h3>
    <h3> Login </h3>
    
    <form action = "login.php" method = "POST">
    Username: <br>
    <input type = "text" id = "loginusername" name = "username"> 
    <br> <br>Password: <br>
    <input type = "password" id = "loginpassword" name = "password"> <br> <br>
    <input type = "submit" value = "Login"> </form> 

    <?php
     if(isset($_GET['login_result'])){
        $login_result = $_GET['login_result'];
     }
     echo "Login Status: ";
     echo $login_result;
    ?> <br> <br>

    <a href="startpage.php"> Back to Start Page </a>
</body>
</html>
