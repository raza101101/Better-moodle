<?php
// timetable_display.php
include("connect.php"); // Include the database connection

$course_id = $_SESSION['course_id'] ?? null; // Safely get course_id with null coalescing operator

if ($course_id === null) {
    echo "<p>Error: Course ID not found in session. Please log in again.</p>";
    exit();
}

$stmt = $conn->prepare("SELECT day_of_week, time_slot, event_name, event_description FROM timetables WHERE course_id = ? ORDER BY FIELD(day_of_week, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), time_slot");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize timetable array for the week (Feb 16–22, 2025)
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$times = ['08:00:00', '09:00:00', '10:00:00', '11:00:00', '12:00:00', '13:00:00', '14:00:00', '15:00:00', '16:00:00', '17:00:00'];
$timetable = array_fill_keys($days, array_fill_keys($times, ['event_name' => '', 'event_description' => '']));

while ($row = $result->fetch_assoc()) {
    $timetable[$row['day_of_week']][$row['time_slot']] = [
        'event_name' => $row['event_name'],
        'event_description' => $row['event_description']
    ];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harzarian - Timetable</title>
    <link rel="stylesheet" href="../css/timetable_display.css"> <!-- Include your existing CSS for consistency -->
    <style>
        /* Timetable Styling */
        .timetable-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 1200px;
            margin: 2rem auto;
        }

        .timetable {
            flex: 1;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            margin-left: 2rem;
            max-width: 700px;
        }

        .timetable table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .timetable th, .timetable td {
            border: 1px solid #ccc;
            padding: 0.5rem;
            text-align: center;
        }

        .timetable th {
            background-color: #004080;
            color: #fff;
        }

        .timetable td {
            background-color: #e6f0ff;
        }

        .timetable .time-slot {
            font-weight: bold;
        }

        /* Event Box Styling */
        .event-box {
            background-color: #e6f0ff;
            padding: 0.3rem;
            border-radius: 4px;
            max-height: 40px; /* Limit height to crop content */
            overflow: hidden; /* Crop content that doesn’t fit */
            cursor: pointer; /* Indicate clickable */
            transition: max-height 0.3s ease; /* Smooth transition for hover */
        }

        .event-box:hover {
            max-height: 80px; /* Expand slightly on hover to show more, if needed */
            overflow: auto; /* Allow scrolling if content overflows after expansion */
        }

        /* Modal Styling */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
            z-index: 1000;
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 80%;
            max-width: 500px;
            position: relative;
        }

        .modal-content h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #000;
        }

        .modal-content p {
            font-size: 1rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .close-modal {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            color: #000;
            cursor: pointer;
            border: none;
            background: none;
            padding: 0;
        }

        .close-modal:hover {
            color: #004080;
        }
    </style>
</head>
<body>
    <h2>Timetable (Feb 16 – 22, 2025)</h2>
    <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
        <button style="background-color: #e6f0ff; border: 1px solid #ccc; padding: 0.5rem; border-radius: 4px; cursor: pointer; margin-right: 0.5rem;">Today</button>
        <button style="background-color: #e6f0ff; border: 1px solid #ccc; padding: 0.5rem; border-radius: 4px; cursor: pointer;"><</button>
        <button style="background-color: #e6f0ff; border: 1px solid #ccc; padding: 0.5rem; border-radius: 4px; cursor: pointer;">></button>
    </div>
    <table>
        <tr>
            <th></th>
            <?php foreach ($days as $day): ?>
                <th><?php echo substr($day, 0, 3) . ' ' . date('m/d', strtotime('2025-02-16 ' . $day)); ?></th>
            <?php endforeach; ?>
        </tr>
        <?php foreach ($times as $time): ?>
            <tr>
                <td class="time-slot"><?php echo date('h:00 a', strtotime($time)); ?></td>
                <?php foreach ($days as $day): ?>
                    <td>
                        <?php if ($timetable[$day][$time]['event_name']): ?>
                            <div class="event-box" data-modal-content='{"event_name": "<?php echo htmlspecialchars($timetable[$day][$time]['event_name']); ?>", "event_description": "<?php echo htmlspecialchars($timetable[$day][$time]['event_description']); ?>"}'>
                                <?php echo htmlspecialchars(substr($timetable[$day][$time]['event_name'], 0, 20)); // Limit to 20 characters for cropping ?>
                                <?php if (strlen($timetable[$day][$time]['event_name']) > 20) echo '...'; ?>
                            </div>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tr>
    </table>

    <!-- Modal -->
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3 id="modalEventName"></h3>
            <p id="modalEventDescription"></p>
        </div>
    </div>

    <script>
        // JavaScript for modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const eventBoxes = document.querySelectorAll('.event-box');
            const modal = document.getElementById('eventModal');
            const closeModal = document.querySelector('.close-modal');
            const modalEventName = document.getElementById('modalEventName');
            const modalEventDescription = document.getElementById('modalEventDescription');

            eventBoxes.forEach(box => {
                box.addEventListener('click', function() {
                    const content = JSON.parse(this.getAttribute('data-modal-content'));
                    modalEventName.textContent = content.event_name;
                    modalEventDescription.textContent = content.event_description;
                    modal.style.display = 'block';
                });
            });

            closeModal.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>