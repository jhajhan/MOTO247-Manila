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

document.addEventListener("DOMContentLoaded", function () {
    const menuLinks = document.querySelectorAll(".settings-menu a");
    const sections = document.querySelectorAll(".profile-section");

    function showSection(event, sectionId) {
        event.preventDefault();

        sections.forEach(section => {
            section.style.display = "none";
        });

        const selectedSection = document.getElementById(sectionId);
        if (selectedSection) {
            selectedSection.style.display = "block";
        }
    }

    menuLinks.forEach(link => {
        link.addEventListener("click", function (event) {
            const sectionId = this.getAttribute("onclick").match(/'([^']+)'/)[1];
            showSection(event, sectionId);
        });
    });

    document.getElementById("edit-profile").style.display = "block";
});

document.querySelectorAll('.eye-icon').forEach(icon => {
    icon.addEventListener('click', function() {
        const targetId = this.getAttribute('data-toggle');
        const passwordField = document.getElementById(targetId);

        if (passwordField.type === "password") {
            passwordField.type = "text";
            this.setAttribute("name", "eye-off-outline"); // Change icon
        } else {
            passwordField.type = "password";
            this.setAttribute("name", "eye-outline"); // Change icon back
        }
    });
});

function isUserLoggedIn() {
    // Example: check if a session or cookie exists. You should replace this with your actual check.
    return localStorage.getItem('userLoggedIn') === 'true';
}

// visibility
function toggleAccountView() {
    if (isUserLoggedIn()) {
        // Hide login register, show account profile
        document.getElementById('login-register-modal').style.display = 'none';
        document.getElementById('profile-settings').style.display = 'block';
    } else {
        // Hide account profile, show login register
        document.getElementById('profile-settings').style.display = 'none';
        document.getElementById('login-register-modal').style.display = 'block';
    }
}

// Call the function to check the login status when the page loads
window.onload = function() {
    toggleAccountView();
};

// Example of setting a user as logged in (you can remove or modify this as per your logic)
document.getElementById('account-link').addEventListener('click', function() {
    localStorage.setItem('userLoggedIn', 'true');
    toggleAccountView();
});

document.querySelector('.last-setting-menu').addEventListener('click', function() {
    localStorage.setItem('userLoggedIn', 'false');
    toggleAccountView();
});
