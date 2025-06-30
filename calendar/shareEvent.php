<?php
require 'database.php'; 

session_start();
session_regenerate_id(true); 

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT username FROM user_info WHERE user_id = ?");
    $stmt->execute([$userId]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = $userData['username'];
} else {
    $response = ['success' => false, 'message' => 'Please log in to share an event'];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if(isset($data['eventName']) && isset($data['otherUsername'])) {
        $eventName = filter_var($data['eventName'], FILTER_SANITIZE_STRING); 
        $otherUsername = filter_var($data['otherUsername'], FILTER_SANITIZE_STRING);
        $stmt = $pdo->prepare("SELECT event_datetime, group_name FROM event_info WHERE event_name = ? AND user_id = ?");
        $stmt->execute([$eventName, $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            $response = ['success' => false, 'message' => 'Event not found'];
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO shared_user (event_name, event_datetime, username, group_name, other_username) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$eventName, $result['event_datetime'], $username, $result['group_name'], $otherUsername]);

                $response = ['success' => true, 'message' => 'Event shared successfully'];
            } catch (PDOException $e) {
                $response = ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
            }
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Incomplete data.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

?>
