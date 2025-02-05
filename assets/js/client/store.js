$(document).ready(function(){
    fetchStoreInfo();
})

function fetchStoreInfo() {

  
        $.ajax({
            url: '/get-store-info',
            method: 'GET',
            contentType: 'application/json',
            success: function(response) {
                // Log the entire response to confirm structure
                console.log(response);
            
                // Access the store_info object
                const storeInfo = response.store_info;
            
                // Use the properties inside store_info
                $('#store-address').text(storeInfo.address);
                $('#store-phone').text(storeInfo.contact_number);
                $('#store-work-hours').text(storeInfo.business_hours);
                
                // If you want to update the specific HTML structure for each list item:
                $('li:nth-child(1) p').text(storeInfo.address);
                $('li:nth-child(2) p').text(storeInfo.email);  // Assuming email is available
                $('li:nth-child(3) p').text(storeInfo.contact_number);
                $('li:nth-child(4) p').text(storeInfo.business_hours);

            }
            ,
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
                console.error("Response Text:", xhr.responseText); // Log the response text for more details
                alert("An error occurred. Please try again.");
            }
            
        })
}