$(document).ready(function(){
    fetchProductsClient();
})

function fetchProductsClient () {
    $.ajax({
        url: '/products',  // Your correct URL
        method: 'GET',
        dataType: 'json',  // We expect JSON response
        success: function(data) {
            console.log(data);  // Log the entire data object
            if (data && data.products) {
                displayProductsClient(data.products);
            } else {
                console.error("Invalid response structure:", data);
            }
        },
        error: function(error) {
            console.error('Error fetching products:', error);
        }
    });
}


function displayProductsClient(products) {
    let productList = $('#product-list');
    productList.empty();  // Clear any existing products

    // Loop through each product and create HTML dynamically
    products.forEach(function(product) {
        let productDiv = $('<div class="pro"></div>');

        // Create image element for product
        let img = $('<img>').attr('src', product.image).attr('alt', product.name);
        productDiv.append(img);

        // Create description for product
        let descDiv = $('<div class="des"></div>');
        descDiv.append(
            $('<span></span>').text(product.description),  // Brand
            $('<h5></h5>').text(product.name),       // Product name
            $('<h4></h4>').text('₱' + product.price) // Price
        );

        // Append description to productDiv
        productDiv.append(descDiv);

        // Add the cart icon and attach the product ID as a data attribute
        let cartLink = $('<a href="#" class="cart"></a>')
            .append('<ion-icon name="cart"></ion-icon>')
            .attr('data-product-id', product.prod_id);  // Attach product ID

        // Set up an event listener for the cart link click
        cartLink.on('click', function(e) {
            e.preventDefault();  // Prevent default link behavior
            let productId = $(this).data('product-id');  // Get the product ID
            console.log('Product ID added to cart:', productId);
            addToCart(productId);  // Call a function to add the product to the cart
        });

        productDiv.append(cartLink);

        // Append the product to the list
        productList.append(productDiv);
    });
}

// Function to handle adding the product to the cart
function addToCart(productId) {

    const action = 'add';
    
    $.ajax({
        url: '/manage-cart',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({productId, action}),
        success: function(response) {
            alert(response.message);

            if (response.notLogged) {
                window.location.href = '/login';
            }
        }
    })
}

