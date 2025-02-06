$(document).ready(function(){
    fetchProfile();
})

function fetchProfile() {
    $.ajax({
        url: '/profile',
        method: 'GET',
        contentType: 'application/json',
        success: function(response) {
            $("#edit-username").val(response.username);
            $("#edit-email").val(response.email);
            $("#edit-phone").val(response.phone_number);
            $("#edit-address").val(response.address);
            $("#edit-registered").val(response.created_at);
            $("#profile-username").text(response.username);
        }
    })
}

$("#edit-profile-details").on('submit', function(e){
    e.preventDefault();


    
       const username = $("#edit-username").val();
       const email = $("#edit-email").val();
       const phone_no = $("#edit-phone").val();
       const address = $("#edit-address").val();
       const action = "edit_details";
       
       alert(phone_no);

    $.ajax({
        url: '/profile',
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify({username, email, phone_no, address, action}),
        success: function(response) {
            console.log(response);
            alert(response.message);
            fetchProfile();
        }
    })
})

$("#edit-password-details").on('submit', function(e){

    e.preventDefault();
    
    const old_password = $("#old-password").val();
    const new_password = $("#new-password").val();
    const confirm_password = $("#confirm-password").val();
    const action = "edit_password";

    $.ajax({
        url: '/profile',
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify({old_password, new_password, confirm_password, action}),
        success: function(response){
            alert(response.message);
            $("#old-password").val('');
            $("#new-password").val('');
            $("#confirm-password").val('');

        },
        error: function(xhr) {
            console.log(xhr.responseText);  // Error handling
        }
    })
})

$("#sign-out-client").on('click', function(e){

    e.preventDefault();

    if (confirm("Are you sure you want to sign out?")) {
        
        $.ajax({
            url: "/logout",
            method: "POST",
            contentType: "application/json", // Ensure JSON response is handled correctly
            success: function (response) {
                console.log(response); // Debugging: Check if response is received
                if (response.status === "success") {
                    window.location.href = response.redirect; // Redirect on success
                } else {
                    alert(response.message || "Logout failed.");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error); // Debugging
                alert("An error occurred. Please try again.");
            },
        });
    }
})