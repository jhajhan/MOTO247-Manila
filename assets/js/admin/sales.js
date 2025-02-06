$(document).ready(function(){

    $(document).on('click', '.editBtn', function() {
        const id = $(this).data('id');
        const payment_method = $(this).data('payment-method');
        const payment_status = $(this).data('payment-status');
        const status = $(this).data('status');

        $("#edit-id").val(id);
        $("#edit-payment-method").val(payment_method.toUpperCase()).change();
        $("#edit-payment-status").val(payment_status.toUpperCase()).change();
        $("#edit-status").val(status.toUpperCase()).change();

        $("#edit-modal").show();
    });

    $(document).on('click', '.deleteBtn', function() {
        const id = $(this).data('id');
        deleteSale(id);
    });

    fetchSales();

    $("#online-sales").hide();

    $("#toggle-sales-btn").click(function () {
        const isOnlineVisible = $("#online-sales").is(":visible");
    
        $("#online-sales").toggle(!isOnlineVisible);
        $("#physical-sales").toggle(isOnlineVisible);
    
        $(this).text(isOnlineVisible ? "SWITCH TO ONLINE SALES" : "SWITCH TO PHYSICAL SALES");
    });
 
})

$('#add-physical-form').on('submit', function(event) {
    event.preventDefault();
  

    const customer_name = $('#physical-customer-name').val();
    const phone_number = $('#physical-phone').val();

    const products = [];
    const quantities = [];
    const total_amounts_per_product = [];
    let total_amount = 0;

    // Collect selected products and quantities
    $('select[name="products[]"]').each(function(index) {
        const productId = $(this).find('option:selected').data('id');
        const productPrice = $(this).find('option:selected').data('price');
        const quantity = $('input[name="quantities[]"]').eq(index).val();

        alert(productId);

        if (productId && quantity) {
            products.push(productId);  // Add product ID to the array
            quantities.push(quantity); // Add quantity to the array

            const productTotalAmount = productPrice * quantity;
            total_amounts_per_product.push(productTotalAmount); 

            // Calculate the total amount for this product
            total_amount += productPrice * quantity;
        }
    });

    // // Validate fields before submitting
    // if (!customer_name|| products.length === 0 || total_amount === 0) {
    //     alert("Please fill out all fields correctly.");
    //     return;
    // }

    const date = $('#physical-order-date').val();
    const payment_method = $('#physical-payment').val();
    const payment_status = $('#physical-payment-status').val();
    const status = $('#physical-status').val();
    const order_type = 'physical';




    $.ajax({
        url: '/admin/sales',
        method: 'POST',
        contentType: 'application/json', // Set the correct content type
        data: JSON.stringify({
            customer_name,
            phone_number,
            products,
            quantities,
            date,
            payment_method,
            payment_status,
            status,
            total_amounts_per_product,
            total_amount,
            order_type
        }),
        success: function() {
            fetchSales(); // Reload sales data after successful submission
            $("#add-physical-order-modal").css('display', 'none');
        },
        error: function(error) {
            console.log(error);
        }
    });
});


$("#edit-form").on('submit', function() {
    editSale();
});


$("#add-physical-order-btn").on('click', function(){
    $("#add-physical-order-modal").show();
})

$("#generate-report-btn-sales").on('click', function(){
    alert('hello');
    $.ajax({
        url: '/generate-report',
        method: "GET",
        success: function() {

        }
    })
})




// Function to populate dropdown with products/services
function populateProductDropDown(selectElement) {
$.ajax({
    url: '/admin/product-service',
    method: 'GET',
    success: function(data) {
        selectElement.empty(); // Clear existing options
        selectElement.append('<option value="">Select a product/service</option>'); // Default option

        data.products_services.forEach(product => {
            selectElement.append(`<option data-id="${product.prod_id}" data-price="${product.price}">${product.name}</option>`);
        });
    }
});
}

$("#close-add-modal").on('click', function(){
$("#add-physical-order-modal").css('display', 'none');
})

// Add another dropdown on button click
$("#add-product").on('click', function() {
// Create a new container div for the dropdown and remove button
const newDropdownContainer = $('<div>', { id: 'product-dropdown-container' });

// Create a new select element (dropdown)
const newSelect = $('<select>', {
    name: 'products[]', // Use the same name for form submission
    required: true,     // Ensure it's required
    class: 'product-dropdown', // Add class for targeting specific dropdowns
});

// Populate the new dropdown with product options
populateProductDropDown(newSelect);

 // Create a new quantity input field
 const quantityInput = $('<input>', {
    type: 'number',
    name: 'quantities[]', // Use the same name for form submission
    required: true,       // Ensure it's required
    min: 1,               // Set minimum quantity to 1
    placeholder: 'Quantity',
    class: 'quantity-input',
});

// Create a remove button for this dropdown and quantity input
const removeButton = $('<button>', {
    type: 'button',
    class: 'remove-product-btn',
    text: '-',
});

// Append the dropdown, quantity input, and remove button to the container
newDropdownContainer.append(newSelect);
newDropdownContainer.append(quantityInput);
newDropdownContainer.append(removeButton);

// Append the new container to the #product-list
$("#product-list").append(newDropdownContainer);

// Add click event to the remove button to remove the dropdown container
removeButton.on('click', function() {
    newDropdownContainer.remove();
});
});


$('#online-apply-filters-btn').on('click', function() {
    const payment_method = $('#online-payment-method-filter').val();
    const payment_status = $('#online-payment-status-filter').val();
    const status = $('#online-status-filter').val();
    const sales_type = 'online';


    filters = {
        payment_method: payment_method,
        payment_status: payment_status,
        status: status,
        sales_type: sales_type
    }

    fetchSales(filters);
});


$('#physical-apply-filters-btn').on('click', function() {
    const payment_method = $('#physical-payment-method-filter').val();
    const payment_status = $('#physical-payment-status-filter').val();
    const status = $('#physical-status-filter').val();
    const sales_type = 'physical';

    filters = {
        payment_method: payment_method,
        payment_status: payment_status,
        status: status,
        sales_type: sales_type
    }

    fetchSales(filters);
});


$("#close-edit-modal").on('click', function(){
    $('.modal').css('display', 'none');
})

$("#cancel-edit-order-btn").on('click', function(){
    $('.modal').css('display', 'none');
})

function fetchSales (filters = {}) {
    $.ajax({
        url: '/admin/sales',
        method: 'GET',
        data: filters,
        success: function(response) {

            let data;
            if (typeof response === "string") {
                try {
                    data = JSON.parse(response);
                } catch (error) {
                    console.error("Invalid JSON response:", error);
                    return;
                }
            } else {
                data = response;
            }

            // ✅ Check if `products_services` exists
            if (!data.physical_sales) {
                console.error("Missing 'physical_sales' in response:", data);
                return;
            }

            if (!data.online_sales) {
                console.error("Missing 'online_sales' in response:", data);
                return;
            }

            const physicalTableBody = $("#physicalSalesTableBody");
            physicalTableBody.empty();

            data.physical_sales.forEach(sale => {
                const row = $('<tr>');

                let productList = sale.products.map(product => 
                    `<div>${product.product_name} (Qty: ${product.quantity})</div>`
                ).join('');


                row.append(
                    `<td>${sale.order_id}</td>
                    <td>${sale.user_id}</td>
                    <td>${sale.customer_name}</td>
                    <td>${sale.phone_number}</td>
                    <td>${sale.address}</td>
                    <td>${sale.delivery_option}</td>
                    <td>${productList}</td>
                    <td>${sale.date}</td>
                    <td>${sale.payment_method}</td>
                    <td>${sale.payment_status}</td>
                    <td>${sale.status}</td>
                    <td>${sale.total}</td>
                    <td><button id = 'editPSales' class = 'editBtn' data-id = ${sale.order_id}  data-payment-method = ${sale.payment_method} data-payment-status = ${sale.payment_status} data-status = ${sale.status}>Edit</button>    <button class = 'deleteBtn' data-id = ${sale.order_id}>Delete</button></td>
                    `
                );

                physicalTableBody.append(row);

            });

            const onlineTableBody = $("#onlineSalesTableBody");
            onlineTableBody.empty();


            data.online_sales.forEach(sale => {
                const row = $('<tr>');

                let productList = sale.products.map(product => 
                    `<div>${product.product_name} (Qty: ${product.quantity})</div>`
                ).join('');


                row.append(
                    `<td>${sale.order_id}</td>
                    <td>${sale.user_id}</td>
                    <td>${sale.customer_name}</td>
                    <td>${sale.phone_number}</td>
                    <td>${sale.address}</td>
                    <td>${sale.delivery_option}</td>
                    <td>${productList}</td>
                    <td>${sale.date}</td>
                    <td>${sale.payment_method}</td>
                    <td>${sale.payment_status}</td>
                    <td>${sale.status}</td>
                    <td>${sale.total}</td>
                    <td><button id = 'editOSales' class = 'editBtn' data-id = ${sale.order_id} data-payment-method = ${sale.payment_method} data-payment-status = ${sale.payment_status} data-status = ${sale.status}>Edit</button>  <button class = 'deleteBtn' data-id = ${sale.order_id}>Delete</button></td>
                    `
                );

                onlineTableBody.append(row);

            });

            $("#editPSales").on('click', function() {
                const id = $(this).data('id');
                const payment_method = $(this).data('payment-method');
                const payment_status = $(this).data('payment-status');
                const status = $(this).data('status');

                $("#edit-id").val(id);
                $("#edit-payment-method").val(payment_method.toUpperCase()).change();
                $("#edit-payment-status").val(payment_status.toUpperCase()).change();
                $("#edit-status").val(status.toUpperCase()).val();

                $("#edit-modal").show();

                
            });

            $("#editOSales").on('click', function() {
                const id = $(this).data('id');
                const payment_method = $(this).data('payment-method');
                const payment_status = $(this).data('payment-status');
                const status = $(this).data('status');

          

                $("#edit-id").val(id);
                $("#edit-payment-method").val(payment_method.toUpperCase()).change();
                $("#edit-payment-status").val(payment_status.toUpperCase()).change();
                $("#edit-status").val(status.toUpperCase()).change();

                $("#edit-modal").show();

                
            });

            $(".deleteBtn").on('click', function() {
                const id = $(this).data('id');
                deleteSale(id);
            });


        }
    })
}


function editSale() {

    const order_id = $("#edit-id").val();
    const payment_method = $("#edit-payment-method").val();
    const payment_status = $("#edit-payment-status").val();
    const status = $("#edit-status").val();



    $.ajax({
        url: '/admin/sales',
        method: "PUT",  // Correct HTTP method
        contentType: 'application/json',  // Correct content type for JSON
        data: JSON.stringify({ order_id, payment_method, payment_status, status }),
        success: function(data) {
            fetchSales();
        },
        error: function(xhr, status, error) {
            console.log("AJAX Error:", error);
            console.log("Response:", xhr.responseText); // Log server response
        }
    });

}

function deleteSale(id) {
    if(confirm('Are you sure you want to delete this sale?')) {
        $.ajax({
            url: '/admin/sales',
            method: 'DELETE',
            data: JSON.stringify({id}),
            success: function(data) {
                fetchSales();
            },
            error: function(error) {
                console.log(error);
            }
        })
    }
}



