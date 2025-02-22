<?php
session_start();
include("../connect.php");

if (!isset($_SESSION['email'])) {
    header("Location: profile.php");
    exit();
}

$module_id = isset($_GET['module_id']) ? (int)$_GET['module_id'] : 1;
$stmt = $conn->prepare("SELECT name FROM modules WHERE id = ?");
$stmt->bind_param("i", $module_id);
$stmt->execute();
$module = $stmt->get_result()->fetch_assoc();
$module_name = $module ? $module['name'] : 'Unknown Module';

// Handle document upload (for teachers only)
if ($_SESSION['role'] === 'teacher' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_document'])) {
    $unit_id = (int)$_POST['unit_id'];
    $file = $_FILES['document'];

    if ($file && $file['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "../uploads/"; // Changed to "../uploads/" to place in Better-moodle/uploads/
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($file['name']);
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_types = ['pdf', 'docx', 'pptx'];

        if (in_array($file_type, $allowed_types)) {
            $target_file = $upload_dir . $file_name;
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO documents (unit_id, file_name, file_path, uploaded_by) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("issi", $unit_id, $file_name, $target_file, $_SESSION['user_id']);
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Document uploaded successfully";
                } else {
                    $_SESSION['error'] = "Failed to save document: " . $conn->error;
                }
            } else {
                $_SESSION['error'] = "Failed to upload file: " . error_get_last()['message'];
            }
        } else {
            $_SESSION['error'] = "Only PDF, PPTX, and DOCX files are allowed.";
        }
    } else {
        $_SESSION['error'] = "No file uploaded or upload error: " . ($file['error'] ? "Error code " . $file['error'] : "No file uploaded");
    }
    header("Location: moduleinfo.php?module_id=" . $module_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harzarian - <?php echo htmlspecialchars($module_name); ?></title>
    <link rel="stylesheet" href="../../css/moduleinfo.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="../index.php"><h1>Harzarian</h1></a>    
            <nav>
                <a>Welcome <?php echo htmlspecialchars($_SESSION['firstName']); ?></a>
            </nav>
        </div>
    </header>

    <main>
        <section class="moduleinfotitle">
            <h1><?php echo htmlspecialchars($module_name); ?></h1>
        </section>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<p class="error">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<p class="success">' . $_SESSION['success'] . '</p>';
            unset($_SESSION['success']);
        }
        ?>

        <!-- Display Units as Cards -->
        <?php
        $stmt = $conn->prepare("SELECT id, title, description FROM units WHERE module_id = ? ORDER BY id");
        $stmt->bind_param("i", $module_id);
        $stmt->execute();
        $units = $stmt->get_result();

        while ($unit = $units->fetch_assoc()) {
            echo '<section class="card">';
            echo '<h2>' . htmlspecialchars($unit['title']) . '</h2>';
            echo '<p>' . htmlspecialchars($unit['description']) . '</p>';

            // Fetch and display documents as smaller cards
            $doc_stmt = $conn->prepare("SELECT file_name, file_path FROM documents WHERE unit_id = ?");
            $doc_stmt->bind_param("i", $unit['id']);
            $doc_stmt->execute();
            $documents = $doc_stmt->get_result();

            if ($documents->num_rows > 0) {
                echo '<div class="document-container">';
                while ($doc = $documents->fetch_assoc()) {
                    echo '<section class="card document-card">';
                    echo '<a href="' . htmlspecialchars($doc['file_path']) . '" target="_blank" class="button" style="display: block; text-align: center;">' . htmlspecialchars($doc['file_name']) . '</a>';
                    echo '</section>';
                }
                echo '</div>';
            } else {
                echo '<p>No documents available.</p>';
            }
            $doc_stmt->close();

            // Teacher upload form within the unit card
            if ($_SESSION['role'] === 'teacher') {
                echo '<form method="POST" action="" enctype="multipart/form-data" class="teacher-actions">';
                echo '<input type="hidden" name="unit_id" value="' . $unit['id'] . '">';
                echo '<input type="file" name="document" accept=".pdf,.docx,.pptx" required style="margin-bottom: 0.5rem;">';
                echo '<button type="submit" name="upload_document" class="button">Upload Document</button>';
                echo '</form>';
            }
            echo '</section>';
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
            <a href="../../about_us.html">About Us</a> | <a href="../../contact.html">Contact Us</a> | 
            <a href="../../cookies.html">Cookies Policy</a> | <a href="../../privacy_policy.html">Privacy Policy</a>
        </div>
    </footer>
    <script src="../js/indexscript.js"></script>
</body>
</html>