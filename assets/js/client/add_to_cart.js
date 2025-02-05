$(document).ready(function() {
    // Fetch cart items when the page loads
    fetchCart();

    // Open the checkout modal when clicking "Checkout"
    $("#checkout-btn").on('click', function(){
        openCheckoutModal();
    
        // Hide the sticky bar
        $('#sticky-bar').hide();
     
       
    
    })

    


    // Close the checkout modal
    $("#close-checkout-btn").click(function() {
        $("#checkout-modal").hide(); // Close the modal
    });

    // Function to fetch cart data and update the cart table
    function fetchCart() {
        $.ajax({

            url: '/cart',  // Your API endpoint to fetch cart items
            method: 'GET',
            dataType: 'json',
            success: function(data) {

        
                let cartHTML = '';
                let subtotal = 0;

                // Build the cart items table HTML
                $.each(data.cart_items, function(index, item) {
                    subtotal += parseFloat(item.price) * parseInt(item.prod_qty);

                    cartHTML += `
                        <tr data-id="${item.id}">
                            <td><input type="checkbox" class="select-product" data-id="${item.id}" ${item.isSelected ? 'checked' : ''}></td>
                            <td><img src="${item.image}" alt="" width="50"></td>
                            <td>${item.name}</td>
                            <td>₱${item.price}</td>
                            <td><input type="number" value="${item.prod_qty}" class="qty" data-id="${item.id}"></td>
                            <td>₱${(item.price * item.prod_qty).toFixed(2)}</td>
                            <td>
                                <a href="#" class="remove-item">
                                    <ion-icon name="close-circle-outline" data-id="${item.id}"></ion-icon>
                                </a>
                            </td>
                        </tr>
                    `;
                });

                        // Update the cart table
                        $("#cart-items").html(cartHTML);

                        // Update cart subtotal
                        $("#cart-subtotal").text(`₱${subtotal.toFixed(2)}`);

                        // Add shipping fee and total calculation
                        let shippingFee = 0;
                        let total = subtotal + shippingFee;
                        $("#shipping-fee").text(shippingFee === 0 ? "Free" : `₱${shippingFee.toFixed(2)}`);
                        $("#cart-total").text(`₱${total.toFixed(2)}`);

                        
                        // Listen for changes in quantity
                        $('.qty').change(function() {
                            const cartId = $(this).data('id');
                            const newQty = $(this).val();

                            updateCartItem(cartId, newQty);
                        });

                        // Handle product selection checkbox
                        $('.select-product').change(function() {
                            const productId = $(this).data('id');
                            const isSelected = $(this).prop('checked') ? 1 : 0;
                            updateProductSelection(productId, isSelected);
                        });

                        // Handle item removal
                        $('.remove-item').click(function(e) {
                            e.preventDefault();
                            const cartId = $(this).find('ion-icon').data('id');
                            removeItemFromCart(cartId);
                        });

                        // Handle 'select-all' checkbox
                    $('#select-all').on('change', function() {
                        const isChecked = $(this).prop('checked');
                        $('.select-product').prop('checked', isChecked);  // Set all checkboxes based on the 'select-all' checkbox
                        updateTotalCartAmount();  // Update the cart total
                    });

                    // Handle individual product checkbox change
                    $('.select-product').on('change', function() {
                        const allSelected = $('.select-product').length === $('.select-product:checked').length;
                        $('#select-all').prop('checked', allSelected);  // Update 'select-all' checkbox
                        updateTotalCartAmount();  // Update the cart total
                    });

                    

            },
            error: function(xhr, status, error) {
                console.error("Error fetching cart data:", error);
            }
        });
    }

    // Open checkout modal with updated HTML
    function openCheckoutModal() {
        const cartData = getCartDataForCheckout();
        let checkoutHTML = '';
        let subtotal = 0;
    
        console.log(cartData);  // Debugging cartData
    
        // Loop through selected items and build the checkout table rows
        $.each(cartData, function(index, item) {
            if (item.isSelected) {  // Only add selected items
                console.log(item.name);  // Debugging the item name
                subtotal += parseFloat(item.price) * parseInt(item.prod_qty);
                checkoutHTML += `
                    <tr>
                        <td><img src="${item.image}" alt="Product Image" width="50"></td>
                        <td>${item.name}</td>
                        <td>₱${item.price}</td>
                        <td>${item.prod_qty}</td>
                        <td>₱${(item.price * item.prod_qty).toFixed(2)}</td>
                    </tr>
                `;
            }
        });
    
        // Update the checkout table with the dynamic HTML
        $("#checkout-items").html(checkoutHTML);
    
        // Calculate the subtotal, shipping, and total
        let shippingFee = 36;  // Fixed shipping fee
        let total = subtotal + shippingFee;
    
        // Update the order summary
        $(".checkout-box p:contains('Cart Subtotal:')").html(`<strong>Cart Subtotal:</strong> ₱${subtotal.toFixed(2)}`);
        $(".checkout-box p:contains('Shipping Fee:')").html(`<strong>Shipping Fee:</strong> ₱${shippingFee}`);
        $(".checkout-box .total-price").text(`₱${total.toFixed(2)}`);
    
        // Update delivery information
        const deliveryInfo = `
            <h3>Delivery Address</h3>
            <p><strong>Name:</strong> Nikki Manginsay</p>
            <p><strong>Contact Number:</strong> (+63) 9926293624</p>
            <p><strong>Address:</strong> Blk 30 Lot 31 Purok 4 Martizano Street, Matro Residence, Central Bicutan, Taguig City, Metro Manila, 1633</p>
        `;
        $(".checkout-box .delivery-info").html(deliveryInfo);
    
        // Show the modal and hide the cart
        $("#checkout").show();
        $("#cart").hide();
    }
    



    // Get the selected items for the checkout modal
    function getCartDataForCheckout() {
        let cartData = [];

        $('#cart-items tr').each(function() {
            const productId = $(this).data('id');
            const isSelected = $(this).find('.select-product').prop('checked');
            const productName = $(this).find('td:nth-child(3)').text();
            const productPrice = $(this).find('td:nth-child(4)').text().replace('₱', '');
            const productQuantity = $(this).find('.qty').val();
            const image = $(this).find('img').attr('src');

            cartData.push({
                id: productId,
                name: productName,
                price: productPrice,
                prod_qty: productQuantity,
                isSelected: isSelected,
                image: image
            });
        });

        return cartData;
    }

    // Update cart item quantity
    function updateCartItem(cartId, newQty) {
        $.ajax({
            url: '/manage-cart',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                cart_id: cartId,
                new_qty: newQty,
                action: 'update'
            }),
            success: function(data) {
                fetchCart();
            },
            error: function(xhr, status, error) {
                console.error("Error updating cart item:", error);
            }
        });
    }

    // Update product selection status
    function updateProductSelection(cartId, isSelected) {
        let total = 0;
    let productCount = 0;

    $('#cart-items tr').each(function() {
        if ($(this).find('.select-product').prop('checked')) {
            const price = parseFloat($(this).find('td:nth-child(4)').text().replace('₱', ''));
            const quantity = parseInt($(this).find('.qty').val());
            total += price * quantity;
            productCount++;
        }
    });

    $("#total-cart-amount").html(`Total (${productCount} item${productCount !== 1 ? 's' : ''}): <strong>₱${total.toFixed(2)}</strong>`);
    }

    // Remove item from the cart
    function removeItemFromCart(cartId) {
        $.ajax({
            url: '/manage-cart',
            method: 'DELETE',
            contentType: 'application/json',
            data: JSON.stringify({ cart_id: cartId, action: 'delete' }),
            success: function(data) {
                fetchCart(); // Refresh cart
            },
            error: function(xhr, status, error) {
                console.error("Error removing item from cart:", error);
            }
        });
    }
});
// checkout button





// Handle product selection checkbox


// Dynamically update the cart total in the sticky bar
function updateTotalCartAmount() {
    let total = 0;
    let productCount = 0;

    // Iterate over all selected items to calculate the total
    $('.select-product:checked').each(function() {
        const price = parseFloat($(this).closest('tr').find('td:nth-child(4)').text().replace('₱', ''));
        const quantity = parseInt($(this).closest('tr').find('.qty').val());
        total += price * quantity;
        productCount++;
    });

    // Update the total in the sticky bar
    $(".total span").html(`Total (${productCount} item${productCount !== 1 ? 's' : ''}): <strong>₱${total.toFixed(2)}</strong>`);
}


