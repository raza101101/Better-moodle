// LOGIN/REGISTER SCRIPTS

//  Login/Register Hide/Show script
// Get references to elements
const toRegisterLink = document.getElementById('toRegister');
const toLoginLink = document.getElementById('toLogin');
const loginSection = document.getElementById('login');
const registerSection = document.getElementById('register');

// Add event listeners for switching forms
toRegisterLink.addEventListener('click', function (event) {
    event.preventDefault(); // Prevent default link behavior
    loginSection.style.display = 'none'; // Hide login form
    registerSection.style.display = 'block'; // Show register form
});

toLoginLink.addEventListener('click', function (event) {
    event.preventDefault(); // Prevent default link behavior
    registerSection.style.display = 'none'; // Hide register form
    loginSection.style.display = 'block'; // Show login form
});
