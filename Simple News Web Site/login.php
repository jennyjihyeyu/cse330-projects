<?php 
    session_start();
    require 'database.php'; 
    $pass_guess = (string) $_POST['password'];
    $user_guess = (string) $_POST['username'];

    $stmt = $mysqli->prepare("select username, password, user_id from userTable where username = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('s', $user_guess);
    $stmt->execute();

    $stmt->bind_result($username, $pwd_hash, $user_id);
    $stmt->fetch();

    if(password_verify($pass_guess, $pwd_hash)){
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $user_guess;
        header("Location:userpage.php");
    }
    else{
        $login_result = "login fail";
        header("Location:login_signup.php?login_result=".$login_result);
    }
?>
