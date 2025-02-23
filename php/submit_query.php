<?php
session_start();
include("connect.php"); // Use your Harzarian database connection

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize_input($_POST['name']);
    $query = sanitize_input($_POST['query']);
    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : NULL; // Link to logged-in user if applicable

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO queries (name, query, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $name, $query, $user_id);

    if ($stmt->execute()) {
        // Success response
        echo json_encode(['success' => true, 'message' => 'Query submitted successfully!']);
    } else {
        // Error response
        echo json_encode(['success' => false, 'message' => 'Failed to submit query: ' . $conn->error]);
    }

    $stmt->close();
    exit();
} else {
    // Invalid request
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}