<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainpage.css" >
    <title>Archive File</title>
</head>
<body>
    <h1>Archive File </h1>
    <form action = "archive.php" method = "GET">
    Choose file: <input type = "text" name = "filename" id = "filename"> <br> 
    <input type="submit" value="Archive">  </form> <br> <br>

   <?php
    session_start();
    $username = (String) $_SESSION['username'];
    $filename  =  (String)  $_GET['filename'];
    $prevloc = sprintf("/srv/module2group/%s/%s", $username, $filename);
    $newloc = sprintf("/srv/archive/%s" , $filename); 

    if(copy($prevloc, $newloc)){
        unlink($prevloc);
        echo "Archive Success";
    } else {
        echo "Archive Fail";
    }
    ?>
    <br><br> <a href="mainpage.php"> Back to Main Page</a>
</body>
</html>