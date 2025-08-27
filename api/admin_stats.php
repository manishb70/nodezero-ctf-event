<?php
// api/admin_stats.php

session_start();
require_once 'config.php';
header('Content-Type: application/json');

// Security: Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
    http_response_code(403); // Forbidden
    echo json_encode(['status' => 'error', 'message' => 'Access denied.']);
    exit;
}

$stats = [
    'total_users' => 0,
    'total_events' => 0,
    'total_labs' => 0,
    'recent_submissions' => 0, // New stat
];

// Get total users
$result = $conn->query("SELECT COUNT(id) as total FROM users");
if ($result) $stats['total_users'] = $result->fetch_assoc()['total'];

// Get total events
$result = $conn->query("SELECT COUNT(id) as total FROM events");
if ($result) $stats['total_events'] = $result->fetch_assoc()['total'];

// Get total labs
$result = $conn->query("SELECT COUNT(id) as total FROM labs");
if ($result) $stats['total_labs'] = $result->fetch_assoc()['total'];

// Get submissions from the last 24 hours
$result = $conn->query("SELECT COUNT(id) as total FROM submissions WHERE submitted_at >= NOW() - INTERVAL 1 DAY");
if ($result) $stats['recent_submissions'] = $result->fetch_assoc()['total'];


echo json_encode(['status' => 'success', 'stats' => $stats]);
$conn->close();
?>
