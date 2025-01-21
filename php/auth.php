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

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: login.php?action=register");
        exit();
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
        // Insert new user
        $insertQuery = $conn->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
        $insertQuery->bind_param("ssss", $firstName, $lastName, $email, $password);
        
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

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['firstName'] = $user['firstName'];
            $_SESSION['lastName'] = $user['lastName'];
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