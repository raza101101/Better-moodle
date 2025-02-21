<?php
session_start();
include("../connect.php");

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: profile.php");
    exit();
}

$module_id = isset($_GET['module_id']) ? (int)$_GET['module_id'] : 1;
$stmt = $conn->prepare("SELECT name FROM modules WHERE id = ?");
$stmt->bind_param("i", $module_id);
$stmt->execute();
$module = $stmt->get_result()->fetch_assoc();
$module_name = $module ? $module['name'] : 'Unknown Module';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harzarian - <?php echo htmlspecialchars($module_name); ?></title>
    <link rel="stylesheet" href="../../css/modules.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="../index.html"><h1>Harzarian</h1></a>    
            <nav>
                <a>Welcome <?php echo $_SESSION['firstName']; ?></a>
            </nav>
        </div>
    </header>
    
    <main>
        <section class="moduleinfotitle">
            <h1><?php echo htmlspecialchars($module_name); ?></h1>
        </section>

        <?php
        $stmt = $conn->prepare("SELECT a.id, a.assignment_title, a.description, a.due_date, u.firstName, u.lastName 
                               FROM assignments a 
                               JOIN users u ON a.teacher_id = u.id 
                               WHERE a.module_id = ? AND a.due_date >= CURDATE()");
        $stmt->bind_param("i", $module_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $lesson_count = 1;
        while ($assignment = $result->fetch_assoc()) {
            echo '<section class="moduleinfocontainer">';
            echo '<h2>Lesson ' . $lesson_count . ': ' . htmlspecialchars($assignment['assignment_title']) . '</h2>';
            echo '<div class="buttons">';
            echo '<a href="lessoninfo.php?assignment_id=' . $assignment['id'] . '" class="button">Lesson Information</a>';
            echo '<a href="lessonvideo.php?assignment_id=' . $assignment['id'] . '" class="button">Video</a>';
            echo '<a href="lessonsubmit.php?assignment_id=' . $assignment['id'] . '" class="button">Submit</a>';
            echo '</div>';
            echo '</section>';
            $lesson_count++;
        }
        ?>

        <div class="returnhome">
            <a href="modules.php" class="returnbutton">Back to Modules</a>
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