<?php
session_start();
include("../connect.php");

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: profile.php");
    exit();
}

$course_id = $_SESSION['course_id'];
$stmt = $conn->prepare("SELECT name FROM courses WHERE id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();
$course_name = $course ? $course['name'] : 'Unknown Course';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harzarian - Modules</title>
    <link rel="stylesheet" href="../../css/modules.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="../../index.html"><h1>Harzarian</h1></a>    
            <nav>
                <a>Welcome <?php echo $_SESSION['firstName']; ?></a>
            </nav>
        </div>
    </header>
    
    <main>
        <div class="modulescontainer1">
            <marquee><b>Attention Students!</b> The library will be closed for the rest of the day due to a special event. Please make sure to return any overdue books before the end of lunch. <b>Important Announcement!</b> All students are reminded to stay in designated areas during lunch. Please do not leave the cafeteria without permission. <b>Special Event!</b> Next Tuesday, we’ll be having a guest speaker from the local university. The talk will focus on college admissions and career planning. All students are welcome to attend in the auditorium at 2:00 PM.</marquee>
        </div>
        <div class="modulescontainer2">
            <h1><?php echo htmlspecialchars($course_name); ?> - Your Modules</h1>
        </div>
        <div class="modulescontainer3">
            <section class="modulesboxsection">
                <?php
                $stmt = $conn->prepare("SELECT id, name, image_path FROM modules WHERE course_id = ?");
                $stmt->bind_param("i", $course_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows === 0) {
                    echo "<p>No modules available for your course.</p>";
                }
                while ($module = $result->fetch_assoc()) {
                    echo '<div class="box">';
                    echo '<a href="moduleinfo.php?module_id=' . $module['id'] . '">';
                    echo '<img src="../../media/courses/' . htmlspecialchars($module['image_path']) . '" alt="' . htmlspecialchars($module['name']) . '">';
                    echo '<h3>' . htmlspecialchars($module['name']) . '</h3>';
                    echo '</a>';
                    echo '</div>';
                }
                ?>
            </section>
        </div>
    </main>
    <footer>
        <div class="footer-container">
            <p>© 2024 Harzarian</p>
            <a href="../../about_us.html">About Us</a> | <a href="../../contact.html">Contact Us</a> | 
            <a href="../../cookies.html">Cookies Policy</a> | <a href="../../privacy_policy.html">Privacy Policy</a>
        </div>
    </footer>
    <script src="../js/indexscript.js"></script>
</body>
</html>