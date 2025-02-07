$(document).ready(function(){
    fetchSettings();

    const $navLinks = $("#settings-nav a");
    const $sections = $(".section-content");

    // Initially hide all sections
    $sections.hide();

    // Set default section
    const defaultSectionId = "user-management";
    const $defaultSection = $("#" + defaultSectionId);

    if ($defaultSection.length) {
        $defaultSection.show(); // Show the default section
    }

    // Set the corresponding navigation link as active
    $navLinks.each(function () {
        const targetSectionId = $(this).data("section");
        if (targetSectionId === defaultSectionId) {
            $(this).addClass("active");
        }
    });

    // Add click event listener to each navigation link
    $navLinks.click(function (event) {
        event.preventDefault();

        const targetSectionId = $(this).data("section");

        $sections.hide();
        $("#" + targetSectionId).show();

        $navLinks.removeClass("active");
        $(this).addClass("active");
    });
})


function fetchSettings() {
    $.ajax({
        url: '/admin/settings',
        method: 'GET',
        success: function(response) {

            $("#settings-name").val(response.personal_info.full_name);
            $("#settings-email").val(response.personal_info.email);

            $('#store-name').val(response.store_info.name);
            $('#contact-number').val(response.store_info.contact_number);
            $('#store-address').val(response.store_info.address);
            $('#business-hours').val(response.store_info.business_hours);
            $('#gcash-name').val(response.store_info.account_name);
            $('#gcash-number').val(response.store_info.account_no);
            $('#admin-name').text(response.personal_info.username);

            if (response.admins) {
                const adminList = $('#admin-list');
                adminList.empty(); // Clear existing list

                response.admins.forEach(admin => {
                    const listItem = `
                        <li>
                            ${admin.username} 
                            <button type="submit" class="submit-btn remove-admin-btn" data-id="${admin.user_id}">
                                Remove
                            </button>
                        </li>
                    `;
                    adminList.append(listItem);
                });

                // Attach event listener after injecting elements
                $('.remove-admin-btn').on('click', function() {
                    const adminId = $(this).data('id');
                    removeAdmin(adminId);
                });
            }
        }
    })
}

$('#edit-profile-form').on('submit', function(e){
    e.preventDefault();
    const name = $('#settings-name').val();
    const email = $('#settings-email').val();
    const old_password = $('#settings-oldpassword').val();
    const new_password = $('#settings-newpassword').val();
    const confirm_password = $('#settings-confirm-password').val();
    const action = 'update_profile'

    $.ajax({
        url: '/admin/settings',
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify({name, email, old_password, new_password, confirm_password, action}),
        success: function(response) {
            alert(response.message);
            fetchSettings();
        }
    })
})

$('#add-admin-form').on('submit', function(event){

    event.preventDefault();

    const name = $('#admin-name').val();
    const username = $('#admin-username').val();
    const email = $('#admin-email').val();
    const password = $('#admin-password').val();
    const action = 'update_profile'
   

    $.ajax({
        url: '/admin/settings',
        method: "POST",
        contentType: 'application/json',
        data: JSON.stringify({name, username, email, password}),
        success: function(response) {
            console.log(response);
            alert(response['success']);

            $('#settings-name').val('');
            $('#settings-email').val('');
            $('#settings-oldpassword').val('');
            $('#settings-newpassword').val('');
            $('#settings-confirm-password').val('');
            fetchSettings();

        }
    })
})

$('#edit-general-form').on('submit', function(event){
    event.preventDefault();
    const business_name = $('#store-name').val();
    const business_contact = $('#contact-number').val();
    const business_address = $('#store-address').val();
    const business_hours = $('#business-hours').val();
    // const business_logo = $('#general-info-logo').val();
    const action = 'update_general_info';
    alert('hello');

    $.ajax({
        url: '/admin/settings',
        method: "PUT",
        contentType: 'application/json',
        data: JSON.stringify({business_name, business_address, business_contact, business_hours, action}),
        success: function() {
            alert('General Info Updated!');
        }
    })
})


$('#edit-payment-form').on('submit', function(event) {
    event.preventDefault();

    const gcash_account = $('#gcash-name').val();
    const gcash_no = $('#gcash-number').val();
    let gcash_qr = '';
    const action = 'update_payment_info';

    var formData = new FormData();
    var fileInput = $('#gcash-qr')[0].files[0];  // Ensure the file is being correctly selected

    if (fileInput) {
        formData.append('file1', fileInput);

        // First AJAX request to upload image
        $.ajax({
            url: '/upload-image',  // Your PHP upload endpoint
            method: 'POST',
            data: formData,
            contentType: false,  // Don't set content type for FormData
            processData: false,  // Don't process the data (important for file uploads)
            success: function(response) {
                alert('yoh');
                data = JSON.parse(response);

                gcash_qr = data.imageUrl;  // The URL of the uploaded image
                console.log("Image uploaded successfully! URL: " + gcash_qr);

                // Second AJAX request to update payment info
                $.ajax({
                    url: '/admin/settings',
                    method: 'PUT',
                    contentType: 'application/json',  // Proper content type for JSON data
                    data: JSON.stringify({ gcash_account, gcash_no, gcash_qr, action }), // Send JSON data
                    success: function() {
                        alert('Payment Info Updated!');
                    },
                    error: function() {
                        alert('Error updating payment info');
                    }
                });
            },
            error: function() {
                alert('Error uploading image');
            }
        });
    }
});


/*
$("#admin-sign-out").on("click", function (event) {
    event.preventDefault();

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
});*/

$("#admin-sign-out").on("click", function (event) {
    event.preventDefault();

    alertify.confirm(
        'LogOut confirmation', // Title of the confirmation box
        'Are you sure you want to log out?', // Message inside the confirmation box
        function() { // On confirm
            $.ajax({
                url: "/logout",
                method: "POST",
                contentType: "application/json", // Ensure JSON response is handled correctly
                success: function (response) {
                    console.log(response); // Debugging: Check if response is received
                    if (response.status === "success") {
                        window.location.href = response.redirect; // Redirect on success
                    } else {
                        alertify.alert(response.message || "Logout failed."); // Show alert if logout fails
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error); // Debugging
                    alertify.alert("An error occurred. Please try again."); // Show error alert
                },
            });
        },
        function() { // On cancel
            alertify.message('Logout cancelled'); // Optional message for cancel action
        }
    );
});


    

/*
function removeAdmin(id) {

    if (!confirm('Are you sure you want to remove this admin?')) {
        return;
    }
    $.ajax({
        url: '/admin/settings',
        method: 'DELETE',
        contentType: 'application/json',
        data: JSON.stringify({id}),
        success: function(response) {
            alert(response.message);
            fetchSettings();
        }

    })
}*/

function removeAdmin(id) {
    alertify.confirm(
        'Remove Admin Confirmation', 
        'Are you sure you want to remove this admin?', 
        function() { // On confirm
            $.ajax({
                url: '/admin/settings',
                method: 'DELETE',
                contentType: 'application/json',
                data: JSON.stringify({ id }),
                success: function(response) {
                    alertify.alert('Success', response.message, function() {
                        fetchSettings(); // Now it refreshes after closing the alert
                    });
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    alertify.alert('Error', 'An error occurred. Please try again.');
                }
            });
        },
        function() { 
            alertify.message('Action cancelled'); 
        }
    );
}



$('#database-backup-form').on('submit', function(event) {
    event.preventDefault();
    
    $.ajax({
        url: '/admin/backup',
        method: 'GET',
        success: function(response) {
            // The PHP backend will handle the download directly
            alert("Backup started! If no download prompt appears, check your browser settings.");
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
            alert("There was an error creating the backup.");
        }
    });
});


