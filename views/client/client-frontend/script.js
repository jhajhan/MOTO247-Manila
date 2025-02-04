const wrapper = document.querySelector('.wrapper');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
const loginForm = document.querySelector('.form-box.login form');
const registerForm = document.querySelector('.form-box.register form');

// Clear all input fields in a form
function clearInputs(form) {
    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => input.value = '');
}

// Switch to register
registerLink.addEventListener('click', () => {
    wrapper.classList.add('active');
    clearInputs(loginForm); // Clear login form inputs if the user switches to regis
});

// Switch to login
loginLink.addEventListener('click', () => {
    wrapper.classList.remove('active');
    clearInputs(registerForm); // Clear register form if the user switches to login
});
