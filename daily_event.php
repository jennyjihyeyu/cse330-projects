<?php
require 'database.php';

if(isset($_POST['year']) && isset($_POST['month']) && isset($_POST['day'])) {
    $year = $_POST['year'];
    $month = $_POST['month'];
    $day = $_POST['day'];
    $dateString = sprintf('%04d-%02d-%02d', $year, $month + 1, $day);

    try {
        $query = "SELECT * FROM event_info WHERE DATE(event_datetime) = :dateString";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':dateString', $dateString);
        $stmt->execute();
        $events = $stmt->fetchAll();
        header('Content-Type: application/json');
        echo json_encode($events);

    } catch (\PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database query failed: ' . $e->getMessage()]);
    }

} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request data provided']);
}

?>
