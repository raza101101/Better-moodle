document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const responseMessage = document.getElementById('responseMessage');

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(form);

        fetch('php/submit_query.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                responseMessage.style.color = '#00ff00';
                responseMessage.textContent = data.message;
                form.reset(); // Clear the form
            } else {
                responseMessage.style.color = '#ff0000';
                responseMessage.textContent = data.message;
            }
        })
        .catch(error => {
            responseMessage.style.color = '#ff0000';
            responseMessage.textContent = 'Error submitting query: ' + error.message;
        });
    });
});