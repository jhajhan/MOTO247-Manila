$(document).ready(function(){
    fetchHome();
});

function fetchHome() {
    $.ajax({
        url: '/', // Assuming this endpoint fetches both top products and new products
        method: 'GET',
        success: function(data){
            displayTopProducts(data.top_products);
            displayNewProducts(data.new_products);
        }
    });
}

function displayTopProducts(top_products) {
    let topHTML = "";

    if (top_products.length === 0) {
        topHTML = `<p class="no-products">No products available.</p>`;
    } else {
        $.each(top_products, function(index, product) {
            topHTML += `
                <div class="pro">
                    <img src="${product.image}" alt="">

                    <div class="des">
                        <span>${product.description}</span>
                        <h5>${product.name}</h5>

                        <div class="star">
                            <ion-icon name="star"></ion-icon>
                            <ion-icon name="star"></ion-icon>
                            <ion-icon name="star"></ion-icon>
                            <ion-icon name="star"></ion-icon>
                            <ion-icon name="star"></ion-icon>
                        </div>

                        <h4>₱${product.price}</h4>
                    </div>

                      <a class="cart" data-product-id="${product.prod_id}" data-product-name="${product.name}" data-product-price="${product.price}" data-product-desc="${product.description}" data-product-img="${product.image}">
                        <ion-icon name="cart"></ion-icon>
                    </a>
                </div>
            `;
        });
    }

    $(".pro-container").first().html(topHTML); // Inserts into the first .pro-container (Best-Selling Products)
    
}

function displayNewProducts(new_products) {
    let newHTML = "";

    if (new_products.length === 0) {
        newHTML = `<p class="no-products">No products available.</p>`;
    } else {
        $.each(new_products, function(index, product) {

            newHTML += `
                <div class="pro">
                    <img src="${product.image}" alt="">

                    <div class="des">
                        <span>${product.description}</span>
                        <h5>${product.name}</h5>

                        <div class="star">
                            <ion-icon name="star"></ion-icon>
                            <ion-icon name="star"></ion-icon>
                            <ion-icon name="star"></ion-icon>
                            <ion-icon name="star"></ion-icon>
                            <ion-icon name="star"></ion-icon>
                        </div>

                        <h4>₱${product.price}</h4>
                    </div>

                    <a class="cart" data-product-id="${product.prod_id}" data-product-name="${product.name}" data-product-price="${product.price}" data-product-desc="${product.description}" data-product-img="${product.image}">
                        <ion-icon name="cart"></ion-icon>
                    </a>

                </div>
            `;
        });
    }

    $(".pro-container").last().html(newHTML); // Inserts into the last .pro-container (New Arrivals)

    // Event delegation to catch clicks on dynamically added .cart elements
    $(document).on('click', '.cart', function (e) {
        e.preventDefault();

        let prodId = $(this).data("product-id");
        let prodName = $(this).data("product-name");
        let prodPrice = $(this).data("product-price");
        let prodDesc = $(this).data("product-desc");
        let prodImg = $(this).data("product-img");

        addToCart(prodId, prodName, prodPrice, prodDesc, prodImg);
    });
}

// Function to add a product to the cart
function addToCart(productId, prodName, prodPrice, prodDesc, prodImg) {
    const action = 'add';

    $.ajax({
        url: '/manage-cart',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({productId, action}),
        success: function(response) {
            alert('Item added to cart.');
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error); // Debugging
            alert("An error occurred. Please try again.");
        }
    });
}
