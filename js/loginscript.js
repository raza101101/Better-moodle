// LOGIN/REGISTER SCRIPTS

document.addEventListener('DOMContentLoaded', function () {
    // Get references to sections and links
    const loginSection = document.getElementById('login');
    const registerSection = document.getElementById('register');


    // Handle URL query parameter for switching
    const params = new URLSearchParams(window.location.search);
    const action = params.get('action');

    if (action === 'register') {
        loginSection.style.display = 'none';
        registerSection.style.display = 'block';
    } else {
        loginSection.style.display = 'block';
        registerSection.style.display = 'none';
    }
});

