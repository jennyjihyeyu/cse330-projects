<?php
require 'database.php';

ini_set("session.cookie_httponly", 1); 

session_start();

$previous_ua = @$_SESSION['useragent'];
$current_ua = $_SERVER['HTTP_USER_AGENT'];

if (isset($_SESSION['useragent']) && $previous_ua !== $current_ua) {
    die("Session hijack detected");
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
    $response = ['success' => false, 'message' => 'Please log in to delete an event'];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'));
    $eventName = filter_var($data->eventName, FILTER_SANITIZE_STRING);
    $stmt = $pdo->prepare("SELECT * FROM shared_user WHERE event_name = ? AND username = ?");
    $stmt->execute([$eventName, $username]);
    $eventExistsInOtherUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($eventExistsInOtherUser) {
        $stmt = $pdo->prepare("DELETE FROM shared_user WHERE event_name = ? AND username = ?");
        $stmt->execute([$eventName, $username]);
    }

    $stmt = $pdo->prepare("DELETE FROM event_info WHERE event_name = ? AND user_id = ?");
    $stmt->execute([$eventName, $userId]);
    $response = ['success' => true, 'message' => 'Event deleted successfully'];
    header('Content-Type: application/json');
    echo json_encode($response);

} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

?>
