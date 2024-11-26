<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Harzarian</title>
        <link rel="stylesheet" href="../css/style.css"> <!-- Uses the same CSS for consistency -->
    </head>
    <body>
        <!-- Page Header -->
        <header>
            <div class="container">
                <a href="../index.html">
                    <h1>Harzarian</h1>
                </a>    
                <nav>
                    <a href="login.php?action=login">Login</a>
                    <a href="login.php?action=register">Register</a>
                </nav>
            </div>
        </header>

        <main>
            <!-- Login Section -->
            <section id="login" class="login-container">
                <h2>Login</h2>
                <form action="login.php?action=login" method="POST" class="login-form">
                    <input type="email" id="email" name="email" placeholder="Email" required>
                    <input type="password" id="password" name="password" placeholder="Password" required>
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
                <form action="login.php?action=register" method="POST" class="login-form">
                    <input type="text" id="firstname" name="firstname" placeholder="First Name" required>
                    <input type="text" id="lastname" name="lastname" placeholder="Last Name" required>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <button type="submit" class="btn" name="register">Register</button>
                </form>
                <p class="signup-link">Already have an account? <a href="login.php?action=login">Log In</a></p>
            </section>
        </main>



        <footer>
            <div class="footer-container">
                <p>© 2024 Harzarian</p>
                <a href="about_us.html">About Us</a> | <a href="contact.html">Contact Us</a>
            </div>
        </footer>
        <script src="../js/loginscript.js"></script>
    </body>
</html>