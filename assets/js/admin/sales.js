$(document).ready(function(){

    fetchSales();

    populateProductDropDown();

    $('#apply-filters').on('click', function() {
        const payment_method = $('#filter-payment-method').val();
        const payment_status = $('#filter-payment-status').val();
        const status = $('#filter-status').val();

        filters = {
            payment_method: payment_method,
            payment_status: payment_status,
            status: status
        }

        fetchSales(filters);
    });

    $('#add-sale-form').on('submit', function(event) {
        event.preventDefault();

        const customer_name = $('#customer_name').val();

        const products = [];
        const quantities = [];
        let totalAmount = 0;

        // Collect selected products and quantities
        $('select[name="products[]"]').each(function(index) {
            const productId = $(this).val();
            const productPrice = $(this).find('option:selected').data('price');
            const quantity = $('input[name="quantities[]"]').eq(index).val();

            products.push(productId);  // Add product ID to the array
            quantities.push(quantity); // Add quantity to the array

            // Calculate the total amount for this product
            totalAmount += productPrice * quantity;
        });

        const date = $('#date').val();
        const payment_method = $('#payment_method').val();
        const payment_status = $('#payment_status').val();
        const status = $('#status').val();
        const order_type = 'physical';

        $.ajax ({
            url: '/admin/sales',
            method: POST,
            type: 'application/json',
            data: JSON.stringify({ customer_id, customer_name, products, quantities, date, payment_method, payment_status, status, totalAmount, order_type}),
            success: function() {
                fetchSales();
            },
            error: function(error) {
                console.log(error);
            }
        })

    });

    function populateProductDropDown () {
        $.ajax({
            url: '/admin/products',
            method: 'GET',
            success: function(data) {
                const select = $('select[name="products[]"]');
                select.empty();

                data.forEach(product => {
                    select.append(`<option value = ${product.id} data-price = ${product.price}>${product.name}</option>`);
                });
            }
    });
    }

    function fetchSales (filters = {}) {
        $.ajax({
            url: '/admin/sales',
            method: 'GET',
            data: filters,
            success: function(data) {
                const tableBody = $("#sales tbody");
                tableBody.empty();

                data.forEach(sale => {
                    const row = $('<tr>');

                    let productList = sale.product.map(product => `<div>${product}</div>`).join('');

                    row.append(
                        `<td>${sale.order_id}</td>
                        <td>${sale.customer_id}</td>
                        <td>${sale.customer_name}</td>
                        <td>${sale.productList}</td>
                        <td>${sale.date}</td>
                        <td>${sale.payment_method}</td>
                        <td>${sale.payment_status}</td>
                        <td>${sale.status}</td>
                        <td>${sale.total}</td>

                        <td><button class = 'edit-btn' data-id = ${sale.id}>Edit</button></td>
                        <td><button class = 'delete-btn' data-id = ${sale.id}>Delete</button></td>`
                    );

                    tableBody.append(row);

                });

                $(".edit-btn").on('click', function() {
                    const id = $(this).data('id');

                    $("#edit-sale-form").on('submit', function() {
                        editSale(id);
                    });
                });

                $(".delete-btn").on('click', function() {
                    const id = $(this).data('id');
                    deleteSale(id);
                });


            }
        })
    }

    function editSale(id) {
        const id = id;
        const payment_method = $("#payment_method").val();
        const payment_status = $("#payment_status").val();
        const status = $("#status").val();

        $.ajax({
            url: 'admin/sales',
            method: PUT,
            type: 'application/json',
            data: JSON.stringify({ id, payment_method, payment_status, status}),
            success: function(data) {
                fetchSales();
            },
            error: function(error) {
                console.log(error);
            }
        })

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


})