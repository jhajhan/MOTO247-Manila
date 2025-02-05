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
        }
    })
}

$("#edit-profile-details").on('submit', function(e){
    e.preventDefault();
       const $username = $("#edit-username").val();
       const $email = $("#edit-email").val();
       const $phone_no = $("#edit-phone").val();
       const $address = $("#edit-address").val();
       const $action = "edit_details";

    $.ajax({
        url: '/profile',
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify({$username, $email, $phone_no, $address, $action}),
        success: function(response) {
            fetchProfile();
        }
    })
})

$("#edit-password-details").on('click', function(){
    const old_password = $("#edit-password");
    const new_password = $("#new-password");
    const confirm_password = $("#confirm-password");
    const action = "edit_password";

    $.ajax({
        url: '/profile',
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify({old_password, new_password, confirm_password, action}),
        success: function(){
            
        }
    })
})