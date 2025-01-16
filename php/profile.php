<?php
session_start();
include("connect.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Harzarian</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
        <!-- Page Header -->
        <header>
            <div class="container">
                <!-- 'href' When clicked directs to specified page -->
                <a href="index.html">
                    <h1>Harzarian</h1>
                </a>    
                <nav>
                    <a href="login.php?action=login">Login</a>
                    <a href="login.php?action=register">Register</a>
                </nav>
            </div>
        </header>
        
        <main>
            <div style="text-align: center; padding: 15%;">
                <p style="font-size: 50px; font-weight: bold;">
                    Hello 
                    <?php
                    $email = $_SESSION['email'];
                    $query = $conn->query("SELECT * FROM users WHERE email = '$email' LIMIT 1");

                    if ($query->num_rows > 0) {
                        $user = $query->fetch_assoc();
                        echo $user['firstName'] . ' ' . $user['lastName'];
                    } else {
                        echo "User";
                    }
                    ?>
                </p>
                <a href="logout.php">Log Out</a>
            </div>
        </main>
        <!-- Page Footer -->
        <footer>
            <div class="footer-container">
                <p>© 2024 Harzarian</p>
                <a href="about_us.html">About Us</a> | <a href="contact.html">Contact Us</a>
            </div>
        </footer>
        <!-- Links the page to the JS -->
        <script src="../js/profilescript.js"></script>
    </body>
</html>