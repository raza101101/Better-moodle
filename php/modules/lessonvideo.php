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
                        <a>Welcome <?php echo htmlspecialchars($_SESSION['firstName']); ?></a> | 
                        <a href="../logout.php">Log Out</a>
                    <?php else: ?>
                        <a href="../login.php?action=login">Login</a> |
                        <a href="../login.php?action=register">Register</a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>
        
        <main>
            <!--Lesson Video Title-->
            <section class="lessonvideocontainer">
                <h1>Lesson 1 Video</h1>
            </section>

            <!--Video Display-->
            <!--Ensure youtube link is an embed link, not watch link. This is easy to do if you look at the link ive preset-->
            <section class="videocontainer">
                <iframe src="https://www.youtube.com/embed/FTEoGK1omug" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </section>
            
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