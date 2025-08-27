<?php
// api/get_user_stats.php

session_start();
require_once 'config.php';
header('Content-Type: application/json');

// Security Check: Ensure a user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to view stats.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$stats = [
    'total_score' => 0,
    'labs_solved' => 0
];

// 1. Get total score across all events
$stmt_score = $conn->prepare("SELECT SUM(score) as total_score FROM event_scores WHERE user_id = ?");
if ($stmt_score) {
    $stmt_score->bind_param("i", $user_id);
    $stmt_score->execute();
    $result_score = $stmt_score->get_result();
    $score_data = $result_score->fetch_assoc();
    if ($score_data && $score_data['total_score'] !== null) {
        $stats['total_score'] = (int)$score_data['total_score'];
    }
    $stmt_score->close();
}

// 2. Get total number of labs solved
$stmt_labs = $conn->prepare("SELECT COUNT(id) as labs_solved FROM submissions WHERE user_id = ?");
if ($stmt_labs) {
    $stmt_labs->bind_param("i", $user_id);
    $stmt_labs->execute();
    $result_labs = $stmt_labs->get_result();
    $labs_data = $result_labs->fetch_assoc();
    if ($labs_data) {
        $stats['labs_solved'] = (int)$labs_data['labs_solved'];
    }
    $stmt_labs->close();
}

echo json_encode(['status' => 'success', 'stats' => $stats]);

$conn->close();
?>
