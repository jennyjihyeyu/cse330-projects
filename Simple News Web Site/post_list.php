<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheet.css" >
    <title>Post List</title>
</head>
<body>
    <?php 
        session_start();
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    ?>
    <h2>View Posts Here</h2>
    <?php
    session_destroy();
    session_start();

    require 'database.php';
    $stmt = $mysqli->prepare("select title, author,  story_id from storyTable");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()){
        $title = $row["title"];
        $author = $row["author"];
        $story_id = $row["story_id"];
        echo "user: ";
        echo "$author";
        echo " | ";
        echo "post: ";
        echo '<a href="unregistered_list.php?story_id='.$story_id.'">'.$title.'</a> <br>';
    }
    ?>
    <br> <br> <a href="startpage.php"> Back to Start Page </a>
</body>
</html>