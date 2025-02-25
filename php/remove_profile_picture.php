<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Remove profile picture in the database
$stmt = $conn->prepare("UPDATE users SET profile_picture = NULL WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $_SESSION['profile_picture'] = "../media/default.jpg"; // Reset session variable
    $_SESSION['success'] = "Profile picture removed.";
} else {
    $_SESSION['error'] = "Failed to remove profile picture.";
}

// Redirect back to profile page
header("Location: profile.php");
exit();
?>
