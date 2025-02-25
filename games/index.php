<?php
session_start();
include("../php/connect.php"); // Use your Harzarian database connection

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../php/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harzarian Games</title>
    <link rel="stylesheet" href="styles.css"> <!-- Create or use an existing CSS file for styling -->
</head>
<body>
    <header>
        <div class="container">
            <a href="../php/index.php"><h1>Harzarian</h1></a>    
            <nav>
                <a>Welcome <?php echo htmlspecialchars($_SESSION['firstName']); ?></a>
            </nav>
        </div>
    </header>
    
    <main>
        <h1 style="text-align: center; margin-bottom: 2rem;">Harzarian Games</h1>

        <div class="game-container">
            <div class="game-card">
                <h3>Numbers Game</h3>
                <p>Test your algebra skills!</p>
                <a href="numbers_game.php" class="button">Play Now</a>
            </div>
            <div class="game-card">
                <h3>Wordle Clone</h3>
                <p>Guess the five-letter word!</p>
                <a href="wordle.php" class="button">Play Now</a>
            </div>
            <div class="game-card">
                <h3>Wordsearch</h3>
                <p>Find all the hidden words!</p>
                <a href="wordsearch.php" class="button">Play Now</a>
            </div>
        </div>

        <div class="returnhome" style="text-align: center; margin-top: 2rem;">
            <a href="../index.html" class="returnbutton">Back to Home</a>
        </div>
    </main>

    <footer>
        <div class="footer-container">
            <p>© 2024 Harzarian</p>
            <a href="../about_us.html">About Us</a> | <a href="../contact.html">Contact Us</a> | 
            <a href="../cookies.html">Cookies Policy</a> | <a href="../privacy_policy.html">Privacy Policy</a>
        </div>
    </footer>
    <script src="../js/indexscript.js"></script>
</body>
</html>