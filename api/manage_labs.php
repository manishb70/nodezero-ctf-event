<?php
// api/manage_labs.php

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
        // Fetch all labs
        $result = $conn->query("SELECT l.*, e.name as event_name FROM labs l JOIN events e ON l.event_id = e.id ORDER BY l.event_id, l.id");
        $labs = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $labs[] = $row;
            }
        }
        echo json_encode(['status' => 'success', 'labs' => $labs]);
        break;

    case 'POST':
        // Create a new lab
        $stmt = $conn->prepare("INSERT INTO labs (event_id, title, description, link, flag, points) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssi", $data['event_id'], $data['title'], $data['description'], $data['link'], $data['flag'], $data['points']);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Lab created successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create lab.']);
        }
        break;

    case 'PUT':
        // Update an existing lab
        $stmt = $conn->prepare("UPDATE labs SET event_id = ?, title = ?, description = ?, link = ?, flag = ?, points = ? WHERE id = ?");
        $stmt->bind_param("issssii", $data['event_id'], $data['title'], $data['description'], $data['link'], $data['flag'], $data['points'], $data['id']);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Lab updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update lab.']);
        }
        break;

    case 'DELETE':
        // Delete a lab
        $id = $data['id'] ?? null;
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM labs WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Lab deleted successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete lab.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Lab ID is required.']);
        }
        break;
}
$conn->close();
?>  