<?php
// api/get_labs.php

// --- Database Connection ---
// It's recommended to have this in a separate, secure file (e.g., config/db.php)
require_once 'config.php';

// --- API Logic ---

// Set the content type to JSON for the response
header('Content-Type: application/json');

// Get the event_id from the URL query parameter
$event_id = $_GET['event_id'] ?? null;

// Validate the event_id
if (empty($event_id) || !filter_var($event_id, FILTER_VALIDATE_INT)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'A valid event ID is required.']);
    exit;
}

// --- Fetch Labs ---
// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT id, title, description, link, points FROM labs WHERE event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

// Check for query errors
if (!$result) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to retrieve labs.']);
    $stmt->close();
    $conn->close();
    exit;
}

// Fetch the results into an array
$labs = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $labs[] = $row;
    }
}

// Send the final JSON response
echo json_encode(['status' => 'success', 'labs' => $labs]);

// Close the statement and connection
$stmt->close();
$conn->close();
?>
