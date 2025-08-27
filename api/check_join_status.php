<?php
// api/check_join_status.php

session_start();

// --- Database Connection ---
require_once 'config.php'; // Use your existing config file

// --- API Logic ---
header('Content-Type: application/json');

// Security Check: Ensure a user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in.']);
    exit;
}

$event_id = $_GET['event_id'] ?? null;
$user_id = $_SESSION['user_id'];

if (empty($event_id) || !filter_var($event_id, FILTER_VALIDATE_INT)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'A valid event ID is required.']);
    exit;
}

// Check if a record exists for this user and event
$stmt = $conn->prepare("SELECT id FROM lab_scores WHERE user_id = ? AND event_id = ?");
$stmt->bind_param("ii", $user_id, $event_id);
$stmt->execute();
$stmt->store_result();

$is_joined = $stmt->num_rows > 0;

echo json_encode(['status' => 'success', 'is_joined' => $is_joined]);

$stmt->close();
$conn->close();
?>
