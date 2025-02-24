<?php
session_start(); // Start the session at the top
include("php/connect.php"); // Use your Harzarian database connection (adjust path if needed)

// Check if user is logged in (optional, but useful for tracking)
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Harzarian</title>
        <link rel="stylesheet" href="css/contact.css">
    </head>
    <body>
        <header>
            <div class="container">
                <a href="php/index.php">
                    <h1>Harzarian</h1>
                </a>
                <nav>
                    <?php if (isset($_SESSION['email'])): ?>
                        <a>Welcome <?php echo htmlspecialchars($_SESSION['firstName']); ?></a> | 
                        <a href="php/logout.php">Log Out</a>
                    <?php else: ?>
                        <a href="php/login.php?action=login">Login</a> |
                        <a href="php/login.php?action=register">Register</a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>
        <main>
            <!-- bulk of webpage--> 
            <!-- Formatting for info container -->
            <div style="background-image: url(media/Contactus/Background.png); height: 356px; width: 100vw;" >
            <section class="contact-containerUpper">
                <h2 class="contactH1">Contact us</h2>

                <h3 class="contactH2"> Do you have a question or need to get in touch? </h3>
                <p class="contactP1"> We have availability for any inquiry, so if you have any concerns or problems, please contact us—we would like to hear from you.</p>
            </section>

            <!-- Input for user to submit a query -->  
            <section class="query-form">
                <h2>Submit Your Query</h2>
                <form id="contactForm" action="php/submit_query.php" method="POST" style="padding: 10px; transform: translateX(-12px);">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" required>
            
                    <label for="query">Your Query:</label>
                    <textarea id="query" name="query" rows="4" required style="resize: none;"></textarea>
            
                    <button type="submit">Submit</button>
                </form>
                <p id="responseMessage"></p>
            </section>
            
            <!-- contact section -->
            <h1 id="staffH"> Staff Contact Information </h1>
            <section class="contact-containerLower">
                <div id="kieraninfo">
                    <ol id="ContactList">
                        <div id="imgstyle"><img src="media/Contactus/Kieran.png" alt="Kieran Image"></div>
                        <li><h5>Kieran Taylor</h5></li>
                        <li>Id: 23005804</li>
                        <li>Phone number: 07984365626</li>
                        <li><a href="/cdn-cgi/l/email-protection#093b3a39393c31393d496166796c27686a277c62">Email: <span class="__cf_email__" data-cfemail="1b29282b2b2e232b2f5b73746b7e357a78356e70">[email&#160;protected]</span></a></li>
                    </ol>       
                </div>

                <div id="harryinfo">
                    <ol id="ContactList">
                        <div id="imgstyle"><img src="media/Contactus/HarryImg.png" alt="Neo Image"></div>
                        <li><h5>Harry Neophytou</h5></li>
                        <li>Id: 23002170</li>
                        <li>Phone number: 07938579876</li>
                        <li><a href="/cdn-cgi/l/email-protection#fccecfcccccecdcbccbc94938c99d29d9fd28997">Email: <span class="__cf_email__" data-cfemail="475574777775767077072f28372269262469322c">[email&#160;protected]</span></a></li>
                    </ol>       
                </div>

                <div id="razainfo">
                    <ol id="ContactList">
                        <div id="imgstyle"><img src="media/Contactus/Blank.jpg" alt="Blank Image"></div>
                        <li><h5>Hasan R Hussain</h5></li>
                        <li>Id: 22012822</li>
                        <li>Phone number: 07948743896</li>
                        <li><a href="/cdn-cgi/l/email-protection#31030301000309030371595e41541f50521f445a">Email: <span class="__cf_email__" data-cfemail="deececeeeeefece6ecec9eb6b1aebbf0bfbdf0abb5">[email&#160;protected]</span></a></li>
                </div> 

                <div id="jessieinfo">
                    <ol id="ContactList">
                        <div id="imgstyle"><img src="media/Contactus/Jessie.jpg" alt="Jessie Image"></div>
                        <li><h5>Jessie Dawson</h5></li>
                        <li>Id: 23012213</li>
                        <li>Phone number: 07940499436</li>
                        <li><a href="/cdn-cgi/l/email-protection#5c6e6f6c6d6e6e6d6f1c34332c39723d3f722937">Email: <span class="__cf_email__" data-cfemail="8fbdbcbfbebdbdbebccfe7e0ffeaa1eeeca1fae4">[email&#160;protected]</span></a></li>
                </div>    

                <div id="Jermaineinfo">
                    <ol id="ContactList">
                        <div id="imgstyle"><img src="media/Contactus/Blank.jpg" alt="Blank Image"></div>
                        <li><h5>Jermaine Gardener</h5></li>
                        <li>Id: 23006783</li>
                        <li>Phone number: 07960394024</li>
                        <li><a href="/cdn-cgi/l/email-protection#6b59585b5a59595a5a2b03041b0e450a08451e00">Email: <span class="__cf_email__" data-cfemail="241617141412131c17644c4b54410a45470a514f">[email&#160;protected]</span></a></li>
                </div>    
            </section>
        </main>
        <!-- Page Footer -->
        <footer>
            <div class="footer-container">
                <p>© 2024 Harzarian</p>
                <a href="about_us.html">About Us</a> | <a href="contact.php">Contact Us</a> | 
                <a href="cookies.html">Cookies Policy</a> | <a href="privacy_policy.html">Privacy Policy</a>
            </div>
        </footer>
        <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
        <script src="js/contactscript.js"></script>
    </body>
</html>