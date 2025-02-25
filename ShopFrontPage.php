<?php
session_start(); // Start the session at the top
include("php/connect.php"); // Use your Harzarian database connection (adjust path if needed)

// Check if user is logged in (optional, but useful for tracking purchases)
if (!isset($_SESSION['email'])) {
    header("Location: php/login.php");
    exit();
}

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
        <title>Harzarian</title>
        <link rel="stylesheet" href="css/shop.css">
        <style>
            .cart-button {
                padding: 10px 20px;
                background-color: #004080;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                margin-left: 1rem;
            }

            .cart-button:hover {
                background-color: #003366;
            }

            .cart-modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1000;
            }

            .modal-content {
                background-color: white;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
                max-width: 500px;
                border-radius: 5px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }

            .cart-item {
                margin-bottom: 10px;
                border-bottom: 1px solid #ccc;
                padding-bottom: 10px;
            }

            .remove-button {
                background-color: #ff4444;
                color: white;
                border: none;
                padding: 5px 10px;
                border-radius: 3px;
                cursor: pointer;
                margin-left: 10px;
            }

            .remove-button:hover {
                background-color: #cc0000;
            }

            .checkout-button {
                padding: 10px 20px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                margin-top: 10px;
            }

            .checkout-button:hover {
                background-color: #45a049;
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
                        <button class="cart-button" onclick="toggleCartModal()">Cart (<?php echo count($_SESSION['cart']); ?>)</button>
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
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianHat.jpg" alt="Image 1"></a>
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianHoodie.jpg" alt="Image 2"></a>
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianTshirt.jpg" alt="Image 3"></a>
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianPants.jpg" alt="Image 4"></a>
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianBeanie.jpg" alt="Image 5"></a>
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianPencils.jpg" alt="Image 6"></a>
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianMathmatical.jpg" alt="Image 7"></a>
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianMultiPurposeCrate.jpg" alt="Image 8"></a>
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianNoteBook.jpg" alt="Image 9"></a>

                    <!-- Duplicated images for looping effect -->
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianHat.jpg" alt="Image 1"></a>
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianHoodie.jpg" alt="Image 2"></a>
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianTshirt.jpg" alt="Image 3"></a>
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianPants.jpg" alt="Image 4"></a>
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianBeanie.jpg" alt="Image 5"></a>
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianPencils.jpg" alt="Image 6"></a>
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianMathmatical.jpg" alt="Image 7"></a>
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianMultiPurposeCrate.jpg" alt="Image 8"></a>
                    <a href="php/ShopPhysicalProducts.php"><img src="media/ShopPhysical/HarzarianNoteBook.jpg" alt="Image 9"></a>
                </div>
            </div>
            <a href="php/ShopPhysicalProducts.php">
                <button class="PhysButton">Go to Physical products store</button>
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
                <a href="php/ShopDigitalProducts.php">
                    <button class="DigiButton">Go to Digital products store</button>
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