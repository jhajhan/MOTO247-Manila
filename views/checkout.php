<?php
session_start();
include('F:\xampp\htdocs\MOTO247-Manila\includes\user_function.php');
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\header.php');
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\navbar.php');

if (!isset($_SESSION['auth_user']['user_id'])) {
    $_SESSION['message'] = "You need to log in first to access the cart.";
    header("Location: login.php");
    exit();
}
?>

<div class="py-3 bg-primary">
    <div class="container">
        <h3 class="text-white">Checkout</h3>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="card card-body shadow">
            <form action="/MOTO247-MANILA/includes/place_order.php" method="POST">
            <div class="row g-4"> <!-- Added spacing -->
                <!-- Shipping Address Section -->
                <div class="col-md-7">
                    <h5>Shipping Address</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold"> Name </label>
                            <input type="text" name="name"  placeholder="Enter your full name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold"> Email </label>
                            <input type="email" name="email"  placeholder="Enter your email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold"> Phone </label>
                            <input type="text" name="phone"  placeholder="Enter your phone number" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold"> Address </label>
                            <input type="text" name="address"   placeholder="Enter your address" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold"> Payment Method </label>
                            <select  name="payment_method" class="form-control">
                                <option value="" selected disabled style="color: grey;">Select Payment Method</option>
                                <option value="cash">Cash</option>
                                <option value="gcash">Gcash</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="fw-bold"> Note</label>
                            <textarea rows="5" name="notes" placeholder="Note" class="form-control"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Section -->
                <div class="col-md-5">
                    <h5>Order Summary</h5>
                    <hr>
                    <?php
                    $items = getCartItems();
                    $total_amount = 0;
                    foreach ($items as $citem) {
                    ?>
                        <div class="mb-2 p-2 border rounded shadow-sm"> <!-- Added spacing & border -->
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center">
                                    <img src="<?php echo $citem['image']; ?>" class="img-fluid rounded" width="60" alt="product image">
                                </div>
                                <div class="col-md-5">
                                    <label class="fw-bold"> <?php echo htmlspecialchars($citem['name']); ?></label>
                                </div>
                                <div class="col-md-2 text-center">
                                    <label> <?php echo htmlspecialchars($citem['price']); ?></label> 
                                </div>
                                <div class="col-md-2 text-center">
                                    <label> x <?php echo htmlspecialchars($citem['prod_qty']); ?></label>
                                </div>
                            </div>
                        </div>
                        <hr> <!-- Horizontal line between items -->
                    <?php
                        $total_amount += $citem['price'] * $citem['prod_qty'];
                    }
                    ?>
                    <h5 class="mt-3">Total: <span class="float-end fw-bold"><?= $total_amount ?></span></h5>
                    <hr>
                    <div class="">
                        <button type="submit" name="placeOrderBtn" class="btn btn-primary w-100">Place Order</button>
                    </div> <!-- Horizontal line after total -->
                </div>
            </div>
            </form>
        </div>
    </div>
</div>


<?php
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\footer.php');
?>
