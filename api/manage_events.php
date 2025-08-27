<?php
// api/manage_events.php

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
        // Fetch all events
        $result = $conn->query("SELECT * FROM events ORDER BY start_time DESC");
        $events = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $events[] = $row;
            }
        }
        echo json_encode(['status' => 'success', 'events' => $events]);
        break;

    case 'POST':
        // Create a new event
        $stmt = $conn->prepare("INSERT INTO events (name, description, start_time, end_time) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $data['name'], $data['description'], $data['start_time'], $data['end_time']);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Event created successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create event.']);
        }
        break;

    case 'PUT':
        // Update an existing event
        $stmt = $conn->prepare("UPDATE events SET name = ?, description = ?, start_time = ?, end_time = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $data['name'], $data['description'], $data['start_time'], $data['end_time'], $data['id']);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Event updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update event.']);
        }
        break;

    case 'DELETE':
        // Delete an event
        $id = $data['id'] ?? null;
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Event deleted successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete event.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Event ID is required.']);
        }
        break;
}
$conn->close();
?>