<?php 
    session_start();
    require 'database.php'; 

    $username = (string) $_POST['username'];
    $newusername = (string) $_POST['newusername'];
    $password = (string) $_POST['password'];

    $stmt = $mysqli->prepare("SELECT password FROM userTable WHERE username = ?");
   
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($input_password);
    $stmt->fetch();
    $stmt->close();

    if(password_verify($password, $input_password)){
        
        $stmt = $mysqli->prepare("UPDATE userTable set username = '$newusername' where username = '$username'");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->close();
        $result = "success";
        header("Location: settings.php?result=".$result);
    }
    else {
        $result = "fail";
        header("Location: settings.php?result=".$result);
    }
?>
