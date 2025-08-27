<?php
// api/get_events.php

// --- Database Connection ---
// It's recommended to have this in a separate, secure file (e.g., config/db.php)



require_once 'config.php';



// Check connection
if ($conn->connect_error) {
    // In a production environment, log this error instead of displaying it.
    // For now, we'll send a generic error response.
    http_response_code(500); // Internal Server Error
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}


// --- API Logic ---

// Set the content type to JSON for the response
header('Content-Type: application/json');

// The SQL query to get events scheduled for the current date
// $sql = "SELECT id, name, description, start_time, end_time FROM events WHERE DATE(start_time) = CURDATE()";
$sql = "SELECT id, name, description, start_time, end_time FROM events ";

// Execute the query
$result = $conn->query($sql);

// Check for query errors
if (!$result) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to retrieve events.']);
    $conn->close();
    exit;
}

// Fetch the results into an array
$events = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

// Send the final JSON response
echo json_encode(['status' => 'success', 'events' => $events]);

// Close the database connection
$conn->close();

?>
