<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheet.css" >
    <title>Website</title>
</head>
    <?php 
        session_start();
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    ?>
<body>
    <h1>Welcome to Our Website!</h1>
    <a href="login_signup.php"> Login / Sign Up</a> <br> <br> <br> 
    <a href="settings.php"> Personal Settings </a> <br> <br> <br>  
    <a href="post_list.php"> Post Lists </a>
</body>
</html>


