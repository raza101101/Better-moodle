<?php
session_start();
include("connect.php");

$errors = []; // Initialize errors at the beginning

function sanitize_input($data) {
    if ($data === null || $data === '') {
        return '';
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'teacher') {
    header("Location: profile.php");
    exit();
}

// Fetch the teacher's course_id from the users table
$email = $_SESSION['email'];
$course_id = null;

$query = $conn->prepare("SELECT course_id FROM users WHERE email = ? AND role = 'teacher'");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();

if ($row = $result->fetch_assoc()) {
    $course_id = $row['course_id'];
    $_SESSION['course_id'] = $course_id; // Store course_id in session to persist it
} else {
    $errors[] = "Could not retrieve your course ID.";
}
$query->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $course_id !== null) {
    $day_of_week = sanitize_input($_POST['day_of_week'] ?? '');
    $time_slot = $_POST['time_slot'] ?? '';
    $event_name = sanitize_input($_POST['event_name'] ?? '');
    $event_description = sanitize_input($_POST['event_description'] ?? '');

    if (empty($day_of_week)) {
        $errors[] = "Day of Week is required.";
    }
    if (empty($time_slot)) {
        $errors[] = "Time Slot is required.";
    }
    if (empty($event_name)) {
        $errors[] = "Event Name is required.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO timetables (course_id, day_of_week, time_slot, event_name, event_description) VALUES (?, ?, ?, ?, ?) 
                                ON DUPLICATE KEY UPDATE event_name = VALUES(event_name), event_description = VALUES(event_description)");
        $stmt->bind_param("issss", $course_id, $day_of_week, $time_slot, $event_name, $event_description);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Timetable updated successfully"; // Set success message in session
            header("Location: profile.php"); // Redirect back to profile.php, where timetable_display.php is included
            exit();
        } else {
            $errors[] = "Error updating timetable. Please try again: " . $conn->error;
        }
        
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Timetable</title>
    <link rel="stylesheet" href="../css/edit_timetable.css?v=<?php echo time(); ?>">
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
        <div class="card">
            <h2>Edit Timetable</h2>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($errors)) {
                echo "<ul class='error-list'>";
                foreach ($errors as $error) {
                    echo "<li>$error</li>";
                }
                echo "</ul>";
            }
            ?>
            <form method="post">
                <label for="day_of_week">Day of Week:</label>
                <select id="day_of_week" name="day_of_week">
                    <option value="">Select a day</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
                
                <label for="time_slot">Time Slot:</label>
                <input type="time" id="time_slot" name="time_slot">
                
                <label for="event_name">Event Name:</label>
                <input type="text" id="event_name" name="event_name" placeholder="Enter event name">
                
                <label for="event_description">Event Description:</label>
                <textarea id="event_description" name="event_description" placeholder="Enter event description"></textarea>
                
                <button type="submit">Update Timetable</button>
            </form>
        </div>
    </main>
    <footer>
        <div class="footer-container">
            <p>© 2024 Harzarian</p>
            <a href="../about_us.html">About Us</a> | <a href="../contact.html">Contact Us</a> | 
            <a href="../cookies.html">Cookies Policy</a> | <a href="../privacy_policy.html">Privacy Policy</a>
        </div>
    </footer>
</body>
</html>