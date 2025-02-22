// timetable_script.js
document.addEventListener('DOMContentLoaded', function() {
    const eventBoxes = document.querySelectorAll('.event-box');
    const modal = document.getElementById('eventModal');
    const closeModal = document.querySelector('.close-modal');
    const modalEventName = document.getElementById('modalEventName');
    const modalEventDescription = document.getElementById('modalEventDescription');
    const deleteButton = document.querySelector('.delete-button');

    eventBoxes.forEach(box => {
        box.addEventListener('click', function() {
            eventBoxes.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const content = JSON.parse(this.getAttribute('data-modal-content') || '{}');
            if (!content.event_name) {
                console.error('No event data found in modal content');
                return;
            }
            modalEventName.textContent = content.event_name;
            modalEventDescription.textContent = content.event_description;
            modal.style.display = 'block';
            console.log('Modal opened with content:', content);
        });
    });

    closeModal.addEventListener('click', function() {
        modal.style.display = 'none';
        eventBoxes.forEach(b => b.classList.remove('active'));
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
            eventBoxes.forEach(b => b.classList.remove('active'));
        }
    });

    // Delete functionality for teachers with enhanced debugging
    if (deleteButton) {
        deleteButton.addEventListener('click', function() {
            const activeBox = document.querySelector('.event-box.active');
            if (!activeBox) {
                alert('No active timetable slot selected.');
                console.error('No active event box found');
                return;
            }

            const content = JSON.parse(activeBox.getAttribute('data-modal-content') || '{}');
            if (!content.course_id || !content.day_of_week || !content.time_slot) {
                alert('Cannot delete: Missing timetable slot details.');
                console.error('Missing timetable slot details:', content);
                return;
            }

            if (!confirm('Are you sure you want to delete this timetable slot?')) {
                return;
            }

            console.log('Attempting to delete:', content);
            fetch('../php/delete_timetable.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `course_id=${encodeURIComponent(content.course_id)}&day_of_week=${encodeURIComponent(content.day_of_week)}&time_slot=${encodeURIComponent(content.time_slot)}`
            })
            .then(response => {
                console.log('Fetch response:', response);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Fetch data:', data);
                if (data.success) {
                    alert('Timetable slot deleted successfully.');
                    modal.style.display = 'none';
                    location.reload(); // Reload the page to update the timetable
                } else {
                    alert('Failed to delete timetable slot: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Error deleting timetable slot: ' + error.message);
            });
        });
    } else {
        console.warn('Delete button not found in the DOM');
    }
});