let products = [];  // Store fetched products
let currentPage = 1;
const itemsPerPage = 8;  // Maximum 8 products per page

// Fetch products from the backend
function fetchProductsClient() {
    $.ajax({
        url: '/products',  // Adjust to your actual PHP file
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log("Fetched Data:", data);  // Debugging
            if (data && Array.isArray(data.products)) { 
                products = data.products;
                displayProductsClient(currentPage);
                setupPagination();
            } else {
                console.error("Invalid response structure:", data);
            }
        },
        error: function (error) {
            console.error('Error fetching products:', error);
        }
    });
}

function displayProductsClient(page) {
    console.log(`Displaying page ${page}`);  // Debugging
    const productList = $("#product-list");
    productList.empty();  // Clear previous items

    let start = (page - 1) * itemsPerPage;
    let end = start + itemsPerPage;
    let paginatedItems = products.slice(start, end);

    if (paginatedItems.length === 0) {
        productList.html("<p>No products available.</p>");
        return;
    }

    paginatedItems.forEach(product => {
        let productHTML = `
            <div class="pro">
                <a href="#">
                    <img src="${product.image || ''}" alt="${product.name || 'Product'}">
                </a>
                <div class="des">
                    <span>${product.brand || 'Unknown Brand'}</span>
                    <h5>${product.name || 'Unnamed Product'}</h5>
                    <div class="star">
                        <ion-icon name="star"></ion-icon>
                        <ion-icon name="star"></ion-icon>
                        <ion-icon name="star"></ion-icon>
                        <ion-icon name="star"></ion-icon>
                        <ion-icon name="star"></ion-icon>
                    </div>
                    <h4>₱${product.price || '0.00'}</h4>
                </div>
                <a class="cart">
                    <ion-icon class="cart-icon" name="cart" 
                        data-product-id="${product.prod_id}" 
                        data-product-name="${product.name}" 
                        data-product-price="${product.price}" 
                        data-product-desc="${product.description}" 
                        data-product-img="${product.image}" ></ion-icon>
                </a>
            </div>
        `;
        productList.append(productHTML);
    });

    // Ensure pagination stays in place
    updatePaginationControls();

    // Scroll back to the top of the product list to prevent "going down" effect
    // Scroll back to the top of the product list only when it's the first page
    if (page === 1) {
        $("html, body").animate({ scrollTop: 0 }, "fast");
    }
}

// Event delegation for handling the cart icon click
$("#product-list").on('click', '.cart-icon', function () {
    let prodId = $(this).data("product-id");
    let prodName = $(this).data("product-name");
    let prodPrice = $(this).data("product-price");
    let prodDesc = $(this).data("product-desc");
    let prodImg = $(this).data("product-img");

    addToCart(prodId, prodName, prodPrice, prodDesc, prodImg);
});

// Setup pagination dynamically
function setupPagination() {
    console.log("Setting up pagination");  // Debugging
    const totalPages = Math.ceil(products.length / itemsPerPage);
    const pageNumbersContainer = $("#pageNumbers");  // The element to hold page numbers
    pageNumbersContainer.empty();  // Clear previous page numbers

    // Create page links dynamically
    for (let page = 1; page <= totalPages; page++) {
        let pageLink = `<a href="#" class="page-link" data-page="${page}" 
            style="margin: 0 5px">
            ${page}
        </a>`;
        pageNumbersContainer.append(pageLink);
    }

    // Attach click events to the newly created page links
    $(".page-link").off('click').on('click', function (e) {
        e.preventDefault();
        let page = parseInt($(this).attr("data-page"));
        if (page !== currentPage) {
            currentPage = page;
            displayProductsClient(currentPage);

            $("html, body").animate({ scrollTop: $("#product-list").offset().top - 150 }, "fast");
        
        }
    });

    // Ensure pagination stays in place
    updatePaginationControls();

    // Scroll back to the top of the product list only when it's the first page
    if (page === 1) {
        $("html, body").animate({ scrollTop: $("#product-list").offset().top - 150 }, "fast");
    }
}

// Update pagination (active page, disable next/prev)
function updatePaginationControls() {
    const totalPages = Math.ceil(products.length / itemsPerPage);

    $(".page-link").removeClass("active");
    $(`.page-link[data-page='${currentPage}']`).addClass("active");

    $("#prevPage").toggle(currentPage > 1);
    $("#nextPage").toggle(currentPage < totalPages);
}

// Previous button click event
$("#prevPage").click(function (e) {
    e.preventDefault();
    if (currentPage > 1) {
        currentPage--;
        displayProductsClient(currentPage);
        $("html, body").animate({ scrollTop: $("#product-list").offset().top - 150 }, "fast");
    }
});

// Next button click event
$("#nextPage").click(function (e) {
    e.preventDefault();
    const totalPages = Math.ceil(products.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        displayProductsClient(currentPage);
        $("html, body").animate({ scrollTop: $("#product-list").offset().top - 150 }, "fast");
    }
});

// Function to add a product to the cart
function addToCart(productId, prodName, prodPrice, prodDesc, prodImg) {
    const action = 'add';

    $.ajax({
        url: '/manage-cart',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({productId, action}),
        success: function(response) {
            alert(response.message);
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error); // Debugging
            alert("An error occurred. Please try again.");
        }
    })
}

// Initialize when document is ready
$(document).ready(function () {
    fetchProductsClient();
});
