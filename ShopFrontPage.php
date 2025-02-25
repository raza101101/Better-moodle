<?php
session_start(); // Start the session at the top
include("php/connect.php"); // Use your Harzarian database connection (adjust path if needed)

// Check if user is logged in (optional, but useful for tracking purchases)
if (!isset($_SESSION['email'])) {
    header("Location: php/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Harzarian</title>
        <link rel="stylesheet" href="css/shop.css">
    </head>
    <body>
        <header>
            <div class="container">
                <a href="php/index.php">
                    <h1>Harzarian</h1>
                </a>
                <nav>
                    <?php if (isset($_SESSION['email'])): ?>
                        <a style="color: white;">Welcome <?php echo htmlspecialchars($_SESSION['firstName']); ?></a> | 
                        <a href="php/logout.php">Log Out</a>
                    <?php else: ?>
                        <a href="php/login.php?action=login">Login</a> |
                        <a href="php/login.php?action=register">Register</a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>

        <!--Page begins here -->
        <div class="WelcomeShop">
            <h1>Welcome to our shop</h1>
            <p1>Here we have a list of products to get you going.</p1>
            <p3>Have a look around; we are sure we have something to meet your needs</p3>
        </div>

        <div class="PhysContainer">
            <p1 class="PhysHeader">A view of our Physical products</p1>
            <div class="carousel-container">
                <div class="carousel">
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianHat.jpg" alt="Image 1"></a>
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianHoodie.jpg" alt="Image 2"></a>
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianTshirt.jpg" alt="Image 3"></a>
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianPants.jpg" alt="Image 4"></a>
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianBeanie.jpg" alt="Image 5"></a>
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianPencils.jpg" alt="Image 6"></a>
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianMathmatical.jpg" alt="Image 7"></a>
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianMultiPurposeCrate.jpg" alt="Image 8"></a>
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianNoteBook.jpg" alt="Image 9"></a>

                    <!-- Duplicated images for looping effect -->
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianHat.jpg" alt="Image 1"></a>
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianHoodie.jpg" alt="Image 2"></a>
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianTshirt.jpg" alt="Image 3"></a>
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianPants.jpg" alt="Image 4"></a>
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianBeanie.jpg" alt="Image 5"></a>
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianPencils.jpg" alt="Image 6"></a>
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianMathmatical.jpg" alt="Image 7"></a>
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianMultiPurposeCrate.jpg" alt="Image 8"></a>
                    <a href="ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianNoteBook.jpg" alt="Image 9"></a>
                </div>
            </div>
            <a>
                <button class="PhysButton" onclick="window.location.href='ShopPhysicalProducts.php'">Go to Physical products store</button>
            </a>
        </div>
        
        <div class="DigiContainer">
            <p1 class="DigiHeader">A view of our Digital products</p1>
            <div class="carousel-container">
                <div class="carousel">
                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/AudioBookCOMPSiIOT.jpg" alt="Image 1"></a>
                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/AudioBookCOMPSIMaths.jpg" alt="Image 2"></a>
                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/AudioBookSportScience.jpg" alt="Image 3"></a>
                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/DocumentationApp.jpg" alt="Image 4"></a>
                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/MathsApp.jpg" alt="Image 5"></a>
                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/FitnessApp.jpg" alt="Image 6"></a>
                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/TreadmillVideo.jpg" alt="Image 7"></a>
                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/GuyLiftingVideo.jpg" alt="Image 8"></a>
                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/CompSiMathVideo.jpg" alt="Image 9"></a>

                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/AudioBookCOMPSiIOT.jpg" alt="Image 1"></a>
                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/AudioBookCOMPSIMaths.jpg" alt="Image 2"></a>
                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/AudioBookSportScience.jpg" alt="Image 3"></a>
                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/DocumentationApp.jpg" alt="Image 4"></a>
                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/MathsApp.jpg" alt="Image 5"></a>
                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/FitnessApp.jpg" alt="Image 6"></a>
                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/TreadmillVideo.jpg" alt="Image 7"></a>
                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/GuyLiftingVideo.jpg" alt="Image 8"></a>
                    <a href="php/ShopDigitalProducts.php"><img src="media/ShopDigital/CompSiMathVideo.jpg" alt="Image 9"></a>
                </div>
                <a>
                    <button class="DigiButton" onclick="window.location.href='ShopDigitalProducts.php'">Go to Digital products store</button>
                </a>
            </div>
        </div>
        <!-- Page Footer -->
        <footer>
            <div class="footer-container">
                <p>© 2024 Harzarian</p>
                <a href="about_us.html">About Us</a> | <a href="contact.php">Contact Us</a> | 
                <a href="cookies.html">Cookies Policy</a> | <a href="privacy_policy.html">Privacy Policy</a>
            </div>
        </footer>
    </body>
</html>