$(document).ready(function() {
    fetchDeliveryInfo();
});

function fetchDeliveryInfo() {
    $.ajax({
        url: '/profile',  // The endpoint to fetch the user's profile or delivery info
        method: 'GET',
        contentType: 'application/json',
        success: function(response) {
            // Debugging: Log the full response
            console.log(response);

            // Assuming the response contains the name, contact, and address
          
                console.log('Updating elements with the following data:');
                console.log('Name:', response.username);
                console.log('Phone:', response.phone_number);
                console.log('Address:', response.address);

                // Set name, phone, and address into respective elements
                $('#delivery-name').text(response.username);           // Set name
                $('#delivery-contact').text(response.phone_number);    // Set contact number
                $('#delivery-address').text(response.address);         // Set address
            
        },
        error: function() {
            alert("An error occurred. Please try again.");
        }
    });
}
