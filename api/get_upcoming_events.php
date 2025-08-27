<?php
// api/get_upcoming_events.php

require_once 'config.php';
header('Content-Type: application/json');

// The SQL query to get the next 3 upcoming events
// It selects events where the start time is in the future and orders them by the soonest first
$sql = "SELECT id, name, description, start_time
        FROM events
        WHERE start_time > NOW()
        ORDER BY start_time ASC
        LIMIT 3";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to retrieve events.']);
    $conn->close();
    exit;
}

$events = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

echo json_encode(['status' => 'success', 'events' => $events]);
$conn->close();
?>
