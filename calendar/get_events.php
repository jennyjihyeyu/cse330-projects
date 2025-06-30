<?php
    require 'database.php';
    session_start();
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
    } else {
        echo json_encode(array('error' => 'User not logged in'));
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sql = "SELECT event_name, group_name, event_datetime FROM event_info WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
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
