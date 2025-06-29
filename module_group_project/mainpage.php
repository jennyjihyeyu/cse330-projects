<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainpage.css" >
    <title>Main Page</title>
</head>
<body>
    <br> <br> <br> <br>
    <form action = "logout.php" method = "POST">
    <input type="submit" value="logout"> </form>

    <?php
        session_start();
        $username = $_SESSION['username'];
        echo $username; 
        echo "'s main page";
    ?>
    
    <br> <br>
    <a href="list.php"> File List </a> <br> <br> <br>
    <a href="upload.php"> Upload File </a> <br> <br> <br>
    <a href="open.html"> Open File </a> <br> <br> <br>
    <a href="rename.php"> Rename File </a> <br> <br> <br>
    <a href="archive.php"> Archive File </a> <br> <br> <br>
    <a href="delete.php"> Delete File </a> <br> <br> <br>

</body>
</html>
