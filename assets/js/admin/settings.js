$(document).ready(function(){
    fetchSettings();
})


function fetchSettings() {
    $.ajax({
        url: '/admin/settings',
        method: 'GET',
        success: function(response) {
            $('#store-name').val(response.store_info.name);
            $('#contact-number').val(response.store_info.contact_number);
            $('#store-address').val(response.store_info.address);
            $('#business-hours').val(response.store_info.business_hours);
            $('#gcash-name').val(response.store_info.account_name);
            $('#gcash-number').val(response.store_info.account_no);

            if (response.admins) {
                const adminList = $('#admin-list');
                adminList.empty(); // Clear existing list

                response.admins.forEach(admin => {
                    const listItem = `
                        <li>
                            ${admin.full_name} 
                            <button type="submit" class="submit-btn remove-admin-btn" data-id="${admin.id}">
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

$('#edit-profile-form').on('submit', function(){
    const name = $('#settings-name').val();
    const email = $('#settings-email').val();
    const old_password = $('#settings-oldpassword').val();
    const new_password = $('#settings-newpassword').val();
    const confirm_password = $('#settings-confirm-password').val();
    const action = 'update_profile'

    $.ajax({
        url: '/admin/settings',
        method: PUT,
        type: 'application/json',
        data: JSON.stringify({name, email, old_password, new_password, confirm_password, action}),
        success: function() {
            alert('Profile updated!');
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
    alert('yow');

    $.ajax({
        url: '/admin/settings',
        method: "POST",
        contentType: 'application/json',
        data: JSON.stringify({name, username, email, password}),
        success: function(response) {
            alert(response['success']);

            $('#settings-name').val('');
            $('#settings-email').val('');
            $('#settings-oldpassword').val('');
            $('#settings-newpassword').val('');
            $('#settings-confirm-password').val('');

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


$('#edit-payment-form').on('submit', function(event){
    event.preventDefault();
    alert('hello');
    const gcash_account = $('#gcash-name').val();
    const gcash_no = $('#gcash-number').val();
    // const gcash_qr = $('#gcash-qr').val();
    const action = 'update_payment_info';

    $.ajax({
        url: '/admin/settings',
        method: 'PUT',
        type: 'application/json',
        data: JSON.stringify({gcash_account, gcash_no, action}),
        success: function() {
            alert('Payment Info Updated!');
        }
    })
})

function removeAdmin(id) {

}

