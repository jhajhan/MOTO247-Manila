$(document).ready(function () {
    // Fetch and display products on page load
    fetchProducts();

    // Fetch and render product data
    function fetchProducts() {
        $.ajax({
            url: '/products', // Backend API endpoint to fetch products
            method: 'GET',
            success: function (data) {
                const productList = $('#product-list'); // Container for product list
                productList.empty(); // Clear existing data

                data.forEach((product) => {
                    const productItem = $(`
                        <li>
                            <h2>${product.name}</h2>
                            <p>${product.description}</p>
                            <p>Price: ${product.price}</p>
                            <button class="add-to-cart-btn" data-id="${product.id}" data-name="${product.name}" data-price="${product.price}">
                                Add to Cart
                            </button>
                        </li>
                    `);

                    productList.append(productItem);
                });

                // Attach event handler for Add to Cart buttons
                $('.add-to-cart-btn').on('click', function () {
                    const productId = $(this).data('id');
                    const productName = $(this).data('name');
                    const productPrice = $(this).data('price');

                    addToCart(productId, productName, productPrice);
                });
            },
            error: function (error) {
                console.error('Error fetching products:', error);
            },
        });
    }

    // Add product to cart
    function addToCart(id, name, price) {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const existingProduct = cart.find((item) => item.id === id);

        if (existingProduct) {
            existingProduct.quantity += 1;
        } else {
            cart.push({ id, name, price, quantity: 1 });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        alert(`${name} added to cart!`);
    }
});