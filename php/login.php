<?php
session_start(); // Start the session at the very top
include("connect.php"); // Include the database connection

// Redirect logged-in users to profile.php
if (isset($_SESSION['email'])) {
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Harzarian</title>
        <link rel="stylesheet" href="../css/login.css">
    </head>
    <body>
        <!-- Page Header -->
        <header>
            <div class="container">
                <a href="index.php">
                    <h1>Harzarian</h1>
                </a>    
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
            <?php
            // Display session messages (no need for session_start() here since it's already started)
            if(isset($_SESSION['error'])) {
                echo '<p class="error">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
            if(isset($_SESSION['success'])) {
                echo '<p class="success">' . $_SESSION['success'] . '</p>';
                unset($_SESSION['success']);
            }
            ?>
            <!-- Login Section -->
            <section id="login" class="login-container">
                <h2>Login</h2>
                <form action="auth.php?action=login" method="POST" class="login-form">
                    <input type="email" id="emailLog" name="email" placeholder="Email" required>
                    <input type="password" id="loginPassword" name="password" placeholder="Password" required>
                    <div class="remember-forgot-container">
                        <label><input type="checkbox">Remember Me</label>
                        <a href="#" class="forgot-password">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn" name="login">Login</button>
                </form>
                <p class="signup-link">Don't have an account? <a href="login.php?action=register">Register</a></p>
            </section>

            <!-- Register Section -->
            <section id="register" class="register-container" style="display: none;">
                <h2>Register</h2>
                <form action="auth.php?action=register" method="POST" class="login-form">
                    <input type="text" id="firstname" name="firstname" placeholder="First Name" required>
                    <input type="text" id="lastname" name="lastname" placeholder="Last Name" required>
                    <input type="email" id="emailReg" name="email" placeholder="Email" required>
                    <input type="password" id="registerPassword" name="password" placeholder="Password" required>
                    <select name="role" id="role" required>
                        <option value="">Select Role</option>
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                    </select>
                    <input type="text" id="course_key" name="course_key" placeholder="Enter Course Key" required>
                    <button type="submit" class="btn" name="register">Register</button>
                </form>
                <p class="signup-link">Already have an account? <a href="login.php?action=login">Log In</a></p>
            </section>
        </main>

        <footer>
            <div class="footer-container">
                <p>© 2024 Harzarian</p>
                <a href="about_us.html">About Us</a> | <a href="contact.php">Contact Us</a> | 
                <a href="cookies.html">Cookies Policy</a> | <a href="privacy_policy.html">Privacy Policy</a>
            </div>
        </footer>
        <script src="../js/loginscript.js"></script>
    </body>
</html>