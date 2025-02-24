<?php
session_start();
include("connect.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Handle unit creation (teachers only)
if ($_SESSION['role'] === 'teacher' && isset($_POST['create_unit'])) {
    $module_id = $_POST['module_id'];
    $title = sanitize_input($_POST['unit_title']);
    $description = sanitize_input($_POST['unit_description']);
    
    $stmt = $conn->prepare("INSERT INTO units (module_id, title, description, created_by) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $module_id, $title, $description, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Unit created successfully";
    } else {
        $_SESSION['error'] = "Failed to create unit: " . $conn->error;
    }
}

// Handle assignment creation (teachers only)
if ($_SESSION['role'] === 'teacher' && isset($_POST['create_assignment'])) {
    $unit_id = $_POST['unit_id'];
    $title = sanitize_input($_POST['title']);
    $description = sanitize_input($_POST['description']);
    $due_date = $_POST['due_date'];
    
    // Fetch module_id from units
    $stmt = $conn->prepare("SELECT module_id FROM units WHERE id = ?");
    $stmt->bind_param("i", $unit_id);
    $stmt->execute();
    $unit = $stmt->get_result()->fetch_assoc();
    $module_id = $unit['module_id'];
    
    $stmt = $conn->prepare("INSERT INTO assignments (teacher_id, module_id, unit_id, assignment_title, description, due_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisss", $_SESSION['user_id'], $module_id, $unit_id, $title, $description, $due_date);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Assignment created successfully";
    } else {
        $_SESSION['error'] = "Failed to create assignment: " . $conn->error;
    }
}

// Handle course key generation (teachers only)
if ($_SESSION['role'] === 'teacher' && isset($_POST['generate_key'])) {
    $course_id = $_POST['course_id'];
    $course_key = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8); // Generate 8-char random key
    
    $stmt = $conn->prepare("INSERT INTO course_keys (course_id, course_key, created_by) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $course_id, $course_key, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Course key generated: $course_key";
    } else {
        $_SESSION['error'] = "Failed to generate course key: " . $conn->error;
    }
}

// Handle timetable editing (teachers only) - Placeholder for editing logic
if ($_SESSION['role'] === 'teacher' && isset($_POST['edit_timetable'])) {
    // This is a placeholder for actual timetable editing logic
    $_SESSION['success'] = "Timetable editing functionality to be implemented";
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harzarian</title>
    <link rel="stylesheet" href="../css/profile.css?v=<?php echo time(); ?>">
</head>
<body>
    <header>
        <div class="container">
            <a href="index.php"><h1>Harzarian</h1></a>    
            <nav>
                <?php if (isset($_SESSION['email'])): ?>
                    <a style="color: white;">Welcome <?php echo htmlspecialchars($_SESSION['firstName']); ?></a> | 
                    <a href="logout.php">Log Out</a>
                <?php else: ?>
                    <a href="login.php?action=login">Login</a> |
                    <a href="login.php?action=register">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    
    <main>
        <div style="text-align: center;">
            <?php
            if(isset($_SESSION['error'])) {
                echo '<p class="error" style="color: #ff0000;">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
            if(isset($_SESSION['success'])) {
                echo '<p class="success" style="color: #00ff00;">' . $_SESSION['success'] . '</p>';
                unset($_SESSION['success']);
            }
            ?>
            <h1 style="font-size: 40px; font-weight: bold;">Hello <?php echo $_SESSION['firstName'] . ' ' . $_SESSION['lastName']; ?>!</h1>
            <p>Role: <?php echo ucfirst($_SESSION['role']); ?> | <a href="logout.php">Log Out</a></p>

            <?php
            $stmt = $conn->prepare("SELECT name FROM courses WHERE id = ?");
            $stmt->bind_param("i", $_SESSION['course_id']);
            $stmt->execute();
            $course = $stmt->get_result()->fetch_assoc();
            $course_name = $course ? htmlspecialchars($course['name']) : 'Not enrolled';
            ?>
            <p>Course: <?php echo $course_name; ?></p>

            <div class="timetable-container">
                <div style="flex: 1; display: flex; flex-direction: column; gap: 1.5rem;">
                    <?php if ($_SESSION['role'] === 'teacher'): ?>
                        <!-- Teacher: Generate Course Key (Card Style) -->
                        <section class="card">
                            <form method="POST" action="">
                                <h2>Generate Course Key</h2>
                                <p>Create a one-time use key for student enrollment.</p>
                                <select name="course_id" required>
                                    <option value="">Select Course</option>
                                    <?php
                                    $result = $conn->query("SELECT id, name FROM courses");
                                    if ($result) {
                                        while ($course = $result->fetch_assoc()) {
                                            echo "<option value='{$course['id']}'>" . htmlspecialchars($course['name']) . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No courses available</option>";
                                        error_log("Error fetching courses: " . $conn->error);
                                    }
                                    ?>
                                </select>
                                <button type="submit" name="generate_key">Generate Key</button>
                            </form>
                        </section>

                        <!-- Teacher: Create Unit (Card Style) -->
                        <section class="card">
                            <form method="POST" action="">
                                <h2>Create Unit</h2>
                                <p>Create a new unit for your modules.</p>
                                <select name="module_id" required>
                                    <option value="">Select Module</option>
                                    <?php
                                    $stmt = $conn->prepare("SELECT id, name FROM modules WHERE course_id = ?");
                                    $stmt->bind_param("i", $_SESSION['course_id']);
                                    $stmt->execute();
                                    $modules = $stmt->get_result();
                                    while ($module = $modules->fetch_assoc()) {
                                        echo "<option value='{$module['id']}'>" . htmlspecialchars($module['name']) . "</option>";
                                    }
                                    $stmt->close();
                                    ?>
                                </select>
                                <input type="text" name="unit_title" placeholder="Unit Title" required style="margin: 0.5rem 0; padding: 0.5rem; border-radius: 4px; border: 1px solid #ccc;">
                                <textarea name="unit_description" placeholder="Unit Description" style="margin: 0.5rem 0; padding: 0.5rem; border-radius: 4px; border: 1px solid #ccc; width: 95.5%;"></textarea>
                                <button type="submit" name="create_unit">Create Unit</button>
                            </form>
                        </section>

                        <!-- Teacher: Create Assignment (Card Style) -->
                        <section class="card">
                            <form method="POST" action="">
                                <h2>Create Assignment</h2>
                                <p>Set a new assignment for your units.</p>
                                <select name="unit_id" required>
                                    <option value="">Select Unit</option>
                                    <?php
                                    $stmt = $conn->prepare("SELECT u.id, u.title, m.name as module_name 
                                                            FROM units u 
                                                            JOIN modules m ON u.module_id = m.id 
                                                            WHERE m.course_id = ?");
                                    $stmt->bind_param("i", $_SESSION['course_id']);
                                    $stmt->execute();
                                    $units = $stmt->get_result();
                                    while ($unit = $units->fetch_assoc()) {
                                        echo "<option value='{$unit['id']}'>" . htmlspecialchars($unit['module_name'] . ' - ' . $unit['title']) . "</option>";
                                    }
                                    $stmt->close();
                                    ?>
                                </select>
                                <input type="text" name="title" placeholder="Assignment Title" required style="margin: 0.5rem 0; padding: 0.5rem; border-radius: 4px; border: 1px solid #ccc;">
                                <textarea name="description" placeholder="Description" style="margin: 0.5rem 0; padding: 0.5rem; border-radius: 4px; border: 1px solid #ccc; width: 95.5%;"></textarea>
                                <input type="date" name="due_date" required style="margin: 0.5rem 0; padding: 0.5rem; border-radius: 4px; border: 1px solid #ccc;">
                                <button type="submit" name="create_assignment">Create Assignment</button>
                            </form>
                        </section>

                        <!-- Teacher: Manage Modules (Card Style) -->
                        <section class="card">
                            <a href="modules/modules.php">
                                <h2>Manage Modules</h2>
                                <p>View and manage your modules, including uploading documents to units.</p>
                            </a>
                        </section>

                        <!-- List of created assignments and submissions (Card Style) -->
                        <section class="card" style="margin-bottom: 500px;">
                            <h2>Your Assignments</h2>
                            <p>View your assignments and submission counts.</p>
                            <?php
                            $stmt = $conn->prepare("SELECT a.*, m.name as module_name, COUNT(s.id) as submission_count 
                                                   FROM assignments a 
                                                   JOIN modules m ON a.module_id = m.id
                                                   LEFT JOIN submissions s ON a.id = s.assignment_id 
                                                   WHERE a.teacher_id = ? 
                                                   GROUP BY a.id");
                            $stmt->bind_param("i", $_SESSION['user_id']);
                            if ($stmt->execute()) {
                                $result = $stmt->get_result();
                                while ($assignment = $result->fetch_assoc()) {
                                    echo "<p style='margin: 0.5rem 0;'>" . htmlspecialchars($assignment['module_name']) . " - " . htmlspecialchars($assignment['assignment_title']) . " ";
                                    echo "(Due: " . $assignment['due_date'] . ") - Submissions: " . $assignment['submission_count'] . "</p>";
                                }
                            } else {
                                echo "<p style='margin: 0.5rem 0; color: #ff0000;'>Error loading assignments: " . $conn->error . "</p>";
                            }
                            $stmt->close();
                            ?>
                        </section>
                    <?php elseif ($_SESSION['role'] === 'student'): ?>
                        <!-- Student: Link to Modules (Card Style) -->
                        <section class="card">
                            <a href="modules/modules.php">
                                <h2>Your Learning</h2>
                                <p>Go to Modules</p>
                            </a>
                        </section>

                        <!-- Show submitted assignments (Card Style) -->
                        <section class="card">
                            <a href="modules/assignments.php">
                                <h2>Your Assignments</h2>
                                <p>Go to Assignments Page</p>
                            </a>
                        </section>
                    <?php endif; ?>
                </div>

                <!-- Timetable for Teachers and Students (beside cards) -->
                <div class="timetable">
                    <?php include 'timetable_display.php'; ?>
                    <?php if ($_SESSION['role'] === 'teacher'): ?>
                        <form method="POST" action="edit_timetable.php" style="text-align: center;">
                            <button type="submit" name="edit_timetable" class="edit-timetable-btn">Edit Timetable</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <div class="footer-container">
            <p>© 2024 Harzarian</p>
            <a href="../about_us.html">About Us</a> | <a href="../contact.php">Contact Us</a> | 
            <a href="../cookies.html">Cookies Policy</a> | <a href="../privacy_policy.html">Privacy Policy</a>
        </div>
    </footer>
    <script src="../js/profilescript.js"></script>
    <!-- Reference the external script -->
    <script src="../js/timetable_script.js"></script>
</body>
</html>