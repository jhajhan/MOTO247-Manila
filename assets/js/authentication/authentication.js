$(document).ready(function() {

   
    // Check if the user is logged in on page load
        $.ajax({
            url: '/check-login-status', // The PHP file to check login status
            method: 'GET',
            contentType: 'application/json',
            success: function(data) {
                
        
                
                if (data.status === 'success' && data.isLoggedIn) {
                    // User is logged in, show the profile settings
                    $('#login-register-modal').hide();
                    $('#profile-settings').show();
                } else {
                    // User is not logged in, show the login/register modal
                    $('#profile-settings').hide();
                    $('#login-register-modal').show();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("AJAX error: " + textStatus + " - " + errorThrown);
                alert("Error checking login status.");
            }
    });
});


// Login Form Submit
$("#login-form").on('submit', function(event) {
    event.preventDefault();

    const email = $("#login-email").val();
    const password = $("#login-password").val();
    const action = 'login';

    $.ajax({
        url: '/login',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ email, password, action }),
        success: function(response) {
            console.log(response);  // Debugging line: Log the response to check

            if (response.status === 'success') {
                // Hide the login/register modal and show profile settings
                $('#login-register-modal').hide();
                $('#profile-settings').show();

                // Optionally redirect if needed
                window.location.href = response.redirect;  // Redirect to the appropriate page
            } else {
                alert(response.message);  // Show error message if login fails
            }
        },
        error: function() {
            alert("An error occurred. Please try again.");
        }
    });
});

// Register Form Submit
$("#register-form").on('submit', function(event) {
    event.preventDefault();

    const username = $("#register-username").val();
    const email = $("#register-email").val();
    const password = $("#register-password").val();
    const phone = $("#register-phone").val();

    $.ajax({
        url: '/register',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ username, email, password, phone }),
        success: function(response) {
            console.log(response);  // Debugging line: Log the response to check

            if (response.status === 'success') {
                alert(response.message);  // Show success message
                $('#login-register-modal').hide();
                // $('#profile-settings').show();
                $(".modal").show();
                window.location.href = response.redirect;  // Redirect to the appropriate page
            } else {
                alert(response.message);  // Show error message if registration fails
            }
        },
        error: function() {
            alert("An error occurred. Please try again.");
        }
    });
});
