<?php
session_start();
include("connect.php");

if (!isset($_SESSION['email'])) {
    header("Location: ../index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Harzarian</title>
        <link rel="stylesheet" href="../css/index.css">
    </head>
    <body>
        <!-- Page Header -->
        <header>
        <div class="container">
            <a href="index.php"><h1>Harzarian</h1></a>    
            <nav>
                <?php if (isset($_SESSION['email'])): ?>
                    <a style="color: white;">Welcome <?php echo htmlspecialchars($_SESSION['firstName']); ?></a> | 
                    <a href="logout.php">Log Out</a>
                <?php else: ?>
                    <a href="login.php?action=login">Login</a> |
                    <a href="login.php?action=register">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
        
        <main>
            <!-- Allows for the card to be styled in CSS -->
            <section class="card">
                <a href="profile.php">
                    <h2>Profile</h2>
                    <p>Access your personal information and settings.</p>
                </a>
            </section>
            <section class="card">
                <a href="../ShopFrontPage.php">
                    <h2>Shop</h2>
                    <p>Purchase merch, digital products, and more.</p>
                </a>
            </section>
            <section class="card">
                <a href="../games/index.php">
                    <h2>Games</h2>
                    <p>Access educational games and activities.</p>
                </a>
            </section>

            <!-- Divs to set styles in CSS -->
            <div class="imgtransform">
                <!-- Creates a container for the slideshow -->
                <div class="slideshow-container">
                    <!-- Each class is an image/slide -->
                    <div class="slide fade">
                        <img src="../media/image1.jpg" alt="Image 1">
                    </div>
                    <div class="slide fade">
                        <img src="../media/image2.jpg" alt="Image 2">
                    </div>
                    <div class="slide fade">
                        <img src="../media/image3.jpg" alt="Image 3">
                    </div>
                    <div class="slide fade">
                        <img src="../media/image4.jpg" alt="Image 4">
                    </div>    
                    <!-- Uses JS to program the next and previous buttons -->
                    <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
                    <a class="next" onclick="changeSlide(-1)">&#10095;</a>
                </div>
            </div>
        </main>
        <!-- Page Footer -->
        <footer>
            <div class="footer-container">
                <p>© 2024 Harzarian</p>
                <a href="../about_us.html">About Us</a> | <a href="../contact.php">Contact Us</a> | <a href="../cookies.html">Cookies Policy</a> | <a href="../privacy_policy.html">Privacy Policy</a>
            </div>
        </footer>
        <!-- Links the page to the JS -->
        <script src="../js/indexscript.js"></script>
    </body>
</html>