<?php
// api/get_leaderboard.php

require_once 'config.php';
header('Content-Type: application/json');

$event_id = $_GET['event_id'] ?? null;

if (empty($event_id) || !filter_var($event_id, FILTER_VALIDATE_INT)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'A valid event ID is required.']);
    exit;
}

// Get the ranked list of users and their scores for the specified event
$stmt = $conn->prepare(
    "SELECT u.username, es.score
     FROM lab_scores es
     JOIN users u ON es.user_id = u.id
     WHERE es.event_id = ?
     ORDER BY es.score DESC, u.username ASC"
);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

$leaderboard = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $leaderboard[] = $row;
    }
}
$stmt->close();

// Also, check if the event is currently live
$stmt_event = $conn->prepare("SELECT start_time, end_time FROM events WHERE id = ?");
$stmt_event->bind_param("i", $event_id);
$stmt_event->execute();
$result_event = $stmt_event->get_result();
$event = $result_event->fetch_assoc();

$is_live = false;
if ($event) {
    $now = new DateTime();
    $start = new DateTime($event['start_time']);
    $end = new DateTime($event['end_time']);
    if ($now >= $start && $now <= $end) {
        $is_live = true;
    }
}
$stmt_event->close();

echo json_encode([
    'status' => 'success',
    'leaderboard' => $leaderboard,
    'is_live' => $is_live
]);

$conn->close();
?>
