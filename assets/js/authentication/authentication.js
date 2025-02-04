$("#login-form").on('submit', function(event){
    event.preventDefault();
    const email = $("#login-email").val();
    const password = $("#login-password").val();
    const action = 'login';


    $.ajax({
        url: '/login',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({email, password, action}),
        success: function(response) {
            if (response.status === 'success') {
                window.location.href = response.redirect; // Redirect to the appropriate page
            } else {
                alert(response.message); // Show error message if login fails
            }
        },
        error: function() {
            alert("An error occurred. Please try again.");
        }
        })
    })

$("#register-form").on('submit', function(event){
    event.preventDefault();

    const username = $("#register-username").val();
    const email = $("#register-email").val();
    const password = $("#register-password").val();
    const phone = $("#register-phone").val();

    alert('yow');

    $.ajax({
        url: '/register',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ username, email, password,  phone}),
        success: function(response) {
            data = JSON.parse(response);

            alert(data.message);

        }
        
    })
})