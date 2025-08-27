<?php
// api/login.php

// Start the session at the very beginning
session_start();

// --- Database Connection ---
// This should be in a separate, secure file (e.g., config/db.php)


require_once 'config.php';


// --- API Logic ---

// Set the content type to JSON for the response
header('Content-Type: application/json');

// Only process POST requests
switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        // Get the raw POST data
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        // --- Input Validation ---
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        // Check for empty fields
        if (empty($username) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Username and password are required.']);
            exit;
        }

        // --- Authenticate User ---
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, username, is_admin, password, is_admin FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify the password against the stored hash
            if (password_verify($password, $user['password'])) {
                // Password is correct, so create session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = $user['is_admin'];

                // Send success response
                echo json_encode(['status' => 'success', 'message' => 'Login successful!']);
            } else {
                // Invalid password
                echo json_encode(['status' => 'error', 'message' => 'Invalid username or password.']);
            }
        } else {
            // User not found
            echo json_encode(['status' => 'error', 'message' => 'Invalid username or password.']);
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
        break;

    default:
        // Handle non-POST requests
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
        break;
}
?>
