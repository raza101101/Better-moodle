<?php
session_start();
include("../connect.php");

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: profile.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Fetch due assignments for the student's modules, excluding submitted ones
$stmt = $conn->prepare("SELECT a.id as assignment_id, a.assignment_title, a.description, a.due_date, a.teacher_id, u.firstName, u.lastName, m.id as module_id 
                       FROM assignments a 
                       JOIN modules m ON a.module_id = m.id 
                       JOIN users u ON a.teacher_id = u.Id 
                       WHERE m.course_id = ? 
                       AND a.due_date >= CURDATE() 
                       AND a.id NOT IN (SELECT assignment_id FROM submissions WHERE student_id = ?) 
                       ORDER BY a.due_date ASC");
$stmt->bind_param("ii", $_SESSION['course_id'], $student_id);
$stmt->execute();
$assignments = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harzarian - My Assignments</title>
    <link rel="stylesheet" href="../../css/assignments.css"> <!-- Use modules.css for consistency with card design -->
</head>
<body>
    <header>
        <div class="container">
            <a href="../index.php"><h1>Harzarian</h1></a>    
            <nav>
                    <?php if (isset($_SESSION['email'])): ?>
                        <a>Welcome <?php echo htmlspecialchars($_SESSION['firstName']); ?></a> | 
                        <a href="../logout.php">Log Out</a>
                    <?php else: ?>
                        <a href="../login.php?action=login">Login</a>
                        <a href="../login.php?action=register">Register</a>
                    <?php endif; ?>
                </nav>
        </div>
    </header>
    
    <main>
        <h1 style="text-align: center; margin-bottom: 2rem;">My Assignments</h1>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<p class="error">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<p class="success">' . $_SESSION['success'] . '</p>';
            unset($_SESSION['success']);
        }

        if ($assignments->num_rows > 0) {
            while ($assignment = $assignments->fetch_assoc()) {
                echo '<section class="card">';
                echo '<h2>' . htmlspecialchars($assignment['assignment_title']) . '</h2>';
                echo '<p><strong>Description:</strong> ' . htmlspecialchars($assignment['description']) . '</p>';
                echo '<p><strong>Due Date:</strong> ' . htmlspecialchars($assignment['due_date']) . '</p>';
                echo '<p><strong>Teacher:</strong> ' . htmlspecialchars($assignment['firstName'] . ' ' . $assignment['lastName']) . '</p>';
                echo '<a href="lessonsubmit.php?assignment_id=' . $assignment['assignment_id'] . '" class="button" style="display: inline-block; margin-top: 0.5rem;">Submit</a>';
                echo '</section>';
            }
        } else {
            echo '<p style="text-align: center;">No due assignments found.</p>';
        }
        $stmt->close();
        ?>

        <div class="returnhome">
            <a href="modules.php" class="returnbutton">Back to Modules</a>
        </div>
    </main>

    <footer>
        <div class="footer-container">
            <p>© 2024 Harzarian</p>
            <a href="../../about_us.html">About Us</a> | <a href="../../contact.php">Contact Us</a> | 
            <a href="../../cookies.html">Cookies Policy</a> | <a href="../../privacy_policy.html">Privacy Policy</a>
        </div>
    </footer>
    <script src="../js/indexscript.js"></script>
</body>
</html>