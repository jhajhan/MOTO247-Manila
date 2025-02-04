$(document).ready(function() {
    $('.increment-btn').click(function(e) {
        e.preventDefault();
        console.log("Increment button clicked"); // Debugging log

        var qtyInput = $(this).parents('.input-group').find('.input-qty');
        var qty = qtyInput.val();
        
        var value = parseInt(qty, 10);
        console.log("Current Value:", value); // Debugging log

        value = isNaN(value) ? 0 : value;
        if (value < 10) {
            value++;
            qtyInput.val(value);
            console.log("New Value:", value); // Debugging log

            var product_id = $(this).attr("data-id");
            updateQuantity(qtyInput, product_id);
        }
    });

    $('.decrement-btn').click(function(e) {
        e.preventDefault();
        console.log("Decrement button clicked"); // Debugging log

        var qtyInput = $(this).parents('.input-group').find('.input-qty');
        var qty = qtyInput.val();
        
        var value = parseInt(qty, 10);
        value = isNaN(value) ? 0 : value;
        if (value > 1) {
            value--;
            qtyInput.val(value);
            console.log("New Value:", value); // Debugging log

            var product_id = $(this).attr("data-id");
            updateQuantity(qtyInput, product_id);
        }
    });
        $('.addToCart-btn').click(function(e) {
                e.preventDefault();
            
                try {
                    var qty = $(this).parents('.product_qty').find('.input-qty').val();
                    var product_id = $(this).attr("data-id");
                    
                    console.log("Product ID:", product_id);
                    console.log("Quantity:", qty);
                    
                    $.ajax({
                        method: "POST",
                        url: "/MOTO247-Manila/includes/addtocart.php",
                        data: {
                            "prod_id": product_id,
                            "prod_qty": qty,
                            "scope": "add"
                        },
                        success: function(response) {
                            console.log("AJAX Response:", response);
                            
                            if (response == 201) {
                                alertify.success('Item added to cart');
                            } else if (response == 'existing') {
                                alertify.success("Product already in cart");
                            } else if (response == 401) {
                                alertify.error("Please login to add to cart");
                            } else if (response == 500) {
                                alertify.error("Something went wrong. Please try again later");
                            } else {
                                alertify.error("Unexpected response: " + response);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", status, error);
                        }
                    });
                } catch (error) {
                    console.error("Error in addToCart-btn click handler:", error);
                }
            });
    function updateQuantity(qtyInput, product_id) {
        var qty = qtyInput.val();

        console.log("Updating Quantity - Product ID:", product_id, "New Quantity:", qty); // Debugging log

        $.ajax({
            method: "POST",
            url: "/MOTO247-Manila/includes/addtocart.php",
            data: {
                "prod_id": product_id,
                "prod_qty": qty,
                "scope": "update"
            },
            success: function(response) {
                alertify.success('Quantity updated successfully');
            },
            error: function(xhr, status, error) {
                alertify.error("Failed to update quantity. Please try again.");
            }
        });
    }

   

    $(document).on('click', '.update_qty', function(e) {
        e.preventDefault();
    
        var qty = $(this).parents('.input-group').find('.input-qty').val();
        var product_id = $(this).attr("data-id");
    
        console.log("Updating Quantity - Product ID:", product_id, "New Quantity:", qty);
    
        $.ajax({
            method: "POST",
            url: "/MOTO247-Manila/includes/addtocart.php",
            data: {
                "prod_id": product_id,
                "prod_qty": qty,
                "scope": "update"
            },
            success: function(response) {
                console.log("AJAX Response:", response);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    });

    //Delete item from cart
    $(document).on('click', '.deleteItem', function(e) {
        var cart_id = $(this).val(); // Ensure this line is correct
        console.log("Cart ID:", cart_id); // Debugging: Log the cart_id to the console
    
        $.ajax({
            method: "POST",
            url: "/MOTO247-Manila/includes/addtocart.php",
            data: {
                "cart_id": cart_id, // Ensure this matches the key in PHP ($_POST['cart_id'])
                "scope": "delete"
            },
            success: function(response) {
                if (response == 200) {
                    alertify.success('Removed from cart');
                    $('#cartItem').load(location.href + " #cartItem");
                } else {
                    alertify.success(response); // Display the response from the server
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error); // Log any AJAX errors
            }
        });
    });
});
