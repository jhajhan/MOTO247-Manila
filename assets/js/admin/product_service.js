

let isEditing = false;

$(document).ready(function() {

    fetchProducts();

    $('#filterBtn').on('click', function() {
        const type = $('#typeFilter').val();
        const stock = $('#stockFilter').val();
        const min_price = $('#minPrice').val();
        const max_price = $('#maxPrice').val();

        filters = {
            type: type,
            stock: stock,
            min_price: min_price,
            max_price: max_price

        }

        fetchProducts(filters)
    })

});




$('#productServiceForm').on('submit', function(event) {
    event.preventDefault();
    
    if (isEditing) {
        editProduct();
    } else {
        addProduct();
    }

});

function fetchProducts(filters = {}) {

    
    $.ajax({
        url: '/admin/product-service',
        method: 'GET',
        data: filters,
        success: function(response) {
            console.log("Data received:", response); // ✅ Debugging
            
            // ✅ Ensure response is JSON (if needed)
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
            if (!data.products_services) {
                console.error("Missing 'products_services' in response:", data);
                return;
            }

            const tableBody = $('#productServiceTableBody');
            tableBody.empty(); // ✅ Clear existing data

            data.products_services.forEach(product => {
                console.log('id: ' + product.prod_id); // ✅ Correct key

                const row = $(`
                    <tr>
                        <td class = 'prod-t'>${product.prod_id}</td>
                        <td >${product.name}</td>
                        <td>${product.type}</td>
                        <td>${product.price}</td>
                        <td>${product.unit_price}</td>
                        <td>${product.stock}</td>
                        <td><button class="editBtn" data-id="${product.prod_id}" data-name = "${product.name}" data-type = "${product.type}", data-price = "${product.price}" data-unit-price = "${product.unit_price}" data-stock = "${product.stock}" data-desc = "${product.description}">Edit</button>  <button class="deleteBtn" data-id="${product.prod_id}">Delete</button></td>
                    </tr>
                `); //  

                tableBody.append(row);
            });

            // ✅ Attach event handlers using delegation (prevents duplicate handlers)
            $("#productServiceTableBody").off("click").on("click", ".editBtn", function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const type = $(this).data('type');
                const price = $(this).data('price');
                const unitPrice = $(this).data('unit-price');
                const stock = $(this).data('stock');
                const description = $(this).data('desc');

                // Populate form fields with existing product data
                $('#productID').val(id);
                $('#productName').val(name);
                $('#productType').val(type);
                $('#productPrice').val(price);
                $('#productUnitPrice').val(unitPrice);
                $('#productStock').val(stock);
                $('#productDescription').val(description);

                // Change modal title and show the modal
                $('#modalTitle').text('EDIT PRODUCT OR SERVICE');
                $('.modal').show();

                isEditing = true;

            });

            $("#productServiceTableBody").on("click", ".deleteBtn", function() {
                const id = $(this).data('id');
                deleteProduct(id);
            });

        },
        error: function(error) {
            console.error("Error fetching products:", error);
        }
    });
}

$('#addProductServiceBtn').on('click', function() {
    isEditing = false;
    $('#modalTitle').text('ADD NEW PRODUCT OR SERVICE'); // ✅ Corrected `.text()`
    $('.modal').show(); // ✅ Use `.show()` or `.css('display', 'block')`
    
    // ✅ Ensure the form is correctly selected
    $('#productServiceForm')[0].reset(); 
});


$("#close-edit-add-modal").on('click', function(){
    $('.modal').css('display', 'none');
})


function addProduct() {
    alert('hallo');

    const name = $('#productName').val();
    const type = $('#productType').val();
    const price = $('#productPrice').val();
    const unit_price = $('#productUnitPrice').val();
    const stock = $('#productStock').val();
    const description = $('#productDescription').val();
    let img = '';

    var formData = new FormData();
    var fileInput = $('#file1')[0].files[0];  // Ensure the file is being correctly selected

    if (fileInput) {
        formData.append('file1', fileInput);

        $.ajax({
            url: '/upload-image',  // Your PHP upload endpoint
            method: 'POST',
            data: formData,
            contentType: false,  // Don't set content type for FormData
            processData: false,  // Don't process the data (important for file uploads)
            success: function(response) {
                var data = JSON.parse(response); // Parse the JSON response
                if (data.status === 'success') {
                    img  = data.imageUrl;  // The URL of the uploaded image
                    console.log("Image uploaded successfully! URL: " + img);

                    // Now proceed with the second AJAX call after the image upload is successful
                    $.ajax({
                        url: '/admin/product-service',
                        method: "POST",
                        type: 'application/json',
                        data: JSON.stringify({ name, type, price, unit_price, stock, description, img }),
                        success: function () {
                            fetchProducts();
                            $('.modal').hide();
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });

                } else {
                    console.log("Error uploading image: " + data.message);
                }
            }
        });
    } else {
        console.log("No file selected!");
    }
}


function editProduct () {
    const id = $('#productID').val();
    const name = $('#productName').val();
    const type = $('#productType').val();
    const price = $('#productPrice').val();
    const unit_price = $("#productUnitPrice").val();
    const stock = $('#productStock').val();
    const description = $('#productDescription').val();
    let img = '';

    var formData = new FormData();
    var fileInput = $('#file1')[0].files[0];  // Ensure the file is being correctly selected

    if (fileInput) {
        formData.append('file1', fileInput);

        $.ajax({
            url: '/upload-image',  // Your PHP upload endpoint
            method: 'POST',
            data: formData,
            contentType: false,  // Don't set content type for FormData
            processData: false,  // Don't process the data (important for file uploads)
            success: function(response) {
                var data = JSON.parse(response); // Parse the JSON response
                if (data.status === 'success') {
                    img  = data.imageUrl;  // The URL of the uploaded image
                    console.log("Image uploaded successfully! URL: " + img);

                    $.ajax({
                        url: '/admin/product-service',
                        method: 'PUT',
                        contentType: 'application/json',
                        data: JSON.stringify({ id, name, type, price, unit_price, stock, description, img}),
                        success: function(data) {
                            fetchProducts();
                            $('.modal').hide();

                        },
                        error: function(error) {
                            console.log(error);
                        }
                    })
                

                } else {
                    console.log("Error uploading image: " + data.message);
                }
            }
        });
    } else {
        console.log("No file selected!");
    }

}

function deleteProduct (id) {
    if (confirm('Are you sure you want to delete this item?')) {
        $.ajax ({
            url: "/admin/product-service",
            method: "DELETE",
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

$('#cancelBtn').on('click', function(){
    $('.modal').css('display', 'none');
}) 
