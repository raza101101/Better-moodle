<?php
session_start(); // Start the session at the top
include("connect.php"); // Use your Harzarian database connection

// Check if user is logged in (optional, but useful for tracking purchases)
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
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

// Add to cart logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $productName = $productDetails['name'];
    $productPrice = $productDetails['price'];
    $productSize = $productDetails['type'] === 'clothing' && isset($_POST['size']) ? $_POST['size'] : null;

    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add product to cart (use product name as key)
    $_SESSION['cart'][$productName] = [
        'price' => $productPrice,
        'size' => $productSize
    ];

    header("Location: " . $_SERVER['HTTP_REFERER']); // Redirect back to the shop page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($productDetails['name']); ?> - Harzarian</title>
        <link rel="stylesheet" href="../css/shop.css">
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

            .returnhome {
                text-align: center;
                margin-top: 2rem;
            }

            .returnbutton {
                background-color: #004080;
                color: #fff;
                border: none;
                padding: 0.75rem 1.5rem;
                border-radius: 6px;
                font-size: 1rem;
                font-weight: bold;
                cursor: pointer;
                transition: background-color 0.3s, transform 0.3s;
                text-decoration: none;
                display: inline-block;
            }

            .returnbutton:hover {
                background-color: #003366;
                transform: translateY(-2px);
            }
        </style>
    </head>
    <body>
        <header>
            <div class="container">
                <a href="index.php">
                    <h1>Harzarian</h1>
                </a>
                <nav>
                    <?php if (isset($_SESSION['email'])): ?>
                        <a style="color: white;">Welcome <?php echo htmlspecialchars($_SESSION['firstName']); ?></a> | 
                        <a href="../php/logout.php">Log Out</a>
                        <button class="cart-button" onclick="toggleCartModal()">Cart (<?php echo count($_SESSION['cart']); ?>)</button>
                    <?php else: ?>
                        <a href="../php/login.php?action=login">Login</a> |
                        <a href="../php/login.php?action=register">Register</a>
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
                        <form method="POST" action="">
                            <label>Select Size:</label>
                            <select name="size" id="size" required>
                                <?php if ($sizes && is_array($sizes)): ?>
                                    <?php foreach ($sizes as $size): ?>
                                        <option value="<?php echo htmlspecialchars($size); ?>"><?php echo htmlspecialchars($size); ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">No sizes available</option>
                                <?php endif; ?>
                            </select>
                            <button type="submit" name="add_to_cart" class="buy-button">Add to Cart</button>
                        </form>
                    </div>
                <?php else: ?>
                    <form method="POST" action="">
                        <button type="submit" name="add_to_cart" class="buy-button">Add to Cart</button>
                    </form>
                <?php endif; ?>
            </div>

            <div class="returnhome">
                <a href="<?php echo $productDetails['type'] === 'digital' ? 'ShopDigitalProducts.php' : 'ShopPhysicalProducts.php'; ?>" class="returnbutton">Back to Products</a>
            </div>
        </main>

        <footer>
            <div class="footer-container">
                <p>© 2024 Harzarian</p>
                <a href="../about_us.html">About Us</a> | <a href="../contact.php">Contact Us</a> | 
                <a href="../cookies.html">Cookies Policy</a> | <a href="../privacy_policy.html">Privacy Policy</a>
            </div>
        </footer>

        <div id="cartModal" class="cart-modal">
            <div class="modal-content">
                <h2>Your Cart</h2>
                <div id="cartItems"></div>
                <button class="checkout-button" onclick="checkout()">Checkout</button>
                <button class="buy-button" onclick="toggleCartModal()">Close</button>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Set initial display style
                const modal = document.getElementById('cartModal');
                modal.style.display = 'none';
                
                // Check if we should open the modal
                if (sessionStorage.getItem('cartModalOpen') === 'true') {
                    modal.style.display = 'block';
                    updateCartDisplay();
                    sessionStorage.removeItem('cartModalOpen'); // Clear after opening
                }
            });

            function toggleCartModal() {
                const modal = document.getElementById('cartModal');
                modal.style.display = modal.style.display === 'none' ? 'block' : 'none';
                if (modal.style.display === 'block') {
                    updateCartDisplay();
                }
            }

            function updateCartDisplay() {
                const cartItems = document.getElementById('cartItems');
                const cart = <?php echo json_encode($_SESSION['cart'] ?? []); ?>;
                cartItems.innerHTML = '';

                if (Object.keys(cart).length === 0) {
                    cartItems.innerHTML = '<p>Your cart is empty.</p>';
                    return;
                }

                Object.entries(cart).forEach(([productName, item]) => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'cart-item';
                    itemDiv.innerHTML = `
                        <p>${productName} - £${parseFloat(item.price).toFixed(2)}${item.size ? ` (Size: ${item.size})` : ''}</p>
                        <button class="remove-button" onclick="removeFromCart('${productName}')">Remove</button>
                    `;
                    cartItems.appendChild(itemDiv);
                });
            }

            function removeFromCart(productName) {
                console.log('Attempting to remove:', productName);
                fetch('update_cart.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=remove&product=' + encodeURIComponent(productName)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Server response:', data);
                    if (data.success) {
                        // Update the cart display
                        updateCartDisplay();
                        
                        // Update the cart count in the header
                        const cartButton = document.querySelector('.cart-button');
                        const currentCount = parseInt(cartButton.textContent.match(/\d+/)[0]);
                        cartButton.textContent = 'Cart (' + (currentCount - 1) + ')';
                    } else {
                        alert('Error removing item from cart: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Error: ' + error.message);
                });
            }

            function checkout() {
                alert('Checkout functionality to be implemented. Redirecting to payment gateway...');
                // You can add actual payment integration here (e.g., PayPal, Stripe)
                toggleCartModal();
            }
        </script>
    </body>
</html>