<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BetterMoodle</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Use the same CSS for consistency -->
</head>
<body>
    <header>
        <div class="container">
            <a href="index.html">
                <h1>BetterMoodle</h1>
            </a>    
            <nav>
                <a href="login.html">Login</a>
                <a href="register.html">Register</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="login-container">
            <h2>Login</h2>
            <form action="register.php" method="POST" class="login-form">
                <input type="email" id="email" name="email" placeholder="Email" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <div class="remember-forgot-container">
                    <label><input type="checkbox">Remember Me</label>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            <p class="signup-link">Don't have an account? <a href="register.html">Register</a></p>
        </section>
    </main>

    <footer>
        <div class="footer-container">
            <p>© 2024 BetterMoodle</p>
            <a href="#">About Us</a> | <a href="#">Contact Us</a>
        </div>
    </footer>

<script src="js/loginscript.js"></script>
</body>
</html>
