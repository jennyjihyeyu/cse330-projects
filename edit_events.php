<?php
require 'database.php';
header('Content-Type: application/json');
session_start();
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON provided.']);
    exit;
}

$eventName = filter_var($data['eventName'], FILTER_SANITIZE_STRING);
$newEventName = filter_var($data['newEventName'], FILTER_SANITIZE_STRING);
$newGroupName = filter_var($data['newGroupName'], FILTER_SANITIZE_STRING);
$newEventDatetime = filter_var($data['newEventDatetime'], FILTER_SANITIZE_STRING);

try {
    $stmt = $pdo->prepare("UPDATE event_info SET event_name = ?, group_name = ?, event_datetime = ? WHERE event_name = ? AND user_id = ?");
    $stmt->execute([$newEventName, $newGroupName, $newEventDatetime, $eventName, $userId]);

    echo json_encode(['success' => true, 'message' => 'Event edited successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
