<?php
session_start(); // Start the session at the top
include("php/connect.php"); // Use your Harzarian database connection (adjust path if needed)

// Check if user is logged in (optional, but useful for tracking purchases)
if (!isset($_SESSION['email'])) {
    header("Location: php/login.php");
    exit();
}

// Fetch physical products from the database
$stmt = $conn->prepare("SELECT id, name, price, image_path FROM products WHERE type = 'physical' OR type = 'clothing'");
$stmt->execute();
$result = $stmt->get_result();
$physicalProducts = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Harzarian - Physical Products</title>
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

        <main>
            <h1 class="page-title">Physical Products</h1>
            
            <div class="products-grid">
                <?php if ($physicalProducts): ?>
                    <?php foreach ($physicalProducts as $product): ?>
                        <div class="product-card">
                            <a href="ProductView.php?product=<?php echo urlencode($product['name']); ?>">
                                <div class="product-image"><img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"></div>
                            </a>
                            <div class="product-description"><?php echo htmlspecialchars($product['name']); ?></div>
                            <div class="product-price">Price: <?php echo htmlspecialchars('£' . number_format($product['price'], 2)); ?></div>
                            <button class="buy-button" onclick="window.location.href='ProductView.php?product=<?php echo urlencode($product['name']); ?>'">Buy</button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No physical products available.</p>
                <?php endif; ?>
            </div>

            <div class="returnhome">
                <a href="ShopFrontPage.php" class="returnbutton">Back to Shop</a>
            </div>
        </main>

        <footer>
            <div class="footer-container">
                <p>© 2024 Harzarian</p>
                <a href="about_us.html">About Us</a> | <a href="contact.php">Contact Us</a> | 
                <a href="cookies.html">Cookies Policy</a> | <a href="privacy_policy.html">Privacy Policy</a>
            </div>
        </footer>
    </body>
</html>