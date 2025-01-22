$(document).ready(function() {

    fetchProducts();

    $('#add-product-form').on('submit', function(event) {
        event.preventDefault();

        const name = $('#product-name').val();
        const type = $('#product-type').val();
        const price = $('#product-price').val();
        const stock = $('#product-stock').val();
        const description = $('#product_description').val();
        //const img

        $.ajax ({
            url: '/adim/product-service',
            method: POST,
            type: 'application/json',
            data: JSON.stringify({ name, type, price, stock, description}),
            success: function () {
                // success
            },
            error: function (error) {
                console.log(error);
            }
            
        })
    })


    function fetchProducts() {
        $.ajax({
            url: '/admin/product_service',
            method: 'GET',
            success: function(data) {
                const tableBody = $('#products-services tbody');
                tableBody.empty(); // clear existing data
                data.forEach(product => {
                    const row = $('<tr>');
                    row.append(
                        `<td>${product.id}</td>
                        <td>${product.name}</td>
                        <td>${product.type}</td>
                        <td>${product.price}</td>
                        <td>${product.stock}</td>
                        <td>${product.unit_price}</td>
                        
                        <td><button class = 'edit-btn' data-id = ${product.id}>Edit</button> </td>
                        <td><button class = 'delete-btn' data-id = ${product.id}>Delete</button> </td>`);

                        tableBody.append(row); 
                });
            

                // attach event handler

                $(".edit-btn").on('click', function() {
                    const id = $(this).data('id');
                    
                    $("#edit-product-form").on('submit', function () {
                        editProduct(id);
                    })
                    });

                $("delete-btn").on('click', function() {
                    const id = $(this).data('id');
                    deleteProduct(id);

                })
    
                },

            error: function(error) {
                console.error(error);
            }
        });
    }

});



function editProduct (id) {
    const name = $('#product-name').val();
    const type = $('#product-type').val();
    const price = $('#product-price').val();
    const stock = $('#product-stock').val();
    const description = $('#product_description').val();

    $.ajax({
        url: '/admin/product_service',
        method: PUT,
        contentType: 'application/json',
        data: JSON.stringify({ id, name, type, price, stock, description}),
        success: function(data) {
            fetchProducts();
        },
        error: function(error) {
            console.log(error);
        }
    })

}

function deleteProduct (id) {
    if (confirm('Are you sure you want to delete this item?')) {
        $.ajax ({
            url: `/admin/product_service/${id}`,
            method: DELETE,
            contentType: 'application/json',
            data: JSON.stringify({ id }),
            success: function(data) {
                fetchProducts();
            },
            error: function(error) {
                console.log(error);
            }

        })
    }
}
