<?php
session_start();
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\header.php');
include('F:\xampp\htdocs\MOTO247-Manila\includes\user_function.php');
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\navbar.php');

if(isset($_GET['id'])) { // Changed from 'product' to 'id'
    $product_id = $_GET['id'];
    $product_data = getIDActiveService($product_id);
    $product = mysqli_fetch_assoc($product_data); // Use mysqli_fetch_assoc

    if($product) {
        // Display product details
        ?>
      <div class="container service_qty">
            <div class ="row">
                 <div class="col-md-5">
           
                    <h1><?= $product['name'] ?></h1>
                    <img src="<?= !empty($product['image']) ? $product['image'] : 'default-image.jpg' ?>" alt="<?= $product['name'] ?>" class="img-fluid">
                  </div>  
                </div>   
                <hr>
                <div class="col-md-6">
                    <p><?= $product['description'] ?></p> <!-- Assuming you have a description field -->
                </div>
                <hr>
                <div class="col-md-6">
        
                <p>Price: <span class="text-success fw-bold"><?= $product['price'] ?></span></p>
                   
                </div>
                     <div class="input-group mb-3" style="width: 130px;">
                        <button class="input-group-text decrement-btn">-</button>
                        <input type="text" class="form-control text-center input-qty bg-white" disabled  value="1">
                         <button class="input-group-text increment-btn">+</button>
                    </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                     <button class="btn btn-primary px-4 addToCart-btn" value='<?= $product['id'];?>'>  Add to Cart </button>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                     <a href="services.php" class="btn btn-primary">Back</a>
                    </div>
                </div>
        </div>
       
        <?php
    } else {
        echo "<h3>No Product found</h3>";
    }
} else {
    echo "<h3>No Product found</h3>";
}

include('F:\xampp\htdocs\MOTO247-Manila\views\includes\footer.php'); 
?>