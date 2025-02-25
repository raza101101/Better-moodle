<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['upload_picture']) && isset($_FILES['profile_picture'])) {
    $user_id = $_SESSION['user_id'];

    // Directory to store uploaded images
    $target_dir = "uploads/";

    // Get file info
    $file_name = basename($_FILES["profile_picture"]["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allowed file types
    $allowed_types = ["jpg", "jpeg", "png", "gif"];

    // Validate file type
    if (!in_array($imageFileType, $allowed_types)) {
        $_SESSION['error'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        header("Location: profile.php");
        exit();
    }

    // Move uploaded file to the uploads directory
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        // Update the database with the new profile picture filename
        $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
        $stmt->bind_param("si", $file_name, $user_id);

        if ($stmt->execute()) {
            // Update session variable to reflect new profile picture
            $_SESSION['profile_picture'] = $target_file;
            $_SESSION['success'] = "Profile picture updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update profile picture.";
        }
    } else {
        $_SESSION['error'] = "Error uploading file. Please try again.";
    }
}

// Redirect back to profile page
header("Location: profile.php");
exit();
?>
