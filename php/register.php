<?php

include 'connect.php';

if (isset($_GET['action']) && $_GET['action'] === 'register' && isset($_POST['register'])) {
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmail);

    if($result->num_rows > 0){
        echo "Email Address Already Exists!";
    } else {
        $insertQuery="INSERT INTO users (firstName, lastName, email, password)
                      VALUES ('$fisrtName', '$lastName', '$email', '$password'";
        
        if ($conn->query($insertQuery) === TRUE) {
            header("Location: login.php");
        } else {
            echo "Error: " . $conn->error;
        }
    }
} elseif (isset($_GET['action']) &&  $_GET['action'] === 'login' &&  isset($_POST['login'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['email'] = $row['email'];
            header("Location: profile.php");
            exit();
        } else {
            echo "Incorrect Password.";
        }
    } else {
        echo "No account found with this email.";
    }
}
?>