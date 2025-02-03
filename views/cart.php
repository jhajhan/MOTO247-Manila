<?php
// Start the session
session_start();

// Include necessary files
include('F:\xampp\htdocs\MOTO247-Manila\includes\user_function.php');
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\header.php');
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\navbar.php');

// Check if the user is authenticated
if (!isset($_SESSION['auth_user']['user_id'])) {
    $_SESSION['message'] = "You need to log in first to access the cart.";        
    header("Location: login.php"); // Redirect to login page
    exit(); // Stop further execution
}

?>

<!-- Page header -->
<div class="py-3 bg-primary">
    <div class="container">
        <h1 class="text-white">Cart</h1>
    </div>
</div>

<!-- Cart table section, outside the blue header -->
<div class="py-5">
    <div class="container">
        <div class="card card-body shadow">
            <?php
            // Retrieve cart items
            $items = getCartItems();

            // Check if there are items in the cart
            if(mysqli_num_rows($items) > 0) 
            {
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <h6>Product</h6>
                            </div>
                            <div class="col-md-3">
                                <h6>Price</h6>
                            </div>
                            <div class="col-md-2 text-center">
                                <h6>Quantity</h6>
                            </div>
                            <div class="col-md-2 text-center">
                                <h6>Remove</h6>
                            </div>
                        </div>

                        <?php
                        // Loop through each cart item
                        foreach ($items as $citem) {
                        ?>
                            <div class="card shadow-sm mb-3">
                                <div class="row align-items-center border-bottom py-2">
                                    <div class="col-md-2 text-center">
                                        <img src="<?php echo $citem['image']; ?>" width="80px" alt="product image">
                                    </div>
                                    <div class="col-md-3">
                                        <h5><?php echo $citem['name']; ?></h5>
                                    </div>
                                    <div class="col-md-3">
                                        <h5><?php echo $citem['price']; ?></h5>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <div class="input-group mb-3" style="width: 130px;">
                                            <button type="button" class="input-group-text decrement-btn update_qty" data-id="<?= $citem['prod_id']; ?>">-</button>
                                            <input type="text" class="form-control text-center input-qty bg-white" value="<?= $citem['prod_qty']; ?>" readonly>
                                            <button type="button" class="input-group-text increment-btn" data-id="<?= $citem['prod_id']; ?>">+</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <button class="btn btn-danger btn-sm deleteItem" value="<?=$citem['cid']?>"><i class="fa fa-trash"></i> Remove</button>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="d-flex justify-content-end">
                            <a href="checkout.php" class="btn btn-outline-primary">Proceed to Checkout</a>
                        </div>
                    </div>
                </div>
            <?php
            }
            else {
                // Display message if no items in the cart
                ?>
                <div class="card card-body text-center">
                    <h5 class='text-center'>No items in the cart</h5>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<?php
// Include the footer
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\footer.php'); 
?>