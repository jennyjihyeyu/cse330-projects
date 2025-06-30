<?php
    session_start(); 
    require 'database.php'; 
    $stmt = $mysqli->prepare("SELECT question FROM userTable WHERE username=?");
    
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    } 

    $username = (string) $_POST['username']; 
    $new_password = (string) $_POST['new_password'];
    $question = (string) $_POST['question']; 

    $stmt->bind_param('s', $username); 
    $stmt->execute();
    $stmt->bind_result($input_question);
    $stmt->fetch();
    $stmt->close();

    if ($question == $input_question) { 
        
        $hash = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare("UPDATE userTable SET password = '$hash' WHERE username = ?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('s', $username); 
        $stmt->execute();
        $stmt->close();
        $rest_result = "reset success";
        header("Location:settings.php?rest_result=".$rest_result);
    } else {
        $rest_result = "reset fail";
        header("Location:settings.php?rest_result=".$rest_result);
    }
?>
