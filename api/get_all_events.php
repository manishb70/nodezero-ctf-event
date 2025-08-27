<?php
// api/get_all_events.php

require_once 'config.php';
header('Content-Type: application/json');

// Fetch all events, ordering by the most recent start time first
$sql = "SELECT id, name FROM events ORDER BY start_time DESC";
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
