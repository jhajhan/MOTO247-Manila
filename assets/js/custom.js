$(document).ready(function () {
    $('.increment-btn').click(function(e) {
        e.preventDefault();
        var qtyInput = $(this).parents('.product_qty, .service_qty').find('.input-qty');
        var qty = qtyInput.val();
        
        var value = parseInt(qty, 10);
        value = isNaN(value) ? 0 : value;
        if (value < 10) {
            value++;
            qtyInput.val(value);
        }
    });

    $('.decrement-btn').click(function(e) {
        e.preventDefault();
        var qtyInput = $(this).parents('.product_qty, .service_qty').find('.input-qty');
        var qty = qtyInput.val();
        
        var value = parseInt(qty, 10);
        value = isNaN(value) ? 0 : value;
        if (value > 1) {
            value--;
            qtyInput.val(value);
        }
    });

    $('.addToCart-btn').click(function(e) {
        e.preventDefault();
    
        try {
            var qty = $(this).parents('.product_qty, .service_qty').find('.input-qty').val();
            var product_id = $(this).attr("data-id"); // Fix: Use data-id instead of val()
            
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
                    console.log("AJAX Response:", response); // Debugging
                    
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
});
