<?php
session_start();
include("connect.php");

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'teacher') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit();
}

// Get POST data
$course_id = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
$day_of_week = isset($_POST['day_of_week']) ? sanitize_input($_POST['day_of_week']) : '';
$time_slot = isset($_POST['time_slot']) ? sanitize_input($_POST['time_slot']) : '';

if ($course_id && $day_of_week && $time_slot) {
    $stmt = $conn->prepare("DELETE FROM timetables WHERE course_id = ? AND day_of_week = ? AND time_slot = ?");
    $stmt->bind_param("iss", $course_id, $day_of_week, $time_slot);
    
    if ($stmt->execute()) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $conn->error]);
    }
    $stmt->close();
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}