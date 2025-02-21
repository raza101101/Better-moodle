<?php
session_start();
include 'connect.php';

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Registration Handler
if (isset($_GET['action']) && $_GET['action'] === 'register' && isset($_POST['register'])) {
    $firstName = sanitize_input($_POST['firstname']);
    $lastName = sanitize_input($_POST['lastname']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = sanitize_input($_POST['role']);
    $course_key = sanitize_input($_POST['course_key']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: login.php?action=register");
        exit();
    }

    $valid_roles = ['student', 'teacher'];
    if (!in_array($role, $valid_roles)) {
        $_SESSION['error'] = "Invalid role selected";
        header("Location: login.php?action=register");
        exit();
    }

    // Validate course key if student
    $course_id = null;
    if ($role === 'student') {
        $stmt = $conn->prepare("SELECT course_id FROM course_keys WHERE course_key = ? AND is_used = FALSE");
        $stmt->bind_param("s", $course_key);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $_SESSION['error'] = "Invalid or already used course key";
            header("Location: login.php?action=register");
            exit();
        }
        
        $key_data = $result->fetch_assoc();
        $course_id = $key_data['course_id'];
        
        // Mark the key as used
        $update_stmt = $conn->prepare("UPDATE course_keys SET is_used = TRUE WHERE course_key = ?");
        $update_stmt->bind_param("s", $course_key);
        $update_stmt->execute();
    }

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if($result->num_rows > 0){
        $_SESSION['error'] = "Email address already exists!";
        header("Location: login.php?action=register");
        exit();
    } else {
        $insertQuery = $conn->prepare("INSERT INTO users (firstName, lastName, email, password, role, course_id) VALUES (?, ?, ?, ?, ?, ?)");
        $insertQuery->bind_param("sssssi", $firstName, $lastName, $email, $password, $role, $course_id);
        
        if ($insertQuery->execute()) {
            $_SESSION['success'] = "Registration successful! Please login.";
            header("Location: login.php?action=login");
            exit();
        } else {
            $_SESSION['error'] = "Registration failed: " . $conn->error;
            header("Location: login.php?action=register");
            exit();
        }
    }
}

// Login Handler
elseif (isset($_GET['action']) && $_GET['action'] === 'login' && isset($_POST['login'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, firstName, lastName, email, password, role, course_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['firstName'] = $user['firstName'];
            $_SESSION['lastName'] = $user['lastName'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['course_id'] = $user['course_id'];
            header("Location: profile.php");
            exit();
        } else {
            $_SESSION['error'] = "Incorrect password";
            header("Location: login.php?action=login");
            exit();
        }
    } else {
        $_SESSION['error'] = "No account found with this email";
        header("Location: login.php?action=login");
        exit();
    }
}
?>