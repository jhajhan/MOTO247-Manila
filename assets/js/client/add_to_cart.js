$(document).ready(function() {
    // Fetch cart items when the page loads
    fetchCart();

    // Open the checkout modal when clicking "Checkout"
    $("#checkout-btn").click(function() {
        openCheckoutModal();
    });

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
            },
            error: function(xhr, status, error) {
                console.error("Error fetching cart data:", error);
            }
        });
    }

    // Open checkout modal
    function openCheckoutModal() {
        const cartData = getCartDataForCheckout();

        if (cartData.length > 0) {
            let checkoutHTML = '';
            let subtotal = 0;

            $.each(cartData, function(index, item) {
                if (item.isSelected) {  // Only selected items
                    subtotal += parseFloat(item.price) * parseInt(item.prod_qty);
                    checkoutHTML += `
                        <tr>
                            <td><img src="${item.image}" alt="" width="50"></td>
                            <td>${item.name}</td>
                            <td>₱${item.price}</td>
                            <td>${item.prod_qty}</td>
                            <td>₱${(item.price * item.prod_qty).toFixed(2)}</td>
                        </tr>
                    `;
                }
            });

            // Update the checkout table
            $("#checkout-items").html(checkoutHTML);

            // Calculate and update subtotal, shipping, and total
            let shippingFee = 0;  // Assuming free shipping
            let total = subtotal + shippingFee;

            $("#checkout-subtotal").text(`₱${subtotal.toFixed(2)}`);
            $("#checkout-shipping-fee").text(shippingFee === 0 ? "Free" : `₱${shippingFee.toFixed(2)}`);
            $("#checkout-total").text(`₱${total.toFixed(2)}`);

            // Show the modal
            $("#checkout-modal").show();
        }
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
        $.ajax({
            url: '/update-cart-product',
            method: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify({ cartId, isSelected }),
            success: function() {
                fetchCart(); // Refresh cart
            }
        });
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
