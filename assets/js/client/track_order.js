$(document).ready(function(){
    fetchOrders();
})

function fetchOrders() {

    $.ajax({
        url: '/orders',
        method: 'GET',
        contentType: 'application/json',
        success: function(response) {
            
        }
    })
}