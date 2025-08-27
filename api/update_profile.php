<?php
// api/update_profile.php

session_start();
require_once 'config.php';
header('Content-Type: application/json');

// Security Check: Ensure a user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to update your profile.']);
    exit;
}

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);
$user_id = $_SESSION['user_id'];

// Input Validation
$username = $data['username'] ?? '';
$email = $data['email'] ?? '';
$college_name = $data['college_name'] ?? '';
$course = $data['course'] ?? '';

if (empty($username) || empty($email) || empty($college_name) || empty($course)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
    exit;
}

// Check if username or email is already taken by ANOTHER user
$stmt = $conn->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
$stmt->bind_param("ssi", $username, $email, $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Username or email is already in use by another account.']);
    $stmt->close();
    exit;
}
$stmt->close();

// Update user details
$stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, college_name = ?, course = ? WHERE id = ?");
$stmt->bind_param("ssssi", $username, $email, $college_name, $course, $user_id);

if ($stmt->execute()) {
    // Update the session username in case it was changed
    $_SESSION['username'] = $username;
    echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully!']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to update profile.']);
}

$stmt->close();
$conn->close();
?>
