<?php
// api/get_submissions.php

// Start the session to identify the current user
session_start();

// --- Database Connection ---
require_once 'config.php';

// --- API Logic ---

header('Content-Type: application/json');

// Security Check: Ensure a user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to view submissions.']);
    exit;
}

// Get the event_id from the URL query parameter
$event_id = $_GET['event_id'] ?? null;

// Validate the event_id
if (empty($event_id) || !filter_var($event_id, FILTER_VALIDATE_INT)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'A valid event ID is required.']);
    exit;
}

// Get the current user's ID from the session
$user_id = $_SESSION['user_id'];

// --- Fetch Solved Labs ---
// This query joins submissions with labs to filter by the current event
// and selects only the lab IDs solved by the current user.
$stmt = $conn->prepare(
    "SELECT s.lab_id
     FROM submissions s
     JOIN labs l ON s.lab_id = l.id
     WHERE s.user_id = ? AND l.event_id = ?"
);
$stmt->bind_param("ii", $user_id, $event_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to retrieve submissions.']);
    $stmt->close();
    $conn->close();
    exit;
}

// Fetch the results into a simple array of lab IDs
$solved_labs = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Add the lab_id to the array
        $solved_labs[] = $row['lab_id'];
    }
}

// Send the final JSON response
echo json_encode(['status' => 'success', 'solved_labs' => $solved_labs]);

$stmt->close();
$conn->close();
?>
