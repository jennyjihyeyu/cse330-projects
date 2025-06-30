<?php
    require 'database.php';
    session_start();

    if (!isset($_SESSION['user_id'])) {
        $response = ['success' => false, 'message' => 'Log in required'];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } 
    else {
        $userId = $_SESSION['user_id'];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'));
        $eventName = filter_var($data->eventName, FILTER_SANITIZE_STRING);
        $groupName = isset($data->groupName) ? filter_var($data->groupName, FILTER_SANITIZE_STRING) : null;
        $eventDatetime = $data->eventDatetime;

        try {
            $stmt = $pdo->prepare("INSERT INTO event_info (event_name, group_name, event_datetime, user_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$eventName, $groupName, $eventDatetime, $userId]);

            $response = ['success' => true, 'message' => 'Event added successfully'];
        } catch (PDOException $e) {
            $response = ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    }
?>
