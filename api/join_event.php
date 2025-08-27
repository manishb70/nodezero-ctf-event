<?php
// api/join_event.php

session_start();

// --- Database Connection ---
require_once 'config.php'; // Use your existing config file

// --- API Logic ---
header('Content-Type: application/json');

// Security Check: Ensure a user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to join an event.']);
    exit;
}

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$event_id = $data['event_id'] ?? null;
$user_id = $_SESSION['user_id'];

if (empty($event_id) || !filter_var($event_id, FILTER_VALIDATE_INT)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'A valid event ID is required.']);
    exit;
}

// --- Add User to Event ---
// This query will insert a new record if the user hasn't joined the event yet.
// If they have already joined, it will do nothing, which is perfect for this use case.
$stmt = $conn->prepare(
    "INSERT IGNORE INTO lab_scores (user_id, event_id, score) VALUES (?, ?, 0)"
);
$stmt->bind_param("ii", $user_id, $event_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'You have successfully joined the event.']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Could not join the event.']);
}

$stmt->close();
$conn->close();

?>
