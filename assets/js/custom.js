$(document).ready(function () {
    $('.increment-btn').click(function(e) {
        e.preventDefault();

        // Find the closest input field within the current context
        var qtyInput = $(this).closest('.product_qty, .service_qty').find('.input-qty');
        var qty = qtyInput.val();
        
        var value = parseInt(qty, 10);
        value = isNaN(value) ? 0 : value;
        if (value < 10) {
            value++;
            qtyInput.val(value); // Update the specific input
        }
    });

    $('.decrement-btn').click(function(e) {
        e.preventDefault();

        // Find the closest input field within the current context
        var qtyInput = $(this).closest('.product_qty, .service_qty').find('.input-qty');
        var qty = qtyInput.val();
        
        var value = parseInt(qty, 10);
        value = isNaN(value) ? 0 : value;
        if (value > 1) {
            value--;
            qtyInput.val(value); // Update the specific input
        }
    });

    $('.addToCart-btn').click(function(e) {
        e.preventDefault();
    
        var qty = $(this).closest('.product_qty, .service_qty').find('.input-qty').val();
        var prod_id = $(this).val();

       
        $.ajax({
            method: "POST",
            url: "/MOTO247-Manila/includes/addtocart.php",
            data:{
                "prod_id": prod_id,
                "prod_qty": qty,
                "scope": "add"
            },
            success: function(response){
                if(response == 201)
                {
                   alertify.success('Item added to cart');
                }
                else if(response == 'existing')
                {
                    alertify.success("Product already in cart");
                }
                else if(response == 401)
                {
                    alertify.success("Please login to add to cart");
                }
               
            }

        })


    });

});
