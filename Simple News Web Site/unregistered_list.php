<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheet.css" >
    <title>View Post</title>
</head>
<body>
    <?php
    session_start(); 
    require 'database.php'; 
    $story_id = htmlentities($_GET['story_id']);

    $_SESSION['story_id'] = $story_id;

    $stmt = $mysqli->prepare("select title, author, author_id, content, link from storyTable where story_id =  ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('i', $story_id);
    $stmt->execute();

    $stmt->bind_result($title, $author, $author_id, $content, $link);
    $stmt->fetch();

    $_SESSION['title'] = $title;
    $_SESSION['author'] = $author;
    $_SESSION['author_id'] = $author_id;
    $_SESSION['content'] = $content;
    $_SESSION['link'] = $link;

    echo "<h1> $title </h1>";
    echo " User: ";
    echo "$author <br>";
  
    echo "Content: ";
    echo "$content <br>";
    echo "Link: ";
    echo "<a href=$link> Click Me! </a>";
    echo '<br>';

    // if(isset($_SESSION['username'])) {
    //     if($_SESSION['user_id'] == $author_id){
    //         echo '<a href = "change_post_page.php"> Edit</a>';
    //         echo '  |  ';
    //         echo '<a href = "delete_post.php"> Delete</a>';
    //         echo '  |  ';
    //         echo '<a href = "duplicate_post.php"> Duplicate</a>';
    //     }
        
    // }
    ?>

    <p> ----------------------------- </p>   
    <p>Comments:</p>

    <?php
    session_start(); 
    require 'database.php'; 
    $story_id = htmlentities($_GET['story_id']);

    $_SESSION['story_id'] = $story_id;

    $stmt = $mysqli->prepare("select comment, username, user_id, comment_id from commentTable where story_id =  ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('i', $story_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()){
        $username = $row["username"];
        $user_id = $row["user_id"];
        $comment = $row["comment"];
        $comment_id = $row["comment_id"];
        echo $username;
        echo " : ";
        echo $comment;
        // if(isset($_SESSION['user_id'])) {
        //     if($_SESSION['user_id'] == $user_id){
        //         echo '<a href = "change_comment_page.php?comment_id='.$comment_id.'"> Edit</a> ';
        //         echo "  | ";
        //         echo '<a href = "delete_comment.php?comment_id='.$comment_id.'"> Delete</a> <br> ';
        //     } 
        // }
        echo "<br>";
    }
    ?>
</body>
</html>


