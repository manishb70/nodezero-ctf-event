<?php
// api/manage_users.php

session_start();
require_once 'config.php';
header('Content-Type: application/json');

// Security: Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Access denied.']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        // Fetch all users
        $result = $conn->query("SELECT id, username, email, college_name, course, is_admin, is_active, created_at FROM users ORDER BY created_at DESC");
        $users = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        echo json_encode(['status' => 'success', 'users' => $users]);
        break;

    case 'PUT':
        // Update a user's details
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, college_name = ?, course = ?, is_admin = ?, is_active = ? WHERE id = ?");
        $stmt->bind_param("ssssiii", $data['username'], $data['email'], $data['college_name'], $data['course'], $data['is_admin'], $data['is_active'], $data['id']);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'User updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update user.']);
        }
        break;

    case 'DELETE':
        // Delete a user
        $id = $data['id'] ?? null;
        if ($id) {
            // Prevent admin from deleting themselves
            if ($id == $_SESSION['user_id']) {
                echo json_encode(['status' => 'error', 'message' => 'You cannot delete your own account.']);
                exit;
            }
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'User deleted successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete user.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User ID is required.']);
        }
        break;
}
$conn->close();
?>
