$(document).ready(function(){
    fetchOrders();
});

function fetchOrders() {
    $.ajax({
        url: '/track-order', // Ensure this endpoint is correctly set in your backend routing
        method: 'GET',
        contentType: 'application/json',
        success: function(response) {
            displayOrders(response);
        },
        error: function() {
            $('#cart-items').html('<tr><td colspan="7">Failed to load orders.</td></tr>');
        }
    });
}

function displayOrders(orders) {
    const cartItems = $('#cart-items');
    cartItems.empty(); // Clear existing content

    if (Object.keys(orders).length === 0) {
        cartItems.append('<tr><td colspan="7">No orders found.</td></tr>');
        return;
    }

    $.each(orders, function(orderId, order) {
        order.products.forEach(function(product, index) {
            const row = `
                <tr>
                    ${index === 0 ? `<td rowspan="${order.products.length}">${order.order_id}</td>` : ''}
                    <td>${product.product_name}</td>
                    <td>${product.quantity}</td>
                    ${index === 0 ? `
                        <td rowspan="${order.products.length}">${order.delivery_option}</td>
                        <td rowspan="${order.products.length}">${order.payment_method}</td>
                        <td rowspan="${order.products.length}">${order.status}</td>
                        <td rowspan="${order.products.length}">${order.total}</td>
                    ` : ''}
                </tr>
            `;
            cartItems.append(row);
        });
    });
}
