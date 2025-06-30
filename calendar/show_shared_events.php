<?php
require 'database.php';
ini_set("session.cookie_httponly", 1); 
session_start();
$previous_ua = @$_SESSION['useragent'];
$current_ua = $_SERVER['HTTP_USER_AGENT'];

if (isset($_SESSION['useragent']) && $previous_ua !== $current_ua) {
    session_destroy();
    echo json_encode(array('error' => 'Session hijack detected'));
    exit;
} else {
    $_SESSION['useragent'] = $current_ua;
}

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT username FROM user_info WHERE user_id = ?");
    $stmt->execute([$userId]);
    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = $userRow['username'];
} else {
    echo json_encode(array('error' => 'User not logged in'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "SELECT event_name, group_name, event_datetime FROM shared_user WHERE other_username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    if ($stmt) {
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($events);
    } else {
        echo json_encode(array('error' => 'Error fetching data'));
    }
} else {
    echo json_encode(array('error' => 'Invalid request method'));
}

?>
