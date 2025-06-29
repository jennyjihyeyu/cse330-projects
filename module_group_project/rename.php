<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainpage.css" >
    <title>Rename file</title>
</head>
<body>
    <h1>Rename file: </h1>
    <form action = "rename.php" method = "GET">
    Choose file: <input type = "text" name = "prevname" id = "prevname"> <br> 
    Choose new name:  <input type = "text" name = "newname" id = "newname"> <br>
    <input type="submit" value="Rename">  </form> <br> <br>
   
   <?php
    session_start();
    $username = (String) $_SESSION['username'];
    $oldfilename  =  (String)  $_GET['prevname'];
    $newfilename =  (String)  $_GET['newname'];
    $prevloc = sprintf("/srv/module2group/%s/%s", $username, $oldfilename);
    $newloc = sprintf("/srv/module2group/%s/%s", $username, $newfilename); 

    if (rename($prevloc,$newloc)) {
       echo "Rename Success";
    } else {
    echo "Rename fail";
    } 
    ?>
    <br><br> <a href="mainpage.php"> Back to Main Page</a>
</body>
</html>