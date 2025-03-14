<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Harzarian</title>
        <link rel="stylesheet" href="../../css/modules.css">
    </head>
    <body>
        <!-- Page Header -->
        <header>
            <div class="container">
                <!-- 'href' When clicked directs to specified page -->
                <a href="../index.php">
                    <h1>Harzarian</h1>
                </a>    
                <nav>
                    <?php if (isset($_SESSION['email'])): ?>
                        <a style="color: white;">Welcome <?php echo htmlspecialchars($_SESSION['firstName']); ?></a> | 
                        <a href="logout.php">Log Out</a>
                    <?php else: ?>
                        <a href="../login.php?action=login">Login</a> |
                        <a href="../login.php?action=register">Register</a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>
        
        <main>
            <!--Lesson Title-->
            <div class="lessoninfocontainer">
                <h2>Lesson 1: Lorem Ipsum</h2>
                <!--Lesson Information-->
                <section class="lessoninformation">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet libero eget felis cursus vehicula. Curabitur hendrerit elit at orci condimentum, sed suscipit nunc sodales. Nullam ac dolor malesuada, feugiat magna et, volutpat libero. Fusce lacinia turpis eu facilisis interdum. Maecenas pharetra, turpis nec tincidunt volutpat, nisl erat facilisis risus, non cursus nisi libero ac dui.</p>
                    <ul style="list-style-type:square;">
                        <li>Lorem ipsum dolor sit amet</li>
                        <li>Consectetur adipiscing elit</li>
                        <li>Curabitur hendrerit elit at orci condimentum</li>
                        <li>Nullam ac dolor malesuada feugiat magna</li>
                    </ul>
                    <p>Ut id purus vel odio elementum viverra. Sed varius, nisi eget tempor viverra, nisi lorem condimentum metus, at scelerisque justo nulla ac odio. Integer malesuada dui lorem, ac dictum ante suscipit sed.</p>
                </section>
            </div>     
            <!-- Back Button -->
            <div class="returnhome">
                <a href="moduleinfo.html" class="returnbutton">Back to Lessons</a>
            </div>     
        </main>
        <!-- Page Footer -->
        <footer>
            <div class="footer-container">
                <p>© 2024 Harzarian</p>
                <a href="../../about_us.html">About Us</a> | <a href="../../contact.php">Contact Us</a> | 
                <a href="../../cookies.html">Cookies Policy</a> | <a href="../../privacy_policy.html">Privacy Policy</a>
            </div>
        </footer>
        <!-- Links the page to the JS -->
        <script src="js/indexscript.js"></script>
    </body>
</html>