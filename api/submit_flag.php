<?php
// api/submit_flag.php

session_start();

// --- Database Connection ---
require_once 'config.php'; // Use your existing config file

// --- API Logic ---
header('Content-Type: application/json');

// Security Check: Ensure a user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to submit a flag.']);
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

$lab_id = $data['lab_id'] ?? null;
$submitted_flag = $data['flag'] ?? '';
$user_id = $_SESSION['user_id'];

if (empty($lab_id) || empty($submitted_flag)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Lab ID and flag are required.']);
    exit;
}

// --- Transactional Flag Check and Scoring ---

$conn->begin_transaction();

try {
    // Step 1: Check if the submitted flag is correct
    $stmt = $conn->prepare("SELECT flag, points, event_id FROM labs WHERE id = ?");
    $stmt->bind_param("i", $lab_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $lab = $result->fetch_assoc();

    if (!$lab || $lab['flag'] !== $submitted_flag) {
        throw new Exception('Incorrect flag.');
    }

    // Step 2: Check if the user has already solved this lab
    $stmt = $conn->prepare("SELECT id FROM submissions WHERE user_id = ? AND lab_id = ?");
    $stmt->bind_param("ii", $user_id, $lab_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        throw new Exception('You have already solved this lab.');
    }

    // Step 3: Insert a record into the submissions table
    $stmt = $conn->prepare("INSERT INTO submissions (user_id, lab_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $lab_id);
    $stmt->execute();

    // Step 4: Add or update the user's score for the event
    $points = $lab['points'];
    $event_id = $lab['event_id'];
    $stmt = $conn->prepare(
        "INSERT INTO lab_scores (user_id, event_id, score) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE score = score + VALUES(score)"
    );
    $stmt->bind_param("iii", $user_id, $event_id, $points);
    $stmt->execute();

    // If all queries were successful, commit the transaction
    $conn->commit();
    echo json_encode(['status' => 'success', 'message' => 'Correct! Well done.', 'points' => $points]);

} catch (Exception $e) {
    // If any query failed, roll back the transaction
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$stmt->close();
$conn->close();

?>
