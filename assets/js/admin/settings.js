$('#update-profile').on('submit', function(){
    const name = $('#update-profile-name').val();
    const email = $('#update-profile-email').val();
    const password = $('#update-profile-password').val();
    const action = 'update_profile'

    $.ajax({
        url: '/admin/settings',
        method: PUT,
        type: 'application/json',
        data: JSON.stringify({name, email, password, action}),
        success: function() {
            alert('Profile updated!');
        }
    })
})

$('#update-general_info').on('submit', function(){
    const business_name = $('#general-info-name').val();
    const business_contact = $('#general-info-contact').val();
    const business_address = $('#general-info-address').val();
    const business_hours = $('#general-info-hours').val();
    const business_logo = $('#general-info-logo').val();
    const action = 'update_general_info';

    $.ajax({
        url: '/admin/settings',
        method: PUT,
        type: 'application/json',
        data: JSON.stringify({business_name, business_address, business_contact, business_hours, business_logo, action}),
        success: function() {
            alert('General Info Updated!');
        }
    })
})


$('#update-payment-info').on('submit', function(){
    const gcash_account = $('#gcash-account').val();
    const gcash_no = $('#gcash-no').val();
    const gcash_qr = $('#gcash-qr').val();
    const action = 'update_payment_info';

    $.ajax({
        url: '/admin/settings',
        method: PUT,
        type: 'application/json',
        data: JSON.stringify({gcash_account, gcash_no, gcash_qr, action}),
        success: function() {
            alert('Payment Info Updated!');
        }
    })
})

