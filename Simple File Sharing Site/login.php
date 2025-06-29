<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainpage.css" >
    <title>Login</title>
</head>
<body>
    <br> <br> 
    <?php
    session_start();
    $h = fopen("/srv/users.txt", "r");
    $username = (String) $_GET['username'];

    while(!feof($h)){
        $validUsername = trim(fgets($h));
        if ($validUsername == $username) {
            $_SESSION['username'] = $username;
            header("Location: mainpage.php");
            exit;
        }
    }
    echo "Type valid username";
    echo "<br>";
    echo "Go back to previous page to login again";
    fclose($h);
    ?>
</body>
</html>

