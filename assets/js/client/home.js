$(document).ready(function(){
    fetchHome()
})

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

                    <a href="#" class="cart"><ion-icon name="cart"></ion-icon></a>
                </div>
            `;
        });
    }

    $("#top-products").html(topHTML); // Insert products inside the container
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

                    <a href="#" class="cart"><ion-icon name="cart"></ion-icon></a>
                </div>
            `;
        });
    }

    $("#new-products").html(newHTML); // Insert products inside the container
}
