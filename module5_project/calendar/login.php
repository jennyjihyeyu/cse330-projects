<?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    error_reporting(E_ALL);

    if (!isset($_SESSION['initialized'])) {
        session_regenerate_id(true);
        $_SESSION['initialized'] = true;
    }

    include 'database.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $stmt = $pdo->prepare("SELECT user_id, password FROM user_info WHERE username = :username");
        $stmt->bindParam(':username', $user, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && password_verify($_POST['type_password'], $result['password'])) {
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['username'] = $user; 
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
            header("Location: calendar.html");
            exit();
        } else {
            echo htmlentities("Failed Login");
            echo '<br><button onclick="window.history.back();">Go Back</button>';
            exit();
        }
    }
?>
