<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List File</title>
    <link rel="stylesheet" href="mainpage.css" >
</head>
<body>
    <br><br><br>
    <?php 
    session_start();
    $username = $_SESSION['username'];
    echo "Files of ";
    echo $username; 
    echo ": ";
    echo "<br>";
    echo "<br>";
    $directory = sprintf("/srv/module2group/%s", $username);

    if($a = opendir($directory)) {
        while(($b = readdir($a)) !== false){
            if($b == "." || $b == ".."){
                continue;
            } else {
            echo $b;
            echo "<br>";
            }
        } closedir($a);
    } 
    ?>
 	<br><br> <a href="mainpage.php"> Back to Main Page</a>
</body>
</html>
