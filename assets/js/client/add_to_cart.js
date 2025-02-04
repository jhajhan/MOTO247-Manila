$(document).ready(function(){
    fetchCart();
})

function fetchCart() {
    $.ajax({
        url: '/cart', // Replace with the actual URL of your API
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log("Cart data received:", data); // Debugging
            let cartHTML = "";

            if (!data.cart_items || !Array.isArray(data.cart_items)) {
                console.error("Unexpected data format:", data);
                return;
            }

            let subtotal = 0;

            $.each(data.cart_items, function (index, item) {
                
                subtotal += parseFloat(item.price) * parseInt(item.prod_qty);

                cartHTML += `
                    <tr data-id="${item.id}">
                        <td>
                            <a href="#" class="remove-item" >
                                <ion-icon name="close-circle-outline" id = "remove-item-cart" data-id="${item.id}"></ion-icon>
                            </a>
                        </td>
                        <td><img src="${item.image}" alt="" width="50"></td>
                        <td>${item.name}</td>
                        <td>₱${item.price}</td>
                        <td><input type="number" value="${item.prod_qty}" class="qty" data-id="${item.id}"></td>
                        <td>₱${item.price * item.prod_qty}</td>
                    </tr>
                `;
            });

            $("#cart-items").html(cartHTML); // Update the correct <tbody>

            let shippingFee = 0; // Set shipping fee, change as per your logic
            let total = subtotal + shippingFee;

            // Update HTML content for the cart items, subtotal, and total
            $("#cart-subtotal").text(`₱${subtotal.toFixed(2)}`); // Update the cart subtotal
            $("#shipping-fee").text(shippingFee === 0 ? "Free" : `₱${shippingFee.toFixed(2)}`); // Update shipping fee
            $("#cart-total").text(`₱${total.toFixed(2)}`); // Update the total price

        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
            console.log(xhr.responseText);
        }
    });
}

// Delegate event to dynamically generated elements
$("#cart-items").on('click', '.remove-item', function(e) {
    e.preventDefault();


    var cart_id = $(this).find("ion-icon").data("id");  // Get data-id from ion-icon element
    const action = 'delete';

    $.ajax({
        url: '/manage-cart',
        method: 'DELETE',
        contentType: 'application/json',
        data: JSON.stringify({ cart_id, action }),
        success: function(data) {
            fetchCart();  // Refresh the cart after deletion
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            console.log(xhr.responseText);
        }
    });
});

// Delegate the event to dynamically created .qty input elements
$("#cart-items").on('change', '.qty', function(e) {
    e.preventDefault();
    

    var cart_id = $(this).data("id");  // Get the item id from data-id
    var new_qty = $(this).val();  // Get the new quantity value

    alert(cart_id);
    alert(new_qty);

    // Optionally, update the subtotal dynamically on input change
    var price = $(this).closest('tr').find('td:nth-child(4)').text().replace('₱', '');  // Get the price from the row
    var subtotal = price * new_qty;  // Calculate the new subtotal

    $(this).closest('tr').find('td:nth-child(6)').text(`₱${subtotal.toFixed(2)}`);  // Update the subtotal cell

    // Send the updated quantity to the server
    $.ajax({
        url: '/manage-cart',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            cart_id: cart_id,
            new_qty: new_qty,
            action: 'update'
        }),
        success: function(data) {
            alert(data.message);
            fetchCart();  // Refresh the cart after updating
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            console.log(xhr.responseText);
        }
    });
});




