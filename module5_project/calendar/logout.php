<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Out</title>
</head>

<body>
    <h3>Log out success</h3>
    <a href="login.html"><button>Login</button></a>
</body>
</html>
<?php
    session_start();
    $_SESSION = array();
    session_destroy();
    exit;
?>