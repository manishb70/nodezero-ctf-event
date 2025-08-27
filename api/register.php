<?php
// api/register.php

// --- Database Connection ---
// This part should be in a separate, secure file (e.g., config/db.php)
// and included here. For this example, it's combined.


require_once 'config.php';




// --- API Logic ---

// Set the content type to JSON for the response
header('Content-Type: application/json');

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the raw POST data
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    // --- Input Validation ---
    $username = $data['username'] ?? '';
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    $confirm_password = $data['confirm_password'] ?? '';
    $college_name = $data['college_name'] ?? '';
    $course = $data['course'] ?? '';

    // Check for empty fields
    if (empty($username) || empty($email) || empty($password) || empty($college_name) || empty($course)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit;
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
        exit;
    }

    // --- Check for Existing User ---
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Username or email already exists.']);
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();


    // --- Create New User ---

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Prepare the INSERT statement
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, college_name, course) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $hashed_password, $college_name, $course);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Registration successful! You can now log in.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'An error occurred. Please try again.']);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

} else {
    // Handle non-POST requests
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
