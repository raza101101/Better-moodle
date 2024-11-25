// LOGIN/REGISTER SCRIPTS

document.addEventListener('DOMContentLoaded', function () {
    // Get references to sections and links
    const loginSection = document.getElementById('login-section');
    const registerSection = document.getElementById('register-section');
    const toRegisterLink = document.getElementById('toRegister');
    const toLoginLink = document.getElementById('toLogin');

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

    // Event listeners for switching forms
    if (toRegisterLink) {
        toRegisterLink.addEventListener('click', function (event) {
            event.preventDefault();
            loginSection.style.display = 'none';
            registerSection.style.display = 'block';
        });
    }


});

