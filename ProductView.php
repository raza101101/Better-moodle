<?php
session_start(); // Start the session at the top
include("php/connect.php"); // Use your Harzarian database connection

// Check if user is logged in (optional, but useful for tracking purchases)
if (!isset($_SESSION['email'])) {
    header("Location: php/login.php");
    exit();
}

// Get the product ID or name from the query parameter
$productIdentifier = isset($_GET['product']) ? $_GET['product'] : '';
if (empty($productIdentifier)) {
    die("Product not specified.");
}

// Fetch product details from the database
$stmt = $conn->prepare("SELECT id, name, price, image_path, type, sizes FROM products WHERE name = ? OR id = ?");
$stmt->bind_param("si", $productIdentifier, $productIdentifier); // Allow search by name or ID
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Product not found.");
}

$productDetails = $result->fetch_assoc();

// Close the statement
$stmt->close();

// Decode sizes if they exist (JSON format for clothing)
$sizes = $productDetails['sizes'] ? json_decode($productDetails['sizes'], true) : null;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($productDetails['name']); ?> - Harzarian</title>
        <link rel="stylesheet" href="css/shop.css">
        <style>
            .product-view {
                max-width: 800px;
                margin: 0 auto;
                padding: 2rem;
                text-align: center;
            }

            .product-image-view {
                max-width: 400px;
                height: auto;
                margin-bottom: 1rem;
            }

            .product-details {
                margin-bottom: 1rem;
            }

            .size-options {
                margin-bottom: 1rem;
            }

            .size-options select, .size-options input[type="radio"] {
                margin: 0.5rem;
                padding: 0.5rem;
            }

            .buy-button {
                padding: 10px 20px;
                background-color: #004080;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
            }

            .buy-button:hover {
                background-color: #003366;
            }
        </style>
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
            <div class="product-view card">
                <img src="<?php echo htmlspecialchars($productDetails['image_path']); ?>" alt="<?php echo htmlspecialchars($productDetails['name']); ?>" class="product-image-view">
                <div class="product-details">
                    <h2><?php echo htmlspecialchars($productDetails['name']); ?></h2>
                    <p><?php echo htmlspecialchars('Price: ' . number_format($productDetails['price'], 2)); ?></p>
                </div>

                <?php if ($productDetails['type'] === 'clothing'): ?>
                    <div class="size-options">
                        <label>Select Size:</label>
                        <select name="size" id="size">
                            <?php if ($sizes && is_array($sizes)): ?>
                                <?php foreach ($sizes as $size): ?>
                                    <option value="<?php echo htmlspecialchars($size); ?>"><?php echo htmlspecialchars($size); ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">No sizes available</option>
                            <?php endif; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <button class="buy-button">Add to Cart</button>
            </div>

            <div class="returnhome">
                <a href="<?php echo $productDetails['type'] === 'digital' ? 'ShopDigitalProducts.php' : 'ShopPhysicalProducts.php'; ?>" class="returnbutton">Back to Products</a>
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