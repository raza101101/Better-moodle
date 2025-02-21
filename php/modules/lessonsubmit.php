<?php
session_start();
include("connect.php");

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: profile.php");
    exit();
}

$assignment_id = $_GET['assignment_id'] ?? 0;
$stmt = $conn->prepare("SELECT a.assignment_title, a.description, a.due_date, a.module_id, u.firstName, u.lastName 
                       FROM assignments a 
                       JOIN users u ON a.teacher_id = u.id 
                       WHERE a.id = ?");
$stmt->bind_param("i", $assignment_id);
$stmt->execute();
$assignment = $stmt->get_result()->fetch_assoc();

if (!$assignment) {
    header("Location: modules.php");
    exit();
}

$module_stmt = $conn->prepare("SELECT name FROM modules WHERE id = ?");
$module_stmt->bind_param("i", $assignment['module_id']);
$module_stmt->execute();
$module = $module_stmt->get_result()->fetch_assoc();
$module_name = $module['name'];

// Handle submission
if (isset($_FILES['document'])) {
    $file = $_FILES['document'];
    $upload_dir = "uploads/";
    $file_name = time() . "_" . basename($file['name']);
    $target_file = $upload_dir . $file_name;
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO submissions (assignment_id, student_id, submission_file, submission_date) 
                               VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $assignment_id, $_SESSION['user_id'], $file_name);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Assignment submitted successfully";
            header("Location: moduleinfo.php?module_id=" . $assignment['module_id']);
            exit();
        }
    }
    $_SESSION['error'] = "Failed to submit assignment";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harzarian - Submit Assignment</title>
    <link rel="stylesheet" href="../css/modules.css">
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
        <div class="submissioncontainer">
            <?php
            if(isset($_SESSION['error'])) {
                echo '<p class="error">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
            if(isset($_SESSION['success'])) {
                echo '<p class="success">' . $_SESSION['success'] . '</p>';
                unset($_SESSION['success']);
            }
            ?>
            <div class="assignmentinfo">
                <h1><?php echo htmlspecialchars($assignment['assignment_title']); ?> Submission</h1>
                <p><?php echo htmlspecialchars($assignment['description']); ?></p>
                <div class="duedate">
                    <strong>Due Date:</strong> <?php echo $assignment['due_date']; ?>
                </div>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="document">Choose a document to upload (PDF/DOCX):</label>
                <input type="file" id="document" name="document" accept=".pdf,.docx" required>
                <button type="submit">Submit</button>
            </form>
        </div>
        <div class="returnhome">
            <a href="moduleinfo.php?module_id=<?php echo $assignment['module_id']; ?>" class="returnbutton">Back to Lessons</a>
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