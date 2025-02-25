<?php
session_start(); // Start the session at the top
include("connect.php"); // Use your Harzarian database connection (adjust path if needed)

// Check if user is logged in (optional, but useful for tracking purchases)
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Fetch physical products from the database
$stmt = $conn->prepare("SELECT id, name, price, image_path FROM products WHERE type = 'physical' OR type = 'clothing'");
$stmt->execute();
$result = $stmt->get_result();
$physicalProducts = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Initialize cart in session if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Harzarian - Physical Products</title>
        <link rel="stylesheet" href="../css/shop.css">
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
                        <a href="php/logout.php">Log Out</a>
                        <button class="cart-button" onclick="toggleCartModal()">Cart (<?php echo count($_SESSION['cart']); ?>)</button>
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
                <a href="../ShopFrontPage.php" class="returnbutton">Back to Shop</a>
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